import 'package:flutter/material.dart';
import 'dart:ui';

class HistoryScreen extends StatelessWidget {
  const HistoryScreen({super.key});

  // ── Colours (same as login) ──
  static const Color _blue1 = Color(0xFF0A1A6B);
  static const Color _blue2 = Color(0xFF0D3B8E);
  static const Color _blue3 = Color(0xFF0A5FB4);
  static const Color _blue4 = Color(0xFF0A8FD1);

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
                              child: const Icon(Icons.history_rounded, color: Colors.white, size: 20),
                            ),
                            const SizedBox(width: 12),
                            const Text(
                              'Riwayat Berkas',
                              style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 17),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),

                // Empty state
                Expanded(
                  child: Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.history_rounded, size: 72, color: Colors.white.withOpacity(0.2)),
                        const SizedBox(height: 16),
                        Text(
                          'Belum ada riwayat',
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.w600,
                            color: Colors.white.withOpacity(0.5),
                          ),
                        ),
                        const SizedBox(height: 6),
                        Text(
                          'Riwayat pengiriman berkas akan tampil disini',
                          style: TextStyle(
                            fontSize: 13,
                            color: Colors.white.withOpacity(0.35),
                          ),
                        ),
                      ],
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
}
