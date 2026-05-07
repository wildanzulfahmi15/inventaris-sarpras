@extends('layouts.guru')

@section('title','Pilih Barang')

@section('content')



<style>


@keyframes fadeBg {
    0% { background-position: left; }
    100% { background-position: right; }
}

.page-container {
    width: 100%;

}

/* ===================== HEADER ===================== */
.header {
    background: rgba(255,255,255,0.55);
    padding: 18px;
    border-radius: 16px;
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 22px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
}

.header h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.header p {
    margin: 5px 0 0 0;
    color: #0b4a82;
}

/* Tombol Kembali */
.btn-back {
    padding: 10px 16px;
    border-radius: 12px;
    background: #d9ebff;
    border: none;
    font-weight: 600;
    cursor: pointer;
    color: #0b65c2;
    transition: .2s;
    box-shadow: 0 5px 12px rgba(0,0,0,0.08);
}
.btn-back:hover {
    background: #c9e2ff;
    transform: translateY(-2px);
}

/* HP */
@media(max-width: 480px) {
    .header { text-align: center; }
    .btn-back { width: 100%; }
}

/* ===================== GRID ===================== */
.grid {
    width:100%;
    margin-top: 25px;
    display: grid;
    gap: 22px;
     grid-template-columns: repeat(auto-fill, minmax(200px,1fr));
    padding-bottom:120px;
}


/* ===================== CARD ===================== */
.card {
    background: rgba(255,255,255,0.65);
    border-radius: 16px;
    padding: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    backdrop-filter: blur(10px);
    transition: .25s ease;
}

.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 35px rgba(0,0,0,0.15);
}

.card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 12px;
}

.name { font-size: 17px; font-weight: 600; margin-bottom: 6px; }
.stok { font-size: 13px; color: #57738f; margin-bottom: 12px; }

/* Counter */
.counter {
    display: flex;
    align-items: center;
    gap: 12px;
}

.counter-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    border: none;
    background: #339de8;
    color: white;
    font-size: 20px;
    cursor: pointer;
    transition: .2s;
}
.btn:hover { background: #1c8bdd; }

.btn:disabled {
    background: #a9d3ee;
    cursor: not-allowed;
}

.qtyInput {
    width: 55px;
    padding: 6px;
    border-radius: 10px;
    border: 1px solid #d0d7e2;
    text-align: center;
    font-size: 15px;
}

/* ===================== SIDEBAR BARU (SLIDE PANEL) ===================== */

.side-wrapper {
    position: fixed;
    top: 80px; 
    right: -450px; 
    width: 320px;
    height: calc(100vh - 100px);
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    padding: 20px;
    border-radius: 16px 0 0 16px;
    box-shadow: -10px 0 25px rgba(0,0,0,0.15);
    transition: 0.35s;
    overflow-y: auto;
    z-index: 999;
}

.side-wrapper.active {
    right: 20px;
}

/* Tombol floating keranjang — tampil di PC & HP */
.cart-float-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #0ea5e9;
    color: white;
    border-radius: 50px;
    padding: 14px 22px;
    font-weight: 700;
    border: none;
    box-shadow: 0 10px 20px rgba(14,165,233,0.4);
    cursor: pointer;
    z-index: 9999;
    display: block; 
}
/* Update bagian media query HP di CSS Anda */

@media(max-width: 768px){
    /* Tombol melayang naik sedikit di atas bottom bar */
    .cart-float-btn {
        bottom: 65px !important; 
        right: 20px;
        z-index: 9999;
    }

    /* Padding bawah grid ditambah supaya card paling bawah 
       bisa di-scroll sampai kelihatan semua tanpa tertutup tombol */
    .grid {
        padding-bottom: 150px !important; 
    }

    /* Sidebar/Drawer Keranjang tetap mulai dari bawah */
    .side-wrapper {
        bottom: -100%;
        height: 60vh;
    }
}

/* Khusus layar sangat kecil */
@media(max-width: 480px){
    .cart-float-btn {
        bottom: 100px !important;
        right: 15px;
        padding: 12px 18px;
        font-size: 14px;
    }
}
/* HP bottom drawer */
@media(max-width: 768px){
    .side-wrapper{
        width: 100%;
        right: 0;
        left: 0;
        bottom: -100%;
        top: auto;
        height: 55%;
        border-radius: 16px 16px 16px 16px;
        
    }

    .side-wrapper.active{
        bottom: 0;
    }
}

/* Tombol close */
.close-side {
    background: rgba(255,255,255,0.7);
    padding: 10px 14px;
    border-radius: 50%;
    font-size: 18px;
    border: 1px solid #d8e3ef;
    float: right;
    cursor: pointer;
}

/* Overlay */
.cart-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.25);
    z-index: 998;
    display: none;
}
.cart-overlay.active {
    display: block;
}

.list-item {
    padding: 8px 0;
    border-bottom: 1px solid #e4ecf5;
    font-size: 14px;
    color: #0e426a;
}

