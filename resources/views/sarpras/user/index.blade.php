@extends('layouts.sarpras')

@section('title','Manajemen Akun')

@section('content')

<style>
/* ================= AKSI TABEL (AMAN, TIDAK GANGGU LAYOUT) ================= */
.table .aksi-wrap {
    display: inline-flex;
    gap: 6px;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}

.table .aksi-wrap form {
    margin: 0;
}

.table .aksi-wrap button {
    padding: 4px 8px;
    font-size: 12px;
    border-radius: 6px;
    line-height: 1;
}

/* Mobile: sembunyikan teks, icon saja */
@media (max-width: 576px) {
    .table .aksi-wrap button span {
        display: none;
    }
}

.custom-alert {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.custom-alert.d-none {
    display: none;
}
.custom-alert-box {
    background: #ffffff;
    width: 90%;
    max-width: 420px;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,.25);
    animation: pop .25s ease;
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

</style>
<h4 class="mb-3">Manajemen Akun Guru & Sarpras</h4>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- FORM TAMBAH --}}
<div class="card mb-4">
    <div class="card-header fw-bold">Tambah Akun</div>
    <div class="card-body">
        @if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

        <form method="POST" action="{{ route('sarpras.user.store') }}">
            @csrf
            <div class="row g-3">

                <div class="col-md-4">
                    <label>Nama</label>
                    <input name="nama" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label>Role</label>
                    <select name="role" id="roleSelect" class="form-select" required>
                        <option value="">-- pilih --</option>
                        <option value="guru">Guru</option>
                        <option value="sarpras">Sarpras</option>
                    </select>
                </div>

                <div class="col-md-3" id="jurusanBox" style="display:none;">
                    <label>Jurusan</label>
<select name="id_jurusan" class="form-select">
    <option value="">Guru Umum (tanpa jurusan)</option>

    @foreach($jurusan as $j)
        <option value="{{ $j->id_jurusan }}">
            {{ $j->nama_jurusan }}
        </option>
    @endforeach
</select>

                </div>

<div class="col-md-2">
    <label>Password</label>
    <div class="input-group">
        <input type="password" name="password" id="passwordInput" class="form-control" required>
        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
            👁
        </button>
    </div>
</div>


                <div class="col-12">
                    <button class="btn btn-primary mt-2">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header fw-bold">Import Akun</div>
    <div class="card-body">
        <form method="POST" action="{{ route('sarpras.user.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="file" name="file" class="form-control" required>
                </div>

                <div class="col-md-6 d-flex gap-2">
                    <button type="button" class="btn btn-primary" onclick="previewUser()">
                        Preview
                    </button>
                    <button class="btn btn-warning">Import</button>
                    <a href="{{ route('sarpras.user.template') }}" class="btn btn-outline-secondary">
                        Download Template
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="previewUserBox" class="mt-3 d-none">
    <h5>Preview Akun</h5>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nama</th>
            <th>Role</th>
            <th>Jurusan</th>
            <th>Status</th>
            <th>Password</th>
            <th>Keterangan</th>
        </tr>
        </thead>
        <tbody id="previewUserBody"></tbody>
    </table>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               class="form-control"
               placeholder="Cari nama / role...">
    </div>

    
    <div class="col-md-2">
        <button class="btn btn-primary w-100">Cari</button>
    </div>

    @if(request('q'))
    <div class="col-md-2">
        <a href="{{ route('sarpras.user.index') }}" class="btn btn-secondary w-100">
            Reset
        </a>
    </div>
    @endif
</form>


{{-- TABLE --}}
<div class="card">
    <div class="card-header fw-bold">Daftar Akun</div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Jurusan</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $u)
            <tr>
                <td>{{ $users->firstItem() + $loop->index }}</td>
                <td>{{ $u->nama }}</td>
                <td>
                    <span class="badge bg-{{ $u->role === 'sarpras' ? 'primary' : 'success' }}">
                        {{ ucfirst($u->role) }}
                    </span>
                </td>
                <td>
                    @if($u->role === 'sarpras')
                        <span class="badge bg-primary">Sarpras</span>
                    @elseif($u->role === 'guru' && $u->jurusan)
                        <span class="badge bg-success"> 
        {{ $u->jurusan->nama_jurusan }}</span>
                    @else
                        <span class="badge bg-secondary">Guru Umum</span>
                    @endif
                </td>

<td>
    <div class="aksi-wrap">

        {{-- RESET --}}
