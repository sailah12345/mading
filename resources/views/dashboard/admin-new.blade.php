@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('navbar-menu')
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h2>Dashboard Administrator</h2>
        <p class="text-muted">Kelola seluruh sistem Web Mading</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total User</h5>
                        <h3 id="total-users">{{ App\Models\User::count() }}</h3>
                    </div>
                    <i class="bi bi-people fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total Artikel</h5>
                        <h3 id="total-articles">{{ App\Models\Article::count() }}</h3>
                    </div>
                    <i class="bi bi-file-text fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total Like</h5>
                        <h3 id="total-likes">{{ App\Models\Like::count() }}</h3>
                    </div>
                    <i class="bi bi-heart fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total Komentar</h5>
                        <h3 id="total-comments">{{ App\Models\Comment::count() }}</h3>
                    </div>
                    <i class="bi bi-chat-dots fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Quick Actions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-lightning"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
                        <i class="bi bi-people"></i> Kelola User
                    </button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#articlesModal">
                        <i class="bi bi-file-text"></i> Kelola Artikel
                    </button>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#verifyModal">
                        <i class="bi bi-check-circle"></i> Verifikasi Artikel
                    </button>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#categoryModal">
                        <i class="bi bi-tags"></i> Kelola Kategori
                    </button>
                    <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                        <i class="bi bi-file-earmark-pdf"></i> Laporan PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Articles -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-file-text"></i> Artikel Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="recent-articles">
                            @foreach(App\Models\Article::latest()->take(5)->get() as $article)
                            <tr>
                                <td>{{ Str::limit($article->title, 20) }}</td>
                                <td>
                                    <span class="badge bg-{{ $article->status == 'published' ? 'success' : 'secondary' }}">
                                        {{ $article->status }}
                                    </span>
                                </td>
                                <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-info">Lihat</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics by Role -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-graph-up"></i> Statistik Role</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Admin</span>
                        <span class="badge bg-danger">{{ App\Models\User::where('role', 'admin')->count() }}</span>
                    </div>
                    <div class="progress mt-1">
                        <div class="progress-bar bg-danger" style="width: {{ App\Models\User::count() > 0 ? App\Models\User::where('role', 'admin')->count() / App\Models\User::count() * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Guru</span>
                        <span class="badge bg-warning">{{ App\Models\User::where('role', 'guru')->count() }}</span>
                    </div>
                    <div class="progress mt-1">
                        <div class="progress-bar bg-warning" style="width: {{ App\Models\User::count() > 0 ? App\Models\User::where('role', 'guru')->count() / App\Models\User::count() * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Siswa</span>
                        <span class="badge bg-info">{{ App\Models\User::where('role', 'siswa')->count() }}</span>
                    </div>
                    <div class="progress mt-1">
                        <div class="progress-bar bg-info" style="width: {{ App\Models\User::count() > 0 ? App\Models\User::where('role', 'siswa')->count() / App\Models\User::count() * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-clock"></i> Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0 px-0">
                        <small class="text-muted">Hari ini</small>
                        <p class="mb-1">Login sebagai Admin</p>
                    </div>
                    @if(App\Models\User::count() > 1)
                    <div class="list-group-item border-0 px-0">
                        <small class="text-muted">{{ App\Models\User::latest()->skip(1)->first()->created_at->diffForHumans() }}</small>
                        <p class="mb-1">User baru terdaftar</p>
                    </div>
                    @endif
                    @if(App\Models\Article::count() > 0)
                    <div class="list-group-item border-0 px-0">
                        <small class="text-muted">{{ App\Models\Article::latest()->first()->created_at->diffForHumans() }}</small>
                        <p class="mb-1">Artikel baru dibuat</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('dashboard.partials.admin-modals')
@endsection

@push('scripts')
<script>
// Auto refresh data setiap 30 detik
setInterval(function() {
    fetch('/api/dashboard/admin-data')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-users').textContent = data.users.total;
            document.getElementById('total-articles').textContent = data.articles.total;
            document.getElementById('total-likes').textContent = data.likes.total;
            document.getElementById('total-comments').textContent = data.comments.total;
        })
        .catch(error => console.log('Error refreshing data:', error));
}, 30000);
</script>
@endpush