.lanjut {
    margin-top: 14px;
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #34b5ff, #0ea5e9);
    color: white;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
}
.lanjut:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(14,165,233,0.4);
}
/* ===================== SEARCH BAR ===================== */
.search-box {
    margin-top: 20px;
    margin-bottom: 10px;
    display: flex;
    justify-content: flex-start;
}

#searchInput {
    width: 320px;
    max-width: 90%;
    padding: 12px 18px;
    border-radius: 14px;
    border: 2px solid #d4eaff;
    font-size: 15px;
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(8px);
    transition: .25s;
    box-shadow: 0 5px 14px rgba(0,0,0,0.07);
}

#searchInput:focus {
    border-color: #34b5ff;
    box-shadow: 0 0 0 5px rgba(52,181,255,0.25);
    outline: none;
    background: white;
}
/* ===================== PERBAIKAN HP ===================== */
@media(max-width: 480px){

    /* Grid lebih rapat */
    .grid {
        gap: 14px;
        grid-template-columns: repeat(2, 1fr);
    }

    /* Card diperkecil */
    .card {
        padding: 10px;
        border-radius: 12px;
    }

    /* Gambar diperkecil */
    .card img {
        height: 110px;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    /* Teks nama barang */
    .name {
        font-size: 14px;
        margin-bottom: 4px;
    }

    /* Stok */
    .stok {
        font-size: 12px;
        margin-bottom: 10px;
    }

    /* Counter lebih kecil */
    .counter {
        gap: 8px;
    }

    .counter-btn {
        width: 28px;
        height: 28px;
        font-size: 16px;
        border-radius: 8px;
    }

    .qtyInput {
        width: 40px;
        padding: 4px;
        font-size: 13px;
    }

    /* Floating cart sedikit dikecilkan */
    .cart-float-btn {
        padding: 10px 16px;
        font-size: 14px;
        border-radius: 40px;
    }
}

/* ===================== PERBAIKAN HP ===================== */
@media(max-width: 768px){
    .side-wrapper{
        width: 100vw !important;      /* Full layar */
        max-width: 100vw !important;
        right: 0 !important;
        left: 0 !important;

        bottom: -100%;
        top: auto;

        height: 60vh;                 /* Tinggi lebih enak untuk HP */
        padding: 20px;

        border-radius: 20px 20px 0 0; /* Sudut atas membulat */
        overflow-y: auto;
        overflow-x: hidden;           /* ← mencegah kepotong horizontal */

        box-sizing: border-box;       /* ← mencegah padding membuat overflow */
    }

    .side-wrapper.active{
        bottom: 0 !important;
    }

    /* Perbaiki konten di dalam panel */
    #list {
        padding-right: 10px;          /* space agar scroll tidak kepotong */
    }
}

/* HP ekstra kecil */
@media(max-width: 480px){
    .cart-float-btn {
        bottom: 15px;
        right: 15px;
        padding: 10px 16px;
        font-size: 14px;
    }
}
/* ===================== FIX FLOATING BUTTON NUTUP KONTEN ===================== */
/* ===================== FIX FLOATING BUTTON NUTUP CARD (FINAL) ===================== */
@media (max-width: 768px) {
    .grid {
        padding-bottom: 55px; /* ruang aman di bawah card */
    }
}
/* ===================== KATEGORI FILTER ===================== */

.kategori-bar{
    margin-top:20px;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    justify-content:flex-start;
}

.kategori-btn{
    padding:8px 16px;
    border:none;
    border-radius:20px;
    background:#d8ecff;
    color:#0b4a82;
    cursor:pointer;
    font-weight:600;
    transition:.2s;
}

.kategori-btn:hover{
    background:#c6e4ff;
}

.kategori-btn.active{
    background:#0ea5e9;
    color:white;
}


</style>

<div class="page-container">


    <!-- SEARCH BAR -->
<div class="search-box">
    <input type="text" id="searchInput" placeholder="Cari barang..." />
</div>

<div class="kategori-bar" id="kategoriBar">
    <button class="kategori-btn active" data-kategori="all">Semua</button>

    @foreach($kategori as $k)
        <button class="kategori-btn" data-kategori="{{ $k->id_kategori }}">
            {{ $k->nama_kategori }}
        </button>
    @endforeach
</div>

    <!-- GRID -->
    <div class="grid">

    @foreach($kategori as $k)
        @foreach($k->barang as $b)
    <div class="card"
        data-id="{{ $b->id_barang }}"
        data-nama="{{ $b->nama_barang }}"
        data-stok="{{ $b->stok }}"
        data-kategori="{{ $k->id_kategori }}">

            @php
                $path = public_path('gambar_barang/' . $b->gambar);
            @endphp

            <img
                src="{{ (!empty($b->gambar) && file_exists($path))
                    ? asset('gambar_barang/' . $b->gambar)
                    : asset('gambar_barang/default.png')
                }}"
                alt="{{ $b->nama_barang }}"
            >


            <div class="name">{{ $b->nama_barang }}</div>
            <div class="stok">Stok: {{ $b->stok }}</div>

            <div class="counter">
                <button class="counter-btn minus" disabled>-</button>
                <input type="number" class="qtyInput" value="0" min="0">
                <button class="counter-btn plus">+</button>
            </div>
        </div>
        @endforeach
        @endforeach
