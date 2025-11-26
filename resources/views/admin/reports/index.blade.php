@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-file-earmark-text me-2"></i>Laporan & Statistik</h2>
                <a href="{{ url('/admin') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $totalArticles }}</h3>
                    <small>Total Artikel</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $publishedArticles }}</h3>
                    <small>Published</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3>{{ $pendingArticles }}</h3>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $totalUsers }}</h3>
                    <small>Total Users</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3>{{ $totalLikes }}</h3>
                    <small>Total Likes</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Laporan Artikel -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-newspaper me-2"></i>Laporan Artikel</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reports.artikel') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Bulan</label>
                            <select name="month" class="form-select">
                                <option value="">Semua Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="format" value="pdf" class="btn btn-danger">
                                <i class="bi bi-file-pdf me-1"></i>Download PDF
                            </button>
                            <button type="submit" name="format" value="view" class="btn btn-primary">
                                <i class="bi bi-eye me-1"></i>Lihat Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Laporan Aktivitas -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-activity me-2"></i>Laporan Aktivitas</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reports.aktivitas') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-t') }}">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="format" value="pdf" class="btn btn-danger">
                                <i class="bi bi-file-pdf me-1"></i>Download PDF
                            </button>
                            <button type="submit" name="format" value="view" class="btn btn-primary">
                                <i class="bi bi-eye me-1"></i>Lihat Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection