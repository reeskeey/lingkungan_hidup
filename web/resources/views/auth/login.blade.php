@extends('layouts.app')

@section('title', 'Masuk Sistem - KLH / BPLH RI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Header Card -->
                <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #0d3b13 0%, #1b5e20 60%, #0d47a1 100%);">
                    <div class="bg-white text-success rounded-circle p-3 d-inline-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 58px; height: 58px;">
                        <i class="bi bi-shield-lock-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Masuk Sistem KLH / BPLH</h5>
                    <p class="small text-white-50 mb-0">Sistem Peta Jalan (Roadmap) Pengelolaan Limbah B3 Medis Fasyankes</p>
                </div>

                <!-- Form Card -->
                <div class="card-body p-4 p-md-5 bg-white">
                    @if(session('success'))
                    <div class="alert alert-success small py-2">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger small py-2">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Alamat Email Resmi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" id="inputEmail" class="form-control bg-light border-start-0" placeholder="nama@klh.go.id" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="password" id="inputPassword" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 small">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                                <label class="form-check-label text-muted" for="remember">Ingat Saya</label>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-lock me-1"></i>Akses Terproteksi</span>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-semibold shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
                        </button>
                    </form>

                    <!-- Quick Demo Credentials -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="text-muted small fw-semibold mb-2 text-center">Akun Akses Default (Klik untuk Mengisi):</div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-sm btn-outline-success text-start py-2" onclick="fillCredentials('admin@klh.go.id', 'password')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="d-block text-dark">Superadmin Nasional (KLH / BPLH)</strong>
                                        <small class="text-muted">admin@klh.go.id</small>
                                    </div>
                                    <span class="badge bg-success">Akses Penuh</span>
                                </div>
                            </button>

                            <button type="button" class="btn btn-sm btn-outline-primary text-start py-2" onclick="fillCredentials('operator.jabar@klh.go.id', 'password')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="d-block text-dark">Operator Daerah (Jawa Barat)</strong>
                                        <small class="text-muted">operator.jabar@klh.go.id</small>
                                    </div>
                                    <span class="badge bg-primary">Regional Jabar</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div class="text-center mt-4 pt-2">
                        <a href="{{ url('/') }}" class="text-decoration-none small text-muted">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Peta WebGIS Publik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillCredentials(email, password) {
    document.getElementById('inputEmail').value = email;
    document.getElementById('inputPassword').value = password;
}
</script>
@endsection
