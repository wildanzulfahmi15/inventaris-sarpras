@extends('layouts.sarpras')

@section('title', 'Manajemen Barang')

@section('content')

<style>
/* ================= RESET & GLOBAL ================= */
* {
    box-sizing: border-box;
}

body {
    overflow-x: hidden;
}

nav[role="navigation"] svg {
    width: 18px !important;
    height: 18px !important;
}

.btn-disabled {
    opacity: .5;
    cursor: not-allowed !important;
    pointer-events: none;
}

/* ================= CONTAINER ================= */
.container-fluid {
    max-width: 100%;
    padding-left: 16px;
    padding-right: 16px;
    overflow-x: hidden;
}

/* ================= CARD ================= */
.card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 10px 28px rgba(0,0,0,.06);
    overflow: hidden;
}

.card-header {
    background: #f8fafc;
    font-weight: 700;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

/* ================= FORM ================= */
.form-control,
.form-select {
    border-radius: 10px;
    font-size: clamp(0.8rem, 1vw, 0.95rem);
}

/* ================= TABLE WRAPPER ================= */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

.table-responsive::-webkit-scrollbar {
    height: 6px;
}
.table-responsive::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

/* ================= TABLE BASE ================= */
.table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;

    /* AUTO SCALE FONT */
    font-size: clamp(0.72rem, 1.05vw, 0.9rem);
}

/* header */
.table th {
    background: #f1f5f9;
    font-weight: 700;
    text-align: center;
    white-space: nowrap;

    padding: clamp(6px, 1vw, 10px);
    font-size: clamp(0.7rem, 1vw, 0.85rem);
}

/* cell */
.table td {
    text-align: center;
    vertical-align: middle;

    padding: clamp(6px, 1vw, 10px);
    font-size: clamp(0.7rem, 1vw, 0.85rem);
}

/* gambar tabel */
.table img {
    max-width: clamp(32px, 4vw, 46px);
    height: auto;
    border-radius: 6px;
}

/* ================= BADGE ================= */
.badge {
    font-size: clamp(0.65rem, 1vw, 0.8rem);
    padding: 4px 8px;
}

/* ================= BUTTON ================= */
.page-barang .btn {

    font-size: clamp(0.7rem, 1vw, 0.85rem);
    border-radius: 8px;
    padding: 4px 10px;
}

.btn-sm {
    padding: 4px 8px;
}

/* ================= PAGINATION ================= */
.pagination {
    justify-content: center;
    flex-wrap: wrap;
    gap: 4px;
}

.pagination .page-link {
    font-size: clamp(0.7rem, 1vw, 0.85rem);
    padding: 6px 10px;
}

/* ================= KATEGORI TABLE ================= */
.table-kategori {
    min-width: 500px;
}

.table-kategori img {
    max-width: 44px;
    border-radius: 6px;
}

/* ================= RESPONSIVE BREAKPOINT ================= */

/* tablet */
@media (max-width: 992px) {
    .table {
        min-width: 800px;
    }
}

/* mobile */
@media (max-width: 768px) {
    h4 {
        font-size: 1.1rem;
    }

    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }

    .table {
        min-width: 760px;
    }
}

/* hp kecil */
@media (max-width: 480px) {
    .table {
        min-width: 720px;
    }

    .pagination .page-link {
        padding: 5px 8px;
    }
}
/* ================= CUSTOM ALERT / CONFIRM ================= */
.ui-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.4);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: .25s ease;
}

.ui-overlay.show {
    opacity: 1;
    pointer-events: auto;
}

.ui-modal {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 380px;
    padding: 22px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,.2);
    animation: scaleIn .25s ease;
}

