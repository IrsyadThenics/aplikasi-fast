import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:file_picker/file_picker.dart';
import 'package:url_launcher/url_launcher.dart';
import 'dart:convert';
import 'dart:ui';

class BerandaScreen extends StatefulWidget {
  const BerandaScreen({super.key});

  @override
  State<BerandaScreen> createState() => _BerandaScreenState();
}

class _BerandaScreenState extends State<BerandaScreen> {
  List<dynamic> _files = [];
  bool _isLoading = false;
  String _userName = 'Vendor PT';
  static const String baseUrl = 'http://127.0.0.1:8000/api';

  // ── Colours (same as login) ──
  static const Color _blue1 = Color(0xFF0A1A6B);
  static const Color _blue2 = Color(0xFF0D3B8E);
  static const Color _blue3 = Color(0xFF0A5FB4);
  static const Color _blue4 = Color(0xFF0A8FD1);
  static const Color _accent = Color(0xFF1E90FF);

  @override
  void initState() {
    super.initState();
    _loadUser();
    _fetchFiles();
  }

  Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  Future<void> _loadUser() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _userName = prefs.getString('user_name') ?? 'Vendor PT';
    });
  }

  Future<void> _fetchFiles() async {
    setState(() => _isLoading = true);
    final token = await _getToken();
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/perencanaan/files'),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        setState(() => _files = data['data'] ?? []);
      }
    } catch (e) {
      _showSnack('Gagal memuat berkas: $e', isError: true);
    } finally {
      setState(() => _isLoading = false);
    }
  }

  Future<void> _pickAndUpload(String type) async {
    try {
      final result = await FilePicker.pickFiles(
        type: FileType.custom,
        allowedExtensions: ['pdf', 'csv', 'xls', 'xlsx', 'doc', 'docx', 'png', 'jpg'],
      );
      if (result == null || result.isEmpty) return;

      final file = result.first;
      final fileName = file.name;
      final filePath = file.path;
      final bytes = await file.readAsBytes();
      final token = await _getToken();

      if (filePath == null && bytes.isEmpty) {
        _showSnack('Gagal membaca data file', isError: true);
        return;
      }

      _showSnack('Mengunggah $fileName ke $type…');

      final endpoint = type == 'Konstruksi' ? 'konstruksi' : 'perencanaan';

      final req = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/$endpoint/upload'),
      )..headers.addAll({
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        });

      if (bytes.isNotEmpty) {
        req.files.add(http.MultipartFile.fromBytes(
          'file',
          bytes,
          filename: fileName,
        ));
      } else {
        req.files.add(await http.MultipartFile.fromPath(
          'file',
          filePath!,
          filename: fileName,
        ));
      }

      final streamed = await req.send();
      if (streamed.statusCode == 200) {
        _showSnack('✓ Upload berhasil!');
        _fetchFiles();
      } else {
        final body = await streamed.stream.bytesToString();
        _showSnack('Upload gagal (${streamed.statusCode}): $body', isError: true);
      }
    } catch (e) {
      _showSnack('Error: $e', isError: true);
    }
  }

  void _showSnack(String msg, {bool isError = false}) {
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(
      content: Text(msg),
      backgroundColor: isError ? Colors.red.shade700 : Colors.green.shade700,
    ));
  }

  void _showUploadOptions() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return ClipRRect(
          borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
          child: BackdropFilter(
            filter: ImageFilter.blur(sigmaX: 15, sigmaY: 15),
            child: Container(
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: _blue1.withOpacity(0.85),
                borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
                border: Border.all(color: Colors.white.withOpacity(0.2)),
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Upload Berkas Ke:',
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.white),
                  ),
                  const SizedBox(height: 16),
                  ListTile(
                    leading: Container(
                      width: 40, height: 40,
                      decoration: BoxDecoration(
                        color: Colors.blue.withOpacity(0.2),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: const Icon(Icons.architecture_rounded, color: Colors.lightBlueAccent),
                    ),
                    title: const Text('Perencanaan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600)),
                    trailing: const Icon(Icons.chevron_right_rounded, color: Colors.white54),
                    contentPadding: EdgeInsets.zero,
                    onTap: () { Navigator.pop(context); _pickAndUpload('Perencanaan'); },
                  ),
                  Divider(color: Colors.white.withOpacity(0.15)),
                  ListTile(
                    leading: Container(
                      width: 40, height: 40,
                      decoration: BoxDecoration(
                        color: Colors.orange.withOpacity(0.2),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: const Icon(Icons.engineering_rounded, color: Colors.orangeAccent),
                    ),
                    title: const Text('Konstruksi', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600)),
                    trailing: const Icon(Icons.chevron_right_rounded, color: Colors.white54),
                    contentPadding: EdgeInsets.zero,
                    onTap: () { Navigator.pop(context); _pickAndUpload('Konstruksi'); },
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }

  Future<bool> _submitVendorReport({
    required String noAgenda,
    required List<PlatformFile> files,
    required List<String> checklist,
    required String catatan,
  }) async {
    final token = await _getToken();
    final request = http.MultipartRequest(
      'POST',
      Uri.parse('$baseUrl/vendor/laporan'),
    )
      ..headers.addAll({
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      })
      ..fields['no_agenda'] = noAgenda
      ..fields['catatan'] = catatan;

    for (var index = 0; index < checklist.length; index++) {
      request.fields['checklist[$index]'] = checklist[index];
    }

    for (final file in files) {
      request.files.add(http.MultipartFile.fromBytes(
        'files[]',
        await file.readAsBytes(),
        filename: file.name,
      ));
    }

    try {
      final response = await request.send();
      if (response.statusCode == 201) {
        _showSnack('Laporan berhasil dikirim ke Perencanaan.');
        return true;
      }
      _showSnack(
        'Pengiriman gagal: ${await response.stream.bytesToString()}',
        isError: true,
      );
    } catch (e) {
      _showSnack('Pengiriman gagal: $e', isError: true);
    }
    return false;
  }

  void _showVendorReportForm(dynamic item) {
    final noAgenda = (item['no_agenda'] ?? '').toString();
    final catatanController = TextEditingController();
    final selectedChecklist = <String>{};
    final selectedFiles = <PlatformFile>[];
    const checklistOptions = [
      'Dokumen pekerjaan lengkap',
      'Pekerjaan sesuai WO',
      'Foto dokumentasi terlampir',
      'Siap ditindaklanjuti Perencanaan',
    ];

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => StatefulBuilder(
        builder: (context, setSheetState) => Padding(
          padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
          child: Container(
            constraints: BoxConstraints(maxHeight: MediaQuery.of(context).size.height * .88),
            padding: const EdgeInsets.fromLTRB(20, 20, 20, 24),
            decoration: const BoxDecoration(
              color: Color(0xFF0A1A6B),
              borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
            ),
            child: SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Text('Kirim Laporan ke Perencanaan', style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 4),
                  Text('No. agenda: $noAgenda', style: const TextStyle(color: Colors.lightBlueAccent, fontSize: 12)),
                  const SizedBox(height: 18),
                  const Text('Checklist pekerjaan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600)),
                  ...checklistOptions.map((option) => CheckboxListTile(
                    value: selectedChecklist.contains(option),
                    onChanged: (checked) => setSheetState(() {
                      checked == true ? selectedChecklist.add(option) : selectedChecklist.remove(option);
                    }),
                    title: Text(option, style: const TextStyle(color: Colors.white, fontSize: 13)),
                    activeColor: _accent,
                    contentPadding: EdgeInsets.zero,
                    controlAffinity: ListTileControlAffinity.leading,
                  )),
                  const SizedBox(height: 8),
                  OutlinedButton.icon(
                    onPressed: () async {
                      final result = await FilePicker.pickFiles(
                        allowMultiple: true,
                        withData: true,
                        type: FileType.custom,
                        allowedExtensions: ['pdf', 'jpg', 'jpeg', 'png'],
                      );
                      if (result.isNotEmpty) setSheetState(() => selectedFiles.addAll(result));
                    },
                    icon: const Icon(Icons.attach_file_rounded),
                    label: const Text('Pilih dokumen / berkas'),
                    style: OutlinedButton.styleFrom(foregroundColor: Colors.white),
                  ),
                  if (selectedFiles.isNotEmpty)
                    ...selectedFiles.map((file) => Padding(
                      padding: const EdgeInsets.only(top: 5),
                      child: Row(children: [
                        const Icon(Icons.description_outlined, color: Colors.lightBlueAccent, size: 16),
                        const SizedBox(width: 6),
                        Expanded(child: Text(file.name, style: const TextStyle(color: Colors.white70, fontSize: 12))),
                        IconButton(
                          onPressed: () => setSheetState(() => selectedFiles.remove(file)),
                          icon: const Icon(Icons.close_rounded, color: Colors.white70, size: 18),
                        ),
                      ]),
                    )),
                  const SizedBox(height: 8),
                  TextField(
                    controller: catatanController,
                    maxLines: 3,
                    style: const TextStyle(color: Colors.white),
                    decoration: const InputDecoration(
                      labelText: 'Catatan untuk Perencanaan (opsional)',
                      labelStyle: TextStyle(color: Colors.white70),
                      enabledBorder: OutlineInputBorder(borderSide: BorderSide(color: Colors.white38)),
                      focusedBorder: OutlineInputBorder(borderSide: BorderSide(color: Colors.lightBlueAccent)),
                    ),
                  ),
                  const SizedBox(height: 18),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: () async {
                        if (selectedFiles.isEmpty) {
                          _showSnack('Pilih minimal satu dokumen atau berkas.', isError: true);
                          return;
                        }
                        final sent = await _submitVendorReport(
                          noAgenda: noAgenda,
                          files: selectedFiles,
                          checklist: selectedChecklist.toList(),
                          catatan: catatanController.text.trim(),
                        );
                        if (sent && context.mounted) Navigator.pop(context);
                      },
                      icon: const Icon(Icons.send_rounded),
                      label: const Text('Kirim ke Perencanaan'),
                      style: ElevatedButton.styleFrom(backgroundColor: _accent, foregroundColor: Colors.white),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  // ── Decorative circle (same as login) ──
  Widget _buildDecorativeCircle(double size, Color color) {
    return Container(
      width: size, height: size,
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        border: Border.all(color: color, width: 60),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        fit: StackFit.expand,
        children: [
          // ── Background gradient ──
          Container(
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
                colors: [_blue1, _blue2, _blue3, _blue4],
              ),
            ),
          ),

          // ── Decorative circles ──
          Positioned(right: -80, top: -60, child: _buildDecorativeCircle(300, Colors.white.withOpacity(0.04))),
          Positioned(right: -40, top: -30, child: _buildDecorativeCircle(200, Colors.white.withOpacity(0.05))),
          Positioned(left: -100, bottom: -80, child: _buildDecorativeCircle(350, Colors.white.withOpacity(0.03))),

          // ── FAST watermark ──
          Center(
            child: Opacity(
              opacity: 0.05,
              child: Text('FAST', style: TextStyle(fontSize: 220, fontWeight: FontWeight.w900, color: Colors.white, letterSpacing: -8)),
            ),
          ),

          // ── Content ──
          SafeArea(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Header
                Padding(
                  padding: const EdgeInsets.fromLTRB(20, 16, 20, 0),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(16),
                    child: BackdropFilter(
                      filter: ImageFilter.blur(sigmaX: 15, sigmaY: 15),
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.12),
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(color: Colors.white.withOpacity(0.25)),
                        ),
                        child: Row(
                          children: [
                            Container(
                              width: 42, height: 42,
                              decoration: BoxDecoration(
                                shape: BoxShape.circle,
                                color: _accent.withOpacity(0.3),
                                border: Border.all(color: Colors.white38, width: 1.5),
                              ),
                              child: const Icon(Icons.business_rounded, color: Colors.white, size: 22),
                            ),
                            const SizedBox(width: 12),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    'Halo, $_userName',
                                    style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 15),
                                  ),
                                  Text(
                                    'VENDOR',
                                    style: TextStyle(color: Colors.white.withOpacity(0.6), fontSize: 11, letterSpacing: 0.8),
                                  ),
                                ],
                              ),
                            ),
                            Icon(Icons.notifications_outlined, color: Colors.white.withOpacity(0.7)),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),

                // Title
                Padding(
                  padding: const EdgeInsets.fromLTRB(20, 20, 20, 8),
                  child: Text(
                    'Daftar Berkas Proyek',
                    style: TextStyle(color: Colors.white.withOpacity(0.85), fontSize: 13, fontWeight: FontWeight.w600, letterSpacing: 0.5),
                  ),
                ),

                // File list
                Expanded(
                  child: RefreshIndicator(
                    onRefresh: _fetchFiles,
                    color: _accent,
                    child: _isLoading
                        ? const Center(child: CircularProgressIndicator(color: Colors.white))
                        : _files.isEmpty
                            ? _buildEmptyState()
                            : ListView.builder(
                                padding: const EdgeInsets.fromLTRB(20, 4, 20, 100),
                                itemCount: _files.length,
                                itemBuilder: (context, index) => _buildFileCard(_files[index]),
                              ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildEmptyState() {
    return ListView(
      children: [
        SizedBox(height: MediaQuery.of(context).size.height * 0.2),
        Column(
          children: [
            Icon(Icons.folder_off_rounded, size: 72, color: Colors.white.withOpacity(0.25)),
            const SizedBox(height: 16),
            Text('Belum ada berkas', style: TextStyle(color: Colors.white.withOpacity(0.5), fontSize: 16, fontWeight: FontWeight.w600)),
            const SizedBox(height: 8),
            Text('Tekan tombol + untuk menambah berkas', style: TextStyle(color: Colors.white.withOpacity(0.35), fontSize: 13)),
          ],
        ),
      ],
    );
  }

  void _openFilePreviewDialog(String namaFile, String jenis, String path) {
    final fileUrl = 'http://127.0.0.1:8000/storage/$path';
    final isImage = path.toLowerCase().endsWith('.png') ||
        path.toLowerCase().endsWith('.jpg') ||
        path.toLowerCase().endsWith('.jpeg');

    showDialog(
      context: context,
      builder: (context) {
        return Dialog(
          backgroundColor: Colors.transparent,
          insetPadding: const EdgeInsets.all(16),
          child: ClipRRect(
            borderRadius: BorderRadius.circular(16),
            child: Container(
              color: const Color(0xFF0A1A6B),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  // Header Dialog
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                    color: Colors.white.withOpacity(0.1),
                    child: Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                          decoration: BoxDecoration(
                            color: Colors.lightBlueAccent.withOpacity(0.25),
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: Text(jenis, style: const TextStyle(color: Colors.lightBlueAccent, fontSize: 11, fontWeight: FontWeight.bold)),
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: Text(
                            namaFile,
                            style: const TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.bold),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                        IconButton(
                          icon: const Icon(Icons.close_rounded, color: Colors.white70, size: 20),
                          onPressed: () => Navigator.pop(context),
                          padding: EdgeInsets.zero,
                          constraints: const BoxConstraints(),
                        ),
                      ],
                    ),
                  ),

                  // Content Preview Area
                  Container(
                    constraints: BoxConstraints(
                      maxHeight: MediaQuery.of(context).size.height * 0.6,
                      minHeight: 220,
                    ),
                    color: Colors.black26,
                    child: isImage
                        ? InteractiveViewer(
                            child: Image.network(
                              fileUrl,
                              fit: BoxFit.contain,
                              errorBuilder: (_, __, ___) => _buildDocPreviewFallback(namaFile, jenis),
                            ),
                          )
                        : Container(
                            padding: const EdgeInsets.all(24),
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Container(
                                  width: 64, height: 64,
                                  decoration: BoxDecoration(
                                    color: Colors.redAccent.withOpacity(0.15),
                                    shape: BoxShape.circle,
                                  ),
                                  child: const Icon(Icons.picture_as_pdf_rounded, color: Colors.redAccent, size: 36),
                                ),
                                const SizedBox(height: 14),
                                Text(
                                  namaFile,
                                  style: const TextStyle(color: Colors.white, fontSize: 14, fontWeight: FontWeight.bold),
                                  textAlign: TextAlign.center,
                                ),
                                const SizedBox(height: 6),
                                Text(
                                  'Jalur Berkas: $path',
                                  style: TextStyle(color: Colors.white.withOpacity(0.5), fontSize: 11),
                                  textAlign: TextAlign.center,
                                ),
                                const SizedBox(height: 16),
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                                  decoration: BoxDecoration(
                                    color: Colors.white.withOpacity(0.08),
                                    borderRadius: BorderRadius.circular(8),
                                    border: Border.all(color: Colors.white12),
                                  ),
                                  child: const Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      Icon(Icons.check_circle_outline, color: Colors.greenAccent, size: 16),
                                      SizedBox(width: 6),
                                      Text(
                                        'Terverifikasi dari Perencanaan',
                                        style: TextStyle(color: Colors.greenAccent, fontSize: 11, fontWeight: FontWeight.w600),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          ),
                  ),

                  // Footer Dialog
                  Padding(
                    padding: const EdgeInsets.all(12),
                    child: ElevatedButton(
                      onPressed: () => Navigator.pop(context),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.white.withOpacity(0.15),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                      ),
                      child: const Text('Tutup Pratinjau'),
                    ),
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }

  void _showDetailModal(dynamic item) {
    final noAgenda = item['no_agenda'] ?? '-';
    final nama = item['nama'] ?? '-';
    final alamat = item['alamat'] ?? '-';
    final transaksi = item['transaksi'] ?? '-';
    final status = item['status'] ?? '-';
    final ulp = item['ulp'] ?? '-';
    final tarifLama = item['tarif_lama'] ?? '-';
    final dayaLama = item['daya_lama']?.toString() ?? '0';
    final tarifBaru = item['tarif_baru'] ?? '-';
    final dayaBaru = item['daya_baru']?.toString() ?? '0';
    final totalBiaya = item['total_biaya']?.toString() ?? '0';
    final dateRaw = (item['created_at'] ?? item['sentAt'] ?? '').toString();
    final List<dynamic> berkas = item['berkas'] ?? [];

    String formattedDate = dateRaw;
    try {
      final dt = DateTime.parse(dateRaw);
      final months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
      formattedDate = '${dt.day} ${months[dt.month - 1]} ${dt.year} ${dt.hour.toString().padLeft(2, '0')}.${dt.minute.toString().padLeft(2, '0')} WIB';
    } catch (_) {}

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return Container(
          constraints: BoxConstraints(
            maxHeight: MediaQuery.of(context).size.height * 0.85,
          ),
          decoration: BoxDecoration(
            color: const Color(0xFF0A1A6B),
            borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
            border: Border.all(color: Colors.white.withOpacity(0.2)),
          ),
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Modal Handle
              Center(
                child: Container(
                  width: 40, height: 4,
                  margin: const EdgeInsets.only(bottom: 16),
                  decoration: BoxDecoration(
                    color: Colors.white30,
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),

              // Title & ULP Badge
              Row(
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('Detail Agenda: $noAgenda', style: const TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 2),
                        Row(
                          children: [
                            const Icon(Icons.location_on_outlined, color: Colors.amberAccent, size: 14),
                            const SizedBox(width: 4),
                            Text('Asal ULP: $ulp', style: const TextStyle(color: Colors.amberAccent, fontSize: 12, fontWeight: FontWeight.bold)),
                          ],
                        ),
                      ],
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.lightBlueAccent.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(status.toUpperCase(), style: const TextStyle(color: Colors.lightBlueAccent, fontSize: 11, fontWeight: FontWeight.bold)),
                  ),
                ],
              ),
              const Divider(color: Colors.white24, height: 24),

              // Detail List
              Expanded(
                child: SingleChildScrollView(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _buildDetailRow('Nama Pelanggan', nama),
                      _buildDetailRow('Alamat', alamat),
                      _buildDetailRow('Jenis Transaksi', transaksi),
                      _buildDetailRow('Tarif / Daya Lama', '$tarifLama / $dayaLama VA'),
                      _buildDetailRow('Tarif / Daya Baru', '$tarifBaru / $dayaBaru VA'),
                      _buildDetailRow('Total Biaya (RAB)', 'Rp $totalBiaya'),
                      _buildDetailRow('Waktu Pengiriman', formattedDate),
                      const SizedBox(height: 16),

                      // Berkas Section
                      const Text(
                        'Daftar Berkas Terlampir:',
                        style: TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 8),

                      if (berkas.isEmpty)
                        Text('Belum ada berkas lampiran.', style: TextStyle(color: Colors.white.withOpacity(0.5), fontSize: 12))
                      else
                        ...berkas.map((b) {
                          final namaFile = b['nama_file'] ?? b['jenis_berkas'] ?? 'Berkas';
                          final path = (b['path_file'] ?? '').toString();
                          final jenis = (b['jenis_berkas'] ?? 'file').toString().toUpperCase();
                          final fileUrl = 'http://127.0.0.1:8000/storage/$path';
                          final isImage = path.toLowerCase().endsWith('.png') ||
                              path.toLowerCase().endsWith('.jpg') ||
                              path.toLowerCase().endsWith('.jpeg');

                          return Container(
                            margin: const EdgeInsets.only(bottom: 12),
                            decoration: BoxDecoration(
                              color: Colors.white.withOpacity(0.08),
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: Colors.white12),
                            ),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                // Header Berkas
                                Padding(
                                  padding: const EdgeInsets.all(10),
                                  child: Row(
                                    children: [
                                      Container(
                                        padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                        decoration: BoxDecoration(
                                          color: Colors.lightBlueAccent.withOpacity(0.2),
                                          borderRadius: BorderRadius.circular(6),
                                        ),
                                        child: Text(jenis, style: const TextStyle(color: Colors.lightBlueAccent, fontSize: 10, fontWeight: FontWeight.bold)),
                                      ),
                                      const SizedBox(width: 8),
                                      Expanded(
                                        child: Text(
                                          namaFile,
                                          style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w600),
                                          maxLines: 1,
                                          overflow: TextOverflow.ellipsis,
                                        ),
                                      ),
                                      if (path.isNotEmpty)
                                        ElevatedButton.icon(
                                          onPressed: () => _openFilePreviewDialog(namaFile, jenis, path),
                                          icon: const Icon(Icons.remove_red_eye_rounded, size: 12),
                                          label: const Text('Pratinjau', style: TextStyle(fontSize: 10)),
                                          style: ElevatedButton.styleFrom(
                                            backgroundColor: Colors.lightBlueAccent,
                                            foregroundColor: Colors.black,
                                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                            minimumSize: Size.zero,
                                            tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                                          ),
                                        ),
                                    ],
                                  ),
                                ),

                                // Preview Box langsung di modal
                                if (path.isNotEmpty)
                                  Container(
                                    width: double.infinity,
                                    height: 140,
                                    margin: const EdgeInsets.fromLTRB(10, 0, 10, 10),
                                    decoration: BoxDecoration(
                                      color: Colors.black.withOpacity(0.3),
                                      borderRadius: BorderRadius.circular(8),
                                      border: Border.all(color: Colors.white10),
                                    ),
                                    clipBehavior: Clip.antiAlias,
                                    child: isImage
                                        ? Image.network(
                                            fileUrl,
                                            fit: BoxFit.cover,
                                            errorBuilder: (_, __, ___) => _buildDocPreviewFallback(namaFile, jenis),
                                          )
                                        : _buildDocPreviewFallback(namaFile, jenis),
                                  ),
                              ],
                            ),
                          );
                        }).toList(),
                    ],
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
                child: SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: () {
                      Navigator.pop(context);
                      _showVendorReportForm(item);
                    },
                    icon: const Icon(Icons.send_rounded, size: 18),
                    label: const Text('Kirim Laporan ke Perencanaan'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: _accent,
                      foregroundColor: Colors.white,
                    ),
                  ),
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildDocPreviewFallback(String namaFile, String jenis) {
    return Container(
      color: Colors.white.withOpacity(0.04),
      child: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.picture_as_pdf_rounded, color: Colors.redAccent, size: 36),
            const SizedBox(height: 6),
            Text(
              'Pratinjau Berkas Dokumen [$jenis]',
              style: const TextStyle(color: Colors.white70, fontSize: 12, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 2),
            Text(
              namaFile,
              style: TextStyle(color: Colors.white.withOpacity(0.4), fontSize: 10),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 130,
            child: Text(label, style: TextStyle(color: Colors.white.withOpacity(0.6), fontSize: 12)),
          ),
          const Text(': ', style: TextStyle(color: Colors.white60, fontSize: 12)),
          Expanded(
            child: Text(value, style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w500)),
          ),
        ],
      ),
    );
  }

  Widget _buildFileCard(dynamic item) {
    final noAgenda = item['no_agenda'] ?? 'Tanpa No Agenda';
    final nama = item['nama'] ?? '-';
    final transaksi = item['transaksi'] ?? '-';
    final status = item['status'] ?? '-';
    final ulp = item['ulp'] ?? '-';
    final dateRaw = (item['created_at'] ?? item['sentAt'] ?? '').toString();
    final List<dynamic> berkas = item['berkas'] ?? [];

    String formattedDate = dateRaw;
    try {
      final dt = DateTime.parse(dateRaw);
      final months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
      formattedDate = '${dt.day} ${months[dt.month - 1]} ${dt.year} ${dt.hour.toString().padLeft(2, '0')}.${dt.minute.toString().padLeft(2, '0')} WIB';
    } catch (_) {}

    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: InkWell(
        onTap: () => _showDetailModal(item),
        borderRadius: BorderRadius.circular(14),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(14),
          child: BackdropFilter(
            filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
            child: Container(
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.1),
                borderRadius: BorderRadius.circular(14),
                border: Border.all(color: Colors.white.withOpacity(0.15)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Header card: No Agenda + Badge Status
                  Row(
                    children: [
                      Expanded(
                        child: Text(
                          noAgenda,
                          style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14),
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                        decoration: BoxDecoration(
                          color: Colors.lightBlueAccent.withOpacity(0.2),
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(status.toUpperCase(), style: const TextStyle(color: Colors.lightBlueAccent, fontSize: 10, fontWeight: FontWeight.bold)),
                      ),
                    ],
                  ),
                  const SizedBox(height: 6),

                  // Info Pelanggan & ULP
                  Text('Nama: $nama', style: const TextStyle(color: Colors.white70, fontSize: 12)),
                  Row(
                    children: [
                      Text('Transaksi: $transaksi | ', style: TextStyle(color: Colors.white.withOpacity(0.6), fontSize: 11)),
                      const Icon(Icons.location_on_outlined, color: Colors.amberAccent, size: 12),
                      const SizedBox(width: 2),
                      Text('ULP: $ulp', style: const TextStyle(color: Colors.amberAccent, fontSize: 11, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  Text('Waktu: $formattedDate', style: TextStyle(color: Colors.white.withOpacity(0.5), fontSize: 10)),

                  if (berkas.isNotEmpty) ...[
                    const Divider(color: Colors.white24, height: 16),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text('Berkas Lampiran (${berkas.length}):', style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w600)),
                        const Text('Ketuk untuk detail >', style: TextStyle(color: Colors.lightBlueAccent, fontSize: 10)),
                      ],
                    ),
                    const SizedBox(height: 6),
                    Column(
                      children: berkas.map<Widget>((b) {
                        final namaFile = b['nama_file'] ?? b['jenis_berkas'] ?? 'Berkas';
                        final path = b['path_file'] ?? '';
                        final jenis = (b['jenis_berkas'] ?? 'file').toString().toUpperCase();
                        return Container(
                          margin: const EdgeInsets.only(bottom: 4),
                          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(0.05),
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Row(
                            children: [
                              const Icon(Icons.insert_drive_file_outlined, color: Colors.white60, size: 16),
                              const SizedBox(width: 8),
                              Expanded(
                                child: Text(
                                  '[$jenis] $namaFile',
                                  style: const TextStyle(color: Colors.white, fontSize: 11),
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                ),
                              ),
                              if (path.isNotEmpty)
                                InkWell(
                                  onTap: () => _openFilePreviewDialog(namaFile, jenis, path),
                                  child: const Padding(
                                    padding: EdgeInsets.symmetric(horizontal: 4, vertical: 2),
                                    child: Row(
                                      children: [
                                        Text('Pratinjau', style: TextStyle(color: Colors.lightBlueAccent, fontSize: 11, fontWeight: FontWeight.bold)),
                                        SizedBox(width: 2),
                                        Icon(Icons.remove_red_eye_rounded, color: Colors.lightBlueAccent, size: 12),
                                      ],
                                    ),
                                  ),
                                ),
                            ],
                          ),
                        );
                      }).toList(),
                    ),
                  ],
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
