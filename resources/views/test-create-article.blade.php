@extends('index')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Test Create Article Form</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Judul</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', 'Test Artikel ' . date('H:i:s')) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach(App\Models\Kategori::all() as $kategori)
                                    <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Konten</label>
                            <textarea name="content" class="form-control" rows="5" required>{{ old('content', 'Ini adalah konten test artikel.') }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Foto (Optional)</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan Artikel</button>
                        <a href="{{ route('articles.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                    
                    <hr class="my-4">
                    
                    <h6>Debug Info:</h6>
                    <p><strong>User:</strong> {{ Auth::check() ? Auth::user()->name . ' (' . Auth::user()->role . ')' : 'Not logged in' }}</p>
                    <p><strong>Categories:</strong> {{ App\Models\Kategori::count() }} available</p>
                    <p><strong>Route:</strong> {{ route('articles.store') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection