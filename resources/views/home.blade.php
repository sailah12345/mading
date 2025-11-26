@extends('index')
@section('content')

@if(Auth::check())
<!-- Dashboard User -->
<div class="page-title" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container">
        <div class="row align-items-center py-4">
            <div class="col-md-8">
                <h2 class="mb-1">
                    <i class="bi bi-person-circle me-2"></i>
                    Selamat Datang, {{ Auth::user()->name }}!
                </h2>
                <p class="mb-0 opacity-75">Dashboard {{ ucfirst(Auth::user()->role) }} - Web Mading Sekolah</p>
                @if(Auth::user()->role == 'admin')
                    <div class="mt-3">
                        <img src="{{ asset('img/logo.webp') }}" alt="Admin Dashboard" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                @endif
            </div>
            <div class="col-md-4 text-md-end">
                @if(Auth::user()->role == 'siswa')
                    <a href="{{ route('dashboard.siswa') }}" class="btn btn-light me-2">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                @elseif(Auth::user()->role == 'guru')
                    <a href="{{ route('dashboard.guru') }}" class="btn btn-light me-2">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                @elseif(Auth::user()->role == 'admin')
                    <a href="{{ url('/admin') }}" class="btn btn-light me-2">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                @endif
                <a href="{{ route('articles.create') }}" class="btn btn-warning">
                    <i class="bi bi-plus-circle me-1"></i> Tulis Artikel
                </a>
            </div>
        </div>
    </div>
</div>



<!-- Quick Stats -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="bi bi-journal-text fs-1"></i>
                        </div>
                        <h5 class="card-title">Artikel</h5>
                        <h3 class="text-primary">{{ App\Models\Article::where('user_id', Auth::id())->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                        <h5 class="card-title">Published</h5>
                        <h3 class="text-success">{{ App\Models\Article::where('user_id', Auth::id())->where('status', 'published')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="bi bi-clock fs-1"></i>
                        </div>
                        <h5 class="card-title">Pending</h5>
                        <h3 class="text-warning">{{ App\Models\Article::where('user_id', Auth::id())->where('status', 'pending')->count() }}</h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@else
<!-- Hero Section untuk Guest -->
<section class="hero-section" style="background: url('{{ asset('img/baknus/pk_lntai_2.jpg') }}'); background-size: cover; background-position: center; color: white; padding: 100px 0; position: relative;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('{{ asset('img/literasi_bhs.jpg') }}'); background-size: cover; background-position: center; filter: blur(3px); z-index: -1;"></div>
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Web Mading Digital Sekolah</h1>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>

                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Artikel Terbaru -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold">{{ $search ? 'Hasil Pencarian: "' . $search . '"' : 'Artikel Terbaru' }}</h2>
                <p class="text-muted">{{ $search ? 'Ditemukan ' . $articles->count() . ' artikel' : 'Baca artikel terbaru dari siswa dan guru' }}</p>
                @if($search ?? false)
                    <div class="mb-3">
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i> Hapus Pencarian
                        </a>
                    </div>
                @endif
                
                <a href="{{ route('articles.index') }}" class="btn btn-outline-primary mt-2">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        
        <div class="row g-4">
            @forelse($articles as $article)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    @if($article->photo)
                        <img src="{{ asset('storage/' . $article->photo) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $article->title }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-primary">{{ $article->kategori->nama_kategori ?? 'Artikel' }}</span>
                        </div>
                        <h5 class="card-title">{{ Str::limit($article->title, 50) }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($article->content, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>{{ $article->user->name }}
                            </small>
                            <small class="text-muted">{{ $article->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-primary btn-sm">Baca Selengkapnya</a>
                            @auth
                                @php
                                    $isLiked = $article->likes->where('user_id', Auth::id())->count() > 0;
                                    $likesCount = $article->likes->count();
                                @endphp
                                <button class="btn btn-sm {{ $isLiked ? 'btn-danger' : 'btn-outline-danger' }} me-2 like-btn" 
                                        data-article-id="{{ $article->id }}" 
                                        data-liked="{{ $isLiked ? 'true' : 'false' }}">
                                    <i class="bi bi-heart{{ $isLiked ? '-fill' : '' }}"></i>
                                    <span class="like-count">{{ $likesCount }}</span>
                                </button>
                            @else
                                <span class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="bi bi-heart"></i> {{ $article->likes->count() }}
                                </span>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @if($search ?? false)
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search fs-1 text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada artikel ditemukan</h4>
                    <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Lihat Semua Artikel</a>
                </div>
            @else
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('img/logo.webp') }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-info">Informasi</span>
                            </div>
                            <h5 class="card-title">Artikel Contoh 1</h5>
                            <p class="card-text text-muted flex-grow-1">Ini adalah contoh artikel untuk menampilkan layout card...</p>
                            <div class="mt-2">
                                <a href="#" class="btn btn-outline-primary btn-sm">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('img/logo.webp') }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-success">Prestasi</span>
                            </div>
                            <h5 class="card-title">Artikel Contoh 2</h5>
                            <p class="card-text text-muted flex-grow-1">Ini adalah contoh artikel kedua untuk menampilkan layout card...</p>
                            <div class="mt-2">
                                <a href="#" class="btn btn-outline-primary btn-sm">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ asset('img/logo.webp') }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-warning">Kegiatan</span>
                        </div>
                        <h5 class="card-title">Artikel Contoh 3</h5>
                        <p class="card-text text-muted flex-grow-1">Ini adalah contoh artikel ketiga untuk menampilkan layout card...</p>
                        <div class="mt-2">
                            <a href="#" class="btn btn-outline-primary btn-sm">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
/* SOAL5: IDENTIFIKASI HASIL EKSEKUSI - AJAX untuk real-time update */
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const articleId = this.dataset.articleId;
            const heartIcon = this.querySelector('i');
            const countSpan = this.querySelector('.like-count');
            
            this.disabled = true;
            
            /* SOAL5: HASIL EKSEKUSI - Fetch API untuk komunikasi dengan server */
            fetch(`/like/${articleId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                /* SOAL5: IDENTIFIKASI HASIL - Mengecek response success/error */
                if (data.success) {
                    if (data.liked) {
                        this.classList.remove('btn-outline-danger');
                        this.classList.add('btn-danger');
                        heartIcon.classList.add('bi-heart-fill');
                        heartIcon.classList.remove('bi-heart');
                        this.dataset.liked = 'true';
                    } else {
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-outline-danger');
                        heartIcon.classList.remove('bi-heart-fill');
                        heartIcon.classList.add('bi-heart');
                        this.dataset.liked = 'false';
                    }
                    /* SOAL5: UPDATE HASIL - Real-time counter update */
                    countSpan.textContent = data.likes_count;
                }
            })
            .catch(error => {
                /* SOAL5: ERROR HANDLING - Menangani error eksekusi */
                console.error('Error:', error);
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
});
</script>
@endpush

