    <!-- resources/views/peminjaman_form.blade.php -->
    @if(empty($barangDipilih) || count($barangDipilih) === 0)
        <script>
            alert('Barang belum dipilih. Silakan pilih barang terlebih dahulu.');
            window.location.href = "{{ route('peminjaman.kategori') }}";
        </script>
    @endif

    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width,initial-scale=1" />
      <title>Form Peminjaman — SMKN 1 Cibinong</title>
      <style>
        :root{
          --blue: #3baee7;
          --blue-dark: #0e95d1;
          --muted: #6b7280;
          --card: #ffffff;
          --radius: 12px;
          --shadow: 0 8px 24px rgba(15,23,42,0.08);
        }
        *{box-sizing:border-box}
        body{font-family:Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial; background:linear-gradient(180deg,#f3f9ff 0%, #eef8ff 100%); margin:0; padding:28px; color:#0f172a}
        .wrap{max-width:1100px;margin:0 auto}
        .topbar{display:flex;align-items:center;gap:16px;margin-bottom:20px}
        .brand{display:flex;gap:12px;align-items:center}
        .logo{width:56px;height:56px;border-radius:10px;background:linear-gradient(135deg,var(--blue),var(--blue-dark));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:18px;box-shadow:0 6px 18px rgba(59,174,231,0.22)}
        .brand h1{margin:0;font-size:20px}
        .brand p{margin:0;color:var(--muted);font-size:13px}
        .grid{display:grid;grid-template-columns:1fr 420px;gap:20px}
        @media (max-width:920px){.grid{grid-template-columns:1fr}}
        .card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px}
        form .row{display:flex;gap:12px;margin-bottom:12px}
        .col{flex:1}
        label{display:block;font-size:13px;font-weight:600;color:#0f172a;margin-bottom:6px}
        input[type=text], input[type=tel], input[type=number], textarea{width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e6eef6;background:linear-gradient(180deg,#fff,#fbfeff);outline:none;font-size:15px}
        input[readonly]{background:#f8fafc;color:#475569}
        textarea{min-height:84px;resize:vertical}
        .muted{color:var(--muted);font-size:13px}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;border:none;cursor:pointer;font-weight:600}
        .btn-primary{background:var(--blue);color:white;box-shadow:0 8px 18px rgba(59,174,231,0.18);transition:transform .14s ease, box-shadow .14s}
        .btn-primary:hover{transform:translateY(-3px);box-shadow:0 14px 32px rgba(59,174,231,0.22)}
        .btn-ghost{background:transparent;border:1px solid #e6eef6;color:var(--muted)}
        .summary h3{font-size:16px;margin:0 0 8px 0}
        #barangContainer{display:flex;flex-direction:column;gap:10px;margin-top:8px}
        .barangRow{display:grid;grid-template-columns:1fr 120px 48px;gap:8px;align-items:center;position:relative}
        .hapusBtn{background:#ff6b6b;color:white;border-radius:8px;border:none;padding:8px;cursor:pointer}
        .dropdown{position:absolute;left:0;right:0;top: calc(100% + 8px);background:white;border-radius:10px;box-shadow:0 12px 32px rgba(2,6,23,0.09);overflow:auto;max-height:220px;border:1px solid #eef6fb;z-index:90}
        .dropdown .item{padding:10px 12px;cursor:pointer;border-bottom:1px solid #f3f7fb}
        .dropdown .item:last-child{border-bottom:none}
        .dropdown .item:hover{background:#f8fbff}
        .dropdown .meta{font-size:12px;color:var(--muted);margin-left:8px}
        .helper{font-size:13px;color:var(--muted)}
        .actions{display:flex;gap:10px;justify-content:flex-end;align-items:center;margin-top:14px}
        @media (max-width:920px){.actions{flex-direction:column-reverse;align-items:stretch}}
        .selected-items{
        display:flex;
        flex-direction:column;
        gap:12px;
        margin:12px 0 20px;
    }

    .item-card{
        display:flex;
        gap:12px;
        align-items:center;
        background:white;
        border-radius:12px;
        padding:12px;
        box-shadow:0 6px 18px rgba(0,0,0,0.06);
    }

    .item-card img{
        width:70px;
        height:70px;
        object-fit:cover;
        border-radius:10px;
    }

    .item-card .name{
        font-size:15px;
        font-weight:600;
    }

    .item-card .qty{
        color:#6b7280;
        margin-top:4px;
    }
    .jenis-wrapper {
      margin-bottom: 16px;
    }

    .jenis-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }

    @media (max-width: 720px) {
      .jenis-grid {
        grid-template-columns: 1fr;
      }
    }

    .jenis-card {
      display: flex;
      gap: 12px;
      align-items: center;
      padding: 14px;
      border-radius: 12px;
      border: 2px solid #e6eef6;
      cursor: pointer;
      transition: all .2s ease;
      background: linear-gradient(180deg,#fff,#f9fcff);
    }

    .jenis-card:hover {
      border-color: var(--blue);
      transform: translateY(-2px);
      box-shadow: 0 10px 22px rgba(59,174,231,.18);
    }

    .jenis-card.active {
      border-color: var(--blue-dark);
      background: linear-gradient(135deg,#e9f6ff,#f4fbff);
    }

    .jenis-card .icon {
      font-size: 28px;
    }

    .jenis-card .title {
      font-weight: 700;
      font-size: 14px;
    }

    .jenis-card .desc {
      font-size: 12px;
      color: var(--muted);
    }
    .jurusan-notice{
      margin-top:10px;
      padding:12px 14px;
      border-radius:10px;
      background:linear-gradient(135deg,#fff7ed,#ffedd5);
      border:1px solid #fed7aa;
      color:#9a3412;
      font-size:13px;
      font-weight:600;
      display:flex;
      align-items:center;
      gap:8px;
      animation: fadeSlide .25s ease;
    }

    @keyframes fadeSlide{
      from{opacity:0;transform:translateY(-6px)}
      to{opacity:1;transform:translateY(0)}
    }

    .summary{
      margin-bottom:10px;
    }

    /* kartu jurusan disabled */
    .jenis-card.disabled{
      opacity:.45;
      cursor:not-allowed;
      pointer-events:none;
    }


    /* ================= NOTIFICATION ================= */
    .notif{
      position:fixed;
      top:24px;
      right:24px;
      min-width:260px;
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
    /* ================= MODAL ================= */
    .modal-overlay{
      position:fixed;
      inset:0;
      background:rgba(15,23,42,.45);
      display:none;
      align-items:center;
      justify-content:center;
      z-index:9998;
    }

    .modal-overlay.show{
      display:flex;
    }

    .modal-card{
      background:white;
      width:100%;
      max-width:380px;
      border-radius:16px;
      padding:20px;
      box-shadow:0 20px 40px rgba(0,0,0,.2);
      animation: scaleIn .2s ease;
    }

    .modal-card h3{
      margin:0 0 8px;
      font-size:18px;
    }

    .modal-card p{
      font-size:14px;
      color:#475569;
      margin-bottom:18px;
    }

    .modal-actions{
      display:flex;
      gap:10px;
      justify-content:flex-end;
    }

    @keyframes scaleIn{
      from{transform:scale(.95);opacity:0}
      to{transform:scale(1);opacity:1}
    }
    /* ===================== FIX PLACEHOLDER HP ===================== */
    @media (max-width: 480px) {
      input::placeholder,
      textarea::placeholder {
        font-size: 12px;        /* lebih kecil */
        line-height: 1.2;
        opacity: 0.75;
      }
    }
    
    
    </style>
    </head>
    <body>
      <div class="wrap">
        <div class="topbar">
          <div class="brand">
            <div class="logo"><img src="{{ asset('img/logo.png') }}" alt="Logo SMKN 1 Cibinong" class="logo" /></div>
            <div>
              <h1>Inventaris Sarpras</h1>
              <p>SMKN 1 Cibinong — Formulir Peminjaman</p>
            </div>
          </div>
        </div>
        
        <div class="grid">
          <div class="card">
            <form id="formPeminjaman" method="POST" action="{{ route('peminjaman.store') }}">
              @csrf
              
              <div class="row">
              <div class="col">
                <label>Guru Penanggung Jawab</label>
                <input type="text" value="{{ auth()->user()->nama }}" readonly>
                <input type="hidden" name="id_guru" value="{{ auth()->user()->id_user }}">
              </div>
            </div>
              <div class="row">
                <div class="col">
                  <label for="nis">NIS Siswa</label>
                  <div style="display:flex;gap:8px">
                    <input type="text" id="nis" name="nis" placeholder="Masukkan NIS" required/>
                    <button type="button" id="isiDataBtn" class="btn btn-ghost">Isi Data</button>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <label>Nama Siswa</label>
                  <input type="text" id="nama" name="nama" readonly />
                </div>
                <div class="col">
                  <label>Kelas</label>
                  <input type="text" id="kelas" name="kelas" readonly />
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <label>No Whatsapp Siswa (opsional)</label>
                  <input type="text" id="no_wa" name="no_siswa" placeholder="No Whatsapp (08123...)" />
                  <div id="waStatus" style="
                    margin-top:6px;
                    font-size:13px;
                    font-weight:600;
                    display:none;
                  "></div>
                </div>
              </div>
              <div class="jenis-wrapper">
      <label>Kategori Ruangan</label>
      <input type="hidden" name="kategori_ruangan" id="kategoriRuanganInput">

      <div class="jenis-grid">
        <div class="jenis-card" data-kategori="teori">
          <div class="icon">🏫</div>
          <div>
            <div class="title">Ruang Teori</div>
            <div class="desc">Teori 1 – 26</div>
          </div>
        </div>

        <div class="jenis-card" data-kategori="lab">
          <div class="icon">🧪</div>
          <div>
            <div class="title">Laboratorium</div>
            <div class="desc">Lab khusus</div>
          </div>
        </div>

        <div class="jenis-card" data-kategori="lainnya">
          <div class="icon">📍</div>
          <div>
            <div class="title">Lainnya</div>
            <div class="desc">Isi manual</div>
          </div>
        </div>
      </div>
    </div>

    <div style="position:relative;margin-bottom:12px">
      <label>Ruangan / Tempat</label>
<input
  type="text"
  id="ruanganInput"
  placeholder="Pilih kategori ruangan"
  autocomplete="off"
  required
>

<input
  type="hidden"
  id="tempatIdInput"
  name="tempat_id"  
  >
  
      <div id="ruanganDropdown" class="dropdown" style="display:none"></div>
    </div>

    <div class="jenis-wrapper">
      <label>Jenis Mata Pelajaran</label>
      <input type="hidden" id="jenisMapel" name="jenis_mapel" />

      
      <div class="jenis-grid">
        <div class="jenis-card" data-value="jurusan">
          <div class="icon">🏫</div>
          <div>
            <div class="title">Mapel Jurusan</div>
            <div class="desc">Sesuai jurusan siswa</div>
          </div>
        </div>
        
        <div class="jenis-card" data-value="umum">
          <div class="icon">📘</div>
          <div>
            <div class="title">Mapel Umum</div>
            <div class="desc">Berlaku semua jurusan</div>
          </div>
        </div>
        
        <div class="jenis-card" data-value="ekskul">
          <div class="icon">⚽</div>
          <div>
            <div class="title">Ekskul / Kegiatan</div>
            <div class="desc">Non mata pelajaran</div>
          </div>
        </div>
      </div>
    </div>

    <div id="jurusanNotice" class="jurusan-notice" style="display:none">
      ⚠️ Siswa ini tidak memiliki jurusan, sehingga tidak dapat memilih Mapel Jurusan.
    </div>
    <div style="position:relative;margin-bottom:12px">
      <label>Mata Pelajaran</label>
                <input type="text" id="mapel" name="mapel" placeholder="Ketik mapel..." autocomplete="off" required>
                <input type="hidden" id="id_mapel" name="id_mapel">
                <div id="mapelDropdown" class="dropdown" style="display:none"></div>
              </div>


              <div>
                <label>Deskripsi / Untuk Apa</label>
                <textarea name="deskripsi" placeholder="Jelaskan singkat tujuan penggunaan" required></textarea>
              </div>

              <hr style="margin:18px 0;border:none;border-top:1px solid #eef6fb" />

            <h3 style="margin:0 0 10px 0">Barang yang Dipinjam</h3>

    <div class="selected-items">
        @forelse($barangDipilih as $i => $item)

            @php
                $barangDB = \App\Models\Barang::find($item['id_barang']);
            @endphp

            <div class="item-card">
              @php
        $img = $barangDB && $barangDB->gambar
            ? public_path('gambar_barang/' . $barangDB->gambar)
            : null;
    @endphp

    <img
      src="{{ ($barangDB && $barangDB->gambar && file_exists($img))
            ? asset('gambar_barang/' . $barangDB->gambar)
            : asset('gambar_barang/default.png') }}"
      alt="{{ $item['nama_barang'] }}"
    >


                <div class="info">
                    <div class="name">{{ $item['nama_barang'] }}</div>
                    <div class="qty">Jumlah: {{ $item['jumlah'] }}</div>
                </div>

                <input type="hidden" name="barang[{{ $i }}][id_barang]" value="{{ $item['id_barang'] }}">
                <input type="hidden" name="barang[{{ $i }}][nama_barang]" value="{{ $item['nama_barang'] }}">
                <input type="hidden" name="barang[{{ $i }}][jumlah]" value="{{ $item['jumlah'] }}">
            </div>
        @empty
            <p class="muted">Tidak ada barang dipilih. <a href="/peminjaman/kategori">Pilih barang dulu</a></p>
        @endforelse
    </div>

    <hr style="margin:18px 0;border:none;border-top:1px solid #eef6fb">

<h3 style="margin-bottom:10px">Persetujuan Guru (Selfie)</h3>

<div style="display:flex;flex-direction:column;gap:10px">

    <video id="camera" autoplay
        style="width:260px;border-radius:12px;border:2px solid #e6eef6">
    </video>

    <button type="button" id="takePhoto" class="btn btn-ghost">
        📸 Ambil Selfie Guru
    </button>

    <canvas id="canvas" style="display:none"></canvas>

    <input type="hidden" name="foto_guru" id="fotoGuru">

    <img id="previewFoto"
        style="width:200px;border-radius:10px;display:none">

</div>


              <div class="actions">
                <button type="button" class="btn btn-ghost" onclick="history.back()">Kembali</button>
                <button type="button" id="submitBtn" class="btn btn-primary">
                  Kirim Peminjaman
                </button>

              </div>
            </form>
          </div>

          <aside class="card summary">
            <h3>Ringkasan</h3>
            <p class="muted">Pastikan NIS valid. Guru penanggung jawab otomatis dari akun yang login.</p>
            <div style="margin-top:14px">
              <strong>Tips cepat</strong>
              <ul class="muted" style="padding-left:18px;margin:8px 0">
                <li>Tekan input barang → tampil semua barang kategori</li>
                <li>Ketik untuk filter. Barang stok 0 tampil tapi disabled</li>
                <li>Barang yang sudah dipilih tidak muncul lagi</li>
              </ul>
            </div>
          </aside>
        </div>
      </div>
      <!-- MODAL KONFIRMASI -->
    <div id="confirmModal" class="modal-overlay">
      <div class="modal-card">
        <h3>Konfirmasi Peminjaman</h3>
        <p>Apakah data peminjaman sudah benar dan ingin dikirim?</p>

        <div class="modal-actions">
          <button id="cancelSubmit" class="btn btn-ghost">Batal</button>
          <button id="confirmSubmit" class="btn btn-primary">Ya, Kirim</button>
        </div>
      </div>
    </div>


      <!-- NOTIFICATION -->
    <div id="notif" class="notif"></div>

    <script>

      /* ================= SELFIE GURU ================= */

const video = document.getElementById("camera");
const canvas = document.getElementById("canvas");
const takePhotoBtn = document.getElementById("takePhoto");
const previewFoto = document.getElementById("previewFoto");
const fotoGuruInput = document.getElementById("fotoGuru");

// aktifkan kamera
if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {

    navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        video.srcObject = stream;
    })
    .catch(() => {
        showNotif('error', 'Kamera tidak bisa diakses');
    });

}

// ambil foto
takePhotoBtn.onclick = () => {

    const ctx = canvas.getContext("2d");

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    ctx.drawImage(video, 0, 0);

    const dataURL = canvas.toDataURL("image/png");

    fotoGuruInput.value = dataURL;

    previewFoto.src = dataURL;
    previewFoto.style.display = "block";

    showNotif('success', 'Selfie guru berhasil diambil');
};
      let kategoriRuangan = '';
    let ruanganList = [];

    const ruanganInput = document.getElementById('ruanganInput');
    const ruanganDropdown = document.getElementById('ruanganDropdown');
const tempatIdInput = document.getElementById('tempatIdInput'); // 🔥 PINDAH KE SINI

      const waInput = document.getElementById('no_wa');
    const waStatus = document.getElementById('waStatus');
  let mapelController = null;

    let waValid = false;
    let waTimeout = null;

    // auto cek saat user berhenti mengetik



    /* ================= STATE ================= */
    let jurusanSiswa = null;
    let mapelList = [];

    /* ================= ELEMENT ================= */
    const nisInput = document.getElementById('nis');
    const namaInput = document.getElementById('nama');
    const kelasInput = document.getElementById('kelas');
    const isiDataBtn = document.getElementById('isiDataBtn');

    const jenisMapel = document.getElementById('jenisMapel');
    const jenisCards = document.querySelectorAll('.jenis-card');


    const mapelInput = document.getElementById('mapel');
    const mapelDropdown = document.getElementById('mapelDropdown');
    const idMapelInput = document.getElementById('id_mapel');

    const jurusanNotice = document.getElementById('jurusanNotice');
    const cardJurusan = document.querySelector('.jenis-card[data-value="jurusan"]');


    jenisCards.forEach(card => {
      card.onclick = () => {
        if (card.classList.contains('disabled')) return;

        // reset tampilan aktif
        jenisCards.forEach(c => c.classList.remove('active'));
        card.classList.add('active');

        // set jenis
        jenisMapel.value = card.dataset.value;

        // 🔥 RESET DATA SEBELUM LOAD BARU

        // load sesuai jenis baru
        loadGuruDanMapel();
      };
    });

  let isGuruLoading = false;
  let isMapelLoading = false;

  function showLoading(dropdown) {
    dropdown.innerHTML = '<div class="item">⏳ Sedang mengambil data...</div>';
    dropdown.style.display = 'block';
  }

    /* ================= ISI DATA SISWA ================= */
    isiDataBtn.onclick = () => {
      const nis = nisInput.value.trim();
      if (!nis) {
        showNotif('error', 'Masukkan NIS terlebih dahulu');
        return;
      }

      fetch(`/api/siswa?nis=${nis}`)
        .then(r => {
          if (!r.ok) throw new Error();
          return r.json();
        })
        .then(d => {
          namaInput.value = d.nama;
          kelasInput.value = d.kelas;
          jurusanSiswa = d.id_jurusan;

          // reset
          jenisMapel.value = '';
          jenisCards.forEach(c => c.classList.remove('active'));
          mapelInput.value = '';
          idMapelInput.value = '';
          mapelDropdown.style.display = 'none';
          jurusanNotice.style.display = 'none';

          // disable jurusan
          if (!jurusanSiswa) {
            cardJurusan.classList.add('disabled');
          } else {
            cardJurusan.classList.remove('disabled');
          }
        })
        .catch(() => {
      // kosongkan data siswa
      namaInput.value = '';
      kelasInput.value = '';
      jurusanSiswa = null;

      showNotif('error', 'NIS tidak ditemukan');
    });


    };
function resetGuruMapel() {
  mapelInput.value = '';
  idMapelInput.value = '';

  mapelDropdown.innerHTML = '';
  mapelDropdown.style.display = 'none';

  mapelList = [];
}

    /* ================= LOAD DATA ================= */
    function loadGuruDanMapel() {
      
      if (jenisMapel.value === 'jurusan' && !jurusanSiswa) {
        jurusanNotice.style.display = 'flex';
        jenisMapel.value = '';
        return;
      }


      jurusanNotice.style.display = 'none';



  const currentJenis = jenisMapel.value;

  fetch(`/mapel-by-jenis?jenis=${currentJenis}&id_jurusan=${jurusanSiswa ?? ''}`)
    .then(r => r.json())
    .then(d => {
      // ❌ kalau user sudah ganti jenis → buang response
      if (jenisMapel.value !== currentJenis) return;

      mapelList = Array.isArray(d) ? d : [];
    });

    }



    /* ================= FILTER MAPEL ================= */
    mapelInput.oninput = () => {
      const q = mapelInput.value.toLowerCase();
      const filtered = mapelList.filter(m =>
        m.nama.toLowerCase().includes(q)
      );
      renderMapel(filtered);
    };

  mapelInput.onfocus = () => {
    if (isMapelLoading) {
      showLoading(mapelDropdown);
      return;
    }

    if (!mapelList.length) {
      mapelDropdown.innerHTML = '<div class="item">Tidak ada data / belum dipilih</div>';
      mapelDropdown.style.display = 'block';
      return;
    }

    renderMapel(mapelList);
  };

    /* ================= RENDER ================= */

    function renderMapel(list) {
      mapelDropdown.innerHTML = '';
      if (!list.length) {
        mapelDropdown.innerHTML = '<div class="item">Tidak ditemukan</div>';
      }

      list.forEach(m => {
        const el = document.createElement('div');
        el.className = 'item';
        el.textContent = m.nama;
        el.onclick = () => {
          mapelInput.value = m.nama;
          idMapelInput.value = m.id_mapel;
          mapelDropdown.style.display = 'none';
        };
        mapelDropdown.appendChild(el);
      });

      mapelDropdown.style.display = 'block';
    }

    /* ================= CLOSE DROPDOWN ================= */
    document.addEventListener('click', e => {
      if (!mapelInput.parentElement.contains(e.target)) {
        mapelDropdown.style.display = 'none';
      }
    });

    const form = document.getElementById('formPeminjaman');
    const submitBtn = document.getElementById('submitBtn');
    const notif = document.getElementById('notif');

    function showNotif(type, message){
      notif.className = `notif ${type} show`;
      notif.textContent = message;

      setTimeout(() => {
        notif.classList.remove('show');
      }, 3000);
    }

    /* ================= SUBMIT & MODAL (FINAL FIX) ================= */
    const modal = document.getElementById('confirmModal');
    const confirmBtn = document.getElementById('confirmSubmit');
    const cancelBtn = document.getElementById('cancelSubmit');

    // Klik "Kirim Peminjaman"
    submitBtn.onclick = () => {
      if (!nisInput.value.trim()) {
        showNotif('error', 'NIS belum diisi');
        return;
      }
      if (!fotoGuruInput.value) {
    showNotif('error', 'Selfie guru wajib diambil');
    return;
}
// VALIDASI RUANGAN
if (!tempatIdInput.value) {
  showNotif('error', 'Ruangan harus dipilih dari daftar');
  return;
}

document.addEventListener('click', e => {
  if (!mapelInput.parentElement.contains(e.target)) {
    mapelDropdown.style.display = 'none';
  }
});


  // ❌ mapel wajib dari database
  const mapelValid = mapelList.some(
    m => String(m.id_mapel) === String(idMapelInput.value)
  );

  if (!mapelValid) {
    showNotif('error', 'Mata pelajaran tidak valid');
    return;
  }



      if (!namaInput.value.trim() || !kelasInput.value.trim()) {
        showNotif('error', 'Klik "Isi Data" terlebih dahulu');
        return;
      }

      if (!jenisMapel.value) {
        showNotif('error', 'Pilih jenis mata pelajaran');
        return;
      }

      modal.classList.add('show');
    };

    // Klik "Batal"
    cancelBtn.onclick = () => {
      modal.classList.remove('show');
      showNotif('info', 'Pengiriman dibatalkan');
    };

    // Klik "Ya, Kirim"
    confirmBtn.onclick = () => {
      modal.classList.remove('show');

      submitBtn.disabled = true;
      submitBtn.textContent = 'Mengirim...';

      form.submit();
    };

    // Klik area gelap
    modal.addEventListener('click', e => {
      if (e.target === modal) modal.classList.remove('show');
    });


function resetRuangan() {
  ruanganInput.value = '';
  tempatIdInput.value = '';
  ruanganList = [];
  ruanganDropdown.style.display = 'none';
}



    document.querySelectorAll('.jenis-card[data-kategori]').forEach(card => {
      card.onclick = () => {
        document
          .querySelectorAll('.jenis-card[data-kategori]')
          .forEach(c => c.classList.remove('active'));

        card.classList.add('active');

        kategoriRuangan = card.dataset.kategori;

         resetRuangan();

        // ===============================
        // TEORI / LAB → WAJIB DARI DB
        // ===============================
        ruanganInput.placeholder = 'Pilih ruangan dari daftar';

        fetch(`/tempat-by-kategori?kategori=${kategoriRuangan}`)
          .then(r => r.json())
          .then(d => {
            ruanganList = Array.isArray(d) ? d : [];
            renderRuangan(ruanganList);
          });
      };
    });
    ruanganInput.oninput = debounce(() => {
      const q = ruanganInput.value.toLowerCase();

      const filtered = ruanganList.filter(r =>
        r.nama.toLowerCase().includes(q)
      );

      if (!filtered.length) {
        ruanganDropdown.innerHTML =
          '<div class="item">Tidak ditemukan</div>';
        ruanganDropdown.style.display = 'block';
        return;
      }

      renderRuangan(filtered);
    }, 400);


    function debounce(fn, delay = 400) {
      let timeout;
      return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, args), delay);
      };
    }

    function renderRuangan(list) {
      ruanganDropdown.innerHTML = '';

      if (!list.length) {
        ruanganDropdown.innerHTML =
          '<div class="item">Tidak ditemukan</div>';
      }

      list.forEach(r => {
        const el = document.createElement('div');
        el.className = 'item';
        el.textContent = r.nama;
el.onclick = () => {
  ruanganInput.value = r.nama;        // tampil ke user
  tempatIdInput.value = r.id;         // dikirim ke backend
  ruanganDropdown.style.display = 'none';
};

        ruanganDropdown.appendChild(el);
      });

      ruanganDropdown.style.display = 'block';
    }
    document.addEventListener('click', e => {
      if (!ruanganInput.parentElement.contains(e.target)) {
        ruanganDropdown.style.display = 'none';
      }
    });

    ruanganInput.onfocus = () => {
      if (kategoriRuangan === 'lainnya') return;
      renderRuangan(ruanganList);
    };


    </script>




    </body>
    </html>
