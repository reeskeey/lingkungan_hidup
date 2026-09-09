import 'dart:convert';
import 'package:flutter/material.dart';
import '../models/fasyankes_model.dart';
import '../models/province_model.dart';
import '../services/api_service.dart';
import '../core/constants/api_constants.dart';

class FasyankesProvider with ChangeNotifier {
  List<FasyankesModel> _fasyankesList = [];
  bool _isLoading = false;
  String? _errorMessage;

  List<ProvinceModel> _provinces = [];
  List<RegencyModel> _regencies = [];
  bool _isLoadingProvinces = false;
  bool _isLoadingRegencies = false;

  List<FasyankesModel> get fasyankesList => _fasyankesList;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  List<ProvinceModel> get provinces => _provinces;
  List<RegencyModel> get regencies => _regencies;
  bool get isLoadingProvinces => _isLoadingProvinces;
  bool get isLoadingRegencies => _isLoadingRegencies;

  Future<void> fetchFasyankes({String? search, String? provinceId, String? type}) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final queryParams = <String, String>{};
      if (search != null && search.isNotEmpty) queryParams['search'] = search;
      if (provinceId != null && provinceId.isNotEmpty) queryParams['province_id'] = provinceId;
      if (type != null && type.isNotEmpty) queryParams['type'] = type;

      final uri = Uri.parse(ApiConstants.fasyankesList).replace(queryParameters: queryParams);
      final res = await ApiService.get(uri.toString());

      if (res.statusCode == 200) {
        final json = jsonDecode(res.body);
        final list = (json['data'] as List).map((i) => FasyankesModel.fromJson(i)).toList();
        _fasyankesList = list;
      } else {
        _errorMessage = 'Gagal memuat data fasyankes.';
      }
    } catch (e) {
      _errorMessage = 'Gagal terhubung ke server: $e';
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> createFasyankes(Map<String, dynamic> payload) async {
    _isLoading = true;
    notifyListeners();

    try {
      final res = await ApiService.post(ApiConstants.fasyankesStore, payload);
      final json = jsonDecode(res.body);

      if (res.statusCode == 201 && json['success'] == true) {
        await fetchFasyankes();
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _errorMessage = json['message'] ?? 'Gagal menyimpan data fasyankes.';
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _errorMessage = 'Kesalahan jaringan: $e';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> loadProvinces() async {
    if (_provinces.isNotEmpty) return;
    _isLoadingProvinces = true;
    notifyListeners();

    try {
      final res = await ApiService.get(ApiConstants.provinces);
      if (res.statusCode == 200) {
        final json = jsonDecode(res.body);
        _provinces = (json['data'] as List).map((p) => ProvinceModel.fromJson(p)).toList();
      }
    } catch (_) {} finally {
      _isLoadingProvinces = false;
      notifyListeners();
    }
  }

  Future<void> loadRegencies(String provinceId) async {
    _isLoadingRegencies = true;
    _regencies = [];
    notifyListeners();

    try {
      final res = await ApiService.get(ApiConstants.regencies(provinceId));
      if (res.statusCode == 200) {
        final json = jsonDecode(res.body);
        _regencies = (json['data'] as List).map((r) => RegencyModel.fromJson(r)).toList();
      }
    } catch (_) {} finally {
      _isLoadingRegencies = false;
      notifyListeners();
    }
  }
}
