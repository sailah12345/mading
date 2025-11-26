@extends('index')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Test Articles Page</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Debug Info:</strong><br>
                        Total Articles: {{ App\Models\Article::count() }}<br>
                        Published Articles: {{ App\Models\Article::where('status', 'published')->count() }}<br>
                        Route: {{ route('articles.index') }}
                    </div>
                    
                    <div class="mb-3">
                        <a href="{{ route('articles.index') }}" class="btn btn-primary">Go to Articles Index</a>
                        <a href="/test-articles" class="btn btn-info">View JSON Data</a>
                    </div>
                    
                    <h6>Published Articles:</h6>
                    @php
                        $articles = App\Models\Article::with(['user', 'kategori'])->where('status', 'published')->latest()->get();
                    @endphp
                    
                    @if($articles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $article)
                                    <tr>
                                        <td>{{ $article->id }}</td>
                                        <td>{{ Str::limit($article->title, 50) }}</td>
                                        <td>{{ $article->user->name ?? 'Unknown' }}</td>
                                        <td>{{ $article->kategori->nama_kategori ?? 'No Category' }}</td>
                                        <td><span class="badge bg-success">{{ $article->status }}</span></td>
                                        <td>
                                            <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">No published articles found.</div>
                    @endif
                    
                    <hr>
                    
                    <h6>All Articles (including pending):</h6>
                    @php
                        $allArticles = App\Models\Article::with(['user', 'kategori'])->latest()->take(10)->get();
                    @endphp
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allArticles as $article)
                                <tr>
                                    <td>{{ $article->id }}</td>
                                    <td>{{ Str::limit($article->title, 40) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $article->status == 'published' ? 'success' : ($article->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ $article->status }}
                                        </span>
                                    </td>
                                    <td>{{ $article->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection