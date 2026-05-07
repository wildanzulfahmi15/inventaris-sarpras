@extends('layouts.main')

@section('title', 'Pilih Kategori Barang')

@section('content')
@if(session('error'))
    <div class="alert-kategori" style="
        margin-bottom:16px;
        padding:14px;
        border-radius:10px;
        background:#fff7ed;
        border:1px solid #fed7aa;
        color:#9a3412;
        font-weight:600;
    ">
        ⚠️ {{ session('error') }}
    </div>

    <script>
        setTimeout(() => {
            document.querySelector('.alert-kategori')?.remove();
        }, 3000);
    </script>
@endif

<style>
/* === CSS ASLI (TIDAK DIUBAH) === */
.container {
    max-width: 1000px;
    margin: 0 auto;
    text-align: center;
}

.grid {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    justify-content: center;
    padding: 20px 0 50px 0;
}

.card {
    width: 250px;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    box-shadow: 0 3px 8px rgba(0,0,0,0.06);
    transition: transform .25s ease, box-shadow .25s ease;
    cursor: pointer;
    text-align: center;
    position: relative;
}

.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 22px rgba(0,0,0,0.12);
}

.card img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    transition: transform .4s ease;
}

.card:hover img {
    transform: scale(1.08);
}

.card-title {
    padding: 14px 10px;
    font-size: 18px;
    font-weight: 600;
    color: #1e3a8a;
}

/* === OVERLAY === */
.overlay {
    position: absolute;
    inset: 0;
    padding: 20px;
    background: rgba(0,0,0,0.45);
    border-radius: 16px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;

    opacity: 0;
    transform: translateY(100px);
    transition: opacity .28s ease, transform .50s ease;
}

/* HANYA HOVER — TANPA ACTIVE */
.card:hover .overlay {
    opacity: 1;
    transform: translateY(0);
}

.overlay p {
    font-size: 14px;
    color: white;
    margin-bottom: 12px;
    max-width: 210px;
}

.pinjamBtn {
    background: #2563eb;
    color: white;
    border: none;
    padding: 8px 18px;
    font-size: 14px;
    border-radius: 8px;
    cursor: pointer;
}

.pinjamBtn:hover {
    background: #1d4ed8;
}
</style>

<div class="container">
    <h1 class="fw-bold mb-4" style="font-size: 26px; color:#1e3a8a;">
        Pilih Kategori Barang
    </h1>

    <div class="grid">
        @foreach($kategori as $k)
            <div class="card">
                @if($k->gambar)
                    <img
                        src="{{ asset('img/kategori/'.$k->gambar) }}"
                        alt="{{ $k->nama_kategori }}"
                        onerror="this.style.display='none'"
                    >
                @endif

                <div class="card-title">{{ $k->nama_kategori }}</div>

                <div class="overlay">
                    <p>{{ $k->keterangan }}</p>
                    <a href="{{ route('peminjaman.pilihBarang', $k->id_kategori) }}">
                        <button class="pinjamBtn">Pinjam</button>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
