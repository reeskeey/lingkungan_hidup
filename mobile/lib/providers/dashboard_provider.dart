import 'dart:convert';
import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../core/constants/api_constants.dart';

class DashboardProvider with ChangeNotifier {
  Map<String, dynamic> _kpi = {};
  Map<String, dynamic> _wasteComposition = {};
  List<dynamic> _topDeficits = [];
  Map<String, dynamic> _horizonSummary = {};
  bool _isLoading = false;
  String? _errorMessage;

  Map<String, dynamic> get kpi => _kpi;
  Map<String, dynamic> get wasteComposition => _wasteComposition;
  List<dynamic> get topDeficits => _topDeficits;
  Map<String, dynamic> get horizonSummary => _horizonSummary;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  Future<void> fetchSummary() async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final res = await ApiService.get(ApiConstants.dashboardSummary);

      if (res.statusCode == 200) {
        final json = jsonDecode(res.body);
        final data = json['data'] ?? {};

        _kpi = data['kpi'] ?? {};
        _wasteComposition = data['waste_composition'] ?? {};
        _topDeficits = data['top_deficits'] ?? [];
        _horizonSummary = data['horizon_summary'] ?? {};
      } else {
        _errorMessage = 'Gagal memuat ringkasan dashboard.';
      }
    } catch (e) {
      _errorMessage = 'Gagal terhubung ke API: $e';
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
