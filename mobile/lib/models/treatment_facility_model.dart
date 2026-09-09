class TreatmentFacilityModel {
  final String id;
  final String name;
  final String type;
  final String category;
  final String province;
  final String regency;
  final double latitude;
  final double longitude;
  final double installedCapKgH;
  final double licensedCapTonDay;
  final String? permitNumber;
  final String status;

  TreatmentFacilityModel({
    required this.id,
    required this.name,
    required this.type,
    required this.category,
    required this.province,
    required this.regency,
    required this.latitude,
    required this.longitude,
    required this.installedCapKgH,
    required this.licensedCapTonDay,
    this.permitNumber,
    required this.status,
  });

  factory TreatmentFacilityModel.fromJson(Map<String, dynamic> json) {
    return TreatmentFacilityModel(
      id: json['id']?.toString() ?? '',
      name: json['name']?.toString() ?? '',
      type: json['type']?.toString() ?? 'Insinerator Berizin',
      category: json['category']?.toString() ?? 'Komersial / Jasa Pengolah',
      province: json['province']?.toString() ?? '',
      regency: json['regency']?.toString() ?? '',
      latitude: (json['lat'] ?? 0.0).toDouble(),
      longitude: (json['lng'] ?? 0.0).toDouble(),
      installedCapKgH: (json['installed_cap_kg_h'] ?? 0.0).toDouble(),
      licensedCapTonDay: (json['licensed_cap_ton_day'] ?? 0.0).toDouble(),
      permitNumber: json['permit_number']?.toString(),
      status: json['status']?.toString() ?? 'Beroperasi Aktif',
    );
  }
}
