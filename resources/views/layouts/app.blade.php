<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WebGIS Nasional Limbah B3 Medis Fasyankes') - KLH / BPLH RI</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <!-- Leaflet MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css"/>
    <!-- Custom WebGIS CSS -->
    <link rel="stylesheet" href="{{ asset('css/webgis-custom.css') }}">
    
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Top Branding & Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-gov sticky-top">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div class="bg-white text-success rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-shield-check fs-5"></i>
                </div>
                <div>
                    <div class="fw-bold lh-1" style="font-size: 1.05rem;">WebGIS Nasional Limbah B3 Medis</div>
                    <div class="text-white-50 fw-normal" style="font-size: 0.72rem; letter-spacing: 0.5px;">Kementerian Lingkungan Hidup / Badan Pengendalian Lingkungan Hidup</div>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') || request()->is('webgis*') ? 'active' : '' }}" href="{{ url('/webgis') }}">
                            <i class="bi bi-geo-alt-fill me-1 text-warning"></i> Peta Interaktif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                            <i class="bi bi-bar-chart-line-fill me-1"></i> Dashboard Eksekutif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('gap-analysis*') ? 'active' : '' }}" href="{{ url('/gap-analysis') }}">
                            <i class="bi bi-pie-chart-fill me-1"></i> Analisis Kesenjangan (Gap)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('roadmap*') ? 'active' : '' }}" href="{{ url('/roadmap') }}">
                            <i class="bi bi-signpost-split-fill me-1 text-info"></i> Peta Jalan 10 Tahun
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('fasyankes*') || request()->is('treatment-facilities*') || request()->is('transfer-locations*') ? 'active' : '' }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-database-fill me-1"></i> Data Master
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item py-2" href="{{ url('/fasyankes') }}"><i class="bi bi-hospital me-2 text-danger"></i> Data Fasyankes & Timbulan</a></li>
                            <li><a class="dropdown-item py-2" href="{{ url('/treatment-facilities') }}"><i class="bi bi-fire me-2 text-warning"></i> Fasilitas Pengolahan Berizin</a></li>
                            <li><a class="dropdown-item py-2" href="{{ url('/transfer-locations') }}"><i class="bi bi-box-seam me-2 text-primary"></i> Lokasi Pemindahan (Depo Transfer)</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sub-header Ribbon -->
    <div class="sub-header-banner d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <span class="badge bg-success-subtle text-success border border-success-subtle me-2">TA 2026 - 2036</span>
            <span class="text-muted d-none d-md-inline">Sistem Pemantauan dan Pengambilan Keputusan Peta Jalan Pengelolaan Limbah B3 Medis Fasyankes</span>
        </div>
        <div class="text-muted" style="font-size: 0.78rem;">
            <i class="bi bi-broadcast text-success me-1"></i> Baseline Nasional Terverifikasi (38 Provinsi)
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white-50 py-3 mt-auto border-top border-secondary">
        <div class="container-fluid px-3 text-center text-md-between d-md-flex justify-content-between align-items-center" style="font-size: 0.8rem;">
            <div>
                &copy; 2026 <strong>Kementerian Lingkungan Hidup / Badan Pengendalian Lingkungan Hidup (KLH / BPLH RI)</strong>. Hak Cipta Dilindungi.
            </div>
            <div class="mt-2 mt-md-0">
                <span>Standar KAK Roadmap TPS B3 Medis Fasyankes</span>
                <span class="mx-2">•</span>
                <span class="text-success"><i class="bi bi-check-circle-fill"></i> Sistem Aktif</span>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    @stack('scripts')
</body>
</html>
