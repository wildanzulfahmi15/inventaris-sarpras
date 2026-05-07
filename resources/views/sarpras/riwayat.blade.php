@extends('layouts.sarpras')

@section('title', 'Riwayat Peminjaman')

@section('content')

<style>
/* =========================================================
   GLOBAL RESET
========================================================= */
html, body {
    max-width: 100%;
    overflow-x: hidden;
}

/* =========================================================
   CARD CONTAINER
========================================================= */
.riwayat-card {
    background: #ffffff;
    border-radius: 14px;
    padding: clamp(14px, 3vw, 24px);
    box-shadow: 0 10px 28px rgba(15,23,42,.08);
    width: 100%;
}

/* =========================================================
   TITLE
========================================================= */
.page-title {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 18px;
    font-size: clamp(1.1rem, 2vw, 1.4rem);
}

/* =========================================================
   FILTER & SEARCH
========================================================= */
.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px,1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.search-box {
    max-width: 480px;
    margin: 0 auto 16px;
}

/* =========================================================
   QUICK FILTER
========================================================= */
.quick-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 18px;
}

/* =========================================================
   TABLE WRAPPER (WAJIB AGAR BISA SCROLL)
========================================================= */
.table-container {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 12px;
}

/* =========================================================
   TABLE BASE
========================================================= */
.table {
    width: 100%;
    min-width: 1100px; /* supaya tidak rusak di HP */
    border-collapse: collapse;
}

/* HEADER */
.table th {
    background: #f1f5f9;
    text-align: center;
    white-space: nowrap;
    font-weight: 600;
    font-size: clamp(11px, 1.1vw, 13px);
    padding: 8px 10px;
}

/* CELL */
.table td {
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
    font-size: clamp(11px, 1.1vw, 13px);
    padding: 8px 10px;
}

/* =========================================================
   BADGE
========================================================= */
.badge {
    padding: 4px 8px;
    font-size: clamp(10px, 1vw, 12px);
    border-radius: 20px;
}

/* =========================================================
   BUTTON DALAM TABEL
========================================================= */
.table .btn {
    padding: 4px 8px;
    font-size: clamp(10px, 1vw, 12px);
    border-radius: 8px;
}

/* =========================================================
   RESPONSIVE BREAKPOINT
========================================================= */

/* Tablet */
@media (max-width: 992px) {
    .table {
        min-width: 1000px;
    }

    .table th,
    .table td {
        padding: 6px 8px;
    }
}

/* HP */
@media (max-width: 576px) {
    .table {
        min-width: 900px;
    }

    .table th,
    .table td {
        font-size: 11px;
        padding: 6px;
    }

    .badge {
        font-size: 10px;
        padding: 3px 6px;
    }

    .table .btn {
        font-size: 10px;
        padding: 3px 6px;
    }
}
/* =========================================================
   PAGINATION UI (CLEAN & MODERN)
========================================================= */

.pagination-wrapper {
    display: flex;
    flex-direction:column;  
    justify-content: center;
    align-items: center;
    gap: 14px;
    margin-top: 20px;
    flex-wrap: wrap;
}

/* info text */
.pagination-info {
    font-size: 13px;
    color: #64748b;
}

/* pagination container */
.pagination {
    display: flex;
    gap: 6px;
    margin: 0;
    padding: 0;
}

/* button base */
.page-item .page-link {
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    color: #2563eb;
    font-size: 13px;
    font-weight: 500;

    display: flex;
    align-items: center;
    justify-content: center;

    transition: all .18s ease;
}

/* hover */
.page-item:not(.disabled):not(.active) .page-link:hover {
    background: #eef2ff;
    border-color: #c7d2fe;
    color: #1d4ed8;
}

/* active */
.page-item.active .page-link {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 6px 16px rgba(37,99,235,.25);
}

/* disabled */
.page-item.disabled .page-link {
    opacity: .45;
    cursor: not-allowed;
}

/* dots (...) */
.page-link.dots {
    cursor: default;
    background: transparent;
    border: none;
    color: #94a3b8;
}

/* responsive */
@media (max-width: 576px) {
    .pagination-wrapper {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .pagination-info {
        font-size: 12px;
        text-align: center;
    }

    .page-item .page-link {
        min-width: 32px;
        height: 32px;
        font-size: 11px;
        border-radius: 10px;
    }
}/* ================= MODAL CONFIRM ================= */
.confirm-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .45);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.confirm-overlay.hidden {
    display: none;
}

.confirm-box {
    background: #fff;
    width: 90%;
    max-width: 360px;
    border-radius: 14px;
    padding: 22px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,.2);
    animation: pop .2s ease;
}

