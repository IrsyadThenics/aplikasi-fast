import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:file_picker/file_picker.dart';
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
      final token = await _getToken();

      if (filePath == null) {
        _showSnack('Gagal membaca jalur file', isError: true);
        return;
      }

      _showSnack('Mengunggah $fileName ke $type…');

      final endpoint = type == 'Konstruksi' ? 'perencanaan' : 'perencanaan';

      final req = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/$endpoint/upload'),
      )..headers.addAll({
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        });

      req.files.add(await http.MultipartFile.fromPath(
        'file',
        filePath,
        filename: fileName,
      ));

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

      // FAB
      floatingActionButton: FloatingActionButton(
        onPressed: _showUploadOptions,
        backgroundColor: _accent,
        child: const Icon(Icons.add, color: Colors.white, size: 28),
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

  Widget _buildFileCard(dynamic file) {
    final name = file['nama_file'] ?? 'Berkas Tidak Dikenal';
    final dateRaw = (file['created_at'] ?? '').toString();
    final path = file['path_file'] ?? '';
    final isKonstruksi = (file['kategori'] ?? '').toString().toLowerCase() == 'konstruksi';
    final badgeText = isKonstruksi ? 'Konstruksi' : 'Perencanaan';
    final badgeColor = isKonstruksi ? Colors.orangeAccent : Colors.lightBlueAccent;

    String formattedDate = dateRaw;
    try {
      final dt = DateTime.parse(dateRaw);
      final months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
      formattedDate = '${dt.day} ${months[dt.month - 1]} ${dt.year} ${dt.hour.toString().padLeft(2, '0')}.${dt.minute.toString().padLeft(2, '0')} WIB';
    } catch (_) {}

    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
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
                // Title + badge
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        name.toUpperCase(),
                        maxLines: 1, overflow: TextOverflow.ellipsis,
                        style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 13),
                      ),
                    ),
                    const SizedBox(width: 8),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                      decoration: BoxDecoration(
                        color: badgeColor.withOpacity(0.2),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(badgeText, style: TextStyle(color: badgeColor, fontSize: 10, fontWeight: FontWeight.bold)),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                // Icon + details
                Row(
                  children: [
                    Container(
                      width: 46, height: 46,
                      decoration: BoxDecoration(
                        color: Colors.white.withOpacity(0.08),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: Icon(Icons.insert_drive_file_rounded, color: Colors.white.withOpacity(0.5), size: 26),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Date', style: TextStyle(color: Colors.white.withOpacity(0.5), fontSize: 10, fontWeight: FontWeight.bold)),
                          Text(formattedDate, style: TextStyle(color: Colors.white.withOpacity(0.8), fontSize: 11)),
                        ],
                      ),
                    ),
                    if (path.isNotEmpty)
                      TextButton.icon(
                        onPressed: () => _showSnack('Membuka: http://127.0.0.1:8000/storage/$path'),
                        icon: const Icon(Icons.open_in_new_rounded, size: 12),
                        label: const Text('Lihat', style: TextStyle(fontSize: 11)),
                        style: TextButton.styleFrom(
                          foregroundColor: Colors.lightBlue.shade200,
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        ),
                      ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
