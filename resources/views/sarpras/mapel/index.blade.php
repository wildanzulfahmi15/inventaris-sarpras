@extends('layouts.sarpras')

@section('title','Manajemen Mapel')

@section('content')
<style>
/* ======================================================
   RESET DASAR (AMAN)
====================================================== */
* {
    box-sizing: border-box;
}

html, body {
    max-width: 100%;
    overflow-x: hidden;
}

/* ======================================================
   CARD
====================================================== */
.card {
    border-radius: 14px;
    border: none;
    box-shadow: 0 8px 24px rgba(0,0,0,.06);
}

.card-header {
    font-weight: 700;
    background: #f8fafc;
}

/* ======================================================
   FORM & INPUT
====================================================== */
.form-control,
.form-select {
    font-size: 14px;
    border-radius: 6px;
}

label {
    font-size: 13px;
    font-weight: 600;
}

/* ======================================================
   TABLE WRAPPER
====================================================== */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

/* ======================================================
   TABLE
====================================================== */
.table {
    width: 100%;
    min-width: 720px;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 10px 12px;
    font-size: 13px;
    vertical-align: middle;
    white-space: nowrap;
}

.table thead th {
    background: #f1f5f9;
    font-weight: 700;
    text-align: center;
}

/* kolom nama boleh wrap */
.table td:nth-child(2) {
    white-space: normal;
}

/* ======================================================
   KOLOM AKSI (AMAN, TIDAK NGERUSAK LAYOUT)
====================================================== */
.table td:last-child {
    text-align: center;
}

