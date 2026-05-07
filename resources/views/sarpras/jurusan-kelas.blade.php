@extends('layouts.sarpras')

@section('title','Jurusan & Kelas')

@section('content')
<div class="page-jurusan">


<style>
/* =====================================================
   GLOBAL RESPONSIVE BASE
===================================================== */
* {
    box-sizing: border-box;
}

.container-fluid {
    max-width: 100%;
    padding-inline: clamp(12px, 2vw, 24px);
}

/* judul */
.page-title {
    font-weight: 800;
    font-size: clamp(1.1rem, 2.2vw, 1.6rem);
    margin-bottom: 16px;
}

/* =====================================================
   CARD
===================================================== */
.card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 28px rgba(0,0,0,.06);
}

.card-header {
    background: #f8fafc;
    font-weight: 700;
    padding: 14px 18px;
}

.card-body {
    padding: clamp(14px, 2vw, 20px);
}

/* =====================================================
   FORM RESPONSIVE
===================================================== */
.form-control,
.form-select,
.btnx {
    font-size: clamp(0.78rem, 1.8vw, 0.95rem);
}

/* =====================================================
   TABLE WRAPPER
===================================================== */
.table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* tabel dasar */
.table {
    width: 100%;
    min-width: 520px;
    border-collapse: collapse;
}

/* header */
.table th {
    font-size: clamp(0.7rem, 1.8vw, 0.9rem);
    font-weight: 700;
    text-align: center;
    white-space: nowrap;
    padding: 8px;
}

/* cell */
.table td {
    font-size: clamp(0.7rem, 1.7vw, 0.88rem);
    padding: 8px;
    vertical-align: middle;
    word-break: break-word;
}

.table-bordered > :not(caption) > * > * {
    border-color: #e5e7eb;
}

/* =====================================================
   BUTTON AKSI
===================================================== */
.action-btns {
    display: flex;
    justify-content: center;
    gap: 6px;
    flex-wrap: nowrap;
}

.action-btns .btnx {
    white-space: nowrap;
    min-width: 64px;
    padding: 5px 10px;
    font-size: 0.75rem;
    line-height: 1.2;
}

/* =====================================================
   PAGINATION
===================================================== */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 16px;
}

.pagination {
    gap: 6px;
    flex-wrap: wrap;
}

.page-item .page-link {
    padding: 6px 12px;
    font-size: 0.8rem;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

.page-item.active .page-link {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
    font-weight: 600;
}

.page-link:hover {
    background: #eef2ff;
}

/* =====================================================
   MODAL RESPONSIVE
===================================================== */
body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
}

.modal-dialog {
    max-width: 520px;
    margin: 1.75rem auto;
}

.modal-content {
    border-radius: 16px;
    border: none;
}

