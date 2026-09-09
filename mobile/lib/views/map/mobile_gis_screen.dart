import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';
import '../../core/constants/app_colors.dart';
import '../../providers/map_provider.dart';
import '../../widgets/facility_detail_sheet.dart';

class MobileGisScreen extends StatefulWidget {
  const MobileGisScreen({super.key});

  @override
  State<MobileGisScreen> createState() => _MobileGisScreenState();
}

class _MobileGisScreenState extends State<MobileGisScreen> {
  final MapController _mapController = MapController();
  final LatLng _indonesiaCenter = const LatLng(-2.548926, 118.0148634); // Pusat Indonesia

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<MapProvider>(context, listen: false).fetchMapData();
    });
  }

  void _showFacilityDetails(dynamic entity) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => FacilityDetailSheet(entity: entity),
    );
  }

  @override
  Widget build(BuildContext context) {
    final mapProvider = Provider.of<MapProvider>(context);

    // Bangun daftar marker dari data aktif
    final markers = <Marker>[];

    if (mapProvider.showFasyankes) {
      for (final f in mapProvider.fasyankes) {
        markers.add(
          Marker(
            point: LatLng(f.latitude, f.longitude),
            width: 36,
            height: 36,
            child: GestureDetector(
              onTap: () => _showFacilityDetails(f),
              child: Container(
                decoration: BoxDecoration(
                  color: AppColors.hazardRed,
                  shape: BoxShape.circle,
                  border: Border.all(color: Colors.white, width: 2),
                  boxShadow: const [
                    BoxShadow(color: Colors.black26, blurRadius: 4, offset: Offset(0, 2)),
                  ],
                ),
                child: const Icon(Icons.local_hospital, color: Colors.white, size: 18),
              ),
            ),
          ),
        );
      }
    }

    if (mapProvider.showFacilities) {
      for (final fac in mapProvider.facilities) {
        markers.add(
          Marker(
            point: LatLng(fac.latitude, fac.longitude),
            width: 38,
            height: 38,
            child: GestureDetector(
              onTap: () => _showFacilityDetails(fac),
              child: Container(
                decoration: BoxDecoration(
                  color: AppColors.warningOrange,
                  shape: BoxShape.circle,
                  border: Border.all(color: Colors.white, width: 2),
                  boxShadow: const [
                    BoxShadow(color: Colors.black26, blurRadius: 4, offset: Offset(0, 2)),
                  ],
                ),
                child: const Icon(Icons.whatshot, color: Colors.white, size: 20),
              ),
            ),
          ),
        );
      }
    }

    if (mapProvider.showTransferLocations) {
      for (final loc in mapProvider.transferLocations) {
        markers.add(
          Marker(
            point: LatLng(loc.latitude, loc.longitude),
            width: 38,
            height: 38,
            child: GestureDetector(
              onTap: () => _showFacilityDetails(loc),
              child: Container(
                decoration: BoxDecoration(
                  color: AppColors.coldStorageBlue,
                  shape: BoxShape.circle,
                  border: Border.all(color: Colors.white, width: 2),
                  boxShadow: const [
                    BoxShadow(color: Colors.black26, blurRadius: 4, offset: Offset(0, 2)),
                  ],
                ),
                child: const Icon(Icons.ac_unit, color: Colors.white, size: 18),
              ),
            ),
          ),
        );
      }
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('WebGIS Limbah B3 Medis'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => mapProvider.fetchMapData(),
            tooltip: 'Muat Ulang Peta',
          ),
        ],
      ),
      body: Stack(
        children: [
          // FlutterMap Canvas
          FlutterMap(
            mapController: _mapController,
            options: MapOptions(
              initialCenter: _indonesiaCenter,
              initialZoom: 5.0,
              minZoom: 3.5,
              maxZoom: 18.0,
            ),
            children: [
              TileLayer(
                urlTemplate: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                userAgentPackageName: 'id.go.klh.mobile',
              ),
              MarkerLayer(markers: markers),
            ],
          ),

          // Floating Layer Controls di atas peta
          Positioned(
            top: 12,
            left: 12,
            right: 12,
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                children: [
                  _layerFilterChip(
                    label: 'Fasyankes (${mapProvider.fasyankes.length})',
                    isSelected: mapProvider.showFasyankes,
                    color: AppColors.hazardRed,
                    icon: Icons.local_hospital,
                    onTap: () => mapProvider.toggleLayer('fasyankes'),
                  ),
                  const SizedBox(width: 8),
                  _layerFilterChip(
                    label: 'Pengolah (${mapProvider.facilities.length})',
                    isSelected: mapProvider.showFacilities,
                    color: AppColors.warningOrange,
                    icon: Icons.whatshot,
                    onTap: () => mapProvider.toggleLayer('facilities'),
                  ),
                  const SizedBox(width: 8),
                  _layerFilterChip(
                    label: 'Lokasi Pemindahan (${mapProvider.transferLocations.length})',
                    isSelected: mapProvider.showTransferLocations,
                    color: AppColors.coldStorageBlue,
                    icon: Icons.ac_unit,
                    onTap: () => mapProvider.toggleLayer('transfer'),
                  ),
                ],
              ),
            ),
          ),

          // Loading Overlay
          if (mapProvider.isLoading)
            Positioned(
              top: 70,
              left: 0,
              right: 0,
              child: Center(
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  decoration: BoxDecoration(
                    color: Colors.black87,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: const Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      SizedBox(width: 14, height: 14, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2)),
                      SizedBox(width: 8),
                      Text('Memuat sebaran spasial...', style: TextStyle(color: Colors.white, fontSize: 12)),
                    ],
                  ),
                ),
              ),
            ),

          // Tombol Reset View Kamera
          Positioned(
            bottom: 20,
            right: 16,
            child: FloatingActionButton.small(
              heroTag: 'recenter_map',
              backgroundColor: Colors.white,
              foregroundColor: AppColors.primary,
              onPressed: () {
                _mapController.move(_indonesiaCenter, 5.0);
              },
              child: const Icon(Icons.center_focus_strong),
            ),
          ),
        ],
      ),
    );
  }

  Widget _layerFilterChip({
    required String label,
    required bool isSelected,
    required Color color,
    required IconData icon,
    required VoidCallback onTap,
  }) {
    return FilterChip(
      avatar: Icon(icon, size: 14, color: isSelected ? Colors.white : color),
      label: Text(label),
      selected: isSelected,
      onSelected: (_) => onTap(),
      selectedColor: color,
      backgroundColor: Colors.white,
      labelStyle: TextStyle(
        fontSize: 12,
        fontWeight: FontWeight.bold,
        color: isSelected ? Colors.white : AppColors.textPrimary,
      ),
      elevation: 2,
      padding: const EdgeInsets.symmetric(horizontal: 4),
    );
  }
}