/* wrapper tombol */
.table .action-wrap {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

.table .action-wrap form {
    margin: 0;
}

/* tombol aksi KHUSUS tabel */
.table .action-wrap button {
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 6px;
    line-height: 1.2;
}

/* icon + teks */
.table .action-wrap i {
    font-size: 13px;
}

/* MOBILE: sembunyikan teks, icon saja */
@media (max-width: 576px) {
    .table .action-wrap span {
        display: none;
    }
}

/* ======================================================
   WARNA TOMBOL (AMAN — tidak override global)
====================================================== */
.table .btn-warning {
    background: #fbbf24;
    border: none;
    color: #000;
}

.table .btn-danger {
    background: #ef4444;
    border: none;
    color: #fff;
}

/* ======================================================
   RESPONSIVE TABLE
====================================================== */
@media (max-width: 768px) {
    .table {
        min-width: 600px;
    }

    .table th,
    .table td {
        padding: 8px;
        font-size: 12px;
    }

    .table .action-wrap button {
        padding: 5px 8px;
        font-size: 11px;
    }
}

@media (max-width: 420px) {
    .table {
        min-width: 560px;
    }

    .table .action-wrap button {
        padding: 5px 7px;
        font-size: 11px;
    }
}

/* ======================================================
   PAGINATION
====================================================== */
.pagination {
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
}

.pagination .page-link {
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 6px;
}
/* ================= CUSTOM ALERT ================= */
.custom-alert {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.custom-alert.show {
    display: flex;
}

.custom-alert-box {
    background: #fff;
    width: 90%;
    max-width: 420px;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,.2);
    animation: pop .25s ease;
}

@keyframes pop {
    from { transform: scale(.9); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}


</style>
<h4 class="mb-3">Manajemen Mata Pelajaran</h4>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4">
    <div class="card-header fw-bold">Tambah Mapel</div>
    <div class="card-body">
        <form method="POST" action="{{ route('sarpras.mapel.store') }}">
            @csrf
            <div class="row g-2">
                <div class="col-md-4">
                    <input name="nama_mapel" class="form-control" placeholder="Nama Mapel" required>
                </div>

                <div class="col-md-4">
                    <select name="jenis_mapel" id="add_jenis_mapel" class="form-select" required>
                        <option value="">-- Jenis Mapel --</option>
                        <option value="umum">Umum</option>
                        <option value="jurusan">Jurusan</option>
                        <option value="ekskul">Ekskul</option>
                    </select>
                </div>

                <div class="col-md-4" id="add-jurusan-wrapper" style="display:none">
    <select name="id_jurusan" id="add_id_jurusan" class="form-select">
        <option value="">Pilih Jurusan</option>
        @foreach($jurusan as $j)
            <option value="{{ $j->id_jurusan }}">
                {{ $j->nama_jurusan }}
            </option>
        @endforeach
    </select>
</div>



                <div class="col-12">
                    <button class="btn btn-primary mt-2">Tambah Mapel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header fw-bold">Import Mapel</div>

    <div class="card-body">
        <form method="POST" action="{{ route('sarpras.mapel.import') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <input type="file" name="file" class="form-control" required>
                </div>

                <div class="col-md-6 d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-primary" onclick="previewMapel()">
                        Preview
                    </button>

                    <button class="btn btn-warning">
                        Import
                    </button>

                    <a href="{{ route('sarpras.mapel.template') }}"
                       class="btn btn-outline-secondary">
                        Download Template
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card-body border-bottom">
    <form method="GET" action="{{ route('sarpras.mapel.index') }}" class="row g-2 align-items-end">

        <div class="col-md-4">
            <label class="form-label">Cari Nama Mapel</label>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Cari nama mapel..."
            >
        </div>

        <div class="col-md-3">
            <label class="form-label">Jenis</label>
            <select name="jenis" class="form-select">
                <option value="">Semua</option>
                <option value="umum" {{ request('jenis')=='umum'?'selected':'' }}>Umum</option>
                <option value="jurusan" {{ request('jenis')=='jurusan'?'selected':'' }}>Jurusan</option>
                <option value="ekskul" {{ request('jenis')=='ekskul'?'selected':'' }}>Ekskul</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Jurusan</label>
            <select name="id_jurusan" class="form-select">
                <option value="">Semua</option>
                @foreach($jurusan as $j)
                    <option value="{{ $j->id_jurusan }}"
                        {{ request('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                        {{ $j->nama_jurusan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-primary w-100">
                🔍 Cari
            </button>

            @if(request()->hasAny(['search','jenis','id_jurusan']))
                <a href="{{ route('sarpras.mapel.index') }}" class="btn btn-secondary w-100">
                    🔄 Reset
                </a>
            @endif
        </div>
    </form>
</div>

<div id="previewMapelBox" class="d-none mt-3">
    
    <h5>Preview Data Mapel</h5>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Jurusan</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
        </thead>
        <tbody id="previewMapelBody"></tbody>
    </table>
</div>

<div class="card">
    <div class="card-header fw-bold">Daftar Mapel</div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Jurusan</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mapel as $m)
                <tr>
                    <td>{{ $mapel->firstItem() + $loop->index }}</td>
                    <td>{{ $m->nama_mapel }}</td>
                    <td>{{ ucfirst($m->jenis_mapel) }}</td>
                    <td>{{ $m->jurusan->nama_jurusan ?? '-' }}</td>
                    <td>
                        <button
                            class="btn btn-sm btn-warning btn-edit"
                            data-id="{{ $m->id_mapel }}"
                            data-nama="{{ $m->nama_mapel }}"
                            data-jenis="{{ $m->jenis_mapel }}"
                            data-jurusan="{{ $m->id_jurusan }}"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEditMapel"
                        >
                            Edit
                        </button>


<form
    action="{{ route('sarpras.mapel.destroy', $m->id_mapel) }}"
    method="POST"
    class="d-inline"
    onsubmit="event.preventDefault(); hapusMapel(this)">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm btn-danger">Hapus</button>
</form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
<!-- MODAL EDIT MAPEL -->
<div class="modal fade" id="modalEditMapel" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formEditMapel">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Mapel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-2">
                        <label>Nama Mapel</label>
                        <input type="text" name="nama_mapel" id="edit_nama" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Jenis Mapel</label>
                        <select name="jenis_mapel" id="edit_jenis" class="form-select" required>
                            <option value="umum">Umum</option>
                            <option value="jurusan">Jurusan</option>
                            <option value="ekskul">Ekskul</option>
                        </select>
                    </div>

                    <div class="mb-2" id="edit-jurusan-wrapper">
                        <label>Jurusan</label>
                        <select name="id_jurusan" id="edit_jurusan" class="form-select">
                            <option value="">(Opsional)</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}">
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


    <div class="p-3 d-flex justify-content-center">
       {{ $mapel->links('pagination::clean') }}


    </div>

</div>
<!-- ================= CUSTOM ALERT & CONFIRM ================= -->
<div id="uiOverlay" class="custom-alert">
    <div class="custom-alert-box">
        <h5 id="uiTitle">Informasi</h5>
        <p id="uiMessage"></p>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <button id="uiCancel" class="btn btn-secondary">Batal</button>
            <button id="uiConfirm" class="btn btn-primary">OK</button>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {

        const id     = this.dataset.id;
        const nama   = this.dataset.nama;
        const jenis  = this.dataset.jenis;
        const jurusan = this.dataset.jurusan;

        // set action form
        document.getElementById('formEditMapel')
            .action = `/sarpras/mapel/${id}`;

        // isi value
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_jenis').value = jenis;
        document.getElementById('edit_jurusan').value = jurusan ?? '';

        toggleJurusanEdit();
    });
});