.modal-footer {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.modal-footer .btnx {
    min-width: 90px;
}

/* =====================================================
   MOBILE OPTIMIZATION
===================================================== */
@media (max-width: 576px) {

    .table {
        min-width: 420px;
    }

    .action-btns .btnx {
        font-size: 0.7rem;
        padding: 5px 8px;
        min-width: 58px;
    }

    /* pagination kecil */
    .page-item .page-link {
        padding: 5px 9px;
        font-size: 0.7rem;
    }

    /* prev next icon */
    .page-item:first-child .page-link,
    .page-item:last-child .page-link {
        font-size: 0;
        padding: 6px 10px;
        position: relative;
    }

    .page-item:first-child .page-link::after {
        content: "«";
        font-size: 14px;
    }

    .page-item:last-child .page-link::after {
        content: "»";
        font-size: 14px;
    }

    /* modal mobile */
    .modal-dialog {
        max-width: 92vw;
        margin: auto;
    }
}
/* ================= CUSTOM ALERT & CONFIRM ================= */
.custom-alert {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.custom-alert-box {
    background: #fff;
    width: 90%;
    max-width: 420px;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,.2);
    animation: pop 0.25s ease;
}

.custom-alert-box h5 {
    font-weight: 700;
    margin-bottom: 8px;
}

.custom-alert-box p {
    font-size: 0.9rem;
    color: #475569;
}

@keyframes pop {
    from {
        transform: scale(0.9);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}
/* =====================================================
   WARNA BUTTON UNTUK btnx (FIX FINAL)
===================================================== */

.btnx {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    padding: 8px 14px;
    border-radius: 10px;
    border: 1px solid transparent;

    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;

    transition: all .15s ease;
}

/* ===== PRIMARY ===== */
.btnx.btn-primary {
    background: #2563eb;
    color: #fff;
}
.btnx.btn-primary:hover {
    background: #1e40af;
}

/* ===== WARNING ===== */
.btnx.btn-warning {
    background: #facc15;
    color: #1f2937;
}
.btnx.btn-warning:hover {
    background: #eab308;
}

/* ===== DANGER ===== */
.btnx.btn-danger {
    background: #dc2626;
    color: #fff;
}
.btnx.btn-danger:hover {
    background: #b91c1c;
}

/* ===== SECONDARY ===== */
.btnx.btn-secondary {
    background: #6b7280;
    color: #fff;
}
.btnx.btn-secondary:hover {
    background: #4b5563;
}

/* ===== OUTLINE ===== */
.btnx.btn-outline-secondary {
    background: transparent;
    border-color: #6b7280;
    color: #6b7280;
}
.btnx.btn-outline-secondary:hover {
    background: #6b7280;
    color: #fff;
}

/* ===== SMALL ===== */
.btnx.btn-sm {
    padding: 5px 10px;
    font-size: 0.75rem;
}



</style>

<div class="container-fluid">

    <h4 class="page-title">Master Jurusan & Kelas</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    

    {{-- ================= JURUSAN ================= --}}
    <div class="card mb-4">
        <div class="card-header">📘 Kelola Jurusan</div>

        <div class="card-body">

            <form method="POST" action="{{ route('sarpras.jurusan.store') }}" class="mb-3">
                @csrf
                <div class="row g-2">
                    <div class="col-12 col-md-8">
                        <input type="text" name="nama_jurusan" class="form-control"
                               placeholder="Masukkan nama jurusan" required>
                    </div>
                    <div class="col-12 col-md-4 d-grid">
                        <button class="btnx btn-primary">Tambah Jurusan</button>
                    </div>
                </div>
            </form>
{{-- IMPORT JURUSAN --}}
<div class="card mb-4">
    <div class="card-header">📥 Import Jurusan</div>
    <div class="card-body">

        <form method="POST" action="{{ route('sarpras.jurusan.import') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-2">
                <div class="col-md-6">
                    <input type="file" name="file" id="fileJurusan" class="form-control" required>

                </div>

                <div class="col-md-6 d-flex gap-2">
                    <button type="button" class="btnx btn-primary" onclick="previewJurusan()">
                        Preview
                    </button>

                    <button class="btnx btn-warning">
                        Import
                    </button>

                    <a href="{{ route('sarpras.jurusan.template') }}"
                       class="btnx btn-outline-secondary">
                        Template
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="previewJurusanBox" class="mt-3 d-none">
    <h5>Preview Jurusan</h5>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Jurusan</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody id="previewJurusanBody"></tbody>
    </table>
</div>
<form method="GET" class="row g-2 mb-3 align-items-center">
    <div class="col-md-6">
        <input type="text"
               name="search_jurusan"
               class="form-control"
               placeholder="Cari jurusan..."
               value="{{ request('search_jurusan') }}">
    </div>

    <div class="col-md-3 d-grid">
        <button class="btnx btn-primary">
            🔍 Search
        </button>
    </div>

    @if(request('search_jurusan'))
    <div class="col-md-3 d-grid">
<a href="{{ url()->current() }}" class="btnx btn-secondary">
    🔄 Reset
</a>

    </div>
    @endif
</form>

            <div class="table-wrapper">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Jurusan</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jurusan as $j)
                        <tr>
                            <td class="text-center">{{ $jurusan->firstItem() + $loop->index }}
</td>
                            <td>{{ $j->nama_jurusan }}</td>
                            <td>
                                <div class="action-btns">
                                    <button class="btnx btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editJurusan{{ $j->id_jurusan }}">
                                        Edit
                                    </button>

                                        <button
  class="btnx btn-danger btn-sm"
  onclick="hapusJurusan({{ $j->id_jurusan }}, {{ $j->kelas_count ?? 0 }})">
  Hapus
</button>

                                    
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
<div class="pagination-wrapper">
    {{ $jurusan->onEachSide(1)->links('vendor.pagination.simple-clean') }}

</div>
<div class="text-center text-muted mt-2 small mb-2">
    Showing
    <strong>{{ $jurusan->firstItem() }}</strong>
    to
    <strong>{{ $jurusan->lastItem() }}</strong>
    of
    <strong>{{ $jurusan->total() }}</strong>
    results
</div>



    {{-- MODAL EDIT JURUSAN --}}
    @foreach($jurusan as $j)
    <div class="modal fade" id="editJurusan{{ $j->id_jurusan }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('sarpras.jurusan.update',$j->id_jurusan) }}">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Jurusan</h5>
                        <button type="button" class="btnx-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="text" name="nama_jurusan"
                               class="form-control"
                               value="{{ $j->nama_jurusan }}" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btnx btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btnx btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    {{-- ================= KELAS ================= --}}
    <div class="card mb-4">
        <div class="card-header">🏫 Kelola Kelas</div>

        <div class="card-body">

            <form method="POST" action="{{ route('sarpras.kelas.store') }}" class="mb-3">
                @csrf
                <div class="row g-2">
                    <div class="col-12 col-md-4">
                        <input type="text" name="nama_kelas" class="form-control" placeholder="Nama Kelas" required>
                    </div>

                    <div class="col-12 col-md-5">
                        <select name="id_jurusan" class="form-select" required>
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}">{{ $j->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3 d-grid">
                        <button class="btnx btn-primary">Tambah Kelas</button>
                    </div>
                </div>
            </form>
{{-- IMPORT KELAS --}}
<div class="card mb-4">
    <div class="card-header">📥 Import Kelas</div>
    <div class="card-body">

        <form method="POST" action="{{ route('sarpras.kelas.import') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-2">
                <div class="col-md-6">
                    <input type="file" name="file" id="fileKelas" class="form-control" required>

                </div>

                <div class="col-md-6 d-flex gap-2">
                    <button type="button" class="btnx btn-primary" onclick="previewKelas()">
                        Preview
                    </button>

                    <button class="btnx btn-warning">
                        Import
                    </button>

                    <a href="{{ route('sarpras.kelas.template') }}"
                       class="btnx btn-outline-secondary">
                        Template
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="previewKelasBox" class="mt-3 d-none">
    <h5>Preview Kelas</h5>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Kelas</th>
                <th>Jurusan</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody id="previewKelasBody"></tbody>
    </table>
