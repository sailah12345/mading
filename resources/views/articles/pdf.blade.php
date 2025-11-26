<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $article->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .meta {
            color: #666;
            font-size: 14px;
        }
        .article-image {
            width: 100%;
            max-width: 500px;
            height: auto;
            margin: 20px auto;
            display: block;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .content {
            text-align: justify;
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $article->title }}</div>
        <div class="meta">
            <strong>Penulis:</strong> {{ $article->user->name }}<br>
            <strong>Kategori:</strong> {{ $article->kategori->nama_kategori ?? '-' }}<br>
            <strong>Tanggal:</strong> {{ $article->created_at->format('d F Y') }}
        </div>
    </div>

    @if($article->photo)
    <div style="text-align: center; margin: 20px 0;">
        @php
            $imagePath = public_path('storage/' . $article->photo);
        @endphp
        @if(file_exists($imagePath))
            <img src="data:image/{{ pathinfo($imagePath, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($imagePath)) }}" class="article-image" alt="{{ $article->title }}">
        @else
            <div style="background: #f8f9fa; padding: 20px; border: 1px dashed #dee2e6; text-align: center; color: #6c757d;">
                <p>Gambar artikel tidak dapat dimuat</p>
            </div>
        @endif
    </div>
    @endif

    <div class="content">
        {!! nl2br(e($article->content)) !!}
    </div>

    <div class="footer">
        <p>Dokumen ini diunduh dari Web Mading pada {{ now()->format('d F Y H:i') }}</p>
    </div>
</body>
</html>