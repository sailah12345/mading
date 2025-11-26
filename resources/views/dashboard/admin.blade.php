<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #6c757d 0%, #f5f5dc 100%) !important;">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-shield-check"></i> Admin Panel</a>
            <div class="navbar-nav ms-auto d-flex align-items-center">
                @include('partials.notification-dropdown')
                <span class="navbar-text me-3 ms-3">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2>Dashboard Administrator</h2>
                <p class="text-muted">Kelola seluruh sistem Web Mading</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Total User</h5>
                                <h3>{{ App\Models\User::count() }}</h3>
                            </div>
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
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
            <div class="col-md-4">
                <div class="card text-white bg-warning">
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
            <!-- User Management -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5><i class="bi bi-people"></i> Manajemen User</h5>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Tambah User</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(App\Models\User::latest()->take(5)->get() as $user)
                                    <tr>
                                        <td>{{ Str::limit($user->name, 15) }}</td>
                                        <td>{{ Str::limit($user->email, 20) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'guru' ? 'warning' : 'info') }}">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Article Management -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5><i class="bi bi-file-text"></i> Manajemen Artikel</h5>
                        <a href="{{ route('articles.create') }}" class="btn btn-success btn-sm">Buat Artikel</a>
                    </div>
                    <div class="card-body">
                        @if(App\Models\Article::count() > 0)
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
                                    <tbody>
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
                                                <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                                <form method="POST" action="{{ route('articles.destroy', $article) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center">Belum ada artikel</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Menu & System Info -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-gear"></i> Menu Admin</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Buat Artikel
                            </a>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#articlesModal">
                                <i class="bi bi-journal-text"></i> Kelola Artikel
                            </button>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#userModal">
                                <i class="bi bi-people"></i> Kelola User
                            </button>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#categoryModal">
                                <i class="bi bi-tags"></i> Kelola Kategori
                            </button>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#verifyModal">
                                <i class="bi bi-check-circle"></i> Verifikasi Artikel
                            </button>
                            <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                                <i class="bi bi-file-earmark-pdf"></i> Laporan PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
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
                                <div class="progress-bar bg-danger" style="width: {{ App\Models\User::where('role', 'admin')->count() / App\Models\User::count() * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Guru</span>
                                <span class="badge bg-warning">{{ App\Models\User::where('role', 'guru')->count() }}</span>
                            </div>
                            <div class="progress mt-1">
                                <div class="progress-bar bg-warning" style="width: {{ App\Models\User::where('role', 'guru')->count() / App\Models\User::count() * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Siswa</span>
                                <span class="badge bg-info">{{ App\Models\User::where('role', 'siswa')->count() }}</span>
                            </div>
                            <div class="progress mt-1">
                                <div class="progress-bar bg-info" style="width: {{ App\Models\User::where('role', 'siswa')->count() / App\Models\User::count() * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
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
    </div>

    <!-- Modal Kelola Artikel -->
    <div class="modal fade" id="articlesModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kelola Artikel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <a href="{{ route('articles.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Artikel
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(App\Models\Article::with(['user', 'kategori'])->latest()->get() as $index => $article)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($article->photo)
                                            <img src="{{ asset('storage/' . $article->photo) }}" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($article->title, 30) }}</td>
                                    <td>{{ $article->user->name }}</td>
                                    <td>{{ $article->kategori->nama_kategori ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $article->status == 'published' ? 'success' : 'warning' }}">
                                            {{ ucfirst($article->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-info">Lihat</a>
                                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form method="POST" action="{{ route('articles.destroy', $article) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus artikel ini?')">Hapus</button>
                                        </form>
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

    <!-- Modal Kelola User -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kelola User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(App\Models\User::all() as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user {{ $user->name }}?')">Hapus</button>
                                        </form>
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

    <!-- Modal Kelola Kategori -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kelola Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Tambah Kategori</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Kategori</th>
                                    <th>Jumlah Artikel</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Informasi</td>
                                    <td>5</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Prestasi</td>
                                    <td>3</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </td>
                                </tr>
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
                    <h5 class="modal-title">Verifikasi Artikel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(App\Models\Article::where('status', 'pending')->get() as $article)
                                <tr>
                                    <td>{{ Str::limit($article->title, 30) }}</td>
                                    <td>{{ $article->user->name }}</td>
                                    <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <form action="{{ route('admin.articles.approve', $article->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui artikel ini?')">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.articles.reject', $article->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak artikel ini?')">Reject</button>
                                        </form>
                                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-info">Lihat</a>
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

    <!-- Modal Laporan -->
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Jenis Laporan</label>
                            <select class="form-select" required>
                                <option value="">Pilih Jenis Laporan</option>
                                <option value="artikel">Laporan Artikel</option>
                                <option value="user">Laporan User</option>
                                <option value="aktivitas">Laporan Aktivitas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <select class="form-select" required>
                                <option value="">Pilih Periode</option>
                                <option value="harian">Harian</option>
                                <option value="mingguan">Mingguan</option>
                                <option value="bulanan">Bulanan</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Generate Laporan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pengaturan -->
    <div class="modal fade" id="settingsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pengaturan Sistem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Website</label>
                        <input type="text" class="form-control" value="Web Mading Sekolah">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="3">Platform mading digital untuk berbagi informasi sekolah</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Kontak</label>
                        <input type="email" class="form-control" value="admin@sekolah.com">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label">Izinkan registrasi siswa baru</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan Pengaturan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>