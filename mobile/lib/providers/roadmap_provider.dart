import 'dart:convert';
import 'package:flutter/material.dart';
import '../models/roadmap_action_model.dart';
import '../services/api_service.dart';
import '../core/constants/api_constants.dart';

class RoadmapProvider with ChangeNotifier {
  List<RoadmapActionModel> _actions = [];
  Map<String, dynamic> _summary = {};
  bool _isLoading = false;
  String? _errorMessage;
  String _selectedHorizon = 'all';

  List<RoadmapActionModel> get actions => _actions;
  Map<String, dynamic> get summary => _summary;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  String get selectedHorizon => _selectedHorizon;

  void setHorizon(String horizon) {
    _selectedHorizon = horizon;
    fetchRoadmap(horizon: horizon == 'all' ? null : horizon);
  }

  Future<void> fetchRoadmap({String? horizon}) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final queryParams = <String, String>{};
      if (horizon != null && horizon.isNotEmpty && horizon != 'all') {
        queryParams['horizon'] = horizon;
      }

      final uri = Uri.parse(ApiConstants.roadmapList).replace(queryParameters: queryParams);
      final res = await ApiService.get(uri.toString());

      if (res.statusCode == 200) {
        final json = jsonDecode(res.body);
        final data = json['data'] ?? {};
        final list = (data['actions'] as List? ?? [])
            .map((i) => RoadmapActionModel.fromJson(i))
            .toList();

        _actions = list;
        _summary = data['summary'] ?? {};
      } else {
        _errorMessage = 'Gagal memuat matriks roadmap.';
      }
    } catch (e) {
      _errorMessage = 'Gagal terhubung ke server: $e';
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> updateProgress(String actionId, int percent) async {
    try {
      final res = await ApiService.patch(
        ApiConstants.roadmapProgress(actionId),
        {'progress_percent': percent},
      );

      if (res.statusCode == 200) {
        final idx = _actions.indexWhere((a) => a.id == actionId);
        if (idx != -1) {
          _actions[idx].progressPercent = percent;
          notifyListeners();
        }
        return true;
      }
      return false;
    } catch (_) {
      return false;
    }
  }
}
