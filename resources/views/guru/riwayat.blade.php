@extends('layouts.guru')
@section('title', 'Riwayat Peminjaman')
@section('content')
<style>
html, body {
    max-width: 100%;
    overflow-x: hidden;
}
.riwayat-card {
    background: #ffffff;
    border-radius: 14px;
    padding: clamp(14px, 3vw, 24px);
    box-shadow: 0 10px 28px rgba(15,23,42,.08);
    width: 100%;
}
.page-title {
    font-weight: 700;
    color: #004f9a;
    margin-bottom: 18px;
    font-size: clamp(1.1rem, 2vw, 1.4rem);
}
.filter-container {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}
.filter-btn {
    padding: 8px 16px;
    border-radius: 999px;
    font-weight: 600;
    font-size: .85rem;
    text-decoration: none;
    background: #e5edff;
    color: #1e40af;
    transition: .2s ease;
    white-space: nowrap;
    border: none;
    cursor: pointer;
}
.filter-btn:hover { background: #c7dbff; color: #1e3a8a; }
.filter-btn.active {
    background: #2563eb;
    color: #ffffff;
    box-shadow: 0 6px 18px rgba(37,99,235,.35);
}
.search-box {
    max-width: 480px;
    margin: 0 auto 16px;
}
.table-container {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 12px;
}
.table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
}
.table th {
    background: #e8f2ff;
    color: #004f9a;
    text-align: center;
    white-space: nowrap;
    font-weight: 700;
    font-size: clamp(11px, 1.1vw, 13px);
    padding: 8px 10px;
}
.table td {
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
    font-size: clamp(11px, 1.1vw, 13px);
    padding: 8px 10px;
}
.table tbody tr:nth-child(odd) { background: #f8fbff; }
.badge {
    padding: 5px 10px;
    font-size: clamp(10px, 1vw, 12px);
    border-radius: 999px;
}
@media (max-width: 576px) {
    .table { min-width: 780px; }
    .table th, .table td { font-size: 11px; padding: 6px; }
    .badge { font-size: 10px; padding: 3px 6px; }
}
/* =========================================================
   PAGINATION
========================================================= */
.pagination-wrapper {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 14px;
    margin-top: 20px;
    flex-wrap: wrap;
}
.pagination-info { font-size: 13px; color: #64748b; }
.pagination { display: flex; gap: 6px; margin: 0; padding: 0; }
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
.page-item:not(.disabled):not(.active) .page-link:hover {
    background: #eef2ff;
    border-color: #c7d2fe;
    color: #1d4ed8;
}
.page-item.active .page-link {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 6px 16px rgba(37,99,235,.25);
}
.page-item.disabled .page-link { opacity: .45; cursor: not-allowed; }
@media (max-width: 576px) {
    .pagination-wrapper { flex-direction: column; align-items: center; gap: 10px; }
    .pagination-info { font-size: 12px; text-align: center; }
    .page-item .page-link { min-width: 32px; height: 32px; font-size: 11px; border-radius: 10px; }
}
/* =========================================================
   LOADING SPINNER
========================================================= */
#loading-overlay {
    display: none;
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,.6);
    border-radius: 14px;
    justify-content: center;
    align-items: center;
    z-index: 10;
}
#loading-overlay.show { display: flex; }
.spinner {
    width: 36px; height: 36px;
    border: 4px solid #e5e7eb;
    border-top-color: #2563eb;
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="riwayat-card" style="position:relative">
    {{-- loading overlay --}}
    <div id="loading-overlay"><div class="spinner"></div></div>

    <h2 class="page-title text-center">📋 Riwayat Peminjaman (Guru)</h2>

    {{-- SEARCH --}}
    <div class="search-box">
        <input type="text"
               id="searchInput"
               class="form-control"
               placeholder="Cari nama siswa, barang, tanggal...">
    </div>

    {{-- QUICK FILTER --}}
    <div class="filter-container">
        <button class="filter-btn active" onclick="setFilter('')" id="btn-semua">Semua</button>
        <button class="filter-btn" onclick="setFilter('berlangsung')" id="btn-berlangsung">Sedang Berlangsung</button>
        <button class="filter-btn" onclick="setFilter('selesai')" id="btn-selesai">Sudah Dikembalikan</button>
    </div>

    {{-- TABLE --}}
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Siswa</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Status Peminjaman</th>
                    <th>Status Pengembalian</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status Transaksi</th>
                </tr>
            </thead>
            <tbody id="riwayat-body">
                <tr>
                    <td colspan="11" class="text-center text-muted py-4">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="pagination-wrapper">
        <div class="pagination-info" id="pagination-info"></div>
        <div id="pagination" class="pagination"></div>
        <div class="d-flex gap-2 align-items-center">
            <span class="text-muted small">Ke halaman:</span>
            <input type="number" id="jumpPage" min="1"
                   class="form-control form-control-sm" style="width:90px" placeholder="ex: 3">
            <button class="btn btn-sm btn-primary" onclick="jumpToPage()">Go</button>
        </div>
    </div>
</div>

<script>
let debounceTimer;
let lastPage    = 1;
let activeFilter = '';

/* ── SET FILTER (tombol) ── */
function setFilter(val) {
    activeFilter = val;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    const id = val === '' ? 'btn-semua' : val === 'berlangsung' ? 'btn-berlangsung' : 'btn-selesai';
    document.getElementById(id).classList.add('active');
    loadRiwayat(1);
}

/* ── LOAD DATA ── */
function loadRiwayat(page = 1) {
    const search = document.getElementById('searchInput').value;
    const params = new URLSearchParams({ search, filter: activeFilter, page });

    document.getElementById('loading-overlay').classList.add('show');

    fetch(`{{ route('guru.riwayat.json') }}?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
            lastPage = data.last_page;
            renderTable(data);
            renderPagination(data);
        })
        .catch(() => {
            document.getElementById('riwayat-body').innerHTML =
                `<tr><td colspan="11" class="text-center text-danger">Gagal memuat data.</td></tr>`;
        })
        .finally(() => {
            document.getElementById('loading-overlay').classList.remove('show');
        });
}

/* ── RENDER TABLE ── */
function renderTable(data) {
    const tbody = document.getElementById('riwayat-body');
    tbody.innerHTML = '';

    if (!data.data || data.data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="11" class="text-center text-muted py-4">Tidak ada data</td></tr>`;
        return;
    }

    data.data.forEach((d, i) => {
        const no    = (data.current_page - 1) * data.per_page + i + 1;
        const siswa = d.peminjaman?.siswa?.nama    ?? '-';
        const nis   = d.peminjaman?.siswa?.nis     ?? '-';
        const kelas = d.peminjaman?.siswa?.kelas?.nama_kelas ?? '-';
        const barang      = d.barang?.nama_barang   ?? '-';
        const jumlah      = d.jumlah                ?? '-';
        const tglPinjam   = d.peminjaman?.tanggal_pinjam ?? '-';
        const tglKembali  = d.tanggal_pengembalian  ?? '-';

        const badgePinjam   = statusBadgePinjam(d.status_peminjaman);
        const badgeKembali  = statusBadgeKembali(d.status_pengembalian);
        const badgeTransaksi= statusBadgeTransaksi(d.peminjaman?.status);

        tbody.innerHTML += `
        <tr>
            <td>${no}</td>
            <td>${siswa}</td>
            <td>${nis}</td>
            <td>${kelas}</td>
            <td><strong>${barang}</strong></td>
            <td>${jumlah}</td>
            <td>${badgePinjam}</td>
            <td>${badgeKembali}</td>
            <td>${tglPinjam}</td>
            <td>${tglKembali}</td>
            <td>${badgeTransaksi}</td>
        </tr>`;
    });
}

function statusBadgePinjam(s) {
    const map = {
        'Disetujui'       : ['bg-success','Disetujui'],
        'Menunggu Guru'   : ['bg-warning text-dark','Menunggu Guru'],
        'Menunggu Sarpras': ['bg-info text-dark','Menunggu Sarpras'],
        'Ditolak'         : ['bg-danger','Ditolak'],
    };
    const [cls, label] = map[s] ?? ['bg-secondary', s ?? '-'];
    return `<span class="badge ${cls}">${label}</span>`;
}

function statusBadgeKembali(s) {
    const map = {
        'Belum'           : ['bg-danger','Belum Dikembalikan'],
        'Menunggu Guru'   : ['bg-warning text-dark','Menunggu Guru'],
        'Menunggu Sarpras': ['bg-info text-dark','Menunggu Sarpras'],
        'Selesai'         : ['bg-success','Sudah Dikembalikan'],
    };
    const [cls, label] = map[s] ?? ['bg-secondary', s ?? '-'];
    return `<span class="badge ${cls}">${label}</span>`;
}

function statusBadgeTransaksi(s) {
    const map = {
        'Dipinjam'    : ['bg-warning text-dark','Sedang Dipinjam'],
        'Dikembalikan': ['bg-success','Selesai'],
        'Diajukan'    : ['bg-info text-dark','Diajukan'],
        'Ditolak'     : ['bg-danger','Ditolak'],
    };
    const [cls, label] = map[s] ?? ['bg-secondary', s ?? '-'];
    return `<span class="badge ${cls}">${label}</span>`;
}

/* ── RENDER PAGINATION ── */
function renderPagination(data) {
    const pag = document.getElementById('pagination');
    pag.innerHTML = '';

    data.links.forEach(link => {
        if (!link.url) return;
        const page = new URL(link.url).searchParams.get('page');
        const btn  = document.createElement('button');
        btn.className = `page-link`;
        btn.innerHTML = link.label;
        btn.onclick   = () => loadRiwayat(page);

        const wrapper = document.createElement('div');
        wrapper.className = `page-item ${link.active ? 'active' : ''}`;
        wrapper.appendChild(btn);
        pag.appendChild(wrapper);
    });

    document.getElementById('pagination-info').innerText =
        `Halaman ${data.current_page} dari ${data.last_page} • Total ${data.total} data`;
}

/* ── JUMP TO PAGE ── */
function jumpToPage() {
    const input = document.getElementById('jumpPage');
    let page = parseInt(input.value);
    if (isNaN(page)) return;
    if (page < 1)       page = 1;
    if (page > lastPage) page = lastPage;
    input.value = page;
    loadRiwayat(page);
}

/* ── SEARCH DEBOUNCE ── */
document.getElementById('searchInput').addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadRiwayat(1), 400);
});

// load pertama
loadRiwayat();
</script>
@endsection