</div>
<form method="GET" class="row g-2 mb-3 align-items-center">
    <div class="col-md-6">
        <input type="text"
               name="search_kelas"
               class="form-control"
               placeholder="Cari kelas / jurusan..."
               value="{{ request('search_kelas') }}">
    </div>

    <div class="col-md-3 d-grid">
        <button class="btnx btn-primary">
            🔍 Search
        </button>
    </div>

    @if(request('search_kelas'))
    <div class="col-md-3 d-grid">
<a href="{{ url()->current() }}" class="btnx btn-secondary">
    🔄 Reset
</a>

    </div>
    @endif
</form>


            <div class="table-wrapper">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Kelas</th>
                            <th>Jurusan</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelas as $k)
                        <tr>
                            <td class="text-center">{{ $kelas->firstItem() + $loop->index }}
</td>
                            <td>{{ $k->nama_kelas }}</td>
                            <td>{{ $k->jurusan->nama_jurusan ?? '-' }}</td>
                            <td>
                                <div class="action-btns">
                                    <button class="btnx btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editKelas{{ $k->id_kelas }}">
                                        Edit
                                    </button>

<button
  class="btnx btn-danger btn-sm"
  onclick="hapusKelas({{ $k->id_kelas }})">
  Hapus
</button>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
<div class="pagination-wrapper">
   {{ $jurusan->onEachSide(1)->links('vendor.pagination.simple-clean') }}

</div>
<div class="text-center text-muted mt-2 small">
    Showing
    <strong>{{ $kelas->firstItem() }}</strong>
    to
    <strong>{{ $kelas->lastItem() }}</strong>
    of
    <strong>{{ $kelas->total() }}</strong>
    results
