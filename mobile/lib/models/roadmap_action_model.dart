class RoadmapActionModel {
  final String id;
  final String programName;
  final String? baseline;
  final String? target;
  final String priorityLocation;
  final String timeHorizon;
  final String responsibleAgency;
  final String? supportingAgency;
  final double indicativeBudget;
  final String? kpi;
  final String? programOutput;
  final String fundingSource;
  int progressPercent;

  RoadmapActionModel({
    required this.id,
    required this.programName,
    this.baseline,
    this.target,
    required this.priorityLocation,
    required this.timeHorizon,
    required this.responsibleAgency,
    this.supportingAgency,
    required this.indicativeBudget,
    this.kpi,
    this.programOutput,
    required this.fundingSource,
    required this.progressPercent,
  });

  factory RoadmapActionModel.fromJson(Map<String, dynamic> json) {
    return RoadmapActionModel(
      id: json['id']?.toString() ?? '',
      programName: json['program_name']?.toString() ?? '',
      baseline: json['baseline']?.toString(),
      target: json['target']?.toString(),
      priorityLocation: json['priority_location']?.toString() ?? 'Nasional',
      timeHorizon: json['time_horizon']?.toString() ?? 'Tahun 1-2 (2026-2027)',
      responsibleAgency: json['responsible_agency']?.toString() ?? 'KLH',
      supportingAgency: json['supporting_agency']?.toString(),
      indicativeBudget: (json['indicative_budget'] ?? 0.0).toDouble(),
      kpi: json['kpi']?.toString(),
      programOutput: json['program_output']?.toString(),
      fundingSource: json['funding_source']?.toString() ?? 'APBN',
      progressPercent: (json['progress_percent'] ?? 0).toInt(),
    );
  }
}
