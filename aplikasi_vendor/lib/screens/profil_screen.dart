import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:ui';

class ProfilScreen extends StatefulWidget {
  const ProfilScreen({super.key});

  @override
  State<ProfilScreen> createState() => _ProfilScreenState();
}

class _ProfilScreenState extends State<ProfilScreen> {
  String _userName = '';
  String _userRole = '';

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
  }

  Future<void> _loadUser() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _userName = prefs.getString('user_name') ?? 'Vendor';
      _userRole = prefs.getString('user_role') ?? 'Vendor PT';
    });
  }

  Future<void> _logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();
    if (mounted) {
      Navigator.pushReplacementNamed(context, '/login');
    }
  }

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
              children: [
                // Title bar
                Padding(
                  padding: const EdgeInsets.fromLTRB(20, 16, 20, 0),
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
                            Container(
                              width: 36, height: 36,
                              decoration: BoxDecoration(
                                shape: BoxShape.circle,
                                color: Colors.white.withOpacity(0.15),
                              ),
                              child: const Icon(Icons.person_rounded, color: Colors.white, size: 20),
                            ),
                            const SizedBox(width: 12),
                            const Text(
                              'Profil Saya',
                              style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 17),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),

                const SizedBox(height: 40),

                // Profile card
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 20),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(20),
                    child: BackdropFilter(
                      filter: ImageFilter.blur(sigmaX: 18, sigmaY: 18),
                      child: Container(
                        width: double.infinity,
                        padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 36),
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.15),
                          borderRadius: BorderRadius.circular(20),
                          border: Border.all(color: Colors.white.withOpacity(0.3), width: 1.2),
                        ),
                        child: Column(
                          children: [
                            // Avatar
                            Container(
                              width: 80, height: 80,
                              decoration: BoxDecoration(
                                shape: BoxShape.circle,
                                color: _accent.withOpacity(0.3),
                                border: Border.all(color: Colors.white38, width: 2),
                              ),
                              child: const Icon(Icons.business_rounded, color: Colors.white, size: 40),
                            ),
                            const SizedBox(height: 16),
                            Text(
                              _userName.isEmpty ? 'Vendor' : _userName,
                              style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.white),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              _userRole.isEmpty ? 'VENDOR' : _userRole.toUpperCase(),
                              style: TextStyle(fontSize: 12, color: Colors.white.withOpacity(0.6), letterSpacing: 1.2),
                            ),
                            const SizedBox(height: 32),

                            // Info rows
                            _buildInfoRow(Icons.badge_outlined, 'Role', _userRole.isEmpty ? 'Vendor' : _userRole),
                            const SizedBox(height: 12),
                            _buildInfoRow(Icons.account_circle_outlined, 'Nama', _userName.isEmpty ? '-' : _userName),

                            const SizedBox(height: 32),

                            // Logout button
                            SizedBox(
                              width: double.infinity,
                              height: 48,
                              child: ElevatedButton.icon(
                                onPressed: _logout,
                                icon: const Icon(Icons.logout_rounded),
                                label: const Text('Keluar (Logout)', style: TextStyle(fontWeight: FontWeight.bold)),
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.red.withOpacity(0.3),
                                  foregroundColor: Colors.white,
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
                                  elevation: 0,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),

                const Spacer(),

                // Footer
                Padding(
                  padding: const EdgeInsets.only(bottom: 16),
                  child: Text(
                    'FULL ACCELERATION & SERVICE TRACKING ON\n360°',
                    textAlign: TextAlign.center,
                    style: TextStyle(fontSize: 10, letterSpacing: 0.8, color: Colors.white.withOpacity(0.4), height: 1.6),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoRow(IconData icon, String label, String value) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.08),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.white.withOpacity(0.1)),
      ),
      child: Row(
        children: [
          Icon(icon, color: Colors.white60, size: 20),
          const SizedBox(width: 12),
          Text(label, style: TextStyle(color: Colors.white.withOpacity(0.5), fontSize: 12)),
          const Spacer(),
          Text(value, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 13)),
        ],
      ),
    );
  }
}
