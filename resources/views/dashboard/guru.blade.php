<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Guru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #6c757d 0%, #f5f5dc 100%) !important;">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard Guru</a>
            <div class="navbar-nav ms-auto d-flex align-items-center">
                @include('partials.notification-dropdown')
                <form action="{{ route('logout') }}" method="POST" class="d-inline ms-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2>Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="text-muted">Role: {{ Auth::user()->role }}</p>
            </div>
        </div>

        <div class="row mt-4 justify-content-center">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Total Artikel</h5>
                                <h3>{{ App\Models\Article::count() }}</h3>
                            </div>
                            <i class="bi bi-file-text fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Total Siswa</h5>
                                <h3>{{ App\Models\User::where('role', 'siswa')->count() }}</h3>
                            </div>
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Total Like</h5>
                                <h3>{{ App\Models\Like::count() }}</h3>
                            </div>
                            <i class="bi bi-heart fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Artikel Terbaru</h5>
                        <a href="{{ route('articles.create') }}" class="btn btn-primary btn-sm">Buat Artikel</a>
                    </div>
                    <div class="card-body">
                        @if(App\Models\Article::count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(App\Models\Article::latest()->take(5)->get() as $article)
                                        <tr>
                                            <td>{{ Str::limit($article->title, 30) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $article->status == 'published' ? 'success' : 'warning' }}">
                                                    {{ $article->status }}
                                                </span>
                                            </td>
                                            <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Belum ada artikel.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Menu Guru</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Buat Artikel Baru
                            </a>
                            <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-list"></i> Lihat Semua Artikel
                            </a>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#verifyModal">
                                <i class="bi bi-check-circle"></i> Verifikasi Artikel ({{ App\Models\Article::where('status', 'pending')->count() }})
                            </button>
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#siswaModal">
                                <i class="bi bi-people"></i> Kelola Siswa ({{ App\Models\User::where('role', 'siswa')->count() }})
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
                            @if(App\Models\Article::count() > 0)
                            <div class="list-group-item border-0 px-0">
                                <small class="text-muted">{{ App\Models\Article::latest()->first()->created_at->diffForHumans() }}</small>
                                <p class="mb-1">Artikel terbaru dibuat</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kelola Siswa -->
    <div class="modal fade" id="siswaModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kelola Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Bergabung</th>
                                    <th>Total Artikel</th>
                                    <th>Artikel Published</th>
                                    <th>Artikel Pending</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(App\Models\User::where('role', 'siswa')->latest()->get() as $index => $siswa)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $siswa->name }}</td>
                                    <td>{{ $siswa->email }}</td>
                                    <td>{{ $siswa->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $siswa->articles->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $siswa->articles->where('status', 'published')->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $siswa->articles->where('status', 'pending')->count() }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada siswa terdaftar
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

    <!-- Modal Verifikasi Artikel -->
    <div class="modal fade" id="verifyModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Artikel Pending</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Kategori</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(App\Models\Article::where('status', 'pending')->latest()->get() as $article)
                                <tr>
                                    <td>
                                        @if($article->photo)
                                            <img src="{{ asset('storage/' . $article->photo) }}" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($article->title, 30) }}</td>
                                    <td>{{ $article->user->name }}</td>
                                    <td>{{ $article->kategori->nama_kategori ?? '-' }}</td>
                                    <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-info" target="_blank">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                        <form action="{{ route('guru.articles.approve', $article->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui artikel ini?')">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('guru.articles.reject', $article->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak artikel ini?')">
                                                <i class="bi bi-x-circle"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Tidak ada artikel yang perlu diverifikasi
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>