function toggleJurusanEdit() {
    const jenis = document.getElementById('edit_jenis').value;
    const wrapper = document.getElementById('edit-jurusan-wrapper');

    if (jenis === 'jurusan') {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
        document.getElementById('edit_jurusan').value = '';
    }
}

document.getElementById('edit_jenis')
    ?.addEventListener('change', toggleJurusanEdit);
</script>

<script>
function previewMapel() {
    const file = document.querySelector('input[name="file"]').files[0];
    if (!file) {
        showAlert('Pilih file terlebih dahulu');
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    fetch("{{ route('sarpras.mapel.preview') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('previewMapelBody');
        tbody.innerHTML = '';

        data.forEach(row => {
            const badge = row.status === 'ok'
                ? '<span class="badge bg-success">OK</span>'
                : '<span class="badge bg-danger">ERROR</span>';

            tbody.innerHTML += `
                <tr>
                    <td>${row.nama_mapel}</td>
                    <td>${row.jenis}</td>
                    <td>${row.jurusan ?? '-'}</td>
                    <td>${badge}</td>
                    <td>${row.pesan}</td>
                </tr>
            `;
        });

        document.getElementById('previewMapelBox').classList.remove('d-none');
    });
}
</script>
<script>
let confirmCallback = null;
let isConfirm = false;

/* ===== SHOW CONFIRM ===== */
function showConfirm(message, onYes) {
    isConfirm = true;
    confirmCallback = onYes;

    document.getElementById('uiTitle').textContent = 'Konfirmasi';
    document.getElementById('uiMessage').textContent = message;

    document.getElementById('uiCancel').style.display = 'inline-block';
    document.getElementById('uiConfirm').textContent = 'Ya';

    document.getElementById('uiOverlay').classList.add('show');
}

/* ===== SHOW ALERT ===== */
function showAlert(message, title = 'Informasi', onClose = null) {
    isConfirm = false;
    confirmCallback = onClose;

    document.getElementById('uiTitle').textContent = title;
    document.getElementById('uiMessage').textContent = message;

    document.getElementById('uiCancel').style.display = 'none';
    document.getElementById('uiConfirm').textContent = 'OK';

    document.getElementById('uiOverlay').classList.add('show');
}

/* ===== CLOSE ===== */
function closeModal() {
    document.getElementById('uiOverlay').classList.remove('show');

    if (!isConfirm && typeof confirmCallback === 'function') {
        confirmCallback();
    }

    confirmCallback = null;
    isConfirm = false;
}

document.getElementById('uiCancel').onclick = closeModal;

document.getElementById('uiConfirm').onclick = function () {
    if (isConfirm && typeof confirmCallback === 'function') {
        confirmCallback();
    }
    closeModal();
};
</script>

<script>
function hapusMapel(form) {
    showConfirm('Yakin ingin menghapus mapel ini?', () => {
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: new FormData(form)
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                showAlert(data.message || 'Gagal menghapus mapel', 'Gagal');
                return;
            }

            showAlert('Mapel berhasil dihapus', 'Berhasil', () => {
                location.reload();
            });
        })
        .catch(() => showAlert('Terjadi kesalahan', 'Gagal'));
    });
}
</script>

<script>
const jenisAdd = document.getElementById('add_jenis_mapel');
const jurusanWrapAdd = document.getElementById('add-jurusan-wrapper');
const jurusanSelectAdd = document.getElementById('add_id_jurusan');

function toggleJurusanAdd() {
    if (jenisAdd.value === 'jurusan') {
        jurusanWrapAdd.style.display = 'block';
        jurusanSelectAdd.required = true;
    } else {
        jurusanWrapAdd.style.display = 'none';
        jurusanSelectAdd.required = false;
        jurusanSelectAdd.value = '';
    }
}

jenisAdd.addEventListener('change', toggleJurusanAdd);
</script>

@endsection