.confirm-box h5 {
    font-weight: 700;
    margin-bottom: 8px;
}

.confirm-box p {
    font-size: 14px;
    color: #475569;
    margin-bottom: 18px;
}

.confirm-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.confirm-actions .btn {
    min-width: 100px;
    border-radius: 10px;
}

/* animasi */
@keyframes pop {
    from {
        transform: scale(.9);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* ================= TOAST ================= */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #16a34a;
    color: white;
    padding: 12px 18px;
    border-radius: 10px;
    font-size: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,.2);
    z-index: 10000;
    animation: slideIn .3s ease;
}

.toast.hidden {
    display: none;
}

@keyframes slideIn {
    from {
        transform: translateX(30px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.img-preview {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    border: 2px solid #e5e7eb;
    transition: .2s;
}

.img-preview:hover {
    transform: scale(1.1);
    border-color: #2563eb;
}



</style>

<div class="riwayat-card">

<h2 class="page-title">📦 Riwayat Peminjaman</h2>

<!-- ================= FILTER ================= -->
<form method="GET" class="filter-grid">
    <div>
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
    </div>

    <div>
        <label class="form-label">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
    </div>

    <div>
        <label class="form-label">Status Transaksi</label>
        <select name="status_transaksi" class="form-control">
            <option value="">Semua</option>
            @foreach(['Diajukan','Dipinjam','Dikembalikan','Ditolak'] as $s)
                <option value="{{ $s }}" {{ request('status_transaksi')==$s?'selected':'' }}>{{ $s }}</option>
            @endforeach
        </select>
    </div>

    <div class="d-flex gap-2 align-items-end">
        <button class="btn btn-primary w-100">Filter</button>
        <a href="{{ route('sarpras.riwayat.pdf', request()->query()) }}" target="_blank" class="btn btn-danger w-100">PDF</a>
    </div>
</form>

<!-- ================= SEARCH ================= -->
<form method="GET" class="search-box">
    <input type="text"
           name="search"
           value="{{ request('search') }}"
           class="form-control"
           placeholder="Cari siswa, guru, barang...">
</form>

<!-- ================= QUICK FILTER ================= -->
<div class="quick-filter">
    <a href="{{ route('sarpras.riwayat') }}" class="btn btn-outline-primary btn-sm">Semua</a>
    <a href="{{ route('sarpras.riwayat',['filter'=>'berlangsung']) }}" class="btn btn-outline-warning btn-sm">Berlangsung</a>
    <a href="{{ route('sarpras.riwayat',['filter'=>'selesai']) }}" class="btn btn-outline-success btn-sm">Selesai</a>
</div>

<!-- ================= DESKTOP TABLE ================= -->
<div class="desktop-only">
<div class="table-container">
<table class="table table-bordered table-hover">
<thead>
<tr>
    <th>No</th>
    <th>Siswa</th>
    <th>NIS</th>
    <th>Kelas</th>
    <th>Guru</th>
    <th>Mapel</th>
    <th>Barang</th>
    <th>Jumlah</th>
    <th>Ruangan</th>
    <th>No WA</th>
    <th>Status Pinjam</th>
    <th>Status Kembali</th>
    <th>Tgl Pinjam</th>
    <th>Foto Pinjam</th>
    <th>Tgl Kembali</th>
    <th>Foto Kembali</th>
    <th>Status</th>
    <th>Hapus</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody id="riwayat-body">
    <tr>
        <td colspan="17" class="text-center text-muted">
            Ketik untuk mencari data...
        </td>
    </tr>
</tbody>


</table>
</div>
</div>

<!-- PAGINATION -->

     {{ $detail->links('components.pagination') }}
<div class="pagination-wrapper">
    <div class="pagination-info" id="pagination-info"></div>

    <div id="pagination" class="pagination"></div>

    <!-- JUMP TO PAGE -->
    <div class="d-flex gap-2 align-items-center">
        <span class="text-muted small">Ke halaman:</span>
        <input type="number"
               id="jumpPage"
               min="1"
               class="form-control form-control-sm"
               style="width:90px"
               placeholder="ex: 3">
        <button class="btn btn-sm btn-primary" onclick="jumpToPage()">Go</button>
    </div>
</div>



</div>
<!-- ================= MODAL KONFIRMASI HAPUS ================= -->
<div id="confirmOverlay" class="confirm-overlay hidden">
    <div class="confirm-box">
        <h5>Konfirmasi Hapus</h5>
        <p>Yakin ingin menghapus data ini?</p>

        <div class="confirm-actions">
            <button class="btn btn-secondary" onclick="closeConfirm()">Batal</button>
            <button class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
        </div>
    </div>
</div>

<!-- ================= NOTIFIKASI ================= -->
<div id="toast" class="toast hidden">
    <span id="toastMessage">Berhasil</span>
</div>
<div id="imgModal" style="
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.7);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;">
    <img id="imgModalSrc" style="
        max-width:90%;
        max-height:90%;
        border-radius:12px;">
</div>

<script>
    function showImg(src) {
    const modal = document.getElementById('imgModal');
    const img = document.getElementById('imgModalSrc');

    img.src = src;
    modal.style.display = 'flex';
}

document.getElementById('imgModal').onclick = function(e){
    if(e.target.id === 'imgModal'){
        this.style.display = 'none';
    }
};
</script>
<script>
let bypassForm = null;

function showConfirmBypass(form, type) {
    bypassForm = form;

    document.querySelector('#confirmOverlay h5').innerText =
        type === 'peminjaman'
            ? 'Konfirmasi Bypass Peminjaman'
            : 'Konfirmasi Bypass Pengembalian';

    document.querySelector('#confirmOverlay p').innerText =
        type === 'peminjaman'
            ? 'Bypass ini akan langsung menyetujui peminjaman. Lanjutkan?'
            : 'Bypass ini akan menyelesaikan pengembalian. Lanjutkan?';

    const btn = document.getElementById('confirmDeleteBtn');
    btn.innerText = 'Ya, Lanjutkan';
    btn.className = 'btn btn-success';

    document.getElementById('confirmOverlay').classList.remove('hidden');
    return false;
}


function isFinalPeminjaman(status) {
    return [
        'Disetujui',
        'Ditolak Sarpras',
        'Ditolak Guru',
        'Stok Habis'
    ].includes(status);
}

function isFinalPengembalian(status) {
    return [
        'Selesai',
        'Ditolak Sarpras',
        'Ditolak Guru'
    ].includes(status);
}



let debounceTimer;
let lastPage = 1;

function loadRiwayat(page = 1) {
    const search = document.querySelector('input[name="search"]').value;
    const tanggal_mulai = document.querySelector('input[name="tanggal_mulai"]').value;
    const tanggal_selesai = document.querySelector('input[name="tanggal_selesai"]').value;
    const status_transaksi = document.querySelector('select[name="status_transaksi"]').value;

        const urlParams = new URLSearchParams(window.location.search);

        const params = new URLSearchParams({
            search,
            tanggal_mulai,
            tanggal_selesai,
            status_transaksi,
            filter: urlParams.get('filter'), // 🔥 INI KUNCI
            page
        });

    fetch(`{{ route('sarpras.riwayat.json') }}?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
            lastPage = data.last_page;
            renderTable(data);
            renderPagination(data);
        });
}


function bisaBypassPeminjaman(d) {
    return ['Menunggu Guru','Menunggu Sarpras'].includes(d.status_peminjaman);
}

function bisaBypassPengembalian(d) {
    return (
        d.status_peminjaman === 'Disetujui' &&
        ['Menunggu Guru','Menunggu Sarpras'].includes(d.status_pengembalian)
    );
}

function renderTable(data) {
    const tbody = document.getElementById('riwayat-body');
    tbody.innerHTML = '';

    if (!data.data || data.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="17" class="text-center text-muted">
                    Tidak ada data
                </td>
            </tr>`;
        return;
    }

    data.data.forEach((d, i) => {
        const no = (data.current_page - 1) * data.per_page + i + 1;

let aksiBtn = `<span class="text-muted">—</span>`;

// ======================
// BYPASS PEMINJAMAN
// ======================
if (bisaBypassPeminjaman(d)) {
    aksiBtn = `
    <form method="POST"
          action="{{ url('/sarpras/riwayat/setujui') }}/${d.id_detail}"

          onsubmit="return showConfirmBypass(this,'peminjaman')">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="btn btn-warning btn-sm">
            ⚡ Setujui Peminjaman
        </button>
    </form>`;
}

// ======================
// BYPASS PENGEMBALIAN
// ======================
else if (bisaBypassPengembalian(d)) {
    aksiBtn = `
    <form method="POST"
          action="{{ url('/sarpras/riwayat/setujui') }}/${d.id_detail}"

          onsubmit="return showConfirmBypass(this,'pengembalian')">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="btn btn-success btn-sm">
            🔁 Setujui Pengembalian
        </button>
    </form>`;
}



        tbody.innerHTML += `
        <tr>
            <td>${no}</td>
            <td>${d.peminjaman?.siswa?.nama ?? '-'}</td>
            <td>${d.peminjaman?.siswa?.nis ?? '-'}</td>
            <td>${d.peminjaman?.siswa?.kelas?.nama_kelas ?? '-'}</td>
            <td>${d.peminjaman?.guru?.nama ?? '-'}</td>
            <td>${d.peminjaman?.mapel?.nama_mapel ?? '-'}</td>
            <td>${d.barang?.nama_barang ?? '-'}</td>
            <td>${d.jumlah}</td>
            <td>${d.peminjaman?.tempat?.nama ?? '-'}</td>
            <td>${d.peminjaman?.no_wa ?? '-'}</td>
            <td><span class="badge bg-info">${d.status_peminjaman}</span></td>
            <td><span class="badge bg-warning">${d.status_pengembalian}</span></td>
<td>${d.peminjaman?.tanggal_pinjam ?? '-'}</td>

<td>
    ${
        d.peminjaman?.foto_pinjam
        ? `<img src="/storage/peminjaman/${d.peminjaman.foto_pinjam}" 
                class="img-preview"
                onclick="showImg(this.src)">`
        : '-'
    }
</td>

<td>${d.tanggal_pengembalian ?? '-'}</td>

<td>
    ${
        d.foto_pengembalian
        ? `<img src="/storage/${d.foto_pengembalian}" 
                class="img-preview"
                onclick="showImg(this.src)">`
        : '-'
    }
</td>
            <td><span class="badge bg-success">${d.peminjaman?.status ?? '-'}</span></td>

            <td>
                <button class="btn btn-danger btn-sm"
                        onclick="openConfirm(${d.id_detail})">
                    Hapus
                </button>
            </td>

            <td>
                ${aksiBtn}
            </td>
        </tr>`;
    });
}



function renderPagination(data) {
    const pag = document.getElementById('pagination');
    pag.innerHTML = '';

    data.links.forEach(link => {
        if (!link.url) return;

        const page = new URL(link.url).searchParams.get('page');

        const btn = document.createElement('button');
        btn.className = `page-link ${link.active ? 'active' : ''}`;
        btn.innerHTML = link.label;

        btn.onclick = () => loadRiwayat(page);

        const wrapper = document.createElement('div');
        wrapper.className = `page-item ${link.active ? 'active' : ''}`;
        wrapper.appendChild(btn);

        pag.appendChild(wrapper);
    });

    // info text
    document.getElementById('pagination-info').innerText =
        `Halaman ${data.current_page} dari ${data.last_page} • Total ${data.total} data`;
}


document.querySelector('input[name="search"]').addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadRiwayat(1), 400);
});
function jumpToPage() {
    const input = document.getElementById('jumpPage');
    let page = parseInt(input.value);

    if (isNaN(page)) return;

    if (page < 1) page = 1;
    if (page > lastPage) page = lastPage;

    input.value = page;
    loadRiwayat(page);
}
let deleteId = null;

