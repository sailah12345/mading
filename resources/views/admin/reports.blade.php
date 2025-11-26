@extends('layouts.admin')
@section('content')

<div class="mb-4">
    <h4><i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan & Statistik</h4>
    <p class="text-muted">Generate laporan aktivitas sistem Web Mading</p>
</div>

<div class="row">
    <!-- Laporan Artikel -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Laporan Artikel</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Laporan artikel berdasarkan periode tanggal</p>
                <form action="{{ route('admin.reports.artikel') }}" method="POST" target="_blank">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-file-earmark-pdf me-1"></i>Generate Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan User -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Laporan User</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Laporan data semua user (Admin, Guru, Siswa)</p>
                <div class="mb-3">
                    <p><strong>Total User:</strong> {{ App\Models\User::count() }}</p>
                    <p><strong>Admin:</strong> {{ App\Models\User::where('role', 'admin')->count() }}</p>
                    <p><strong>Guru:</strong> {{ App\Models\User::where('role', 'guru')->count() }}</p>
                    <p><strong>Siswa:</strong> {{ App\Models\User::where('role', 'siswa')->count() }}</p>
                </div>
                <a href="{{ route('admin.reports.user') }}" class="btn btn-success w-100" target="_blank">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Generate Laporan
                </a>
            </div>
        </div>
    </div>

    <!-- Laporan Aktivitas -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Laporan Aktivitas</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Laporan aktivitas posting per periode</p>
                <form action="{{ route('admin.reports.aktivitas') }}" method="POST" target="_blank">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-file-earmark-pdf me-1"></i>Generate Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cepat -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Statistik Keseluruhan</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h3 class="text-primary">{{ App\Models\Article::count() }}</h3>
                        <p class="text-muted">Total Artikel</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="text-success">{{ App\Models\Article::where('status', 'published')->count() }}</h3>
                        <p class="text-muted">Artikel Published</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="text-warning">{{ App\Models\Article::where('status', 'pending')->count() }}</h3>
                        <p class="text-muted">Artikel Pending</p>
                    </div>
                    <div class="col-md-3">
                        <h3 class="text-info">{{ App\Models\User::count() }}</h3>
                        <p class="text-muted">Total User</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
