@extends('layouts.guru')

@section('title', 'Konfirmasi Pengembalian')

@section('content')

<style>
/* ===== TITLE ===== */
.page-title {
    font-weight: 800;
    font-size: clamp(1.4rem, 4vw, 1.9rem);
    text-align: center;
    color: #004f9a;
    margin-bottom: 18px;
}

/* ===== TOP BAR ===== */
.top-bar {
    max-width: 1100px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
}

.search-box {
    flex: 1;
    min-width: 200px;
    max-width: 400px;
}

.search-input {
    width: 100%;
    padding: 10px 14px;
    border-radius: 10px;
    border: 2px solid #1c84ff;
    outline: none;
    font-size: .95rem;
}

.search-input:focus {
    border-color: #004f9a;
    box-shadow: 0 0 8px rgba(0, 79, 154, .3);
}

.select-all-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .9rem;
    font-weight: 600;
    color: #004f9a;
    cursor: pointer;
    user-select: none;
}

.select-all-wrap input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #2563eb;
    cursor: pointer;
}

/* ===== TABLE WRAPPER ===== */
.table-wrapper {
    background: #ffffff;
    padding: 16px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table-wrapper::-webkit-scrollbar { height: 8px; }
.table-wrapper::-webkit-scrollbar-thumb { background: #c7dbff; border-radius: 10px; }
.table-wrapper::-webkit-scrollbar-track { background: transparent; }

/* ===== TABLE ===== */
.custom-table {
    border-collapse: collapse;
    width: 100%;
    min-width: 1150px; /* Ditambah lebar karena ada kolom foto */
}

.custom-table th, .custom-table td { white-space: nowrap; }
.custom-table thead { background: #e8f2ff; }

.custom-table th {
    padding: 12px;
    font-size: .9rem;
    color: #004f9a;
    font-weight: 700;
    border-bottom: 2px solid #bcd9ff;
}

.custom-table td {
    padding: 10px;
    font-size: .88rem;
    border-bottom: 1px solid #e4efff;
}

.custom-table tr:hover { background: #f5f9ff; }

/* Checkbox Kolom Pertama */
.custom-table th:first-child, .custom-table td:first-child {
    width: 50px;
    text-align: center;
}

.row-check {
    width: 18px;
    height: 18px;
    accent-color: #2563eb;
    cursor: pointer;
}

/* Highlight Baris Dicentang */
.custom-table tr.row-selected { background: #eff6ff !important; }

/* ===== BADGES ===== */
.badge-blue {
    background: #1c84ff;
    padding: 4px 8px;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    font-size: .75rem;
}

.status-badge {
    background: #bde3ff;
    color: #084d7f;
    padding: 6px 12px;
    border-radius: 10px;
    font-size: .8rem;
}

/* ===== FOTO PINJAM ===== */
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
    border-color: #004f9a;
}

/* ===== FLOATING ACTION BUTTON ===== */
.fab-wrap {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9000;
    opacity: 0;
    pointer-events: none;
    transform: translateY(20px);
    transition: opacity .3s, transform .3s;
}

.fab-wrap.visible {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0);
}

.fab-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    border: none;
    padding: 14px 24px;
    border-radius: 16px;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 8px 28px rgba(37, 99, 235, .45);
    transition: transform .15s, box-shadow .15s;
}

.fab-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 36px rgba(37, 99, 235, .55);
}

.fab-btn:active { transform: scale(.97); }
.fab-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }

.fab-badge {
    background: #fbbf24;
    color: #1e293b;
    font-size: .8rem;
    font-weight: 800;
    min-width: 26px;
    height: 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    padding: 0 6px;
}

/* ===== MODAL KAMERA ===== */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .55);
    display: flex;
    align-items: safe center; 
    justify-content: center;
    z-index: 10000;
    opacity: 0;
    pointer-events: none;
    transition: opacity .25s;
    overflow-y: auto; 
    padding: 20px 0; 
}

.modal-overlay.show { opacity: 1; pointer-events: auto; }

.modal-card {
    background: #fff;
    border-radius: 18px;
    padding: 24px;
    width: 92%;
    max-width: 440px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, .2);
    transform: translateY(20px) scale(.97);
    transition: transform .25s;
    margin: auto; 
}

.modal-overlay.show .modal-card { transform: translateY(0) scale(1); }

