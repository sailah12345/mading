<!DOCTYPE html>
<html>
<head>
    <title>Laporan User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; color: #333; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #2196F3; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .print-btn { margin: 20px 0; }
        @media print { .print-btn { display: none; } }
        .summary { display: flex; justify-content: space-around; margin: 20px 0; }
        .summary-box { text-align: center; padding: 15px; background: #f5f5f5; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="print-btn">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2196F3; color: white; border: none; cursor: pointer;">
            Print / Save PDF
        </button>
    </div>

    <h2>LAPORAN DATA USER WEB MADING</h2>
    
    <div class="info">
        <p><strong>Tanggal Cetak:</strong> {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>{{ $totalAdmin }}</h3>
            <p>Admin</p>
        </div>
        <div class="summary-box">
            <h3>{{ $totalGuru }}</h3>
            <p>Guru</p>
        </div>
        <div class="summary-box">
            <h3>{{ $totalSiswa }}</h3>
            <p>Siswa</p>
        </div>
        <div class="summary-box">
            <h3>{{ $users->count() }}</h3>
            <p>Total User</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Total Artikel</th>
                <th>Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ $user->articles_count }}</td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <p>Dicetak oleh: {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</p>
    </div>
</body>
</html>
