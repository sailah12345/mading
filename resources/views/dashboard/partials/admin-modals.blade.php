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
                <div class="mb-3">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Tambah User
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\User::all() as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'guru' ? 'warning' : 'info') }}">{{ $user->role }}</span></td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user {{ $user->name }}?')">Hapus</button>
                                    </form>
                                    @endif
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
                            @foreach(App\Models\Kategori::withCount('articles')->get() as $kategori)
                            <tr>
                                <td>{{ $kategori->nama_kategori }}</td>
                                <td>{{ $kategori->articles_count }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning">Edit</button>
                                    <button class="btn btn-sm btn-danger">Hapus</button>
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
                            @forelse(App\Models\Article::where('status', 'pending')->get() as $article)
                            <tr>
                                <td>{{ Str::limit($article->title, 30) }}</td>
                                <td>{{ $article->user->name }}</td>
                                <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>
                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-info" target="_blank">Lihat</a>
                                    <form action="{{ route('admin.articles.approve', $article->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui artikel ini?')">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.articles.reject', $article->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak artikel ini?')">Reject</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
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