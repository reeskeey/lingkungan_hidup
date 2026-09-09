class UserModel {
  final String id;
  final String name;
  final String email;
  final String role;
  final String? provinceId;
  final bool isSuperadmin;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    this.provinceId,
    required this.isSuperadmin,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id']?.toString() ?? '',
      name: json['name']?.toString() ?? '',
      email: json['email']?.toString() ?? '',
      role: json['role']?.toString() ?? 'operator_daerah',
      provinceId: json['province_id']?.toString(),
      isSuperadmin: json['is_superadmin'] == true,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'role': role,
      'province_id': provinceId,
      'is_superadmin': isSuperadmin,
    };
  }

  String get roleDisplayTitle {
    if (isSuperadmin) return 'Superadmin KLH / BPLH';
    return 'Operator DLH Daerah';
  }
}
