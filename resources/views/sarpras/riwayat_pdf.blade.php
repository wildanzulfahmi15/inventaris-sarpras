<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>
@page {
    margin: 30px 40px;
}

body {
    font-family: "Times New Roman", serif;
    font-size: 11px;
    color: #000;
}

/* ================= KOP SURAT ================= */
.kop table {
    width: 100%;
    border: none;
}

.kop td {
    border: none;
    vertical-align: middle;
}

.logo {
    width: 80px;
}

.kop-text {
    text-align: center;
}

.kop-text .prov {
    font-size: 13px;
    font-weight: bold;
    text-transform: uppercase;
}

.kop-text .school {
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    margin: 2px 0;
}

.kop-text .alamat {
    font-size: 10px;
    line-height: 1.4;
}

.hr-bold {
    border-top: 2px solid #000;
    margin-top: 8px;
}

.hr-thin {
    border-top: 1px solid #000;
    margin-top: 2px;
    margin-bottom: 16px;
}

/* ================= JUDUL ================= */
.judul {
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    letter-spacing: 1px;
    margin-bottom: 14px;
}

/* ================= INFO ================= */
.info {
    border: 1px solid #000;
    padding: 8px 12px;
    margin-bottom: 12px;
}

.info table {
    width: 100%;
    border: none;
}

.info td {
    border: none;
    padding: 3px 0;
}

.label {
    width: 22%;
}

/* ================= PARAGRAF LAPORAN ================= */
.paragraf {
    text-align: justify;
    margin-bottom: 14px;
}

/* ================= TABEL ================= */
table.data {
    width: 100%;
    border-collapse: collapse;
}

table.data th {
    border: 1px solid #000;
    padding: 6px 5px;
    text-align: center;
    font-weight: bold;
    background: #f2f2f2;
}

table.data td {
    border: 1px solid #000;
    padding: 6px 5px;
    vertical-align: top;
}

.center {
    text-align: center;
}

/* ================= TTD ================= */
.ttd {
    margin-top: 30px;
    width: 40%;
    float: right;
    text-align: center;
}
.foto-img {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border: 1px solid #ccc;
}

.foto-cell {
    text-align: center;
}
</style>

</head>
<body>

<!-- ================= KOP ================= -->
<div class="kop">
<table>
<tr>
    <!-- KIRI : LOGO -->
    <td width="15%" align="center">
        <img src="{{ public_path('img/logo.png') }}" class="logo">
    </td>

    <!-- TENGAH : TEKS -->
    <td width="70%" class="kop-text">
        <div class="prov">Pemerintah Provinsi Jawa Barat</div>
        <div class="school">SMK Negeri 1 Cibinong</div>
        <div class="alamat">
            Jl. Raya Karadenan No.7, Cibinong – Kabupaten Bogor<br>
            Telp. (021) 8790xxxx | Email: smkn1cibinong@sch.id
        </div>
    </td>

    <!-- KANAN : PENYEIMBANG -->
    <td width="15%"></td>
</tr>
</table>

<div class="hr-bold"></div>
<div class="hr-thin"></div>
</div>

<!-- ================= JUDUL ================= -->
<div class="judul">
LAPORAN RIWAYAT PEMINJAMAN SARANA DAN PRASARANA
</div>

<!-- ================= INFO ================= -->
<div class="info">
<table>
<tr>
    <td class="label">Periode</td>
    <td width="2%">:</td>
    <td>{{ request('tanggal_mulai') ?? '-' }} s/d {{ request('tanggal_selesai') ?? '-' }}</td>
</tr>
<tr>
    <td>Status Peminjaman</td>
    <td>:</td>
    <td>{{ request('status_peminjaman') ?? 'Semua' }}</td>
</tr>
<tr>
    <td>Status Pengembalian</td>
    <td>:</td>
    <td>{{ request('status_pengembalian') ?? 'Semua' }}</td>
</tr>
<tr>
    <td>Kelas</td>
    <td>:</td>
    <td>{{ request('kelas') ?? 'Semua' }}</td>
</tr>
</table>
</div>

<!-- ================= PARAGRAF ================= -->
<div class="paragraf">
Dengan ini melaporkan bahwa data berikut merupakan
<strong>laporan riwayat peminjaman sarana dan prasarana</strong>
di SMK Negeri 1 Cibinong yang disusun berdasarkan data transaksi
peminjaman yang tercatat pada sistem, sesuai dengan periode
dan kriteria yang telah ditentukan sebagai bahan dokumentasi
dan pertanggungjawaban administratif.
</div>

<!-- ================= TABEL ================= -->
<table class="data">
<thead>
<tr>
    <th width="3%">No</th>
    <th width="7%">Foto Pinjam</th>
    <th width="7%">Foto Kembali</th>
    <th>Nama Siswa</th>
    <th width="7%">Kelas</th>
    <th>Guru</th>
    <th>Barang (Jumlah)</th>
    <th width="8%">Tgl Pinjam</th>
    <th width="8%">Tgl Kembali</th>
    <th>Status Pinjam</th>
    <th>Status Kembali</th>
    <th width="9%">Transaksi</th>
</tr>
</thead>

<tbody>
@php $no = 1; @endphp

@foreach($detail as $d)
@php
    $p = $d->peminjaman;

    $fotoPinjam = $p->foto_pinjam
        ? public_path('storage/peminjaman/' . $p->foto_pinjam)
        : null;

    $fotoKembali = $d->foto_pengembalian
        ? public_path('storage/' . $d->foto_pengembalian)
        : null;
@endphp

<tr>
    <td class="center">{{ $no++ }}</td>

    <!-- FOTO PINJAM -->
    <td class="foto-cell">
        @if($fotoPinjam && file_exists($fotoPinjam))
            <img src="{{ $fotoPinjam }}" class="foto-img">
        @else
            -
        @endif
    </td>

    <!-- FOTO KEMBALI -->
    <td class="foto-cell">
        @if($fotoKembali && file_exists($fotoKembali))
            <img src="{{ $fotoKembali }}" class="foto-img">
        @else
            -
        @endif
    </td>

    <td>{{ $p->siswa->nama ?? '-' }}</td>
    <td class="center">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
    <td>{{ $p->guru->nama ?? '-' }}</td>

    <td>{{ $d->barang->nama_barang }} ({{ $d->jumlah }})</td>

    <td class="center">{{ $p->tanggal_pinjam }}</td>
    <td class="center">{{ $d->tanggal_pengembalian ?? '-' }}</td>

    <td>{{ $d->status_peminjaman }}</td>
    <td>{{ $d->status_pengembalian }}</td>

    <td class="center">{{ $p->status }}</td>
</tr>
@endforeach
</tbody>
</table>

<!-- ================= TTD ================= -->
<div class="ttd">
<p>Cibinong, {{ date('d F Y') }}</p>
<p><strong>Petugas Sarana dan Prasarana</strong></p>
<br><br><br>
<p><strong>( _______________________ )</strong></p>
<p>NIP. _______________________</p>
</div>

</body>
</html>
