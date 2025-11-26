<!DOCTYPE html>
<html>
<head>
    <title>Test Like Feature</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Like Feature</h1>
        
        @auth
            <p>Logged in as: {{ Auth::user()->name }}</p>
            
            @php
                $article = App\Models\Article::first();
            @endphp
            
            @if($article)
                <div class="card">
                    <div class="card-body">
                        <h5>{{ $article->title }}</h5>
                        <p>{{ Str::limit($article->content, 100) }}</p>
                        
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
                        
                        <div id="debug-info" class="mt-3">
                            <h6>Debug Info:</h6>
                            <p>Article ID: {{ $article->id }}</p>
                            <p>User ID: {{ Auth::id() }}</p>
                            <p>Is Liked: {{ $isLiked ? 'Yes' : 'No' }}</p>
                            <p>Likes Count: {{ $likesCount }}</p>
                        </div>
                    </div>
                </div>
            @else
                <p>No articles found</p>
            @endif
        @else
            <p>Please login first</p>
            <a href="/login" class="btn btn-primary">Login</a>
        @endauth
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Test page loaded');
        const likeBtn = document.querySelector('.like-btn');
        
        if (likeBtn) {
            console.log('Like button found');
            likeBtn.addEventListener('click', function() {
                console.log('Like button clicked!');
                const articleId = this.dataset.articleId;
                const isLiked = this.dataset.liked === 'true';
                console.log('Article ID:', articleId, 'Is Liked:', isLiked);
                
                this.disabled = true;
                
                fetch(`/like/${articleId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
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
                        
                        countSpan.textContent = data.likes_count;
                        console.log('UI updated successfully');
                    } else {
                        console.error('Server error:', data.message);
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Network error: ' + error.message);
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        } else {
            console.log('No like button found');
        }
    });
    </script>
</body>
</html>