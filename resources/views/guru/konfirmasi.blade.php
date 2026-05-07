@extends('layouts.guru')

@section('title', 'Konfirmasi Peminjaman')

@section('content')

<style>
/* ================= FORCE FIX DARI PARENT ================= */

/* paksa area content boleh scroll */
.content,
.content > *,
.page-wrap{
    max-width:100% !important;
    overflow-x: visible !important;
}

/* ================= HALAMAN ================= */
.page-wrap{
    padding:24px;
}

/* ================= JUDUL ================= */
.page-title{
    text-align:center;
    font-weight:800;
    font-size:clamp(1.4rem,4vw,1.9rem);
    color:#004f9a;
    margin-bottom:24px;
}

/* ================= SEARCH ================= */
.search-box{
    max-width:420px;
    margin:0 auto 24px;
}
.search-input{
    width:100%;
    padding:12px 16px;
    border-radius:12px;
    border:2px solid #1c84ff;
    font-size:.95rem;
}

/* ================= CARD ================= */
.card-table{
    background:#fff;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    padding:16px;

    /* 🔥 INI YANG PENTING */
    width:100%;
    overflow-x:auto;
}

/* ================= TABLE ================= */
.data-table{
    border-collapse:collapse;
    min-width:1200px;  /* sengaja lebih besar */
    width:100%;
}

.data-table thead{
    background:#e8f2ff;
}

.data-table th{
    padding:12px;
    font-size:.8rem;
    font-weight:800;
    color:#004f9a;
    border-bottom:2px solid #bcd9ff;
    white-space:nowrap;
}

.data-table td{
    padding:10px 12px;
    font-size:.85rem;
    border-bottom:1px solid #e4efff;
    white-space:nowrap;
}

.data-table tbody tr:hover{
    background:#f5f9ff;
}

/* ================= BADGE ================= */
.badge-blue{
    background:#1c84ff;
    color:#fff;
    padding:4px 10px;
    border-radius:999px;
    font-size:.75rem;
    font-weight:700;
}

/* ================= ACTION ================= */
.action-btns{
    display:flex;
    gap:6px;
}

.btn-approve,
.btn-reject{
    padding:6px 12px;
    font-size:.75rem;
    font-weight:700;
    border:none;
    border-radius:8px;
    color:#fff;
}

