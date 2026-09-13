import 'dart:convert';
import 'dart:ui';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class HistoryScreen extends StatefulWidget {
  const HistoryScreen({super.key});

  @override
  State<HistoryScreen> createState() => _HistoryScreenState();
}

class _HistoryScreenState extends State<HistoryScreen> {
  static const _baseUrl = 'http://127.0.0.1:8000/api';
  static const _blue1 = Color(0xFF0A1A6B);
  static const _blue2 = Color(0xFF0D3B8E);
  static const _blue3 = Color(0xFF0A5FB4);
  static const _blue4 = Color(0xFF0A8FD1);
  static const _accent = Color(0xFF1E90FF);
  static const _options = [
    'Dokumen pekerjaan lengkap',
    'Pekerjaan sesuai WO',
    'Foto dokumentasi terlampir',
    'Siap ditindaklanjuti Perencanaan',
  ];

  List<dynamic> _reports = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _loadReports();
  }

  Future<String?> _token() async => (await SharedPreferences.getInstance()).getString('token');

  Future<void> _loadReports() async {
    setState(() => _loading = true);
    try {
      final response = await http.get(
        Uri.parse('$_baseUrl/vendor/laporan'),
        headers: {'Authorization': 'Bearer ${await _token()}', 'Accept': 'application/json'},
      );
      if (response.statusCode != 200) throw Exception();
      if (mounted) setState(() => _reports = jsonDecode(response.body)['data'] ?? []);
    } catch (_) {
      _snack('Riwayat belum dapat dimuat.', error: true);
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  Future<bool> _update(dynamic report, Set<String> checklist, String note, List<PlatformFile> files) async {
    final request = http.MultipartRequest('POST', Uri.parse('$_baseUrl/vendor/laporan/${report['id']}/update'))
      ..headers.addAll({'Authorization': 'Bearer ${await _token()}', 'Accept': 'application/json'})
      ..fields['catatan'] = note;
    for (var i = 0; i < checklist.length; i++) {
      request.fields['checklist[$i]'] = checklist.elementAt(i);
    }
    for (final file in files) {
      request.files.add(http.MultipartFile.fromBytes('files[]', await file.readAsBytes(), filename: file.name));
    }
    try {
      if ((await request.send()).statusCode != 200) throw Exception();
      _snack('Riwayat berhasil diperbarui.');
      await _loadReports();
      return true;
    } catch (_) {
      _snack('Riwayat gagal diperbarui.', error: true);
      return false;
    }
  }

  Future<void> _delete(dynamic report) async {
    final ok = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Hapus pengiriman?'),
        content: Text('Laporan agenda ${report['no_agenda']} beserta berkasnya akan dihapus.'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context, false), child: const Text('Batal')),
          FilledButton(onPressed: () => Navigator.pop(context, true), style: FilledButton.styleFrom(backgroundColor: Colors.redAccent), child: const Text('Hapus')),
        ],
      ),
    );
    if (ok != true) return;
    try {
      final response = await http.delete(
        Uri.parse('$_baseUrl/vendor/laporan/${report['id']}'),
        headers: {'Authorization': 'Bearer ${await _token()}', 'Accept': 'application/json'},
      );
      if (response.statusCode != 200) throw Exception();
      _snack('Riwayat pengiriman dihapus.');
      await _loadReports();
    } catch (_) {
      _snack('Riwayat gagal dihapus.', error: true);
    }
  }

  void _edit(dynamic report) {
    final note = TextEditingController(text: (report['catatan'] ?? '').toString());
    final selected = <String>{...((report['checklist'] as List? ?? []).map((v) => v.toString()))};
    final addedFiles = <PlatformFile>[];
    final oldFiles = report['files'] as List? ?? [];
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => StatefulBuilder(
        builder: (context, sheetSetState) => Padding(
          padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
          child: Container(
            constraints: BoxConstraints(maxHeight: MediaQuery.of(context).size.height * .88),
            padding: const EdgeInsets.fromLTRB(20, 20, 20, 24),
            decoration: const BoxDecoration(color: _blue1, borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
            child: SingleChildScrollView(
              child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                const Text('Edit Pengiriman', style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                Text('No. agenda: ${report['no_agenda']}', style: const TextStyle(color: Colors.lightBlueAccent, fontSize: 12)),
                const SizedBox(height: 12),
                ..._options.map((option) => CheckboxListTile(
                      value: selected.contains(option),
                      onChanged: (value) => sheetSetState(() => value == true ? selected.add(option) : selected.remove(option)),
                      title: Text(option, style: const TextStyle(color: Colors.white, fontSize: 13)),
                      activeColor: _accent,
                      contentPadding: EdgeInsets.zero,
                      controlAffinity: ListTileControlAffinity.leading,
                    )),
                TextField(
                  controller: note,
                  maxLines: 3,
                  style: const TextStyle(color: Colors.white),
                  decoration: const InputDecoration(labelText: 'Catatan untuk Perencanaan', labelStyle: TextStyle(color: Colors.white70), enabledBorder: OutlineInputBorder(borderSide: BorderSide(color: Colors.white38)), focusedBorder: OutlineInputBorder(borderSide: BorderSide(color: Colors.lightBlueAccent))),
                ),
                const SizedBox(height: 12),
                if (oldFiles.isNotEmpty) ...[
                  const Text('Berkas yang sudah dikirim', style: TextStyle(color: Colors.white70, fontSize: 12)),
                  ...oldFiles.map((file) => Text('• ${file['nama_file']}', style: const TextStyle(color: Colors.white60, fontSize: 12))),
                  const SizedBox(height: 8),
                ],
                OutlinedButton.icon(
                  onPressed: () async {
                    final result = await FilePicker.pickFiles(allowMultiple: true, withData: true, type: FileType.custom, allowedExtensions: ['pdf', 'jpg', 'jpeg', 'png']);
                    if (result != null) sheetSetState(() => addedFiles.addAll(result));
                  },
                  icon: const Icon(Icons.attach_file_rounded),
                  label: const Text('Tambah berkas'),
                  style: OutlinedButton.styleFrom(foregroundColor: Colors.white),
                ),
                ...addedFiles.map((file) => ListTile(
                      dense: true,
                      contentPadding: EdgeInsets.zero,
                      title: Text(file.name, style: const TextStyle(color: Colors.white70, fontSize: 12)),
                      trailing: IconButton(onPressed: () => sheetSetState(() => addedFiles.remove(file)), icon: const Icon(Icons.close_rounded, color: Colors.white70)),
                    )),
                const SizedBox(height: 12),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: () async {
                      if (await _update(report, selected, note.text.trim(), addedFiles) && context.mounted) Navigator.pop(context);
                    },
                    icon: const Icon(Icons.save_rounded),
                    label: const Text('Simpan Perubahan'),
                    style: ElevatedButton.styleFrom(backgroundColor: _accent, foregroundColor: Colors.white),
                  ),
                ),
              ]),
            ),
          ),
        ),
      ),
    );
  }

  void _snack(String message, {bool error = false}) {
    if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(message), backgroundColor: error ? Colors.redAccent : Colors.green));
  }

  @override
  Widget build(BuildContext context) => Scaffold(
        body: Container(
          decoration: const BoxDecoration(gradient: LinearGradient(begin: Alignment.topLeft, end: Alignment.bottomRight, colors: [_blue1, _blue2, _blue3, _blue4])),
          child: SafeArea(
            child: Column(children: [
              Padding(
                padding: const EdgeInsets.fromLTRB(20, 16, 20, 10),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(16),
                  child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 15, sigmaY: 15),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                      color: Colors.white.withOpacity(.12),
                      child: Row(children: [
                        const Icon(Icons.history_rounded, color: Colors.white),
                        const SizedBox(width: 12),
                        const Expanded(child: Text('Riwayat Pengiriman', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 17))),
                        IconButton(onPressed: _loadReports, icon: const Icon(Icons.refresh_rounded, color: Colors.white)),
                      ]),
                    ),
                  ),
                ),
              ),
              Expanded(child: _body()),
            ]),
          ),
        ),
      );

  Widget _body() {
    if (_loading) return const Center(child: CircularProgressIndicator(color: Colors.white));
    if (_reports.isEmpty) return Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [Icon(Icons.history_rounded, size: 72, color: Colors.white.withOpacity(.2)), const SizedBox(height: 16), Text('Belum ada riwayat', style: TextStyle(color: Colors.white.withOpacity(.6), fontSize: 16, fontWeight: FontWeight.w600)), const SizedBox(height: 6), Text('Pengiriman Anda akan tampil di sini', style: TextStyle(color: Colors.white.withOpacity(.4), fontSize: 13))]));
    return RefreshIndicator(
      onRefresh: _loadReports,
      color: _accent,
      child: ListView.builder(
        padding: const EdgeInsets.fromLTRB(20, 6, 20, 24),
        itemCount: _reports.length,
        itemBuilder: (context, i) {
          final report = _reports[i];
          final files = report['files'] as List? ?? [];
          final checklist = report['checklist'] as List? ?? [];
          final date = (report['created_at'] ?? '').toString().replaceFirst('T', ' ').split('.').first;
          return Container(
            margin: const EdgeInsets.only(bottom: 12),
            padding: const EdgeInsets.all(14),
            decoration: BoxDecoration(color: Colors.white.withOpacity(.1), borderRadius: BorderRadius.circular(14), border: Border.all(color: Colors.white.withOpacity(.16))),
            child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              Row(children: [
                const Icon(Icons.assignment_turned_in_rounded, color: Colors.lightBlueAccent, size: 20),
                const SizedBox(width: 8),
                Expanded(child: Text('Agenda ${report['no_agenda'] ?? '-'}', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold))),
                IconButton(onPressed: () => _edit(report), icon: const Icon(Icons.edit_rounded, color: Colors.lightBlueAccent), tooltip: 'Edit'),
                IconButton(onPressed: () => _delete(report), icon: const Icon(Icons.delete_outline_rounded, color: Colors.redAccent), tooltip: 'Hapus'),
              ]),
              Text(date, style: TextStyle(color: Colors.white.withOpacity(.5), fontSize: 11)),
              const SizedBox(height: 8),
              Text((report['catatan'] ?? '').toString().isNotEmpty ? report['catatan'].toString() : 'Tanpa catatan', style: TextStyle(color: Colors.white.withOpacity(.85), fontSize: 12)),
              const SizedBox(height: 8),
              Wrap(spacing: 6, runSpacing: 5, children: [
                Chip(label: Text('${files.length} berkas', style: const TextStyle(fontSize: 11)), backgroundColor: Colors.lightBlueAccent.withOpacity(.2), labelStyle: const TextStyle(color: Colors.lightBlueAccent)),
                ...checklist.map((item) => Chip(label: Text(item.toString(), style: const TextStyle(fontSize: 10)), backgroundColor: Colors.white.withOpacity(.1), labelStyle: const TextStyle(color: Colors.white70))),
              ]),
            ]),
          );
        },
      ),
    );
  }
}
