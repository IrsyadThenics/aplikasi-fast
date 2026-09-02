import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:file_picker/file_picker.dart';
import 'dart:convert';
import 'dart:ui';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  List<dynamic> _files = [];
  bool _isLoading = false;
  String _userName = '';
  String _userRole = '';
  static const String baseUrl = 'http://127.0.0.1:8000/api';

  // ── Colours ──────────────────────────────────────────────────────────────
  static const Color _blue1 = Color(0xFF0A1A6B);
  static const Color _blue2 = Color(0xFF0A5FB4);
  static const Color _accent = Color(0xFF1E90FF);

  @override
  void initState() {
    super.initState();
    _loadUser();
    _fetchFiles();
  }

  // ── Helpers ───────────────────────────────────────────────────────────────
  Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  Future<void> _loadUser() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _userName = prefs.getString('user_name') ?? '';
      _userRole = prefs.getString('user_role') ?? '';
    });
  }

  // ── Fetch ─────────────────────────────────────────────────────────────────
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

  // ── Upload ────────────────────────────────────────────────────────────────
  Future<void> _pickAndUpload() async {
    try {
      final result = await FilePicker.platform.pickFiles(
        type: FileType.custom,
        allowedExtensions: ['pdf', 'csv', 'xls', 'xlsx', 'doc', 'docx', 'png', 'jpg'],
      );
      if (result == null) return;

      final fileName = result.files.single.name;
      final fileBytes = result.files.single.bytes;
      final token = await _getToken();

      _showSnack('Mengunggah $fileName…');

      final req = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/perencanaan/upload'),
      )
        ..headers.addAll({
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        });

      if (fileBytes != null) {
        req.files.add(http.MultipartFile.fromBytes(
          'file',
          fileBytes,
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

  // ── Logout ────────────────────────────────────────────────────────────────
  Future<void> _logout() async {
    final token = await _getToken();
    try {
      await http.post(
        Uri.parse('$baseUrl/logout'),
        headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
      );
    } catch (_) {}
    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();
    if (mounted) Navigator.pushReplacementNamed(context, '/login');
  }

  void _showSnack(String msg, {bool isError = false}) {
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(
      content: Text(msg),
      backgroundColor: isError ? Colors.red.shade700 : Colors.green.shade700,
    ));
  }

  // ── Build ─────────────────────────────────────────────────────────────────
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: _blue1,
      body: Stack(
        fit: StackFit.expand,
        children: [
          // Background gradient
          Container(
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
                colors: [_blue1, _blue2, Color(0xFF0A8FD1)],
              ),
            ),
          ),

          // Decorative circles
          Positioned(
            right: -80, top: -60,
            child: _circle(280, Colors.white.withOpacity(0.04)),
          ),
          Positioned(
            left: -100, bottom: -80,
            child: _circle(320, Colors.white.withOpacity(0.03)),
          ),

          // Watermark
          Center(
            child: Opacity(
              opacity: 0.05,
              child: Text(
                'FAST',
                style: TextStyle(
                  fontSize: 200,
                  fontWeight: FontWeight.w900,
                  color: Colors.white,
                  letterSpacing: -8,
                ),
              ),
            ),
          ),

          // Content
          SafeArea(
            child: Column(
              children: [
                _buildHeader(),
                Expanded(
                  child: RefreshIndicator(
                    onRefresh: _fetchFiles,
                    color: _accent,
                    child: _isLoading
                        ? const Center(
                            child: CircularProgressIndicator(color: Colors.white))
                        : _files.isEmpty
                            ? _buildEmptyState()
                            : _buildFileList(),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),

      // Upload FAB
      floatingActionButton: FloatingActionButton.extended(
        onPressed: _pickAndUpload,
        backgroundColor: _accent,
        foregroundColor: Colors.white,
        icon: const Icon(Icons.upload_file_rounded),
        label: const Text(
          'Upload Berkas',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
      ),
    );
  }

  // ── Header ────────────────────────────────────────────────────────────────
  Widget _buildHeader() {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(16),
        child: BackdropFilter(
          filter: ImageFilter.blur(sigmaX: 15, sigmaY: 15),
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 14),
            decoration: BoxDecoration(
              color: Colors.white.withOpacity(0.12),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: Colors.white.withOpacity(0.25)),
            ),
            child: Row(
              children: [
                // Avatar
                Container(
                  width: 44,
                  height: 44,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: _accent.withOpacity(0.3),
                    border: Border.all(color: Colors.white38, width: 1.5),
                  ),
                  child: const Icon(Icons.person, color: Colors.white, size: 24),
                ),
                const SizedBox(width: 12),
                // Name + role
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        _userName.isEmpty ? 'Pengguna' : _userName,
                        style: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                          fontSize: 15,
                        ),
                      ),
                      Text(
                        _userRole.isEmpty ? 'Vendor' : _userRole.toUpperCase(),
                        style: TextStyle(
                          color: Colors.white.withOpacity(0.65),
                          fontSize: 11,
                          letterSpacing: 0.8,
                        ),
                      ),
                    ],
                  ),
                ),
                // Logout
                IconButton(
                  onPressed: _logout,
                  icon: const Icon(Icons.logout_rounded, color: Colors.white70),
                  tooltip: 'Logout',
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  // ── File list ─────────────────────────────────────────────────────────────
  Widget _buildFileList() {
    return ListView(
      padding: const EdgeInsets.fromLTRB(16, 8, 16, 100),
      children: [
        // Section title
        Padding(
          padding: const EdgeInsets.only(bottom: 12),
          child: Row(
            children: [
              const Icon(Icons.folder_open_rounded, color: Colors.white70, size: 18),
              const SizedBox(width: 8),
              Text(
                'Berkas Perencanaan (${_files.length})',
                style: const TextStyle(
                  color: Colors.white70,
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                  letterSpacing: 0.5,
                ),
              ),
            ],
          ),
        ),
        ..._files.map((file) => _buildFileCard(file)),
      ],
    );
  }

  Widget _buildFileCard(dynamic file) {
    final name = file['nama_file'] ?? 'Unknown';
    final date = (file['created_at'] ?? '').toString();
    final path = file['path_file'] ?? '';
    final ext = name.split('.').last.toLowerCase();

    IconData icon;
    Color iconColor;
    switch (ext) {
      case 'pdf':
        icon = Icons.picture_as_pdf_rounded;
        iconColor = Colors.red.shade300;
        break;
      case 'xls':
      case 'xlsx':
      case 'csv':
        icon = Icons.table_chart_rounded;
        iconColor = Colors.green.shade300;
        break;
      case 'doc':
      case 'docx':
        icon = Icons.description_rounded;
        iconColor = Colors.blue.shade300;
        break;
      case 'png':
      case 'jpg':
      case 'jpeg':
        icon = Icons.image_rounded;
        iconColor = Colors.orange.shade300;
        break;
      default:
        icon = Icons.insert_drive_file_rounded;
        iconColor = Colors.white70;
    }

    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(14),
        child: BackdropFilter(
          filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
            decoration: BoxDecoration(
              color: Colors.white.withOpacity(0.1),
              borderRadius: BorderRadius.circular(14),
              border: Border.all(color: Colors.white.withOpacity(0.15)),
            ),
            child: Row(
              children: [
                // File icon
                Container(
                  width: 46,
                  height: 46,
                  decoration: BoxDecoration(
                    color: iconColor.withOpacity(0.15),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: Icon(icon, color: iconColor, size: 26),
                ),
                const SizedBox(width: 14),
                // Name + date
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        name,
                        style: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.w600,
                          fontSize: 13,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      const SizedBox(height: 3),
                      Text(
                        _formatDate(date),
                        style: TextStyle(
                          color: Colors.white.withOpacity(0.55),
                          fontSize: 11,
                        ),
                      ),
                    ],
                  ),
                ),
                // View button
                if (path.isNotEmpty)
                  TextButton.icon(
                    onPressed: () => _openFile(path),
                    icon: const Icon(Icons.open_in_new_rounded, size: 14),
                    label: const Text('Lihat', style: TextStyle(fontSize: 12)),
                    style: TextButton.styleFrom(
                      foregroundColor: Colors.lightBlue.shade200,
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                    ),
                  ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  // ── Empty state ───────────────────────────────────────────────────────────
  Widget _buildEmptyState() {
    return ListView(
      children: [
        SizedBox(height: MediaQuery.of(context).size.height * 0.2),
        Column(
          children: [
            Icon(
              Icons.folder_off_rounded,
              size: 72,
              color: Colors.white.withOpacity(0.25),
            ),
            const SizedBox(height: 16),
            Text(
              'Belum ada berkas',
              style: TextStyle(
                color: Colors.white.withOpacity(0.5),
                fontSize: 16,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              'Tekan tombol Upload untuk menambah berkas',
              style: TextStyle(
                color: Colors.white.withOpacity(0.35),
                fontSize: 13,
              ),
            ),
          ],
        ),
      ],
    );
  }

  // ── Utilities ─────────────────────────────────────────────────────────────
  Widget _circle(double size, Color color) {
    return Container(
      width: size,
      height: size,
      decoration: BoxDecoration(
        shape: BoxShape.circle,
        border: Border.all(color: color, width: 55),
      ),
    );
  }

  String _formatDate(String raw) {
    if (raw.isEmpty) return '';
    try {
      final dt = DateTime.parse(raw);
      return '${dt.day.toString().padLeft(2, '0')}/'
          '${dt.month.toString().padLeft(2, '0')}/'
          '${dt.year}  ${dt.hour.toString().padLeft(2, '0')}:'
          '${dt.minute.toString().padLeft(2, '0')}';
    } catch (_) {
      return raw;
    }
  }

  void _openFile(String path) {
    final url = 'http://127.0.0.1:8000/storage/$path';
    _showSnack('Membuka: $url');
    // url_launcher can be added later; for now show the URL
  }
}
