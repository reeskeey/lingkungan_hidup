class TransferLocationModel {
  final String id;
  final String name;
  final String province;
  final String regency;
  final String? address;
  final double latitude;
  final double longitude;
  final double holdingCapTon;
  final bool hasColdStorage;
  final String status;
  final int targetFasyankes;

  TransferLocationModel({
    required this.id,
    required this.name,
    required this.province,
    required this.regency,
    this.address,
    required this.latitude,
    required this.longitude,
    required this.holdingCapTon,
    required this.hasColdStorage,
    required this.status,
    required this.targetFasyankes,
  });

  factory TransferLocationModel.fromJson(Map<String, dynamic> json) {
    return TransferLocationModel(
      id: json['id']?.toString() ?? '',
      name: json['name']?.toString() ?? '',
      province: json['province']?.toString() ?? '',
      regency: json['regency']?.toString() ?? '',
      address: json['address']?.toString(),
      latitude: (json['lat'] ?? 0.0).toDouble(),
      longitude: (json['lng'] ?? 0.0).toDouble(),
      holdingCapTon: (json['holding_cap_ton'] ?? 0.0).toDouble(),
      hasColdStorage: json['has_cold_storage'] == true,
      status: json['status']?.toString() ?? 'Aktif Beroperasi',
      targetFasyankes: (json['target_fasyankes'] ?? 0).toInt(),
    );
  }
}
