<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Artikel</title>
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
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .stats-table {
            width: 60%;
            margin-bottom: 30px;
        }
        .approved { color: green; font-weight: bold; }
        .rejected { color: red; font-weight: bold; }
        .pending { color: orange; font-weight: bold; }
        .total-row { background-color: #e9ecef; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN ARTIKEL</h2>
        <h3>Web Mading Digital Sekolah</h3>
    </div>

    <div class="info">
        <strong>Periode:</strong> {{ $month }} {{ date('Y') }}<br>
        <strong>Kategori:</strong> {{ $kategori }}<br>
        <strong>Total Artikel:</strong> {{ $articles->count() }} artikel<br>
        <strong>Tanggal Generate:</strong> {{ $generated_at }}
    </div>

    <!-- TABEL STATISTIK APPROVE/REJECT -->
    <h4>📊 Statistik Status Artikel</h4>
    <table class="stats-table">
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = $articles->count();
                $approved = $articles->where('status', 'published')->count();
                $rejected = $articles->where('status', 'rejected')->count();
                $pending = $articles->where('status', 'pending')->count();
            @endphp
            <tr>
                <td class="approved">✅ Disetujui (Published)</td>
                <td>{{ $approved }}</td>
                <td>{{ $total > 0 ? round(($approved/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td class="rejected">❌ Ditolak (Rejected)</td>
                <td>{{ $rejected }}</td>
                <td>{{ $total > 0 ? round(($rejected/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td class="pending">⏳ Menunggu (Pending)</td>
                <td>{{ $pending }}</td>
                <td>{{ $total > 0 ? round(($pending/$total)*100, 1) : 0 }}%</td>
            </tr>
            <tr class="total-row">
                <td>📈 TOTAL</td>
                <td>{{ $total }}</td>
                <td>100%</td>
            </tr>
        </tbody>
    </table>

    <!-- TABEL ARTIKEL PER PENULIS -->
    <h4>👥 Artikel per Penulis</h4>
    <table class="stats-table">
        <thead>
            <tr>
                <th>Penulis</th>
                <th>Role</th>
                <th>Total</th>
                <th>Disetujui</th>
                <th>Ditolak</th>
                <th>Pending</th>
            </tr>
        </thead>
        <tbody>
            @php
                $articlesByUser = $articles->groupBy('user.name');
            @endphp
            @foreach($articlesByUser as $userName => $userArticles)
            <tr>
                <td>{{ $userName }}</td>
                <td>{{ ucfirst($userArticles->first()->user->role) }}</td>
                <td>{{ $userArticles->count() }}</td>
                <td class="approved">{{ $userArticles->where('status', 'published')->count() }}</td>
                <td class="rejected">{{ $userArticles->where('status', 'rejected')->count() }}</td>
                <td class="pending">{{ $userArticles->where('status', 'pending')->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h4>📋 Ringkasan</h4>
        <p>Total artikel: <strong>{{ $articles->count() }}</strong></p>
        <p>Total likes: <strong>{{ $articles->sum(function($article) { return $article->likes->count(); }) }}</strong></p>
        <p>Artikel terpopuler: <strong>{{ $articles->sortByDesc(function($article) { return $article->likes->count(); })->first()->title ?? 'Tidak ada' }}</strong></p>
        <p>Tingkat persetujuan: <strong>{{ $total > 0 ? round(($approved/$total)*100, 1) : 0 }}%</strong></p>
    </div>

    <!-- TABEL DETAIL ARTIKEL -->
    <h4>📝 Detail Artikel</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Artikel</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Likes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($articles as $index => $article)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $article->title }}</td>
                <td>{{ $article->user->name }}</td>
                <td>{{ $article->kategori->nama_kategori ?? '-' }}</td>
                <td>
                    @if($article->status == 'published')
                        <span class="approved">✅ Disetujui</span>
                    @elseif($article->status == 'rejected')
                        <span class="rejected">❌ Ditolak</span>
                    @else
                        <span class="pending">⏳ Pending</span>
                    @endif
                </td>
                <td>{{ $article->created_at->format('d/m/Y') }}</td>
                <td>{{ $article->likes->count() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data artikel</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem Web Mading Digital Sekolah</p>
        <p>Generated on: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
