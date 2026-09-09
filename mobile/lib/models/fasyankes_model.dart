class FasyankesModel {
  final String id;
  final String name;
  final String type;
  final String province;
  final String? provinceId;
  final String regency;
  final String? regencyId;
  final String? address;
  final double latitude;
  final double longitude;
  final int bedCapacity;
  final String tpsPermitStatus;
  final String storageMethod;
  final double dailyGenerationKg;
  final String? createdAt;

  FasyankesModel({
    required this.id,
    required this.name,
    required this.type,
    required this.province,
    this.provinceId,
    required this.regency,
    this.regencyId,
    this.address,
    required this.latitude,
    required this.longitude,
    required this.bedCapacity,
    required this.tpsPermitStatus,
    required this.storageMethod,
    required this.dailyGenerationKg,
    this.createdAt,
  });

  factory FasyankesModel.fromJson(Map<String, dynamic> json) {
    return FasyankesModel(
      id: json['id']?.toString() ?? '',
      name: json['name']?.toString() ?? '',
      type: json['type']?.toString() ?? 'RS Kelas C',
      province: json['province']?.toString() ?? '',
      provinceId: json['province_id']?.toString(),
      regency: json['regency']?.toString() ?? '',
      regencyId: json['regency_id']?.toString(),
      address: json['address']?.toString(),
      latitude: (json['lat'] ?? json['latitude'] ?? -6.2088).toDouble(),
      longitude: (json['lng'] ?? json['longitude'] ?? 106.8456).toDouble(),
      bedCapacity: (json['beds'] ?? json['bed_capacity'] ?? 0).toInt(),
      tpsPermitStatus: json['permit_status'] ?? json['tps_permit_status'] ?? 'Belum Memiliki Izin',
      storageMethod: json['storage_method']?.toString() ?? 'TPS B3 Standar',
      dailyGenerationKg: (json['daily_waste_kg'] ?? json['daily_generation_kg'] ?? 0.0).toDouble(),
      createdAt: json['created_at']?.toString(),
    );
  }

  bool get isLicensed => tpsPermitStatus == 'Memiliki Izin';
}
