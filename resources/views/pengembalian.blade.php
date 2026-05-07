@extends('layouts.main')

@section('title', 'Pengembalian Barang')

@section('content')

<style>
.pg-return {
    --primary:#1f6fb2;
    --border:#d7e6f7;
    --text-soft:#4f5b72;
    --success:#22c55e;
    --danger:#ef4444;
    --warning:#f59e0b;
    --info:#3c9edc;
    font-family: Inter, system-ui, sans-serif;
}

/* LAYOUT */
.pg-return__wrap {
    padding: 32px 16px;
    display: flex;
    justify-content: center;
}
.pg-return__box {
    max-width: 1200px;
    width: 100%;
}
.pg-return__title {
    text-align: center;
    font-size: 2rem;
    font-weight: 900;
    color: var(--primary);
    margin-bottom: 28px;
}

/* CARD */
.pg-return__card {
    background: #fff;
    border-radius: 18px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: 0 10px 28px rgba(0,0,0,.06);
    margin-bottom: 28px;
}

/* FORM */
.pg-return__label { font-weight: 800; }
.pg-return__input {
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid var(--border);
}
.pg-return__btn {
    width: 100%;
    margin-top: 12px;
    padding: 12px;
    border-radius: 12px;
    border: none;
    font-weight: 800;
    background: var(--primary);
    color: #fff;
}

/* GRID TABLE */
.pg-return__grid-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.pg-return__grid {
    min-width: 1000px;
    display: grid;
    grid-template-columns: 2fr 0.8fr 1.2fr 1.2fr 1.2fr 1.6fr;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
}
.pg-return__head,
.pg-return__row { display: contents; }

.pg-return__cell {
    padding: 10px 10px;
    border-bottom: 1px solid var(--border);
    text-align: center;
    color: var(--text-soft);
    background: #fff;
    font-size: 0.78rem;
}
.pg-return__row:hover .pg-return__cell {
    background: #f2f8ff;
}
.pg-return__head .pg-return__cell {
    background: var(--primary);
    color: #fff;
    font-weight: 800;
    font-size: 0.75rem;
}

/* BADGE */
.pg-return__badge {
    padding: 4px 8px;
    border-radius: 999px;
    font-size: 0.65rem;
    font-weight: 800;
    color: #fff;
    white-space: nowrap;
}
.pg-return__badge--success { background: var(--success); }
.pg-return__badge--warning { background: var(--warning); }
.pg-return__badge--info { background: var(--info); }

/* ACTION */
.pg-return__action {
    width: 100%;
    padding: 6px;
    border-radius: 8px;
    border: none;
    font-weight: 800;
    font-size: 0.65rem;
    background: var(--danger);
    color: #fff;
}
.pg-return__action[disabled] {
    background: #cbd6e3;
    color: #6b7a93;
}

/* EMPTY */
.pg-return__empty {
    text-align: center;
    padding: 48px 20px;
    color: var(--text-soft);
}
.pg-return__empty i {
    font-size: 42px;
    margin-bottom: 10px;
    color: #94a3b8;
}

