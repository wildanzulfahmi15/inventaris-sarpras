@extends('layouts.sarpras')

@section('title', 'Data Pengembalian')

@section('content')

<style>

/* ============================
   TITLE
============================ */
.page-title {
    font-weight: 800;
    font-size: clamp(1.4rem, 2vw, 1.7rem);
    color: #1e3a8a;
    text-align: center;
    margin-bottom: 25px;
}

/* ============================
   SEARCH
============================ */
.search-box {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-bottom: 20px;
}

.search-input {
    width: 100%;
    max-width: 450px;
    padding: 12px 14px;
    border-radius: 12px;
    border: 2px solid #1e3a8a;
    font-size: .95rem;
    transition: .2s;
}

.search-input:focus {
    border-color: #1e40af;
    box-shadow: 0 0 8px rgba(30, 64, 175, .35);
}

/* ============================
   TABLE CARD WRAPPER
============================ */
.table-wrapper {
    background: #ffffff;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 8px 22px rgba(30, 58, 138, 0.12);
    width: 100%;
    overflow: auto;
    max-height: 75vh;
    border: 1px solid #edf2f7;
}

/* ============================
   TABLE
============================ */
.custom-table {
    width: 100%;
    min-width: 900px; /* Ditambah sedikit karena ada kolom foto baru */
    border-collapse: collapse;
    font-size: .9rem;
}

.custom-table thead {
    background: #e1effe;
}

.custom-table th {
    padding: 12px;
    font-weight: 700;
    color: #1e3a8a;
    border-bottom: 2px solid #bfdbfe;
    white-space: nowrap;
}

.custom-table td {
    padding: 10px;
    border-bottom: 1px solid #e2e8f0;
}

.custom-table tr:hover td {
    background: #f8fafc;
}

/* ============================
   BADGES
============================ */
.status-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: .78rem;
    font-weight: 600;
}

/* ============================
   FOTO
============================ */
.foto-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: .2s;
    border: 2px solid #e2e8f0;
}

.foto-img:hover {
    transform: scale(1.05);
    border-color: #1e3a8a;
}

/* ============================
   BUTTONS
============================ */
.action-btns {
    display: flex;
    gap: 8px;
}

.btn-acc, .btn-tolak {
    border: none;
    padding: 7px 14px;
    color: white;
    border-radius: 8px;
    font-weight: 600;
    font-size: .8rem;
    transition: .15s ease;
}

.btn-acc { background: #10b981; }
.btn-acc:hover { background: #059669; }

.btn-tolak { background: #ef4444; }
.btn-tolak:hover { background: #dc2626; }

/* ============================
   MODAL FOTO
============================ */
#fotoModal {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

#modalImg {
    max-width: 90%;
    max-height: 90%;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    transform: scale(0.8);
    transition: .2s ease;
}

#fotoModal.show #modalImg {
    transform: scale(1);
}

/* ============================
   RESPONSIVE HP
============================ */
@media (max-width: 576px) {
    .table-wrapper {
        padding: 14px;
        border-radius: 12px;
    }

    .custom-table {
        min-width: 700px;
    }

    .custom-table th,
    .custom-table td {
        font-size: .78rem;
        padding: 6px;
    }

    .foto-img {
        width: 40px;
        height: 40px;
    }

    .action-btns {
        flex-direction: column;
        gap: 6px;
    }

    .btn-acc, .btn-tolak {
        width: 100%;
        padding: 6px;
        font-size: .75rem;
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
    font-weight:600;
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

@media(max-width:576px){
    .notif{
        left:12px;
        right:12px;
        top:16px;
    }
}

</style>

<div class="container mt-3">

    <h1 class="page-title">Pengembalian Menunggu Konfirmasi</h1>

    <!-- SEARCH -->
    <div class="search-box">
        <input 
            type="text" 
            id="searchInput" 
            class="search-input"
            placeholder="Cari siswa, guru, mapel, barang..."
        >
    </div>

    @if($detail->count())

    <!-- TABLE CARD -->
    <div class="table-wrapper">
        <table class="custom-table" id="dataTable">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Guru</th>
                    <th>Mapel</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Ruangan</th>
                    <th>Foto</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="tableBody">
                @foreach($detail as $d)
                <tr class="search-item">

                    <td>{{ $d->peminjaman->siswa->nis }}</td>
                    <td>{{ $d->peminjaman->siswa->nama }}</td>
                    <td>{{ $d->peminjaman->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $d->peminjaman->guru->nama }}</td>
                    <td>{{ $d->peminjaman->mapel->nama_mapel }}</td>
                    <td>{{ $d->barang->nama_barang }}</td>
                    <td>{{ $d->jumlah }}</td>
                    <td>{{ $d->peminjaman->tempat->nama ?? '-' }}</td>

                    <td>
                        @if($d->foto_pengembalian && file_exists(public_path('storage/' . $d->foto_pengembalian)))
                            <img 
                                src="{{ asset('storage/' . $d->foto_pengembalian) }}"
                                class="foto-img"
                                onclick="showFoto(this.src)"
                            >
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        <span class="status-badge">Menunggu Sarpras</span>
                    </td>

                    <td>
                        <div class="action-btns">
                            <button 
                                class="btn-acc action-btn"
                                data-url="{{ route('sarpras.pengembalian.setuju', $d->id_detail) }}"
                                data-type="success"
                            >
                                Setujui
                            </button>

                            <button 
                                class="btn-tolak action-btn"
                                data-url="{{ route('sarpras.pengembalian.tolak', $d->id_detail) }}"
                                data-type="error"
                            >
                                Tolak
                            </button>
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    @else
        <div class="alert alert-light text-center p-3 shadow-sm" style="border-left: 6px solid #ef4444;">
            Tidak ada barang menunggu konfirmasi.
        </div>
    @endif

</div>

<!-- NOTIF -->
<div id="notif" class="notif"></div>

<!-- MODAL FOTO -->
<div id="fotoModal">
    <img id="modalImg">
</div>

<script>
function showNotif(type, message){
    const notif = document.getElementById('notif');
    if(!notif) return;

    notif.className = `notif ${type} show`;
    notif.textContent = message;

    setTimeout(() => {
        notif.classList.remove('show');
    }, 3000);
}

/* ===== MODAL FOTO ===== */
function showFoto(src){
    const modal = document.getElementById('fotoModal');
    const img = document.getElementById('modalImg');

    img.src = src;
    modal.style.display = 'flex';

    setTimeout(()=>{
        modal.classList.add('show');
    },10);
}

/* klik di luar gambar = close */
document.getElementById('fotoModal').addEventListener('click', function(e){
    if(e.target.id === 'fotoModal'){
        this.style.display = 'none';
        this.classList.remove('show');
    }
});
</script>

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

<script>
/* ===== SEARCH FILTER ===== */
document.getElementById("searchInput").addEventListener("keyup", function () {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll("#tableBody .search-item");

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(keyword) ? "" : "none";
    });
});

document.querySelectorAll('.action-btn').forEach(button => {

    button.addEventListener('click', function () {

        const url = this.dataset.url;
        const type = this.dataset.type;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const row = this.closest('tr');
        const btn = this;

        btn.disabled = true;
        btn.innerText = "Memproses...";

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": token,
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {

            showNotif(type, data.message);

            row.style.transition = "0.3s";
            row.style.opacity = "0";

            setTimeout(() => {
                row.remove();
            }, 300);

        })
        .catch(err => {
            showNotif('error', 'Terjadi kesalahan.');
            btn.disabled = false;
            btn.innerText = type === 'success' ? "Setujui" : "Tolak";
        });

    });

});
</script>

@endsection