.btn-loading {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-approve{ background:#0b9b33; }
.btn-reject{ background:#da1c1c; }

/* ================= SCROLLBAR ================= */
.card-table::-webkit-scrollbar{
    height:8px;
}
.card-table::-webkit-scrollbar-thumb{
    background:#c7dbff;
    border-radius:10px;
}

/* ================= MOBILE ================= */
@media(max-width:768px){
    .page-wrap{
        padding:14px;
    }
}
/* ================= MOBILE TEXT & SPACING ================= */
@media (max-width: 768px){

    /* Judul */
    .page-title{
        font-size:1.25rem;
        margin-bottom:16px;
    }

    /* Search */
    .search-input{
        font-size:.85rem;
        padding:10px 14px;
    }

    /* Card */
    .card-table{
        padding:10px;
    }

    /* Table header */
    .data-table th{
        font-size:.7rem;
        padding:8px 10px;
    }

    /* Table body */
    .data-table td{
        font-size:.75rem;
        padding:8px 10px;
    }

    /* Badge jumlah */
    .badge-blue{
        font-size:.65rem;
        padding:3px 8px;
    }

    /* Tombol aksi */
    .action-btns{
        gap:4px;
    }

    .btn-approve,
    .btn-reject{
        font-size:.65rem;
        padding:5px 8px;
        border-radius:6px;
    }
}
/* ================= NOTIFICATION ================= */
.notif{
    position:fixed;
    top:24px;
    right:24px;
    min-width:260px;
    max-width:90vw;
    padding:14px 16px;
    border-radius:12px;
    font-weight:700;
    font-size:14px;
    color:white;
    box-shadow:0 12px 28px rgba(0,0,0,.15);
    opacity:0;
    pointer-events:none;
    transform:translateY(-10px);
    transition:.3s ease;
    z-index:9999;
}

.notif.show{
    opacity:1;
    transform:translateY(0);
}

.notif.success{background:linear-gradient(135deg,#22c55e,#16a34a);}
.notif.error{background:linear-gradient(135deg,#ef4444,#dc2626);}
.notif.info{background:linear-gradient(135deg,#3b82f6,#2563eb);}

@media(max-width:768px){
    .notif{
        left:12px;
        right:12px;
        top:16px;
    }
}

</style>

<div class="page-wrap">

    <h1 class="page-title">Konfirmasi Peminjaman</h1>

    @if($detail->count())

    <div class="search-box">
        <input id="searchInput" class="search-input"
               placeholder="Cari nama, barang, kelas, mapel...">
    </div>

    <div class="card-table">
        <table class="data-table" id="dataTable">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                    <th>Guru</th>
                    <th>Mapel</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>WA</th>
                    <th>Ruangan</th>
                    <th>Alasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            @foreach($detail as $d)
                <tr class="search-item" id="row-{{ $d->id_detail }}">
                    <td>{{ $d->peminjaman->siswa->nama }}</td>
                    <td>{{ $d->peminjaman->siswa->nis }}</td>
                    <td>{{ $d->peminjaman->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $d->peminjaman->guru->nama }}</td>
                    <td>{{ $d->peminjaman->mapel->nama_mapel }}</td>
                    <td>{{ $d->barang->nama_barang }}</td>
                    <td><span class="badge-blue">{{ $d->jumlah }}</span></td>
                    <td>{{ $d->peminjaman->no_wa }}</td>
                    <td>{{ $d->peminjaman->tempat->nama ?? '-' }}</td>
                    <td>{{ $d->peminjaman->alasan }}</td>
                    <td>
                        <div class="action-btns">
                            <form onsubmit="return approve(event, {{ $d->id_detail }})">
                                @csrf
                                <button class="btn-approve">Setujui</button>
                            </form>
                            <form onsubmit="return reject(event, {{ $d->id_detail }})">
                                @csrf
                                <button class="btn-reject">Tolak</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @else
        <div class="alert alert-secondary text-center">Belum ada peminjaman.</div>
    @endif

</div>
@if(session('success'))
<script>


window.addEventListener('load', () => {
    showNotif('success', "{{ session('success') }}");
});
</script>
@endif

@if(session('error'))
<script>
window.addEventListener('load', () => {
    showNotif('error', "{{ session('error') }}");
});
</script>
@endif

@if(session('info'))
<script>
window.addEventListener('load', () => {
    showNotif('info', "{{ session('info') }}");
});
</script>
@endif

<div id="notif" class="notif"></div>


<script>
    function showNotif(type, message){
    const notif = document.getElementById('notif');
    notif.className = `notif ${type} show`;
    notif.textContent = message;

    setTimeout(() => {
        notif.classList.remove('show');
    }, 3000);
}

document.getElementById("searchInput")?.addEventListener("input", function(){
    const k = this.value.toLowerCase();
    document.querySelectorAll("#tableBody tr").forEach(r=>{
        r.style.display = r.innerText.toLowerCase().includes(k) ? "" : "none";
    });
});
document.getElementById("searchInput")?.addEventListener("input", function(){
    const k = this.value.toLowerCase();
    document.querySelectorAll("#tableBody tr").forEach(r=>{
        r.style.display = r.innerText.toLowerCase().includes(k) ? "" : "none";
    });
});

async function approve(event, id) {
    event.preventDefault();

    const row = document.getElementById(`row-${id}`);
    const buttons = row.querySelectorAll('button');

    const approveBtn = buttons[0];
    const rejectBtn = buttons[1];

    // 🔥 loading state
    approveBtn.disabled = true;
    rejectBtn.disabled = true;
    approveBtn.classList.add('btn-loading');
    approveBtn.innerText = 'Memproses...';

    try {

        const url = `{{ route('guru.detail.setuju', ':id') }}`
            .replace(':id', id);

        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.message);
        }

        showNotif('success', data.message);

        // kasih delay biar smooth
        setTimeout(() => row.remove(), 500);

    } catch (err) {

        // 🔥 balikin normal kalau gagal
        approveBtn.disabled = false;
        rejectBtn.disabled = false;
        approveBtn.classList.remove('btn-loading');
        approveBtn.innerText = 'Setujui';

        showNotif('error', err.message);
    }

    return false;
}

async function reject(event, id) {
    event.preventDefault();

    const row = document.getElementById(`row-${id}`);
    const buttons = row.querySelectorAll('button');

    const approveBtn = buttons[0];
    const rejectBtn = buttons[1];

    // 🔥 loading state
    approveBtn.disabled = true;
    rejectBtn.disabled = true;
    rejectBtn.classList.add('btn-loading');
    rejectBtn.innerText = 'Memproses...';

    try {

        const url = `{{ route('guru.detail.tolak', ':id') }}`
            .replace(':id', id);

        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.message);
        }

        showNotif('error', data.message);

        setTimeout(() => row.remove(), 500);

    } catch (err) {

        // 🔥 balikin normal kalau gagal
        approveBtn.disabled = false;
        rejectBtn.disabled = false;
        rejectBtn.classList.remove('btn-loading');
        rejectBtn.innerText = 'Tolak';

        showNotif('error', err.message);
    }

    return false;
}</script>

@endsection