@keyframes scaleIn {
    from {
        transform: scale(.9);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.ui-modal h5 {
    font-weight: 700;
    margin-bottom: 10px;
}

.ui-modal p {
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 20px;
}

.ui-actions {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.ui-actions .btn {
    min-width: 90px;
}

/* status warna */
.ui-success h5 { color: #16a34a; }
.ui-error h5 { color: #dc2626; }
.ui-confirm h5 { color: #0d6efd; }

.hidden {
    display: none;
}
/* ================= AKSI BARANG (HORIZONTAL & RAPI) ================= */
.aksi-barang {
    white-space: nowrap;
    display: flex;
    gap: 6px;
    justify-content: center;
    align-items: center;
}

/* tombol biar tidak kepanjangan */
.aksi-barang .btn {
    padding: 4px 10px;
    font-size: 0.8rem;
}

/* mobile: boleh turun jadi kolom */
@media (max-width: 576px) {
    .aksi-barang {
        flex-direction: column;
        gap: 6px;
    }
}
/* KHUSUS KOLOM KETERANGAN */
.keterangan-cell {
    white-space: normal;      /* boleh turun */
    word-break: break-word;
    max-width: 300px;
    text-align: left;
    line-height: 1.4;
    vertical-align: top;
}


</style>

<div class="container-fluid">

    <h4 class="mb-3 fw-bold">Manajemen Barang (Sarpras)</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ================= TAMBAH BARANG ================= --}}
    <div class="card mb-4">
        <div class="card-header">Tambah Barang</div>
        <div class="card-body">
            <form method="POST" action="{{ route('sarpras.barang.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>

    <div class="col-md-3">
        <label>Kategori</label>

        <div class="input-group">
            <select name="id_kategori" id="kategoriSelect" class="form-control" required>
                <option value="">-- pilih --</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id_kategori }}">
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>

            <button
                type="button"
                class="btn btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalKategori">
                +
            </button>
        </div>
    </div>


                    <div class="col-lg-2 col-md-4">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" min="0">
                    </div>

                    <div class="col-lg-3 col-md-8">
                        <label>Gambar</label>
                        <input type="file" name="gambar" class="form-control">
                    </div>
                </div>

                <button class="btn btn-primary mt-3">Simpan Barang</button>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
    <input
        type="text"
        id="namaKategoriBaru"
        class="form-control mb-2"
        placeholder="Nama kategori..."
    >
    <textarea
    id="keteranganKategori"
    class="form-control"
    rows="3"
    placeholder="Keterangan kategori"
></textarea>


    <input
        type="file"
        id="gambarKategori"
        class="form-control"
        accept="image/*"
    >
    </div>


            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button class="btn btn-primary" onclick="simpanKategori()">
                    Simpan
                </button>
            </div>

        </div>
    </div>
    </div>
    <div class="modal fade" id="modalEditKategori" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="editKategoriId">

                <input
                    type="text"
                    id="editNamaKategori"
                    class="form-control mb-2"
                    placeholder="Nama kategori"
                >
                <textarea
    id="editKeteranganKategori"
    class="form-control mb-2"
    placeholder="Keterangan kategori"
></textarea>


                <img id="previewEditKategori"
                     src=""
                     style="max-width:100px;display:none;margin-bottom:10px;border-radius:6px">

                <input
                    type="file"
                    id="editGambarKategori"
                    class="form-control"
                    accept="image/*"
                >
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button class="btn btn-primary" onclick="updateKategori()">
                    Simpan Perubahan
                </button>
            </div>

        </div>
    </div>
    </div>
    <div class="table-responsive">

    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Kategori</th>
            <th>Gambar</th>
            <th>Keterangan</th>

            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kategori as $k)
        <tr>
            <td>{{ $k->nama_kategori }}</td>
            <td>
    @if($k->gambar)
        <img 
            src="{{ asset('img/kategori/'.$k->gambar) }}" 
            width="45"
            
        >
    @else
        -
    @endif
    <td class="keterangan-cell">{{ $k->keterangan ?? '-' }}</td>

    </td>

            <td>
                <button 
                type="button"
                    class="btn btn-sm btn-danger"
                    onclick="hapusKategori({{ $k->id_kategori }})">
                    Hapus
                </button>
<button
    class="btn btn-sm btn-warning"
    onclick="editKategori(
        {{ $k->id_kategori }},
        '{{ $k->nama_kategori }}',
        '{{ $k->gambar }}',
        @json($k->keterangan)
    )"
>

                    Edit
                </button>

            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
    </div>
    {{-- ================= IMPORT ================= --}}
    <div class="card mb-4">
        <div class="card-header">Import Barang</div>
        <div class="card-body">
            <form method="POST" action="{{ route('sarpras.barang.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="col-md-6 d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-primary" onclick="previewBarang()">
                            Preview
                        </button>

                        <button class="btn btn-warning">
                            Import
                        </button>
                        <a href="{{ route('sarpras.barang.template') }}" class="btn btn-outline-secondary">
                            Download Template
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

<div id="previewBoxBarang" class="mt-3 d-none">
    <h5>Preview Data Barang</h5>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
        </thead>
        <tbody id="previewBarangBody"></tbody>
    </table>
