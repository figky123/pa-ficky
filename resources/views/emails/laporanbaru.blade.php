<!DOCTYPE html>
<html>

<head>
    <title>Laporan Warga Baru</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            border-left: 6px solid #007bff;
        }

        h1 {
            color: #333333;
            font-size: 24px;
            margin-top: 0;
            text-align: center;
        }

        p {
            color: #555555;
            line-height: 1.6;
            font-size: 16px;
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #888888;
            border-top: 1px solid #eeeeee;
            padding-top: 10px;
        }

        .footer p {
            margin: 5px 0;
        }

        .contact-info {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Laporan Warga Baru</h1>
        <p>Yth. Bapak Lurah Wahyu Nofriyandri,</p>
        <p>Berikut ini adalah laporan baru yang masuk:</p>

        <p><strong>Nama Warga:</strong> {{ $laporan->user->name }}<br>
            <strong>Tanggal Laporan:</strong> {{ $laporan->tgl_laporan }}<br>
            <strong>Keterangan:</strong> {{ $laporan->ket_laporan }}
        </p>

        <a href="{{ url('http://127.0.0.1:8000/laporan_warga') }}" class="button">
            Lihat Laporan
        </a>

        <div class="footer">
            <p>Hormat kami,</p>
            <p><strong>Kantor Kelurahan Labuhbaru Timur</strong></p>
            <div class="contact-info">
                <p>Alamat: Jl. Kayu Dadap Putih No. 01, Kota Pekanbaru</p>
                <p>Telepon: (0761) 7865008</p>
                <p>Email: admlurah@gmail.com</p>
            </div>
        </div>
    </div>
</body>
</html>