/* buka modal */
function openConfirm(id) {
    deleteId = id;
    document.getElementById('confirmOverlay').classList.remove('hidden');
}

/* tutup modal */
function closeConfirm() {
    deleteId = null;
    document.getElementById('confirmOverlay').classList.add('hidden');
}

/* klik YA HAPUS */
document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {

    // =====================
    // BYPASS SUBMIT
    // =====================
    if (bypassForm) {
        bypassForm.submit();
        bypassForm = null;
        closeConfirm();
        return;
    }

    // =====================
    // HAPUS DATA
    // =====================
    if (!deleteId) return;

    try {
        const res = await fetch(`{{ url('/sarpras/riwayat/detail') }}/${deleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        let data = {};
        try {
            data = await res.json();
        } catch (e) {
            data.message = 'Data berhasil dihapus';
        }

        if (!res.ok) {
            throw new Error(data.message || 'Gagal menghapus');
        }

        closeConfirm();
        showToast(data.message || 'Data berhasil dihapus ✅');
        loadRiwayat();

    } catch (err) {
        console.error(err);
        closeConfirm();
        showToast('Gagal menghapus data ❌', true);
    }
});



/* TOAST */
function showToast(message, error = false) {
    const toast = document.getElementById('toast');
    const text = document.getElementById('toastMessage');

    if (!toast || !text) {
        console.error('Toast element tidak ditemukan');
        return;
    }

    text.textContent = message;

    toast.style.background = error ? '#dc2626' : '#16a34a';

    // pastikan muncul
    toast.classList.remove('hidden');
    toast.style.display = 'block';
    toast.style.opacity = '1';

    setTimeout(() => {
        toast.classList.add('hidden');
        toast.style.display = 'none';
    }, 2500);
}

// load pertama
loadRiwayat();
</script>
@endsection