</div>

    {{-- ================= DAFTAR BARANG ================= --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Barang</span>
        </div>

        <div class="card-body">

            {{-- SEARCH --}}
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="q"
                           value="{{ request('q') }}"
                           class="form-control"
                           placeholder="Cari nama barang / kategori...">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Cari</button>
                </div>

                @if(request('q'))
                <div class="col-md-2">
                    <a href="{{ route('sarpras.barang.index') }}"
                       class="btn btn-secondary w-100">Reset</a>
                </div>
                @endif
            </form>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Dipinjam</th>
                            <th>Tersedia</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($barang as $b)
                        <tr>
                            <td>{{ $barang->firstItem() + $loop->index }}</td>
                            <td>{{ $b->nama_barang }}</td>
                            <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $b->stok }}</td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $b->stok_dipinjam }}
                                </span>
                            </td>

                            <td>
                                @if($b->stok_tersedia == 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($b->stok_tersedia <= 2)
                                    <span class="badge bg-warning text-dark">
                                        {{ $b->stok_tersedia }}
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        {{ $b->stok_tersedia }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                @php $path = public_path('gambar_barang/'.$b->gambar); @endphp
                                <img src="{{ (!empty($b->gambar) && file_exists($path))
                                    ? asset('gambar_barang/'.$b->gambar)
                                    : asset('gambar_barang/default.png') }}"
                                     width="45">
                            </td>

                            <td class="aksi-barang">

                                <a href="{{ route('sarpras.barang.edit',$b->id_barang) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                @if($b->stok_dipinjam > 0)
                                    <button class="btn btn-sm btn-danger btn-disabled" disabled>
                                        Hapus
                                    </button>
                                @else
                                    <form >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="hapusBarang({{ $b->id_barang }})">
                                        Hapus
                                    </button>

                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Data tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

<div class="mt-3 d-flex flex-wrap justify-content-center align-items-center gap-2">

    {{-- pagination --}}
    {{ $barang->withQueryString()->links('vendor.pagination.custom-bottom') }}



</div>
<div style="display:flex; justify-content:center;">
    {{-- skip page --}}
    <form method="GET" class="d-flex align-items-center gap-1">
        {{-- pertahankan search --}}
        @if(request('q'))
            <input type="hidden" name="q" value="{{ request('q') }}">
        @endif

        <span style="font-size:.85rem">Ke halaman:</span>

        <input
            type="number"
            name="page"
            min="1"
            max="{{ $barang->lastPage() }}"
            class="form-control"
            style="width:80px"
            placeholder="..."
            required
        >

        <button class="btn btn-sm btn-outline-primary">
            Go
        </button>
    </form>
