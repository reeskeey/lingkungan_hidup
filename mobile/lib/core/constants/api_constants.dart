import 'dart:io';

class ApiConstants {
  // Default base URL disesuaikan dengan platform (Android Emulator vs iOS / Desktop / LAN)
  static String get defaultBaseUrl {
    try {
      if (Platform.isAndroid) {
        return 'http://10.0.2.2:8000/api/v1';
      }
    } catch (_) {}
    return 'http://127.0.0.1:8000/api/v1';
  }

  static String baseUrl = defaultBaseUrl;

  // Endpoints
  static String get login => '$baseUrl/auth/login';
  static String get logout => '$baseUrl/auth/logout';
  static String get userProfile => '$baseUrl/user';
  static String get dashboardSummary => '$baseUrl/dashboard/summary';
  static String get webGisData => '$baseUrl/webgis/data';
  static String get fasyankesList => '$baseUrl/fasyankes';
  static String get fasyankesStore => '$baseUrl/fasyankes';
  static String get provinces => '$baseUrl/provinces';
  static String regencies(String provinceId) => '$baseUrl/provinces/$provinceId/regencies';
  static String get roadmapList => '$baseUrl/roadmap';
  static String roadmapProgress(String actionId) => '$baseUrl/roadmap/$actionId/progress';
}
