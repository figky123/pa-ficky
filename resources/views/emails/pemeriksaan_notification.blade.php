<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header, .footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .details {
            margin-top: 20px;
        }
        .details ul {
            list-style-type: none;
            padding: 0;
        }
        .details ul li {
            padding: 5px 0;
        }
        .signature {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Pemberitahuan Pemeriksaan</h2>
    </div>
    <div class="content">
        <p>Kepada Yth. Bapak/Ibu Lurah,</p>
        <p>Dengan hormat,</p>
        <p>Berikut ini adalah hasil pemeriksaan terbaru yang perlu mendapatkan perhatian:</p>
        <div class="details">
            <ul>
                <li><strong>Nama Kepala Keluarga:</strong> {{ $data['id_user'] }}</li>
                <li><strong>Tanggal Pemeriksaan:</strong> {{ \Carbon\Carbon::parse($data['tgl_pemeriksaan'])->format('d-m-Y') }}</li>
                <li><strong>Siklus:</strong> {{ $data['siklus'] }}</li>
                <li><strong>Status Jentik:</strong> {{ $data['status_jentik'] }}</li>
                <li><strong>RT:</strong> {{ $data['RT'] }}</li>
                <li><strong>RW:</strong> {{ $data['RW'] }}</li>
                <li><strong>Alamat:</strong> {{ $data['alamat'] }}</li>
            </ul>
        </div>
        <p>Mohon untuk segera menindaklanjuti hasil pemeriksaan ini sesuai dengan prosedur yang berlaku.</p>
        <p>Terima kasih atas perhatian dan kerjasamanya.</p>
        <div class="signature">
            <p>Hormat kami,</p>
            <p>Tim Pemeriksaan Kesehatan</p>
        </div>
    </div>
    <div class="footer">
        <p>Kelurahan Labuhbaru Timur, Jl. Kayu Dadap Putih No. 01, Kota Pekanbaru</p>
        <p>Telepon: (0761) 7865008 | Email: admlurah@gmail.com</p>
    </div>
</body>
</html>