<form onsubmit="event.preventDefault(); resetPassword(this)"
      action="{{ route('sarpras.user.reset', $u->id_user) }}"
      method="POST">
    @csrf
    <button class="btn btn-warning btn-sm">
        Reset
    </button>
</form>


        {{-- HAPUS --}}
<form onsubmit="event.preventDefault(); hapusUser(this)"
      action="{{ route('sarpras.user.destroy', $u->id_user) }}"
      method="POST">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger btn-sm">
        Hapus
    </button>
</form>


    </div>
</td>

            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="p-3">
        {{ $users->links('vendor.pagination.akun') }}
    </div>
</div>
<!-- ================= CUSTOM ALERT ================= -->
<div id="customAlert" class="custom-alert d-none">
    <div class="custom-alert-box">
        <h5 id="alertTitle">Informasi</h5>
        <p id="alertMessage"></p>
        <div class="text-end mt-3">
            <button class="btn btn-primary btn-sm" onclick="closeAlert()">OK</button>
        </div>
    </div>
</div>

<!-- ================= CUSTOM CONFIRM ================= -->
<div id="customConfirm" class="custom-alert d-none">
    <div class="custom-alert-box">
        <h5 id="confirmTitle">Konfirmasi</h5>
        <p id="confirmMessage"></p>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <button class="btn btn-secondary btn-sm" onclick="closeConfirm()">Batal</button>
            <button class="btn btn-danger btn-sm" id="confirmYes">Ya</button>
        </div>
    </div>
</div>

<script>
document.getElementById('roleSelect')?.addEventListener('change', function () {
    document.getElementById('jurusanBox').style.display =
        this.value === 'guru' ? 'block' : 'none';
});

function togglePassword() {
    const input = document.getElementById('passwordInput');
    input.type = input.type === 'password' ? 'text' : 'password';
}

function previewUser() {
    const file = document.querySelector('input[name="file"]').files[0];
    if (!file) {
        showAlert('Pilih file terlebih dahulu');
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    fetch("{{ route('sarpras.user.preview') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('previewUserBody');
        tbody.innerHTML = '';

        data.forEach(row => {
            const badge = row.status === 'ok'
                ? `<span class="badge bg-success">OK</span>`
                : `<span class="badge bg-danger">ERROR</span>`;

            tbody.innerHTML += `
                <tr>
                    <td>${row.nama}</td>
                    <td>${row.role}</td>
                    <td>${row.jurusan}</td>
                    <td>${badge}</td>
                    <td><code>${row.password ?? '-'}</code></td>
                    <td>${row.pesan}</td>
                </tr>
            `;
        });

        document.getElementById('previewUserBox').classList.remove('d-none');
    });
}



</script>
<script>
/* ================= ALERT ================= */
let alertCallback = null;

function showAlert(message, title = 'Informasi', onClose = null) {
    document.getElementById('alertTitle').innerText = title;
    document.getElementById('alertMessage').innerText = message;
    document.getElementById('customAlert').classList.remove('d-none');
    alertCallback = onClose;
}

function closeAlert() {
    document.getElementById('customAlert').classList.add('d-none');
    if (typeof alertCallback === 'function') alertCallback();
    alertCallback = null;
}

/* ================= CONFIRM ================= */
let confirmCallback = null;

function showConfirm(message, onYes, title = 'Konfirmasi') {
    document.getElementById('confirmTitle').innerText = title;
    document.getElementById('confirmMessage').innerText = message;
    document.getElementById('customConfirm').classList.remove('d-none');
    confirmCallback = onYes;
}

function closeConfirm() {
    document.getElementById('customConfirm').classList.add('d-none');
    confirmCallback = null;
}

document.getElementById('confirmYes').onclick = function () {
    if (typeof confirmCallback === 'function') confirmCallback();
    closeConfirm();
};
</script>

<script>
function resetPassword(form) {
    showConfirm(
        'Reset password akun ini menjadi 123456 ?',
        () => {
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(() => {
                showAlert('Password berhasil di-reset ke 123456', 'Berhasil', () => {
                    location.reload();
                });
            })
            .catch(() => showAlert('Gagal reset password', 'Gagal'));
        }
    );
}
</script>
<script>
function hapusUser(form) {
    showConfirm(
        'Yakin ingin menghapus akun ini?\nAkun tidak bisa dikembalikan.',
        () => {
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(() => {
                showAlert('Akun berhasil dihapus', 'Berhasil', () => {
                    location.reload();
                });
            })
            .catch(() => showAlert('Gagal menghapus akun', 'Gagal'));
        }
    );
}
</script>


@endsection
