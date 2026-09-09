import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/constants/app_colors.dart';
import '../../providers/fasyankes_provider.dart';
import '../../widgets/status_badge.dart';
import 'fasyankes_form_screen.dart';

class FasyankesListScreen extends StatefulWidget {
  const FasyankesListScreen({super.key});

  @override
  State<FasyankesListScreen> createState() => _FasyankesListScreenState();
}

class _FasyankesListScreenState extends State<FasyankesListScreen> {
  final _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<FasyankesProvider>(context, listen: false).fetchFasyankes();
    });
  }

  void _onSearch(String value) {
    Provider.of<FasyankesProvider>(context, listen: false).fetchFasyankes(search: value);
  }

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<FasyankesProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Inspeksi & Data Fasyankes'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => provider.fetchFasyankes(),
            tooltip: 'Segarkan Data',
          ),
        ],
      ),
      body: Column(
        children: [
          // Search & Filter Box
          Container(
            color: Colors.white,
            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
            child: TextField(
              controller: _searchController,
              onSubmitted: _onSearch,
              decoration: InputDecoration(
                hintText: 'Cari nama fasyankes / rumah sakit...',
                prefixIcon: const Icon(Icons.search, size: 20),
                suffixIcon: _searchController.text.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear, size: 18),
                        onPressed: () {
                          _searchController.clear();
                          _onSearch('');
                        },
                      )
                    : null,
                contentPadding: const EdgeInsets.symmetric(vertical: 0, horizontal: 12),
              ),
            ),
          ),

          // Content List
          Expanded(
            child: provider.isLoading
                ? const Center(child: CircularProgressIndicator())
                : provider.fasyankesList.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.inbox, size: 48, color: Colors.grey[400]),
                            const SizedBox(height: 12),
                            const Text('Tidak ada data fasyankes.'),
                          ],
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: () => provider.fetchFasyankes(),
                        child: ListView.builder(
                          padding: const EdgeInsets.symmetric(vertical: 8),
                          itemCount: provider.fasyankesList.length,
                          itemBuilder: (context, index) {
                            final item = provider.fasyankesList[index];
                            return Card(
                              child: ListTile(
                                contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                                leading: Container(
                                  width: 44,
                                  height: 44,
                                  decoration: BoxDecoration(
                                    color: AppColors.primaryContainer,
                                    borderRadius: BorderRadius.circular(10),
                                  ),
                                  child: const Icon(Icons.local_hospital, color: AppColors.primary),
                                ),
                                title: Text(
                                  item.name,
                                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                                ),
                                subtitle: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    const SizedBox(height: 4),
                                    Text(
                                      '${item.type} • ${item.regency}, ${item.province}',
                                      style: const TextStyle(fontSize: 11, color: AppColors.textSecondary),
                                    ),
                                    const SizedBox(height: 6),
                                    Row(
                                      children: [
                                        StatusBadge.permit(item.tpsPermitStatus),
                                        const SizedBox(width: 8),
                                        Text(
                                          '${item.dailyGenerationKg.toStringAsFixed(1)} kg/hari',
                                          style: const TextStyle(
                                            fontSize: 11,
                                            fontWeight: FontWeight.bold,
                                            color: AppColors.hazardRed,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                                trailing: const Icon(Icons.chevron_right, color: AppColors.textMuted),
                              ),
                            );
                          },
                        ),
                      ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () {
          Navigator.of(context).push(
            MaterialPageRoute(builder: (_) => const FasyankesFormScreen()),
          );
        },
        backgroundColor: AppColors.primary,
        foregroundColor: Colors.white,
        icon: const Icon(Icons.add_location_alt),
        label: const Text('Input Fasyankes Baru', style: TextStyle(fontWeight: FontWeight.bold)),
      ),
    );
  }
}
