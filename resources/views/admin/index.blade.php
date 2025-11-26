@extends('layouts.admin')
@section('content')

<div class="mb-4">
    <h4>Selamat Datang, {{ Auth::user()->name }}!</h4>
    <p class="text-muted">Kelola sistem web mading sekolah dari dashboard ini.</p>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="bi bi-journal-text fs-1 mb-2"></i>
                <h5>Total Artikel</h5>
                <h3>{{ $totalArticles ?? 0 }}</h3>
                <small>Artikel dipublikasi</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="bi bi-people fs-1 mb-2"></i>
                <h5>Total User</h5>
                <h3>{{ $totalUsers ?? 0 }}</h3>
                <small>Siswa & Guru</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="bi bi-heart fs-1 mb-2"></i>
                <h5>Like</h5>
                <h3>{{ $totalLikes ?? 0 }}</h3>
                <small>Total like</small>
            </div>
        </div>
    </div>
</div>

<!-- Menu Utama -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-tags fs-1 text-primary mb-3"></i>
                <h5>Kelola Kategori</h5>
                <p>Tambah, edit, dan hapus kategori artikel</p>
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#categoryModal">Lihat Kategori</button>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Tambah Kategori</button>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-check-circle fs-1 text-warning mb-3"></i>
                <h5>Verifikasi Artikel</h5>
                <p>Review dan approve artikel pending</p>
                <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#verifyModal">Artikel Pending</button>
                <a href="{{ route('articles.index') }}" class="btn btn-outline-warning">Semua Artikel</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-people fs-1 text-success mb-3"></i>
                <h5>Kelola User</h5>
                <p>Manage akun siswa dan guru</p>
                <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#userModal">Lihat User</button>
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addUserModal">Tambah User</button>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-file-earmark-text fs-1 text-info mb-3"></i>
                <h5>Laporan</h5>
                <p>Generate laporan sistem dan aktivitas</p>
                <button class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#reportModal">Buat Laporan</button>
                <button class="btn btn-outline-info">Download Laporan</button>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-gear fs-1 text-secondary mb-3"></i>
                <h5>Pengaturan</h5>
                <p>Konfigurasi sistem dan website</p>
                <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#settingsModal">Pengaturan</button>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary" target="_blank">Lihat Website</a>
            </div>
        </div>
    </div>
</div>

<!-- Artikel Terbaru -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Artikel Terbaru</h5>
        <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(App\Models\Article::latest()->take(5)->get() as $article)
                    <tr>
                        <td>
                            @if($article->photo)
                                <img src="{{ asset('storage/' . $article->photo) }}" alt="Foto" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($article->title, 30) }}</td>
                        <td>{{ $article->user->name }}</td>
                        <td>{{ $article->created_at->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $article->status == 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($article->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada artikel</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal User List -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar User</h5>
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
                                <th>Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\User::all() as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" required>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Tambah User</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kategori -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Tambah Kategori</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Artikel -->
<div class="modal fade" id="verifyModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Artikel Pending Verifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\Article::where('status', 'pending')->get() as $article)
                            <tr>
                                <td>{{ Str::limit($article->title, 30) }}</td>
                                <td>{{ $article->user->name }}</td>
                                <td>{{ $article->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success">Approve</button>
                                    <button class="btn btn-sm btn-danger">Reject</button>
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

<!-- Modal Settings -->
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

@endsection