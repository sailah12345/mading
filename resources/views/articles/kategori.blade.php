@extends('index')
@section('content')

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Artikel {{ $kategori->nama_kategori }}</h2>
            <p class="text-muted">Artikel dalam kategori {{ $kategori->nama_kategori }}</p>
        </div>
    </div>
    
    <div class="row g-4">
        @forelse($articles as $article)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                @if($article->photo)
                    <img src="{{ asset('storage/' . $article->photo) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <span class="badge bg-primary">{{ $kategori->nama_kategori }}</span>
                    </div>
                    <h5 class="card-title">{{ Str::limit($article->title, 50) }}</h5>
                    <p class="card-text text-muted flex-grow-1">{{ Str::limit($article->content, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <small class="text-muted">
                            <i class="bi bi-person me-1"></i>{{ $article->user->name }}
                        </small>
                        <small class="text-muted">{{ $article->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('articles.show', $article->id) }}" class="btn btn-outline-primary btn-sm">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-journal-x display-1 text-muted"></i>
            <h4 class="mt-3">Belum Ada Artikel</h4>
            <p class="text-muted">Belum ada artikel dalam kategori {{ $kategori->nama_kategori }}</p>
        </div>
        @endforelse
    </div>
</div>

@endsection