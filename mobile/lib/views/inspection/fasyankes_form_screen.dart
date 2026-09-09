import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:geolocator/geolocator.dart';
import '../../core/constants/app_colors.dart';
import '../../providers/fasyankes_provider.dart';

class FasyankesFormScreen extends StatefulWidget {
  const FasyankesFormScreen({super.key});

  @override
  State<FasyankesFormScreen> createState() => _FasyankesFormScreenState();
}

class _FasyankesFormScreenState extends State<FasyankesFormScreen> {
  final _formKey = GlobalKey<FormState>();

  final _nameController = TextEditingController();
  final _addressController = TextEditingController();
  final _latController = TextEditingController(text: '-6.208800');
  final _lngController = TextEditingController(text: '106.845600');
  final _bedCapacityController = TextEditingController(text: '50');
  final _dailyWasteController = TextEditingController(text: '40.0');

  String _selectedType = 'RS Kelas C';
  String? _selectedProvinceId;
  String? _selectedRegencyId;
  String _selectedPermitStatus = 'Memiliki Izin';
  String _selectedStorageMethod = 'Ruang Berpendingin/Cold Storage';

  bool _isGettingLocation = false;

  final List<String> _fasyankesTypes = [
    'RS Kelas A',
    'RS Kelas B',
    'RS Kelas C',
    'RS Kelas D',
    'Puskesmas',
    'Klinik Pratama',
  ];

  final List<String> _permitStatuses = [
    'Memiliki Izin',
    'Dalam Proses Perpanjangan',
    'Belum Memiliki Izin',
  ];

