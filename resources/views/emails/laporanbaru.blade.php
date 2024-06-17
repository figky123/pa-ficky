<x-mail::message>
# Laporan Warga Baru

Ada laporan baru yang masuk dengan detail sebagai berikut:

**Nama Warga:** {{ $laporan->user->name }}<br>
**Tanggal Laporan:** {{ $laporan->tgl_laporan }}<br>
**Keterangan:** {{ $laporan->ket_laporan }}

<x-mail::button :url="url('http://127.0.0.1:8000/laporan_warga')">
Lihat Laporan
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
