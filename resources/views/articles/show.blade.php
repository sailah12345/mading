@extends('index')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Artikel</a></li>
                    <li class="breadcrumb-item active">{{ $article->title }}</li>
                </ol>
            </nav>

            <!-- Article Card -->
            <div class="card shadow-sm">
                <!-- Article Image -->
                @if($article->photo)
                <img src="{{ asset('storage/' . $article->photo) }}" class="card-img-top" alt="{{ $article->title }}" style="max-height: 400px; object-fit: cover;">
                @endif

                <div class="card-body">
                    <!-- Category Badge -->
                    <div class="mb-3">
                        <span class="badge bg-primary">{{ $article->kategori->nama_kategori ?? 'Artikel' }}</span>
                        <span class="badge bg-{{ $article->status == 'published' ? 'success' : 'warning' }}">
                            {{ ucfirst($article->status) }}
                        </span>
                    </div>

                    <!-- Article Title -->
                    <h1 class="mb-3">{{ $article->title }}</h1>

                    <!-- Article Meta -->
                    <div class="d-flex align-items-center justify-content-between text-muted mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="me-4">
                                <i class="bi bi-person-circle me-1"></i>
                                <strong>{{ $article->user->name }}</strong>
                            </div>
                            <div class="me-4">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $article->created_at->format('d M Y') }}
                            </div>
                            <div>
                                <i class="bi bi-clock me-1"></i>
                                {{ $article->created_at->diffForHumans() }}
                            </div>
                        </div>
                        
                        <!-- Like Button -->
                        @auth
                            @php
                                $isLiked = $article->likes->where('user_id', Auth::id())->count() > 0;
                                $likesCount = $article->likes->count();
                            @endphp
                            <button type="button" class="btn {{ $isLiked ? 'btn-danger' : 'btn-outline-danger' }} btn-sm like-btn" 
                                    data-article-id="{{ $article->id }}" 
                                    data-liked="{{ $isLiked ? 'true' : 'false' }}" 
                                    title="{{ $isLiked ? 'Unlike' : 'Like' }} artikel ini">
                                <i class="bi bi-heart{{ $isLiked ? '-fill' : '' }}"></i>
                                <span class="likes-count">{{ $likesCount }}</span> Suka
                            </button>
                        @else
                            <span class="text-muted">
                                <i class="bi bi-heart"></i> {{ $article->likes->count() }} Suka
                            </span>
                        @endauth
                    </div>

                    <!-- Article Content -->
                    <div class="article-content" style="font-size: 1.1rem; line-height: 1.8;">
                        {!! nl2br(e($article->content)) !!}
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('articles.download', $article->id) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-download me-1"></i>Download PDF
                        </a>
                        
                        @auth
                            @if(Auth::id() == $article->user_id || Auth::user()->role == 'admin')
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil me-1"></i>Edit Artikel
                            </a>
                            <form method="POST" action="{{ route('articles.destroy', $article) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus artikel ini?')">
                                    <i class="bi bi-trash me-1"></i>Hapus Artikel
                                </button>
                            </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Comment Section -->
            @auth
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Komentar</h5>
                </div>
                <div class="card-body">
                    <!-- Form Komentar -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('comments.store', $article->id) }}" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" name="content" rows="3" placeholder="Tulis komentar Anda..." required>{{ old('content') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-send me-1"></i>Kirim Komentar
                        </button>
                    </form>
                    


                    <!-- Daftar Komentar -->
                    <div class="comments-list">
                        @if($article->comments && $article->comments->count() > 0)
                            @foreach($article->comments as $comment)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $comment->user->name }}</strong>
                                        <small class="text-muted d-block">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if(Auth::id() == $comment->user_id || Auth::user()->role == 'admin')
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus komentar ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                <p class="mb-0 mt-2">{{ $comment->content }}</p>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                        @endif
                    </div>
                </div>
            </div>
            @endauth

            <!-- Back Button -->
            <div class="mt-3">
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Artikel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Like functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, looking for like buttons...');
    const likeBtn = document.querySelector('.like-btn');
    console.log('Like button found:', likeBtn);
    
    if (likeBtn) {
        likeBtn.addEventListener('click', function() {
            console.log('Like button clicked!');
            const articleId = this.dataset.articleId;
            const isLiked = this.dataset.liked === 'true';
            console.log('Article ID:', articleId, 'Is Liked:', isLiked);
            
            // Disable button during request
            this.disabled = true;
            
            fetch(`/like/${articleId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update button appearance
                    const icon = this.querySelector('i');
                    const countSpan = this.querySelector('.likes-count');
                    
                    if (data.liked) {
                        this.className = 'btn btn-danger btn-sm like-btn';
                        icon.className = 'bi bi-heart-fill';
                        this.title = 'Unlike artikel ini';
                        this.dataset.liked = 'true';
                    } else {
                        this.className = 'btn btn-outline-danger btn-sm like-btn';
                        icon.className = 'bi bi-heart';
                        this.title = 'Like artikel ini';
                        this.dataset.liked = 'false';
                    }
                    
                    // Update likes count
                    countSpan.textContent = data.likes_count;
                    console.log('UI updated successfully');
                } else {
                    console.error('Server error:', data.message);
                    alert('Terjadi kesalahan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Terjadi kesalahan saat memproses like');
            })
            .finally(() => {
                // Re-enable button
                this.disabled = false;
                console.log('Button re-enabled');
            });
        });
    } else {
        console.log('No like button found on this page');
    }
});
</script>
@endpush