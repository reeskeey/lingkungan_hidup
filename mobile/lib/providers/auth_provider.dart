import 'dart:convert';
import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';
import '../core/constants/api_constants.dart';

class AuthProvider with ChangeNotifier {
  UserModel? _currentUser;
  bool _isLoading = false;
  String? _errorMessage;

  UserModel? get currentUser => _currentUser;
  bool get isAuthenticated => _currentUser != null;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  Future<void> tryAutoLogin() async {
    _isLoading = true;
    notifyListeners();

    try {
      final token = await ApiService.getToken();
      final userJson = await ApiService.getSavedUser();

      if (token != null && userJson != null) {
        _currentUser = UserModel.fromJson(userJson);
      }
    } catch (_) {
      await ApiService.clearAuth();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final res = await ApiService.post(ApiConstants.login, {
        'email': email,
        'password': password,
      });

      final json = jsonDecode(res.body);

      if (res.statusCode == 200 && json['success'] == true) {
        final token = json['token'];
        final userMap = json['user'];
        _currentUser = UserModel.fromJson(userMap);
        await ApiService.saveAuth(token, userMap);
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _errorMessage = json['message'] ?? 'Login gagal. Periksa email dan password.';
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _errorMessage = 'Koneksi ke server gagal. Pastikan backend Laravel berjalan.';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    try {
      await ApiService.post(ApiConstants.logout, {});
    } catch (_) {}

    await ApiService.clearAuth();
    _currentUser = null;
    notifyListeners();
  }
}
