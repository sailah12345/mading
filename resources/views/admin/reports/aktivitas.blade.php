<!DOCTYPE html>
<html>
<head>
    <title>Laporan Aktivitas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; color: #333; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #FF9800; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .print-btn { margin: 20px 0; }
        @media print { .print-btn { display: none; } }
        .summary { display: flex; justify-content: space-around; margin: 20px 0; }
        .summary-box { text-align: center; padding: 15px; background: #f5f5f5; border-radius: 5px; flex: 1; margin: 0 10px; }
    </style>
</head>
<body>
    <div class="print-btn">
        <button onclick="window.print()" style="padding: 10px 20px; background: #FF9800; color: white; border: none; cursor: pointer;">
            Print / Save PDF
        </button>
    </div>

    <h2>LAPORAN AKTIVITAS WEB MADING</h2>
    
    <div class="info">
        <p><strong>Periode:</strong> {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>{{ $totalArtikel }}</h3>
            <p>Total Artikel</p>
        </div>
        <div class="summary-box">
            <h3>{{ $artikelPublished }}</h3>
            <p>Published</p>
        </div>
        <div class="summary-box">
            <h3>{{ $artikelPending }}</h3>
            <p>Pending</p>
        </div>
    </div>

    <h3>Artikel Per Kategori</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Jumlah Artikel</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($artikelPerKategori as $idKategori => $articles)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $articles->first()->kategori->nama_kategori ?? 'Tanpa Kategori' }}</td>
                <td>{{ $articles->count() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center;">Tidak ada data pada periode ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <p>Dicetak oleh: {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</p>
    </div>
</body>
</html>