</div>



        </div>
    </div>
    </div>

    <!-- ===== CUSTOM ALERT / CONFIRM ===== -->
    <div id="uiOverlay" class="ui-overlay hidden">
    <div class="ui-modal">
        <h5 id="uiTitle">Konfirmasi</h5>
        <p id="uiMessage">Apakah kamu yakin?</p>

        <div class="ui-actions">
            <button id="uiCancel" class="btn btn-secondary">Batal</button>
            <button id="uiConfirm" class="btn btn-danger">Ya</button>
        </div>
    </div>
    </div>

    <script>
        
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el)
    })
    function simpanKategori() {
        const nama = document.getElementById('namaKategoriBaru').value.trim();
        const gambar = document.getElementById('gambarKategori').files[0];
        const keterangan = document.getElementById('keteranganKategori').value;
        if (!nama) {
            showAlert('Nama kategori wajib diisi');
            return;
        }

        const formData = new FormData();
        formData.append('nama_kategori', nama);
formData.append('keterangan', keterangan); 
        if (gambar) {
            formData.append('gambar', gambar);
        }

        fetch("{{ route('sarpras.kategori.ajax') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                showAlert(data.message);
                return;
            }
            showAlert(data.message)
            const select = document.getElementById('kategoriSelect');

            const option = document.createElement('option');
            option.value = data.id;
            option.textContent = data.nama;
            option.selected = true;

            select.appendChild(option);

            document.getElementById('namaKategoriBaru').value = '';
            document.getElementById('gambarKategori').value = '';

            bootstrap.Modal.getInstance(
                document.getElementById('modalKategori')
            ).hide();
            
        })
        .catch(err => {
            console.error(err);
            showAlert('Gagal menyimpan kategori');
        });
    }



    </script>
    <script>
        function previewBarang() {
    const file = document.querySelector('input[name="file"]').files[0];
    if (!file) {
        showAlert('Pilih file terlebih dahulu', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    fetch("{{ route('sarpras.barang.preview') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('previewBarangBody');
        tbody.innerHTML = '';

        data.forEach(row => {
            const badge = row.status === 'ok'
                ? `<span class="badge bg-success">OK</span>`
                : `<span class="badge bg-danger">ERROR</span>`;

            tbody.innerHTML += `
                <tr>
                    <td>${row.nama_barang}</td>
                    <td>${row.stok}</td>
                    <td>${row.kategori}</td>
                    <td>${badge}</td>
                    <td>${row.pesan}</td>
                </tr>
            `;
        });

        document.getElementById('previewBoxBarang').classList.remove('d-none');
    });
}
function hapusKategori(id, jumlahBarang = 0) {

    let pesan = jumlahBarang > 0
        ? `Kategori ini memiliki ${jumlahBarang} barang.\n\nSEMUA barang akan ikut terhapus.\n\nLanjutkan?`
        : 'Yakin ingin menghapus kategori ini?';

    showConfirm(pesan, () => {
        fetch(`/sarpras/kategori/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                showAlert(data.message, 'error');
                return;
            }

            showAlert(data.message, 'success');
        })
        .catch(() => {
            showAlert('Gagal menghapus kategori', 'error');
        });
    });
}


    function editKategori(id, nama, gambar,keterangan) {
        document.getElementById('editKategoriId').value = id;
        document.getElementById('editNamaKategori').value = nama;
document.getElementById('editKeteranganKategori').value = keterangan ?? '';
        const preview = document.getElementById('previewEditKategori');

        if (gambar) {
            preview.src = `/img/kategori/${gambar}`;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }

        new bootstrap.Modal(document.getElementById('modalEditKategori')).show();
    }

function updateKategori() {
    const id = document.getElementById('editKategoriId').value;
    const nama = document.getElementById('editNamaKategori').value;
    const keterangan = document.getElementById('editKeteranganKategori').value;
    const gambar = document.getElementById('editGambarKategori').files[0];

    if (!nama) {
        showAlert('Nama kategori wajib diisi');
        return;
    }

    const formData = new FormData();
    formData.append('nama_kategori', nama);
    formData.append('keterangan', keterangan);

    if (gambar) {
        formData.append('gambar', gambar);
    }

    fetch(`/sarpras/kategori/update/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            showAlert(data.message);
            return;
        }

        showAlert(data.message);
    })
    .catch(() => {
        showAlert('Gagal update kategori');
    });
}

    let confirmCallback = null;

    function showConfirm(message, onYes) {
        const overlay = document.getElementById('uiOverlay');
        const title = document.getElementById('uiTitle');
        const msg = document.getElementById('uiMessage');

        overlay.className = 'ui-overlay show ui-confirm';
        title.textContent = 'Konfirmasi';
        msg.textContent = message;

        confirmCallback = onYes;
    }

    function showAlert(message, type = 'success') {
        const overlay = document.getElementById('uiOverlay');
        const title = document.getElementById('uiTitle');
        const msg = document.getElementById('uiMessage');

        overlay.className = 'ui-overlay show ui-' + type;
        title.textContent = type === 'error' ? 'Gagal' : 'Berhasil';
        msg.textContent = message;

        document.getElementById('uiCancel').style.display = 'none';
        document.getElementById('uiConfirm').textContent = 'OK';

        document.getElementById('uiConfirm').onclick = () => {
            closeModal();
            location.reload();
        };
    }

    function closeModal() {
        const overlay = document.getElementById('uiOverlay');
        overlay.classList.remove('show');

        setTimeout(() => {
            document.getElementById('uiCancel').style.display = '';
            document.getElementById('uiConfirm').textContent = 'Ya';
            confirmCallback = null;
        }, 200);
    }

    document.getElementById('uiCancel').onclick = closeModal;

    document.getElementById('uiConfirm').onclick = function () {
        if (confirmCallback) confirmCallback();
        closeModal();
    };

function hapusBarang(id) {
    showConfirm(
        'Yakin ingin menghapus barang ini?\n\nSemua data peminjaman yang terhubung akan ikut terhapus.',
        () => {
            fetch(`/sarpras/barang/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    showAlert(data.message, 'error');
                    return;
                }

                showAlert(data.message, 'success');
            })
            .catch(() => {
                showAlert('Gagal menghapus barang', 'error');
            });
        }
    );
}
function forceHapusKategori(id) {
    fetch(`/sarpras/kategori/${id}?force=1`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            showAlert(data.message, 'error');
            return;
        }
        showAlert(data.message, 'success');
    });
}


    </script>

    @endsection
