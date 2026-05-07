<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventaris Sarpras — SMKN 1 Cibinong</title>


  {{-- CSS HOME --}}
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

  {{-- NAVBAR COMPONENT --}}
  <x-navbar />

  <!-- BANNER -->
<header class="banner" id="banner"
  data-img1="{{ asset('img/banner1.png') }}"
  data-img2="{{ asset('img/banner2.png') }}"
  data-img3="{{ asset('img/banner3.png') }}"
>
  <div class="banner-layer layer1"></div>
  <div class="banner-layer layer2"></div>

  <div class="banner-content" id="banner-content">
    <h1>Selamat Datang di Inventaris Sarpras</h1>
    <p>SMKN 1 Cibinong</p>
  </div>
</header>
  
  
  <!-- ABOUT -->
  <section class="about" id="about">
    <h2>Tentang Sistem Inventaris Sarpras</h2>
    <p>Sistem ini dirancang untuk mempermudah pengelolaan, pencatatan, dan pelacakan barang inventaris di SMKN 1 Cibinong secara digital dan efisien.</p>
    
    <div class="features">
      <div class="card">
        <div class="card-icon">📦</div>
        <h3>Data Barang</h3>
        <p>Menampilkan data barang yang tersimpan di gudang sekolah secara real-time.</p>
      </div>
      
      <div class="card">
        <div class="card-icon">📝</div>
        <h3>Peminjaman</h3>
        <p>Mempermudah proses peminjaman dan pengembalian barang bagi guru dan siswa.</p>
      </div>
      
      <div class="card">
        <div class="card-icon">👩‍💼</div>
        <h3>Admin Sekolah</h3>
        <p>Dikelola langsung oleh petugas sarpras untuk menjaga keakuratan data inventaris.</p>
      </div>
    </div>
  </section>
  
  <!-- FOOTER -->
<x-footer />
  
  {{-- SCRIPT HOME --}}
  <script src="{{ asset('js/home.js') }}"></script>


</body>
</html>
