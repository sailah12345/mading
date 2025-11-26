<!DOCTYPE html>
<html>
<head>
    <title>Laporan Artikel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; color: #333; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .print-btn { margin: 20px 0; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <div class="print-btn">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer;">
            Print / Save PDF
        </button>
    </div>

    <h2>LAPORAN ARTIKEL WEB MADING</h2>
    
    <div class="info">
        <p><strong>Periode:</strong> {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</p>
        <p><strong>Total Artikel:</strong> {{ $articles->count() }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Artikel</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($articles as $index => $article)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $article->title }}</td>
                <td>{{ $article->user->name }}</td>
                <td>{{ $article->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ ucfirst($article->status) }}</td>
                <td>{{ $article->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data artikel pada periode ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <p>Dicetak oleh: {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</p>
    </div>
</body>
</html>
