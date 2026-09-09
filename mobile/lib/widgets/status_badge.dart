import 'package:flutter/material.dart';
import '../core/constants/app_colors.dart';

class StatusBadge extends StatelessWidget {
  final String label;
  final Color color;
  final IconData? icon;

  const StatusBadge({
    super.key,
    required this.label,
    required this.color,
    this.icon,
  });

  factory StatusBadge.permit(String status) {
    if (status == 'Memiliki Izin') {
      return const StatusBadge(
        label: 'Berizin Resmi',
        color: AppColors.successGreen,
        icon: Icons.check_circle,
      );
    } else if (status.contains('Perpanjangan')) {
      return const StatusBadge(
        label: 'Proses Perpanjangan',
        color: AppColors.warningOrange,
        icon: Icons.hourglass_top,
      );
    } else {
      return const StatusBadge(
        label: 'Belum Memiliki Izin',
        color: AppColors.hazardRed,
        icon: Icons.warning_amber_rounded,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(
        color: color.withOpacity(0.12),
        borderRadius: BorderRadius.circular(6),
        border: Border.all(color: color.withOpacity(0.3)),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (icon != null) ...[
            Icon(icon, size: 12, color: color),
            const SizedBox(width: 4),
          ],
          Text(
            label,
            style: TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w600,
              color: color,
            ),
          ),
        ],
      ),
    );
  }
}
