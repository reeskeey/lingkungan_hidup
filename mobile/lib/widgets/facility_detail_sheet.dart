import 'package:flutter/material.dart';
import '../core/constants/app_colors.dart';
import '../models/fasyankes_model.dart';
import '../models/treatment_facility_model.dart';
import '../models/transfer_location_model.dart';
import 'status_badge.dart';

class FacilityDetailSheet extends StatelessWidget {
  final dynamic entity;

  const FacilityDetailSheet({super.key, required this.entity});

  @override
  Widget build(BuildContext context) {
    if (entity is FasyankesModel) {
      return _buildFasyankesSheet(context, entity as FasyankesModel);
    } else if (entity is TreatmentFacilityModel) {
      return _buildFacilitySheet(context, entity as TreatmentFacilityModel);
    } else if (entity is TransferLocationModel) {
      return _buildTransferSheet(context, entity as TransferLocationModel);
    }
    return const SizedBox.shrink();
  }

  Widget _buildFasyankesSheet(BuildContext context, FasyankesModel f) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: AppColors.hazardRed.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: const Icon(Icons.local_hospital, color: AppColors.hazardRed, size: 28),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      f.name,
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      '${f.type} • ${f.regency}, ${f.province}',
                      style: const TextStyle(color: AppColors.textSecondary, fontSize: 12),
                    ),
                  ],
                ),
              ),
              StatusBadge.permit(f.tpsPermitStatus),
            ],
          ),
          const Divider(height: 24),
          Row(
            children: [
              _infoTile('Kapasitas TT', '${f.bedCapacity} TT', Icons.hotel),
              _infoTile('Timbulan Harian', '${f.dailyGenerationKg.toStringAsFixed(1)} kg/hari', Icons.delete_outline),
              _infoTile('Penyimpanan', f.storageMethod, Icons.inventory_2_outlined),
            ],
          ),
          const SizedBox(height: 16),
          if (f.address != null && f.address!.isNotEmpty)
            Text(
              'Alamat: ${f.address}',
              style: const TextStyle(fontSize: 12, color: AppColors.textSecondary),
            ),
          const SizedBox(height: 12),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () => Navigator.pop(context),
              icon: const Icon(Icons.check, size: 16),
              label: const Text('Tutup'),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFacilitySheet(BuildContext context, TreatmentFacilityModel fac) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: AppColors.warningOrange.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: const Icon(Icons.whatshot, color: AppColors.warningOrange, size: 28),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      fac.name,
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      '${fac.type} • ${fac.regency}, ${fac.province}',
                      style: const TextStyle(color: AppColors.textSecondary, fontSize: 12),
                    ),
                  ],
                ),
              ),
              StatusBadge(label: fac.status, color: AppColors.successGreen),
            ],
          ),
          const Divider(height: 24),
          Row(
            children: [
              _infoTile('Kapasitas Izin', '${fac.licensedCapTonDay.toStringAsFixed(1)} ton/hari', Icons.verified),
              _infoTile('Kapasitas Terpasang', '${fac.installedCapKgH.toInt()} kg/jam', Icons.speed),
              _infoTile('Kategori', fac.category, Icons.business),
            ],
          ),
          const SizedBox(height: 12),
          if (fac.permitNumber != null)
            Text(
              'No. Izin SK: ${fac.permitNumber}',
              style: const TextStyle(fontSize: 12, color: AppColors.textSecondary, fontFamily: 'monospace'),
            ),
          const SizedBox(height: 12),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () => Navigator.pop(context),
              icon: const Icon(Icons.check, size: 16),
              label: const Text('Tutup'),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTransferSheet(BuildContext context, TransferLocationModel loc) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: const EdgeInsets.all(10),
                decoration: BoxDecoration(
                  color: AppColors.coldStorageBlue.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: const Icon(Icons.ac_unit, color: AppColors.coldStorageBlue, size: 28),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      loc.name,
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      'Lokasi Pemindahan • ${loc.regency}, ${loc.province}',
                      style: const TextStyle(color: AppColors.textSecondary, fontSize: 12),
                    ),
                  ],
                ),
              ),
              StatusBadge(
                label: loc.hasColdStorage ? 'Cold Storage Ada' : 'Standar',
                color: loc.hasColdStorage ? AppColors.coldStorageBlue : AppColors.textSecondary,
              ),
            ],
          ),
          const Divider(height: 24),
          Row(
            children: [
              _infoTile('Daya Tampung', '${loc.holdingCapTon.toStringAsFixed(1)} ton', Icons.inventory_2),
              _infoTile('Target Layanan', '${loc.targetFasyankes} Fasyankes', Icons.domain),
              _infoTile('Status', loc.status, Icons.check_circle_outline),
            ],
          ),
          const SizedBox(height: 16),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () => Navigator.pop(context),
              icon: const Icon(Icons.check, size: 16),
              label: const Text('Tutup'),
            ),
          ),
        ],
      ),
    );
  }

  Widget _infoTile(String label, String value, IconData icon) {
    return Expanded(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(icon, size: 14, color: AppColors.textMuted),
              const SizedBox(width: 4),
              Flexible(
                child: Text(
                  label,
                  style: const TextStyle(fontSize: 10, color: AppColors.textMuted),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
            ],
          ),
          const SizedBox(height: 4),
          Text(
            value,
            style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: AppColors.textPrimary),
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
          ),
        ],
      ),
    );
  }
}