  final List<String> _storageMethods = [
    'Ruang Berpendingin/Cold Storage',
    'TPS B3 Standar',
    'Penyimpanan Sederhana',
  ];

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final prov = Provider.of<FasyankesProvider>(context, listen: false);
      prov.loadProvinces();
    });
  }

  // Ambil titik koordinat GPS aktual perangkat di lapangan
  Future<void> _getCurrentLocation() async {
    setState(() => _isGettingLocation = true);

    try {
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('GPS tidak aktif. Harap nyalakan GPS lokasi Anda.')),
          );
        }
        setState(() => _isGettingLocation = false);
        return;
      }

      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied) {
          if (mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Izin akses lokasi ditolak.')),
            );
          }
          setState(() => _isGettingLocation = false);
          return;
        }
      }

      final position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );

      setState(() {
        _latController.text = position.latitude.toStringAsFixed(6);
        _lngController.text = position.longitude.toStringAsFixed(6);
      });

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            backgroundColor: AppColors.successGreen,
            content: Text('Titik GPS berhasil didapatkan: ${position.latitude.toStringAsFixed(4)}, ${position.longitude.toStringAsFixed(4)}'),
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal membaca GPS: $e')),
        );
      }
    } finally {
      if (mounted) setState(() => _isGettingLocation = false);
    }
  }

  void _onBedCapacityChanged(String val) {
    final beds = int.tryParse(val) ?? 0;
    final waste = beds * 0.8;
    _dailyWasteController.text = waste.toStringAsFixed(1);
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) return;

    if (_selectedProvinceId == null || _selectedRegencyId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap pilih provinsi dan kabupaten/kota.')),
      );
      return;
    }

    final provider = Provider.of<FasyankesProvider>(context, listen: false);

    final payload = {
      'name': _nameController.text.trim(),
      'type': _selectedType,
      'province_id': _selectedProvinceId,
      'regency_id': _selectedRegencyId,
      'address': _addressController.text.trim(),
      'latitude': double.tryParse(_latController.text) ?? -6.2088,
      'longitude': double.tryParse(_lngController.text) ?? 106.8456,
      'bed_capacity': int.tryParse(_bedCapacityController.text) ?? 50,
      'tps_permit_status': _selectedPermitStatus,
      'storage_method': _selectedStorageMethod,
      'daily_generation_kg': double.tryParse(_dailyWasteController.text) ?? 40.0,
    };

    final success = await provider.createFasyankes(payload);

    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          backgroundColor: AppColors.successGreen,
          content: Text('Data fasyankes baru berhasil disimpan ke database!'),
        ),
      );
      Navigator.of(context).pop();
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          backgroundColor: AppColors.hazardRed,
          content: Text(provider.errorMessage ?? 'Gagal menyimpan fasyankes.'),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<FasyankesProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Input Fasyankes Lapangan'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Info Banner
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: AppColors.primaryContainer,
                  borderRadius: BorderRadius.circular(10),
                  border: Border.all(color: AppColors.primary.withOpacity(0.3)),
                ),
                child: const Row(
                  children: [
                    Icon(Icons.info, color: AppColors.primary, size: 20),
                    SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        'Form inspeksi verifikasi izin TPS dan timbulan limbah B3 medis fasyankes langsung dari lokasi.',
                        style: TextStyle(fontSize: 12, color: AppColors.primaryDark),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 16),

              // Nama Fasyankes
              const Text('Nama Fasilitas Pelayanan Kesehatan *', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              const SizedBox(height: 6),
              TextFormField(
                controller: _nameController,
                decoration: const InputDecoration(hintText: 'Contoh: RSUD Pratama Mandiri'),
                validator: (v) => (v == null || v.isEmpty) ? 'Nama fasyankes wajib diisi' : null,
              ),
              const SizedBox(height: 14),

              // Jenis Fasyankes
              const Text('Jenis / Klasifikasi Fasyankes *', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              const SizedBox(height: 6),
              DropdownButtonFormField<String>(
                value: _selectedType,
                decoration: const InputDecoration(),
                items: _fasyankesTypes.map((t) => DropdownMenuItem(value: t, child: Text(t))).toList(),
                onChanged: (val) => setState(() => _selectedType = val!),
              ),
              const SizedBox(height: 14),

              // Provinsi & Kabupaten (Cascading)
              const Text('Provinsi Administrasi *', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              const SizedBox(height: 6),
              DropdownButtonFormField<String>(
                value: _selectedProvinceId,
                decoration: const InputDecoration(hintText: '-- Pilih Provinsi --'),
                items: provider.provinces.map((p) => DropdownMenuItem(value: p.id, child: Text(p.name))).toList(),
                onChanged: (val) {
                  setState(() {
                    _selectedProvinceId = val;
                    _selectedRegencyId = null;
                  });
                  if (val != null) provider.loadRegencies(val);
                },
              ),
              const SizedBox(height: 14),

              const Text('Kabupaten / Kota *', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              const SizedBox(height: 6),
              DropdownButtonFormField<String>(
                value: _selectedRegencyId,
                decoration: InputDecoration(
                  hintText: provider.isLoadingRegencies ? 'Memuat kabupaten...' : '-- Pilih Kabupaten/Kota --',
                ),
                items: provider.regencies.map((r) => DropdownMenuItem(value: r.id, child: Text(r.name))).toList(),
                onChanged: (val) => setState(() => _selectedRegencyId = val),
              ),
              const SizedBox(height: 14),

              // GPS Coordinates Card
              Card(
                margin: EdgeInsets.zero,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10),
                  side: const BorderSide(color: AppColors.border),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('Titik Koordinat Spasial GPS *', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                          OutlinedButton.icon(
                            onPressed: _isGettingLocation ? null : _getCurrentLocation,
                            icon: _isGettingLocation
                                ? const SizedBox(width: 14, height: 14, child: CircularProgressIndicator(strokeWidth: 2))
                                : const Icon(Icons.my_location, size: 16),
                            label: const Text('Ambil GPS Saya', style: TextStyle(fontSize: 12)),
                            style: OutlinedButton.styleFrom(
                              foregroundColor: AppColors.primary,
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          Expanded(
                            child: TextFormField(
                              controller: _latController,
                              keyboardType: const TextInputType.numberWithOptions(decimal: true, signed: true),
                              decoration: const InputDecoration(labelText: 'Latitude (Lintang)'),
                            ),
                          ),
                          const SizedBox(width: 10),
                          Expanded(
                            child: TextFormField(
                              controller: _lngController,
                              keyboardType: const TextInputType.numberWithOptions(decimal: true, signed: true),
                              decoration: const InputDecoration(labelText: 'Longitude (Bujur)'),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 14),

              // Kapasitas & Timbulan
              Row(
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Tempat Tidur (TT) *', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _bedCapacityController,
                          keyboardType: TextInputType.number,
                          onChanged: _onBedCapacityChanged,
                          decoration: const InputDecoration(suffixText: 'TT'),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Timbulan (kg/hari)', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                        const SizedBox(height: 6),
                        TextFormField(
                          controller: _dailyWasteController,
                          keyboardType: const TextInputType.numberWithOptions(decimal: true),
                          decoration: const InputDecoration(suffixText: 'kg/hr'),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 14),

              // Status Izin TPS & Penyimpanan
              const Text('Status Izin TPS Limbah B3 *', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              const SizedBox(height: 6),
              DropdownButtonFormField<String>(
                value: _selectedPermitStatus,
                items: _permitStatuses.map((s) => DropdownMenuItem(value: s, child: Text(s))).toList(),
                onChanged: (val) => setState(() => _selectedPermitStatus = val!),
              ),
              const SizedBox(height: 14),

              const Text('Metode Penyimpanan TPS *', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              const SizedBox(height: 6),
              DropdownButtonFormField<String>(
                value: _selectedStorageMethod,
                items: _storageMethods.map((m) => DropdownMenuItem(value: m, child: Text(m))).toList(),
                onChanged: (val) => setState(() => _selectedStorageMethod = val!),
              ),
              const SizedBox(height: 14),

              // Alamat
              const Text('Alamat Lengkap', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
              const SizedBox(height: 6),
              TextFormField(
                controller: _addressController,
                maxLines: 2,
                decoration: const InputDecoration(hintText: 'Nama jalan, kelurahan, kecamatan...'),
              ),
              const SizedBox(height: 24),

              // Tombol Simpan
              ElevatedButton.icon(
                onPressed: provider.isLoading ? null : _submitForm,
                icon: const Icon(Icons.save),
                label: provider.isLoading
                    ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                    : const Text('Simpan Data Fasyankes ke Sistem', style: TextStyle(fontWeight: FontWeight.bold)),
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(vertical: 14),
                ),
              ),
              const SizedBox(height: 24),
            ],
          ),
        ),
      ),
    );
  }
}
