<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Aktivitas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .info {
            margin-bottom: 20px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            background-color: #f8f9fa;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN AKTIVITAS</h2>
        <h3>Web Mading Digital Sekolah</h3>
    </div>

    <div class="info">
        <strong>Periode:</strong> {{ $start_date }} - {{ $end_date }}<br>
        <strong>Tanggal Generate:</strong> {{ $generated_at }}
    </div>

    <div class="section">
        <h4>Statistik Umum</h4>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell">
                    <strong>{{ $activities['articles_created'] }}</strong><br>
                    Artikel Dibuat
                </div>
                <div class="stats-cell">
                    <strong>{{ $activities['articles_published'] }}</strong><br>
                    Artikel Published
                </div>
                <div class="stats-cell">
                    <strong>{{ $activities['total_likes'] }}</strong><br>
                    Total Likes
                </div>
                <div class="stats-cell">
                    <strong>{{ $activities['new_users'] }}</strong><br>
                    User Baru
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <h4>Artikel Terpopuler</h4>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Artikel</th>
                    <th>Penulis</th>
                    <th>Likes</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities['top_articles'] as $index => $article)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->user->name }}</td>
                    <td>{{ $article->likes_count }}</td>
                    <td>{{ $article->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h4>Artikel per Kategori</h4>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Jumlah Artikel</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities['articles_by_category'] as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $category->nama_kategori }}</td>
                    <td>{{ $category->total }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem Web Mading Digital Sekolah</p>
    </div>
</body>
</html>