</div>



    {{-- MODAL EDIT KELAS --}}
    @foreach($kelas as $k)
    <div class="modal fade" id="editKelas{{ $k->id_kelas }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('sarpras.kelas.update',$k->id_kelas) }}">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kelas</h5>
                        <button type="button" class="btnx-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control"
                                   value="{{ $k->nama_kelas }}" required>
                        </div>

                        <div class="mb-2">
                            <label>Jurusan</label>
                            <select name="id_jurusan" class="form-select" required>
                                @foreach($jurusan as $j)
                                    <option value="{{ $j->id_jurusan }}"
                                        {{ $k->id_jurusan == $j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btnx btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btnx btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach
<!-- ================= CUSTOM ALERT ================= -->
<div id="customAlert" class="custom-alert d-none">
    <div class="custom-alert-box">
        <h5 id="alertTitle">Informasi</h5>
        <p id="alertMessage"></p>
        <button class="btnx btn-primary" onclick="closeAlert()">OK</button>
    </div>
</div>

<!-- ================= CUSTOM CONFIRM ================= -->
<div id="customConfirm" class="custom-alert d-none">
    <div class="custom-alert-box">
        <h5 id="confirmTitle">Konfirmasi</h5>
        <p id="confirmMessage"></p>
        <div class="d-flex justify-content-end gap-2 mt-3">
            <button class="btnx btn-secondary" onclick="closeConfirm()">Batal</button>
            <button class="btnx btn-danger" id="confirmYes">Ya, Hapus</button>
        </div>
    </div>
</div>

</div>
<script>
function previewJurusan() {
    const file = document.getElementById('fileJurusan').files[0];

    if (!file) {
        showAlert('Pilih file terlebih dahulu');
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    fetch("{{ route('sarpras.jurusan.preview') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('previewJurusanBody');
        tbody.innerHTML = '';

        data.forEach(row => {
            const badge = row.status === 'ok'
                ? `<span class="badge bg-success">OK</span>`
                : `<span class="badge bg-danger">ERROR</span>`;

            tbody.innerHTML += `
                <tr>
                    <td>${row.nama_jurusan}</td>
                    <td>${badge}</td>
                    <td>${row.pesan}</td>
                </tr>
            `;
        });

        document.getElementById('previewJurusanBox').classList.remove('d-none');
    });
}

function previewKelas() {
    const file = document.getElementById('fileKelas').files[0];

    if (!file) {
        showAlert('Pilih file terlebih dahulu');
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    fetch("{{ route('sarpras.kelas.preview') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('previewKelasBody');
        tbody.innerHTML = '';

        data.forEach(row => {
            const badge = row.status === 'ok'
                ? `<span class="badge bg-success">OK</span>`
                : `<span class="badge bg-danger">ERROR</span>`;

            tbody.innerHTML += `
                <tr>
                    <td>${row.nama_kelas}</td>
                    <td>${row.jurusan}</td>
                    <td>${badge}</td>
                    <td>${row.pesan}</td>
                </tr>
            `;
        });

        document.getElementById('previewKelasBox').classList.remove('d-none');
    });
}

/* ================= ALERT ================= */
function showAlert(message, title = "Informasi", onClose = null) {
    document.getElementById('alertTitle').innerText = title;
    document.getElementById('alertMessage').innerText = message;
    document.getElementById('customAlert').classList.remove('d-none');

    // simpan callback dengan BENAR
    window.__alertCallback = onClose;
}


function closeAlert() {
    document.getElementById('customAlert').classList.add('d-none');

    if (typeof window.__alertCallback === 'function') {
        window.__alertCallback();
        window.__alertCallback = null;
    }
}


/* ================= CONFIRM ================= */
let confirmCallback = null;

function showConfirm(message, callback, title = "Konfirmasi") {
    confirmCallback = callback;
    document.getElementById('confirmTitle').innerText = title;
    document.getElementById('confirmMessage').innerText = message;
    document.getElementById('customConfirm').classList.remove('d-none');
}

function closeConfirm() {
    document.getElementById('customConfirm').classList.add('d-none');
    confirmCallback = null;
}

document.getElementById('confirmYes').addEventListener('click', function () {
    if (confirmCallback) confirmCallback();
    closeConfirm();
});

function hapusJurusan(id, jumlahKelas = 0) {
    const pesan = jumlahKelas > 0
        ? `Jurusan ini memiliki ${jumlahKelas} kelas.\n\nSEMUA kelas akan ikut terhapus.\n\nLanjutkan?`
        : 'Yakin ingin menghapus jurusan ini?\nSemua data yang berkaitan dengan jurusan ini akan ikut terhapus';

    showConfirm(pesan, () => {
        fetch(`/sarpras/jurusan/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                showAlert(data.message, 'Gagal');
                return;
            }
showAlert(data.message, 'Berhasil', () => {
    location.reload();
});

        })
        .catch(() => showAlert('Gagal menghapus jurusan', 'Gagal'));
    });
}
function hapusKelas(id) {
    showConfirm(
        'Yakin ingin menghapus kelas ini?\nSemua data terkait akan ikut terhapus.',
        () => {
            fetch(`/sarpras/kelas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    showAlert(data.message, 'Gagal');
                    return;
                }
showAlert(data.message, 'Berhasil', () => {
    location.reload();
});

            });
        }
    );
}

</script>

</div>
@endsection
