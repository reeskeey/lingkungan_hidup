class ProvinceModel {
  final String id;
  final String name;
  final double latitude;
  final double longitude;

  ProvinceModel({
    required this.id,
    required this.name,
    required this.latitude,
    required this.longitude,
  });

  factory ProvinceModel.fromJson(Map<String, dynamic> json) {
    return ProvinceModel(
      id: json['id']?.toString() ?? '',
      name: json['name']?.toString() ?? '',
      latitude: (json['latitude'] ?? json['lat'] ?? 0.0).toDouble(),
      longitude: (json['longitude'] ?? json['lng'] ?? 0.0).toDouble(),
    );
  }
}

class RegencyModel {
  final String id;
  final String name;
  final double latitude;
  final double longitude;

  RegencyModel({
    required this.id,
    required this.name,
    required this.latitude,
    required this.longitude,
  });

  factory RegencyModel.fromJson(Map<String, dynamic> json) {
    return RegencyModel(
      id: json['id']?.toString() ?? '',
      name: json['name']?.toString() ?? '',
      latitude: (json['latitude'] ?? 0.0).toDouble(),
      longitude: (json['longitude'] ?? 0.0).toDouble(),
    );
  }
}
