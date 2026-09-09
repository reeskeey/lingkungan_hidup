import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static const String _tokenKey = 'auth_bearer_token';
  static const String _userKey = 'auth_user_data';

  // Menyimpan token ke penyimpanan lokal perangkat
  static Future<void> saveAuth(String token, Map<String, dynamic> userJson) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
    await prefs.setString(_userKey, jsonEncode(userJson));
  }

  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_tokenKey);
  }

  static Future<Map<String, dynamic>?> getSavedUser() async {
    final prefs = await SharedPreferences.getInstance();
    final data = prefs.getString(_userKey);
    if (data == null) return null;
    try {
      return jsonDecode(data) as Map<String, dynamic>;
    } catch (_) {
      return null;
    }
  }

  static Future<void> clearAuth() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
    await prefs.remove(_userKey);
  }

  static Future<Map<String, String>> _headers() async {
    final token = await getToken();
    final headers = {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };
    if (token != null) {
      headers['Authorization'] = 'Bearer $token';
    }
    return headers;
  }

  // HTTP GET
  static Future<http.Response> get(String url) async {
    final headers = await _headers();
    return await http.get(Uri.parse(url), headers: headers);
  }

  // HTTP POST
  static Future<http.Response> post(String url, Map<String, dynamic> body) async {
    final headers = await _headers();
    return await http.post(
      Uri.parse(url),
      headers: headers,
      body: jsonEncode(body),
    );
  }

  // HTTP PATCH
  static Future<http.Response> patch(String url, Map<String, dynamic> body) async {
    final headers = await _headers();
    return await http.patch(
      Uri.parse(url),
      headers: headers,
      body: jsonEncode(body),
    );
  }
}
