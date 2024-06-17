<!DOCTYPE html>
<html>

<head>
    <title>Laporan Jumantik</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
            font-size: 16px;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            border-bottom: 2px solid #ccc;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
            transition: background-color 0.3s;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('assets/img/kop_surat.jpg') }}" alt="Kop Surat">
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
    </div>
</body>

</html>