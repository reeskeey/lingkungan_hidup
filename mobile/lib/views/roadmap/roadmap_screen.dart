import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../../core/constants/app_colors.dart';
import '../../providers/roadmap_provider.dart';
import '../../providers/auth_provider.dart';
import '../../models/roadmap_action_model.dart';

class RoadmapScreen extends StatefulWidget {
  const RoadmapScreen({super.key});

  @override
  State<RoadmapScreen> createState() => _RoadmapScreenState();
}

class _RoadmapScreenState extends State<RoadmapScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final _currencyFormat = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

  final List<String> _horizons = ['all', 'Tahun 1-2', 'Tahun 3-5', 'Tahun 6-10'];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    _tabController.addListener(() {
      if (!_tabController.indexIsChanging) {
        final horizon = _horizons[_tabController.index];
        Provider.of<RoadmapProvider>(context, listen: false).setHorizon(horizon);
      }
    });

    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<RoadmapProvider>(context, listen: false).fetchRoadmap();
    });
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  void _showUpdateProgressDialog(RoadmapActionModel act) {
    double currentVal = act.progressPercent.toDouble();

    showDialog(
      context: context,
      builder: (ctx) => StatefulBuilder(
        builder: (context, setDialogState) => AlertDialog(
          title: const Text('Update Progres Aksi', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                act.programName,
                style: const TextStyle(fontSize: 13, color: AppColors.textSecondary),
              ),
              const SizedBox(height: 16),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Capaian Progres:'),
                  Text(
                    '${currentVal.toInt()}%',
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: AppColors.primary),
                  ),
                ],
              ),
              Slider(
                value: currentVal,
                min: 0,
                max: 100,
                divisions: 20,
                activeColor: AppColors.primary,
                onChanged: (v) => setDialogState(() => currentVal = v),
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () async {
                final prov = Provider.of<RoadmapProvider>(context, listen: false);
                final success = await prov.updateProgress(act.id, currentVal.toInt());
                if (ctx.mounted) Navigator.pop(ctx);
                if (mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      backgroundColor: success ? AppColors.successGreen : AppColors.hazardRed,
                      content: Text(success ? 'Progres capaian berhasil disimpan!' : 'Gagal memperbarui progres.'),
                    ),
                  );
                }
              },
              child: const Text('Simpan'),
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final roadmapProvider = Provider.of<RoadmapProvider>(context);
    final auth = Provider.of<AuthProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Peta Jalan 10 Tahun (2026-2036)'),
        bottom: TabBar(
          controller: _tabController,
          isScrollable: true,
          labelColor: Colors.white,
          unselectedLabelColor: Colors.white70,
          indicatorColor: Colors.white,
          tabs: const [
            Tab(text: 'Semua Tahap'),
            Tab(text: 'Tahun 1-2 (Pendek)'),
            Tab(text: 'Tahun 3-5 (Menengah)'),
            Tab(text: 'Tahun 6-10 (Panjang)'),
          ],
        ),
      ),
      body: roadmapProvider.isLoading
          ? const Center(child: CircularProgressIndicator())
          : roadmapProvider.actions.isEmpty
              ? const Center(child: Text('Tidak ada rencana aksi pada horizon ini.'))
              : RefreshIndicator(
                  onRefresh: () => roadmapProvider.fetchRoadmap(),
                  child: ListView.builder(
                    padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 8),
                    itemCount: roadmapProvider.actions.length,
                    itemBuilder: (context, index) {
                      final act = roadmapProvider.actions[index];
                      return Card(
                        child: Padding(
                          padding: const EdgeInsets.all(14.0),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Expanded(
                                    child: Text(
                                      act.programName,
                                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                                    ),
                                  ),
                                  if (auth.isAuthenticated)
                                    IconButton(
                                      icon: const Icon(Icons.edit_note, color: AppColors.primary, size: 24),
                                      onPressed: () => _showUpdateProgressDialog(act),
                                      tooltip: 'Ubah Progres Status',
                                    ),
                                ],
                              ),
                              const SizedBox(height: 6),
                              Row(
                                children: [
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                    decoration: BoxDecoration(
                                      color: AppColors.secondaryContainer,
                                      borderRadius: BorderRadius.circular(4),
                                    ),
                                    child: Text(
                                      act.timeHorizon,
                                      style: const TextStyle(fontSize: 10, color: AppColors.secondary, fontWeight: FontWeight.bold),
                                    ),
                                  ),
                                  const SizedBox(width: 8),
                                  Text(
                                    act.responsibleAgency,
                                    style: const TextStyle(fontSize: 11, color: AppColors.textSecondary),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 10),
                              if (act.kpi != null)
                                Text(
                                  'KPI: ${act.kpi}',
                                  style: const TextStyle(fontSize: 11, color: AppColors.textPrimary),
                                ),
                              const SizedBox(height: 6),
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    'Anggaran: ${_currencyFormat.format(act.indicativeBudget)}',
                                    style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: AppColors.textPrimary),
                                  ),
                                  Text(
                                    '${act.progressPercent}%',
                                    style: TextStyle(
                                      fontWeight: FontWeight.bold,
                                      fontSize: 12,
                                      color: act.progressPercent > 50 ? AppColors.successGreen : AppColors.secondary,
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 6),
                              LinearProgressIndicator(
                                value: act.progressPercent / 100.0,
                                backgroundColor: AppColors.border,
                                color: act.progressPercent > 50 ? AppColors.successGreen : AppColors.primary,
                                minHeight: 6,
                                borderRadius: BorderRadius.circular(3),
                              ),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
                ),
    );
  }
}
