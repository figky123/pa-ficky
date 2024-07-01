<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jumantik</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px; /* Mengurangi padding untuk memastikan tidak melebihi batas */
            text-align: center;
            font-size: 12px;
            transition: background-color 0.3s, transform 0.3s;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 3px solid #ccc;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #d1e7dd;
            transform: scale(1.02);
            transition: background-color 0.3s, transform 0.3s;
        }

        td:hover {
            background-color: #e9f5f3;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/img/kopsurat.jpg') }}" alt="Kop Surat">
    </div>
    <h1>Laporan Jumantik</h1>

    <table>
        <thead>
            <tr>
                <th>Nama Petugas</th>
                <th>Siklus</th>
                <th>Jumlah Rumah Warga yang Diperiksa</th>
                <th>Jumlah Rumah Positif Jentik</th>
                <th>ABJ (Angka Bebas Jentik)</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summaryData as $item)
            <tr>
                <td>{{ $item['nama_petugas'] }}</td>
                <td>{{ $item['siklus'] }}</td>
                <td>{{ $item['uniqueBuildingsCount'] }}</td>
                <td>{{ $item['positiveLarvaeCount'] }}</td>
                <td>{{ number_format($item['abj'], 2) }}%</td>
                <td>{{ $item['kategori'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
