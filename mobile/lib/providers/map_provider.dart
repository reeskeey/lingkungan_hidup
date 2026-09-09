import 'dart:convert';
import 'package:flutter/material.dart';
import '../models/fasyankes_model.dart';
import '../models/treatment_facility_model.dart';
import '../models/transfer_location_model.dart';
import '../services/api_service.dart';
import '../core/constants/api_constants.dart';

class MapProvider with ChangeNotifier {
  List<FasyankesModel> _fasyankes = [];
  List<TreatmentFacilityModel> _facilities = [];
  List<TransferLocationModel> _transferLocations = [];
  bool _isLoading = false;
  String? _errorMessage;

  // Toggle visibilitas layer
  bool showFasyankes = true;
  bool showFacilities = true;
  bool showTransferLocations = true;

  // Entitas yang dipilih untuk bottom sheet
  dynamic selectedEntity;

  List<FasyankesModel> get fasyankes => _fasyankes;
  List<TreatmentFacilityModel> get facilities => _facilities;
  List<TransferLocationModel> get transferLocations => _transferLocations;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;

  void toggleLayer(String layer) {
    if (layer == 'fasyankes') showFasyankes = !showFasyankes;
    if (layer == 'facilities') showFacilities = !showFacilities;
    if (layer == 'transfer') showTransferLocations = !showTransferLocations;
    notifyListeners();
  }

  void selectEntity(dynamic entity) {
    selectedEntity = entity;
    notifyListeners();
  }

  void clearSelection() {
    selectedEntity = null;
    notifyListeners();
  }

  Future<void> fetchMapData({String? provinceId, String? fasyankesType, String? gapStatus}) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final queryParams = <String, String>{};
      if (provinceId != null && provinceId.isNotEmpty) queryParams['province_id'] = provinceId;
      if (fasyankesType != null && fasyankesType.isNotEmpty) queryParams['fasyankes_type'] = fasyankesType;
      if (gapStatus != null && gapStatus.isNotEmpty) queryParams['gap_status'] = gapStatus;

      final uri = Uri.parse(ApiConstants.webGisData).replace(queryParameters: queryParams);
      final res = await ApiService.get(uri.toString());

      if (res.statusCode == 200) {
        final json = jsonDecode(res.body);
        final data = json['data'] ?? json;

        _fasyankes = (data['fasyankes'] as List? ?? [])
            .map((i) => FasyankesModel.fromJson(i))
            .toList();

        _facilities = (data['treatment_facilities'] as List? ?? [])
            .map((i) => TreatmentFacilityModel.fromJson(i))
            .toList();

        _transferLocations = (data['transfer_locations'] as List? ?? [])
            .map((i) => TransferLocationModel.fromJson(i))
            .toList();
      } else {
        _errorMessage = 'Gagal memuat data geospasial.';
      }
    } catch (e) {
      _errorMessage = 'Gagal terhubung ke API WebGIS: $e';
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
