@extends('layouts.sarpras')

@section('title','Manajemen Ruangan')

@section('content')

<style>
/* ===== PAGE SCOPE (AMAN) ===== */
.ruangan-page .card {
    border-radius: 14px;
    border: none;
    box-shadow: 0 8px 24px rgba(0,0,0,.06);
}

.ruangan-page .card-header {
    background: #f8fafc;
    font-weight: 700;
}

.ruangan-page .page-title {
    font-weight: 800;
    font-size: clamp(1.3rem, 4vw, 1.7rem);
}

.ruangan-page .page-sub {
    font-size: 13px;
    color: #64748b;
}

/* table */
.ruangan-page .table th {
    background: #f1f5f9;
    font-weight: 700;
    text-align: center;
}

.ruangan-page .table td {
    font-size: 13px;
    vertical-align: middle;
}

.ruangan-page .table td:last-child {
    white-space: nowrap;
}

/* action button */
.ruangan-page .action-btns {
    display: inline-flex;
    gap: 6px;
}

/* badge */
.ruangan-page .badge {
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 999px;
}
</style>

<div class="ruangan-page">

    {{-- HEADER --}}
    <div class="mb-3">
        <div class="page-title">Manajemen Ruangan</div>
        <div class="page-sub">
            Kelola ruang teori, laboratorium, dan fasilitas lainnya
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- TAMBAH RUANGAN --}}
    <div class="card mb-4">
        <div class="card-header">Tambah Ruangan</div>
        <div class="card-body">
            <form method="POST" action="{{ route('sarpras.tempat.store') }}">
                @csrf
                <div class="row g-2">

                    <div class="col-md-4">
                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            placeholder="Nama Ruangan"
                            required>
                    </div>

                    <div class="col-md-4">
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Kategori --</option>
                            <option value="teori">Ruang Teori</option>
                            <option value="lab">Laboratorium</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="aktif" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary mt-2">
                            Tambah Ruangan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="card mb-4">
        <div class="card-header">Filter & Pencarian</div>
        <div class="card-body">
            <form method="GET"
                  action="{{ route('sarpras.tempat.index') }}"
                  class="row g-2 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Cari Nama</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Nama ruangan...">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua</option>
                        <option value="teori" {{ request('kategori')=='teori'?'selected':'' }}>Teori</option>
                        <option value="lab" {{ request('kategori')=='lab'?'selected':'' }}>Lab</option>
                        <option value="lainnya" {{ request('kategori')=='lainnya'?'selected':'' }}>Lainnya</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="aktif" class="form-select">
                        <option value="">Semua</option>
                        <option value="1" {{ request('aktif')==='1'?'selected':'' }}>Aktif</option>
                        <option value="0" {{ request('aktif')==='0'?'selected':'' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100">Cari</button>
                    @if(request()->query())
                        <a href="{{ route('sarpras.tempat.index') }}"
                           class="btn btn-secondary w-100">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="card">
        <div class="card-header">Daftar Ruangan</div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th width="150">Aksi</th>
                </tr>
                </thead>
                <tbody>

                @forelse($tempat as $t)
                    <tr>
                        <td>{{ $tempat->firstItem() + $loop->index }}</td>
                        <td>{{ $t->nama }}</td>
                        <td>{{ ucfirst($t->kategori) }}</td>
                        <td>
                            @if($t->aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btns">
                                <button
                                    class="btn btn-sm btn-warning btn-edit"
                                    data-id="{{ $t->id }}"
                                    data-nama="{{ $t->nama }}"
                                    data-kategori="{{ $t->kategori }}"
                                    data-aktif="{{ $t->aktif }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditTempat">
                                    Edit
                                </button>

                                <form
                                    action="{{ route('sarpras.tempat.destroy', $t->id) }}"
                                    method="POST"
                                    onsubmit="event.preventDefault(); hapusTempat(this)">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada data
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        <div class="p-3 d-flex justify-content-center">
            {{ $tempat->links('pagination::clean') }}
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal fade" id="modalEditTempat" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" id="formEditTempat">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Ruangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-2">
                            <label>Nama Ruangan</label>
                            <input type="text" name="nama" id="edit_nama"
                                   class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label>Kategori</label>
                            <select name="kategori" id="edit_kategori"
                                    class="form-select" required>
                                <option value="teori">Teori</option>
                                <option value="lab">Lab</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label>Status</label>
                            <select name="aktif" id="edit_aktif"
                                    class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div> {{-- .ruangan-page --}}

<script>
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {

        const id       = this.dataset.id;
        const nama     = this.dataset.nama;
        const kategori = this.dataset.kategori;
        const aktif    = this.dataset.aktif === '1' ? '1' : '0'; // 🔥 FIX

        const form = document.getElementById('formEditTempat');
        form.action = `/sarpras/tempat/${id}`;

        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_kategori').value = kategori;

        const statusSelect = document.getElementById('edit_aktif');
        statusSelect.value = aktif;

        // 🔥 FORCE refresh select (penting)
        statusSelect.dispatchEvent(new Event('change'));
    });
});
function hapusTempat(form) {
    showConfirm('Yakin ingin menghapus ruangan ini?', () => {
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
                showAlert(data.message || 'Gagal menghapus', 'Gagal');
                return;
            }
            showAlert('Ruangan berhasil dihapus', 'Berhasil', () => {
                location.reload();
            });
        });
    });
}
</script>

@endsection