</div>

<!-- Floating Button -->
<button class="cart-float-btn" id="openCartBtn">
    Keranjang (<span id="countCart">0</span>)
</button>

<!-- Overlay -->
<div class="cart-overlay" id="cartOverlay"></div>

<!-- SIDEBAR -->
<div class="side-wrapper" id="sidebarCart">
    <button class="close-side" id="closeCartBtn">✕</button>

    <h3 style="margin-top:20px;">Barang Dipilih</h3>
    <div id="list"></div>

    <form action="{{ route('peminjaman.formBarang') }}" method="POST">
        @csrf
        <input type="hidden" name="kategori" value="{{ $kategori }}">
        <div id="inputBarang"></div>
        <button class="lanjut" type="submit">Lanjut ke Form</button>
    </form>
</div>


<script>
const kategoriBtns = document.querySelectorAll(".kategori-btn");

kategoriBtns.forEach(btn => {

    btn.addEventListener("click", () => {

        kategoriBtns.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        const kategori = btn.dataset.kategori;

        cards.forEach(card => {

            if (kategori === "all") {
                card.style.display = "block";
            } else {

                if (card.dataset.kategori === kategori) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }

            }

        });

    });

});

let keranjang = @json($barangDipilih ?? []);

const cards = document.querySelectorAll(".card");
const list = document.getElementById("list");
const inputBarang = document.getElementById("inputBarang");
const btnLanjut = document.querySelector(".lanjut");
const sidebarCart = document.getElementById("sidebarCart");
const openBtn = document.getElementById("openCartBtn");
const closeBtn = document.getElementById("closeCartBtn");
const overlay = document.getElementById("cartOverlay");
const countCart = document.getElementById("countCart");

cards.forEach(card => {
    const id = card.dataset.id;
    const nama = card.dataset.nama;
    const stok = parseInt(card.dataset.stok);

    const input = card.querySelector(".qtyInput");
    const plus = card.querySelector(".plus");
    const minus = card.querySelector(".minus");

    plus.addEventListener("click", () => {
        let qty = Number(input.value);
        if (qty < stok) qty++;
        input.value = qty;
        updateCart(id, nama, qty);
        minus.disabled = qty === 0;
    });

    minus.addEventListener("click", () => {
        let qty = Number(input.value);
        if (qty > 0) qty--;
        input.value = qty;
        updateCart(id, nama, qty);
        minus.disabled = qty === 0;
    });

    input.addEventListener("input", () => {
        let qty = Number(input.value);
        if (qty < 0 || isNaN(qty)) qty = 0;
        if (qty > stok) qty = stok;
        input.value = qty;
        updateCart(id, nama, qty);
        minus.disabled = qty === 0;
    });
});

function updateCart(id, nama, qty) {
    if (qty === 0) {
        delete keranjang[id];
    } else {
        keranjang[id] = {
            id_barang: id,
            nama_barang: nama,
            jumlah: qty
        };
    }
    render();
}

function render() {
    list.innerHTML = "";
    inputBarang.innerHTML = "";

    let i = 0;
    for (const item of Object.values(keranjang)) {
        list.innerHTML += `
            <div class="list-item">
                ${item.nama_barang} — ${item.jumlah}
            </div>
        `;

        inputBarang.innerHTML += `
            <input type="hidden" name="barang[${i}][id_barang]" value="${item.id_barang}">
            <input type="hidden" name="barang[${i}][nama_barang]" value="${item.nama_barang}">
            <input type="hidden" name="barang[${i}][jumlah]" value="${item.jumlah}">
        `;
        i++;
    }

    updateCartCount();
    cekKeranjangKosong();
}

function cekKeranjangKosong() {
    if (Object.keys(keranjang).length === 0) {
        btnLanjut.disabled = true;
        btnLanjut.style.opacity = "0.5";
        btnLanjut.style.cursor = "not-allowed";
    } else {
        btnLanjut.disabled = false;
        btnLanjut.style.opacity = "1";
        btnLanjut.style.cursor = "pointer";
    }
}

function updateCartCount(){
    countCart.textContent = Object.keys(keranjang).length;
}

openBtn.addEventListener("click", () => {
    sidebarCart.classList.add("active");
    overlay.classList.add("active");
});

closeBtn.addEventListener("click", () => {
    sidebarCart.classList.remove("active");
    overlay.classList.remove("active");
});

overlay.addEventListener("click", () => {
    sidebarCart.classList.remove("active");
    overlay.classList.remove("active");
});
// ===================== SEARCH FUNCTION =====================
const searchInput = document.getElementById("searchInput");

searchInput.addEventListener("input", function () {
    const keyword = this.value.toLowerCase();
    
    cards.forEach(card => {
        const name = card.dataset.nama.toLowerCase();

        if (name.includes(keyword)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
});

render();

</script>


@endsection