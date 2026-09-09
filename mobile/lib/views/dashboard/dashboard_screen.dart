import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/constants/app_colors.dart';
import '../../providers/dashboard_provider.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/kpi_card.dart';
import '../auth/login_screen.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<DashboardProvider>(context, listen: false).fetchSummary();
    });
  }

  void _handleLogout() async {
    final auth = Provider.of<AuthProvider>(context, listen: false);
    await auth.logout();
    if (mounted) {
      Navigator.of(context).pushReplacement(
        MaterialPageRoute(builder: (_) => const LoginScreen()),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final dashboard = Provider.of<DashboardProvider>(context);
    final auth = Provider.of<AuthProvider>(context);
    final kpi = dashboard.kpi;
    final user = auth.currentUser;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Dashboard & Akun Petugas'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => dashboard.fetchSummary(),
            tooltip: 'Segarkan Data',
          ),
        ],
      ),
      body: dashboard.isLoading
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: () => dashboard.fetchSummary(),
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    // Profil Petugas Card
                    Card(
                      margin: EdgeInsets.zero,
                      color: AppColors.primaryContainer,
                      child: Padding(
                        padding: const EdgeInsets.all(16),
                        child: Row(
                          children: [
                            CircleAvatar(
                              backgroundColor: AppColors.primary,
                              radius: 24,
                              child: const Icon(Icons.person, color: Colors.white, size: 28),
                            ),
                            const SizedBox(width: 14),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    user?.name ?? 'Petugas Lapangan',
                                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15),
                                  ),
                                  const SizedBox(height: 2),
                                  Text(
                                    user?.email ?? 'Petugas Tamu',
                                    style: const TextStyle(fontSize: 12, color: AppColors.textSecondary),
                                  ),
                                  const SizedBox(height: 4),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                    decoration: BoxDecoration(
                                      color: AppColors.primary,
                                      borderRadius: BorderRadius.circular(4),
                                    ),
                                    child: Text(
                                      user?.roleDisplayTitle ?? 'Petugas KLH',
                                      style: const TextStyle(fontSize: 10, color: Colors.white, fontWeight: FontWeight.bold),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            IconButton(
                              icon: const Icon(Icons.logout, color: AppColors.hazardRed),
                              onPressed: _handleLogout,
                              tooltip: 'Keluar',
                            ),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 18),

                    const Text(
                      'Indikator Kinerja Nasional (KPI)',
                      style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 10),

                    KpiCard(
                      title: 'Estimasi Timbulan Medis Nasional',
                      value: '${kpi['total_waste_ton_day'] ?? 0} ton/hari',
                      subtitle: 'Akumulasi dari ${kpi['total_fasyankes'] ?? 0} Fasilitas Pelayanan Kesehatan',
                      icon: Icons.delete_sweep,
                      iconColor: AppColors.hazardRed,
                    ),

                    KpiCard(
                      title: 'Kapasitas Pengolahan Berizin',
                      value: '${kpi['total_capacity_ton_day'] ?? 0} ton/hari',
                      subtitle: 'Insinerator & Autoklaf berizin resmi KLH',
                      icon: Icons.whatshot,
                      iconColor: AppColors.successGreen,
                    ),

                    KpiCard(
                      title: 'Rasio Ketercakupan Pengolahan',
                      value: '${kpi['national_coverage_ratio'] ?? 0}%',
                      subtitle: 'Rasio agregat kapasitas terhadap timbulan limbah',
                      icon: Icons.pie_chart,
                      iconColor: AppColors.secondary,
                    ),

                    KpiCard(
                      title: 'Kepatuhan Izin TPS Fasyankes',
                      value: '${kpi['licensed_tps_percent'] ?? 0}%',
                      subtitle: '${kpi['licensed_tps_count'] ?? 0} fasyankes berizin resmi',
                      icon: Icons.verified,
                      iconColor: AppColors.primary,
                    ),

                    const SizedBox(height: 18),

                    // 5 Wilayah Defisit Terbesar
                    if (dashboard.topDeficits.isNotEmpty) ...[
                      const Text(
                        'Prioritas Wilayah Defisit Pengolahan Terbesar',
                        style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 10),
                      Card(
                        margin: EdgeInsets.zero,
                        child: ListView.separated(
                          shrinkWrap: true,
                          physics: const NeverScrollableScrollPhysics(),
                          itemCount: dashboard.topDeficits.take(5).length,
                          separatorBuilder: (_, __) => const Divider(height: 1),
                          itemBuilder: (context, idx) {
                            final d = dashboard.topDeficits[idx];
                            return ListTile(
                              dense: true,
                              leading: CircleAvatar(
                                radius: 14,
                                backgroundColor: AppColors.hazardRed.withOpacity(0.12),
                                child: Text(
                                  '${idx + 1}',
                                  style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: AppColors.hazardRed),
                                ),
                              ),
                              title: Text(
                                d['province'] ?? '',
                                style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
                              ),
                              subtitle: Text(
                                'Cakupan: ${d['coverage_ratio']}%',
                                style: const TextStyle(fontSize: 11),
                              ),
                              trailing: Text(
                                '-${(d['deficit_ton_day'] as num?)?.toStringAsFixed(1) ?? 0} ton/hr',
                                style: const TextStyle(fontWeight: FontWeight.bold, color: AppColors.hazardRed),
                              ),
                            );
                          },
                        ),
                      ),
                    ],
                  ],
                ),
              ),
            ),
    );
  }
}