/* STEPS */
.pg-return__steps-title {
    text-align: center;
    font-weight: 900;
    margin-bottom: 18px;
}
.pg-return__steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap: 18px;
}
.pg-return__step {
    background: #f9fcff;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 18px;
    text-align: center;
}
.pg-return__step-num {
    width: 42px;
    height: 42px;
    margin: 0 auto 8px;
    border-radius: 50%;
    background: var(--primary);
    color: #fff;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* MODAL */
.pg-return__modal {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.55);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
.pg-return__modal-box {
    background: #fff;
    border-radius: 20px;
    padding: 26px 24px;
    width: 92%;
    max-width: 360px;
    text-align: center;
}
.pg-return__modal-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

/* TOAST */
.pg-return__toast {
    position: fixed;
    right: -420px;
    bottom: 24px;
    background: #0f172a;
    color: #fff;
    padding: 14px 18px;
    border-radius: 16px;
    transition: transform .4s ease;
    z-index: 10000;
}
.pg-return__toast.show {
    transform: translateX(-440px);
}

/* =============================
   EXTRA SMALL MOBILE
============================= */
@media (max-width: 480px) {
    .pg-return__grid { min-width: 680px; }
    .pg-return__cell { font-size: 0.68rem; }
    .pg-return__badge { font-size: 0.6rem; }
    .pg-return__action { font-size: 0.6rem; }
}
/* =============================
   CURSOR POINTER (INTERAKTIF)
============================= */

/* Semua tombol utama */
.pg-return button,
.pg-return .pg-return__btn,
.pg-return .pg-return__action {
    cursor: pointer;
}

/* Tombol disabled tetap normal */
.pg-return button[disabled],
.pg-return .pg-return__action[disabled] {
    cursor: not-allowed;
}

/* Badge (kalau mau terasa klik-able) */
.pg-return .pg-return__badge {
    cursor: default;
}

/* Modal action */
.pg-return__modal-actions button {
    cursor: pointer;
}
.pg-return button:hover:not([disabled]),
.pg-return .pg-return__action:hover:not([disabled]) {
    transform: translateY(-1px);
}
.pg-return__badge--danger { background: var(--danger); }

</style>

<div class="pg-return">
<div class="pg-return__wrap">
<div class="pg-return__box">

<h2 class="pg-return__title">Pengembalian Barang</h2>

<div class="pg-return__card">
<form method="POST" action="{{ route('pengembalian.cari') }}">
@csrf
<label class="pg-return__label">Masukkan NIS</label>
<input name="nis" class="pg-return__input" required>
<button class="pg-return__btn">Cari Data</button>
</form>
</div>

@isset($peminjaman)

@if($peminjaman->count() > 0)

<div class="pg-return__card">
<div class="pg-return__grid-wrap">
<div class="pg-return__grid">

<div class="pg-return__head">
<div class="pg-return__cell">Barang</div>
<div class="pg-return__cell">Jumlah</div>
<div class="pg-return__cell">Ruangan</div>
<div class="pg-return__cell">Status Pinjam</div>
<div class="pg-return__cell">Status Kembali</div>
<div class="pg-return__cell">Aksi</div>
</div>

@foreach($peminjaman as $p)
@foreach($p->detail as $d)

<div class="pg-return__row" data-row="{{ $d->id_detail }}">
<div class="pg-return__cell">{{ $d->barang->nama_barang }}</div>
<div class="pg-return__cell">{{ $d->jumlah }}</div>
<div class="pg-return__cell">{{ $p->ruangan }}</div>

<div class="pg-return__cell">
<span class="pg-return__badge pg-return__badge--success">{{ $d->status_peminjaman }}</span>
</div>

<div class="pg-return__cell status-kembali">
@php
$badgeClass = match($d->status_pengembalian){
    'Belum' => 'pg-return__badge--warning',
    'Menunggu Guru' => 'pg-return__badge--info',
    'Menunggu Sarpras' => 'pg-return__badge--info',
    'Ditolak Guru', 'Ditolak Sarpras' => 'pg-return__badge--danger',
    'Selesai', 'Dikembalikan' => 'pg-return__badge--success',
    default => 'pg-return__badge--warning',
};

@endphp

<span class="pg-return__badge {{ $badgeClass }}">
    {{ $d->status_pengembalian }}
</span>

</div>

<div class="pg-return__cell">
@php
    $bolehKembalikan = in_array($d->status_pengembalian, [
        'Belum',
        'Ditolak Guru',
        'Ditolak Sarpras'
    ]);
@endphp

@if($bolehKembalikan)
    <button class="pg-return__action open-modal" data-id="{{ $d->id_detail }}">
        Kembalikan
    </button>
@else
    <button class="pg-return__action" disabled>
        Menunggu
    </button>
@endif

</div>
</div>

@endforeach
@endforeach

</div>
</div>
</div>

@else
<div class="pg-return__card">
<div class="pg-return__empty">
<i class="fa fa-box-open"></i>
<h5>Tidak ada data peminjaman</h5>
<p>Barang yang masih dipinjam akan muncul di sini.</p>
</div>
</div>
@endif
@endisset

<div class="pg-return__toast" id="toast">Pengembalian berhasil diproses</div>
@if(session('error'))
<div class="pg-return__card">
    <div class="pg-return__empty">
        <i class="fa fa-triangle-exclamation"></i>
        <h5>{{ session('error') }}</h5>
        <p>Masukkan NIS untuk melihat data pengembalian.</p>
    </div>
</div>
@endif
<div class="pg-return__card">
    <h4 class="pg-return__steps-title">Alur Pengembalian Barang</h4>
    <div class="pg-return__steps">
        <div class="pg-return__step"><div class="pg-return__step-num">1</div>Masukkan NIS</div>
        <div class="pg-return__step"><div class="pg-return__step-num">2</div>Data ditampilkan</div>
        <div class="pg-return__step"><div class="pg-return__step-num">3</div>Klik kembalikan</div>
        <div class="pg-return__step"><div class="pg-return__step-num">4</div>Guru cek kondisi</div>
<div class="pg-return__step"><div class="pg-return__step-num">5</div>Sarpras validasi</div>
<div class="pg-return__step"><div class="pg-return__step-num">6</div>Status selesai</div>
</div>
</div>

</div>
</div>

<div class="pg-return__modal" id="confirmModal">
<div class="pg-return__modal-box">
<h5>Konfirmasi Pengembalian</h5>
<p>Yakin ingin mengembalikan barang ini?</p>
<div class="pg-return__modal-actions">
<button class="pg-return__btn" id="confirmYes">Ya</button>
<button class="pg-return__btn" style="background:#e5e7eb;color:#374151" id="confirmNo">Batal</button>
</div>
</div>
</div>


<script>
let selectedId=null;
const modal=document.getElementById('confirmModal');
const toast=document.getElementById('toast');

document.addEventListener('keydown',e=>{ if(e.key==='Escape')e.preventDefault(); });

document.body.addEventListener('click',e=>{
    if(e.target.classList.contains('open-modal')){
        selectedId=e.target.dataset.id;
        modal.style.display='flex';
    }
});

function closeModal(){ modal.style.display='none'; }
document.getElementById('confirmNo').onclick=closeModal;
modal.onclick=e=>{ if(e.target===modal)closeModal(); };

document.getElementById('confirmYes').onclick=()=>{
    fetch("{{ url('pengembalian/kembalikan') }}/"+selectedId,{
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
    })
    .then(r=>r.json())
    .then(d=>{
        closeModal();
        if(d.success){
            const row=document.querySelector(`[data-row='${selectedId}']`);
            row.querySelector('.status-kembali').innerHTML=
                `<span class="pg-return__badge pg-return__badge--info">Menunggu</span>`;
            row.querySelector('.open-modal').outerHTML=
                `<button class="pg-return__action" disabled>Menunggu</button>`;
            toast.classList.add('show');
            setTimeout(()=>toast.classList.remove('show'),2500);
        }
    });
};
</script>

@endsection