.modal-card h3 {
    font-weight: 700;
    font-size: 1.1rem;
    color: #004f9a;
    margin: 0 0 6px;
    text-align: center;
}

.modal-subtitle {
    text-align: center;
    font-size: .82rem;
    color: #64748b;
    margin-bottom: 14px;
}

.modal-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-top: 14px;
    flex-wrap: wrap;
}

.modal-actions .btn {
    padding: 9px 18px;
    border-radius: 10px;
    font-size: .85rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: background .2s, transform .1s;
}

.modal-actions .btn:active { transform: scale(.96); }
.modal-actions .btn-ghost { background: #e8f2ff; color: #004f9a; }
.modal-actions .btn-ghost:hover { background: #d0e5ff; }
.modal-actions .btn-primary { background: #2563eb; color: #fff; }
.modal-actions .btn-primary:hover { background: #1d4ed8; }
.modal-actions .btn-primary:disabled { opacity: .5; cursor: not-allowed; }

/* ===== MODAL FOTO POPUP ===== */
#fotoModal {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 99999;
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

/* ===== NOTIFICATION ===== */
.notif {
    position: fixed;
    top: 24px;
    right: 24px;
    min-width: 260px;
    max-width: 90vw;
    padding: 14px 16px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    color: white;
    box-shadow: 0 12px 28px rgba(0, 0, 0, .15);
    opacity: 0;
    pointer-events: none;
    transform: translateY(-10px);
    transition: .3s;
    z-index: 99998;
}

.notif.show { opacity: 1; transform: translateY(0); }
.notif.success { background: linear-gradient(135deg, #22c55e, #16a34a); }
.notif.error { background: linear-gradient(135deg, #ef4444, #dc2626); }
.notif.info { background: linear-gradient(135deg, #3b82f6, #2563eb); }

/* ===== MOBILE ===== */
@media (max-width: 768px) {
    .page-title { font-size: 1.25rem; margin-bottom: 14px; }
    .search-input { font-size: .85rem; padding: 10px 14px; }
    .table-wrapper { padding: 10px; }
    .custom-table th { font-size: .7rem; padding: 8px 10px; }
    .custom-table td { font-size: .75rem; padding: 8px 10px; }
    .badge-blue, .status-badge { font-size: .65rem; padding: 4px 8px; }
    .notif { left: 12px; right: 12px; top: 16px; }
    .modal-card { padding: 18px; }
    .fab-wrap { bottom: 18px; right: 14px; left: 14px; }
    .fab-btn { width: 100%; justify-content: center; padding: 14px 18px; font-size: .9rem; border-radius: 14px; }
    .top-bar { flex-direction: column; align-items: stretch; }
    .search-box { max-width: 100%; }
    .row-check { width: 16px; height: 16px; }
    .foto-img { width: 40px; height: 40px; }
}
</style>


<div class="container mt-3">

    <h1 class="page-title">Konfirmasi Pengembalian Barang</h1>

    @if($detail->count())

    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="search-box">
            <input type="text" id="searchInput" class="search-input" placeholder="Cari nama, barang, kelas...">
        </div>
        <label class="select-all-wrap">
            <input type="checkbox" id="selectAll">
            <span>Pilih Semua</span>
        </label>
    </div>

    <!-- TABLE -->
    <div class="table-wrapper">
        <table class="custom-table" id="returnTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllHead" class="row-check"></th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tgl Pinjam</th>
                    <th>Foto Pinjam</th>
                    <th>Status</th>
                    <th>No WA</th>
                    <th>Ruangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail as $d)
                @php $p = $d->peminjaman; @endphp
                <tr class="search-item" id="row-{{ $d->id_detail }}">
                    <td>
                        <input type="checkbox" class="row-check item-check" data-id="{{ $d->id_detail }}">
                    </td>
                    <td>{{ $p->siswa->nama }}</td>
                    <td>{{ $p->siswa->nis }}</td>
                    <td>{{ $p->siswa->kelasRelasi->nama_kelas ?? '-' }}</td>
                    <td>{{ $d->barang->nama_barang }}</td>
                    <td><span class="badge-blue">{{ $d->jumlah }}</span></td>
                    <td>{{ $p->tanggal_pinjam }}</td>
                    <td>
                        @if($p->foto_pinjam)
                            <img 
                                src="{{ asset('storage/peminjaman/'.$d->peminjaman->foto_pinjam) }}"
                                class="foto-img"
                                onclick="showFoto(this.src)"
                                onerror="this.outerHTML='-'"
                            >
                        @else
                            -
                        @endif
                    </td>
                    <td><span class="status-badge">{{ $d->status_pengembalian }}</span></td>
                    <td>{{ $p->no_wa }}</td>
                    <td>{{ $p->tempat->nama ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @else
    <div class="alert alert-secondary text-center">
        Tidak ada barang menunggu konfirmasi pengembalian guru.
    </div>
    @endif

</div>

<!-- NOTIF -->
<div id="notif" class="notif"></div>

<!-- MODAL FOTO POPUP -->
<div id="fotoModal">
    <img id="modalImg">
</div>

<!-- FAB -->
<div class="fab-wrap" id="fabWrap">
    <button class="fab-btn" id="fabBtn" onclick="openCamera()">
        📸 Selfie & Kembalikan
        <span class="fab-badge" id="fabCount">0</span>
    </button>
</div>

<!-- MODAL KAMERA -->
<div id="cameraModal" class="modal-overlay">
    <div class="modal-card">
        <h3>Selfie Pengembalian</h3>
        <p class="modal-subtitle" id="modalSubtitle">0 barang dipilih</p>
        <video id="cameraReturn" autoplay playsinline style="width:100%;border-radius:12px;background:#000;"></video>
        <canvas id="canvasReturn" style="display:none;"></canvas>
        <img id="previewReturn" style="width:100%;margin-top:10px;display:none;border-radius:10px;">
        <div class="modal-actions">
            <button onclick="takeSelfie()" class="btn btn-ghost">📸 Ambil Foto</button>
            <button onclick="submitSelfie()" class="btn btn-primary" id="btnSubmit">✅ Kirim Pengembalian</button>
            <button onclick="closeCamera()" class="btn btn-ghost">✕ Tutup</button>
        </div>
    </div>
</div>


<script>
/* ===================== NOTIFIKASI ===================== */
function showNotif(type, message) {
    const notif = document.getElementById('notif');
    if (!notif) return;
    notif.className = `notif ${type} show`;
    notif.textContent = message;
    setTimeout(() => notif.classList.remove('show'), 3500);
}

/* ===================== MODAL FOTO POPUP ===================== */
function showFoto(src){
    const modal = document.getElementById('fotoModal');
    const img = document.getElementById('modalImg');
    img.src = src;
    modal.style.display = 'flex';
    setTimeout(()=>{ modal.classList.add('show'); },10);
}

document.getElementById('fotoModal').addEventListener('click', function(e){
    if(e.target.id === 'fotoModal'){
        this.style.display = 'none';
        this.classList.remove('show');
    }
});

/* ===================== SEARCH ===================== */
document.getElementById("searchInput").addEventListener("input", function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll("#returnTable tbody .search-item").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(keyword) ? "" : "none";
    });
});

/* ===================== CHECKBOX LOGIC ===================== */
const selectAll      = document.getElementById('selectAll');
const selectAllHead  = document.getElementById('selectAllHead');
const fabWrap        = document.getElementById('fabWrap');
const fabCount       = document.getElementById('fabCount');
const modalSubtitle  = document.getElementById('modalSubtitle');

function getVisibleChecks() {
    return [...document.querySelectorAll('.item-check')].filter(cb => cb.closest('tr').style.display !== 'none');
}

function getCheckedIds() {
    return [...document.querySelectorAll('.item-check:checked')].map(cb => cb.dataset.id);
}

function syncSelection() {
    const ids = getCheckedIds();
    const count = ids.length;

    fabWrap.classList.toggle('visible', count > 0);
    fabCount.textContent = count;
    modalSubtitle.textContent = count + ' barang dipilih';

    document.querySelectorAll('.item-check').forEach(cb => {
        cb.closest('tr').classList.toggle('row-selected', cb.checked);
    });

    const visible = getVisibleChecks();
    const checkedVisible = visible.filter(cb => cb.checked);
    const allChecked = visible.length > 0 && checkedVisible.length === visible.length;
    const someChecked = checkedVisible.length > 0 && !allChecked;

    selectAll.checked = allChecked;
    selectAll.indeterminate = someChecked;
    selectAllHead.checked = allChecked;
    selectAllHead.indeterminate = someChecked;
}

document.querySelectorAll('.item-check').forEach(cb => cb.addEventListener('change', syncSelection));
selectAll.addEventListener('change', function () {
    getVisibleChecks().forEach(cb => cb.checked = this.checked);
    syncSelection();
});
selectAllHead.addEventListener('change', function () {
    getVisibleChecks().forEach(cb => cb.checked = this.checked);
    syncSelection();
});

syncSelection();

/* ===================== KAMERA & SELFIE ===================== */
let stream = null;
let selfieData = null;

function openCamera() {
    if (getCheckedIds().length === 0) {
        showNotif('error', 'Pilih barang yang ingin dikembalikan');
        return;
    }

    selfieData = null;
    const modal   = document.getElementById('cameraModal');
    const video   = document.getElementById('cameraReturn');
    const preview = document.getElementById('previewReturn');
    const btnSub  = document.getElementById('btnSubmit');

    preview.style.display = 'none';
    preview.src = '';
    btnSub.disabled = false;
    btnSub.textContent = '✅ Kirim Pengembalian';

    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; 

    navigator.mediaDevices.getUserMedia({
        video: { facingMode: "user", width: { ideal: 720 }, height: { ideal: 960 } }
    })
    .then(s => {
        stream = s;
        video.srcObject = s;
    })
    .catch(() => {
        showNotif('error', 'Kamera tidak bisa diakses');
        closeCamera();
    });
}

function takeSelfie() {
    const video   = document.getElementById('cameraReturn');
    const canvas  = document.getElementById('canvasReturn');
    const preview = document.getElementById('previewReturn');

    if (!video.videoWidth) {
        showNotif('error', 'Kamera belum siap');
        return;
    }

    const ctx = canvas.getContext('2d');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);

    selfieData = canvas.toDataURL('image/jpeg', 0.8);

    preview.src = selfieData;
    preview.style.display = 'block';
    showNotif('info', 'Foto berhasil diambil, silakan kirim');
}

async function submitSelfie() {
    if (!selfieData) {
        showNotif('error', 'Ambil foto dulu sebelum mengirim');
        return;
    }

    const ids = getCheckedIds();
    if (ids.length === 0) {
        showNotif('error', 'Tidak ada barang yang dipilih');
        return;
    }

    const btnSub = document.getElementById('btnSubmit');
    btnSub.disabled = true;
    btnSub.textContent = 'Mengirim...';

    let berhasil = 0;
    let gagal = 0;
    let successIds = [];

    for (const id of ids) {
        try {
            const url = `{{ route('guru.kembalikan', ':id') }}`.replace(':id', id);

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ foto: selfieData })
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Gagal');

            berhasil++;
            successIds.push(id);
        } catch (err) {
            gagal++;
        }

        if (ids.indexOf(id) !== ids.length - 1) {
            await new Promise(r => setTimeout(r, 300));
        }
    }

    closeCamera();

    if (berhasil > 0) {
        showNotif('success', `${berhasil} barang berhasil dikembalikan`);
        successIds.forEach(id => {
            const row = document.getElementById(`row-${id}`);
            if (row) row.remove();
        });

        document.querySelectorAll('.item-check:checked').forEach(cb => cb.checked = false);
        syncSelection();

        const remaining = document.querySelectorAll('#returnTable tbody .search-item');
        if (remaining.length === 0) {
            setTimeout(() => location.reload(), 600);
        }
    }

    if (gagal > 0) {
        showNotif('error', `${gagal} barang gagal dikirim`);
    }

    btnSub.disabled = false;
    btnSub.textContent = '✅ Kirim Pengembalian';
}

function closeCamera() {
    const modal = document.getElementById('cameraModal');
    modal.classList.remove('show');
    document.body.style.overflow = ''; 

    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }

    document.getElementById('cameraReturn').srcObject = null;
}

/* ===================== FLASH SESSION ===================== */
@if(session('success'))
window.addEventListener('load', () => showNotif('success', "{{ session('success') }}"));
@endif
@if(session('error'))
window.addEventListener('load', () => showNotif('error', "{{ session('error') }}"));
@endif
@if(session('info'))
window.addEventListener('load', () => showNotif('info', "{{ session('info') }}"));
@endif
</script>

@endsection