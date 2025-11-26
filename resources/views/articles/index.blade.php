@extends('index')
@section('content')

<style>
    .article-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    .article-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    .article-card img {
        transition: transform 0.3s ease;
    }
    .article-card:hover img {
        transform: scale(1.05);
    }
    .badge-custom {
        padding: 6px 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .article-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }
    .article-excerpt {
        font-size: 0.9rem;
        color: #6c757d;
        line-height: 1.6;
    }
    .article-meta {
        font-size: 0.8rem;
        color: #95a5a6;
    }
    .btn-read-more {
        border-radius: 20px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-read-more:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,123,255,0.3);
    }
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
    }
</style>

<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-2"><i class="bi bi-newspaper me-2"></i>Semua Artikel</h1>
                <p class="mb-0 opacity-75">Jelajahi semua artikel menarik dari komunitas sekolah</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="badge bg-light text-dark fs-6 px-3 py-2">
                    <i class="bi bi-file-text me-1"></i>{{ $articles->count() }} Artikel
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        @if(isset($articles) && $articles->count() > 0)
        @foreach($articles as $article)
        <div class="col-md-6 col-lg-4">
            <div class="card article-card border-0 shadow-sm h-100">
                @if($article->photo)
                <img src="{{ asset('storage/' . $article->photo) }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $article->title }}">
                @endif
                <div class="card-body d-flex flex-column p-4">
                    <div class="mb-3">
                        <span class="badge badge-custom bg-{{ $article->kategori ? 'primary' : 'secondary' }}">{{ $article->kategori->nama_kategori ?? 'Artikel' }}</span>
                    </div>
                    <h5 class="article-title">{{ Str::limit($article->title, 60) }}</h5>
                    <p class="article-excerpt flex-grow-1">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center article-meta">
                        <span>
                            <i class="bi bi-person-circle me-1"></i>{{ Str::limit($article->user->name, 20) }}
                        </span>
                        <span>
                            <i class="bi bi-clock me-1"></i>{{ $article->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('articles.show', $article->id) }}" class="btn btn-primary btn-read-more flex-grow-1">
                            <i class="bi bi-book me-1"></i>Baca
                        </a>
                        @auth
                            @php
                                $isLiked = $article->likes->where('user_id', Auth::id())->count() > 0;
                                $likesCount = $article->likes->count();
                            @endphp
                            <button class="btn {{ $isLiked ? 'btn-danger' : 'btn-outline-danger' }} like-btn" 
                                    data-article-id="{{ $article->id }}" 
                                    data-liked="{{ $isLiked ? 'true' : 'false' }}" 
                                    title="{{ $isLiked ? 'Unlike' : 'Like' }} artikel ini">
                                <i class="bi bi-heart{{ $isLiked ? '-fill' : '' }}"></i>
                                <span class="like-count">{{ $likesCount }}</span>
                            </button>
                        @else
                            <span class="btn btn-outline-secondary disabled">
                                <i class="bi bi-heart"></i> {{ $article->likes->count() }}
                            </span>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h3 class="mt-3 text-muted">Belum Ada Artikel</h3>
            <p class="text-muted">Artikel akan muncul setelah disetujui oleh admin/guru</p>
            @auth
            <a href="{{ route('articles.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-circle me-1"></i>Tulis Artikel Pertama
            </a>
            @endauth
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle like button clicks
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const articleId = this.dataset.articleId;
            const isLiked = this.dataset.liked === 'true';
            const heartIcon = this.querySelector('i');
            const countSpan = this.querySelector('.like-count');
            
            // Disable button during request
            this.disabled = true;
            
            // Show loading state
            const originalText = countSpan.textContent;
            countSpan.textContent = '...';
            
            fetch(`/like/${articleId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Like response:', data);
                
                if (data.success) {
                    // Update UI
                    if (data.liked) {
                        this.classList.remove('btn-outline-danger');
                        this.classList.add('btn-danger');
                        heartIcon.classList.add('bi-heart-fill');
                        heartIcon.classList.remove('bi-heart');
                        this.dataset.liked = 'true';
                        this.title = 'Unlike artikel ini';
                    } else {
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-outline-danger');
                        heartIcon.classList.remove('bi-heart-fill');
                        heartIcon.classList.add('bi-heart');
                        this.dataset.liked = 'false';
                        this.title = 'Like artikel ini';
                    }
                    
                    countSpan.textContent = data.likes_count;
                } else {
                    alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
                    countSpan.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses like. Pastikan Anda sudah login.');
                countSpan.textContent = originalText;
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
});
</script>
@endpush
