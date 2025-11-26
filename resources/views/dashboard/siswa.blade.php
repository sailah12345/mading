@extends('siswa-dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="text-muted">Dashboard Siswa - Kelola artikel dan lihat aktivitas Anda</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Artikel Saya</h5>
                            <h3>{{ Auth::user()->articles->count() }}</h3>
                        </div>
                        <i class="bi bi-file-text fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Published</h5>
                            <h3>{{ Auth::user()->articles->where('status', 'published')->count() }}</h3>
                        </div>
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Pending</h5>
                            <h3>{{ Auth::user()->articles->where('status', 'pending')->count() }}</h3>
                        </div>
                        <i class="bi bi-clock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Total Like</h5>
                            <h3>{{ Auth::user()->articles->sum(function($article) { return $article->likes->count(); }) }}</h3>
                        </div>
                        <i class="bi bi-heart fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Artikel Terbaru -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Artikel Saya Terbaru</h5>
                    <a href="{{ route('articles.create') }}" class="btn btn-primary btn-sm">Tulis Artikel Baru</a>
                </div>
                <div class="card-body">
                    @if(Auth::user()->articles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Status</th>
                                        <th>Like</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(Auth::user()->articles->sortByDesc('created_at')->take(5) as $article)
                                    <tr>
                                        <td>{{ Str::limit($article->title, 30) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $article->status == 'published' ? 'success' : ($article->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($article->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $article->likes->count() }}</span>
                                        </td>
                                        <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                                            <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                            <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-file-text display-1 text-muted"></i>
                            <h4 class="mt-3 text-muted">Belum Ada Artikel</h4>
                            <p class="text-muted">Mulai tulis artikel pertama Anda!</p>
                            <a href="{{ route('articles.create') }}" class="btn btn-primary">Tulis Artikel</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Menu Siswa -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Menu Siswa</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('articles.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tulis Artikel Baru
                        </a>
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-list"></i> Lihat Semua Artikel
                        </a>
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-info">
                            <i class="bi bi-bell"></i> Notifikasi
                        </a>
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="bi bi-graph-up"></i> Status Artikel Saya
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <small class="text-muted">Hari ini</small>
                            <p class="mb-1">Login ke dashboard</p>
                        </div>
                        @if(Auth::user()->articles->count() > 0)
                        <div class="list-group-item border-0 px-0">
                            <small class="text-muted">{{ Auth::user()->articles->sortByDesc('created_at')->first()->created_at->diffForHumans() }}</small>
                            <p class="mb-1">Artikel terbaru dibuat</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Status Artikel -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Status Artikel Saya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Like</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(Auth::user()->articles->sortByDesc('created_at') as $article)
                            <tr>
                                <td>{{ Str::limit($article->title, 40) }}</td>
                                <td>
                                    <span class="badge bg-{{ $article->status == 'published' ? 'success' : ($article->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $article->likes->count() }}</span>
                                </td>
                                <td>{{ $article->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                                    <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada artikel
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection