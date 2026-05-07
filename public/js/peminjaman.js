const dataBarang = {
  "Elektronik": [
    { id: 1, name: "Proyektor", stok: 5 },
    { id: 2, name: "Laptop", stok: 10 },
    { id: 3, name: "Mic", stok: 7 }
  ],
  "Kesenian": [
    { id: 1, name: "Cat Air", stok: 20 },
    { id: 2, name: "Kuass", stok: 15 }
  ],
  "Olahraga": [
    { id: 1, name: "Bola", stok: 12 },
    { id: 2, name: "Net", stok: 5 }
  ],
  "Aula": [{ id: 1, name: "Aula 1" }, { id: 2, name: "Aula 2" }],
  "Kelas Teori": [{ id: 1, name: "Teori 1" }, { id: 2, name: "Teori 2" }],
  "Lab": [{ id: 1, name: "Lab Kimia" }, { id: 2, name: "Lab Fisika" }],
  "Lapangan": [{ id: 1, name: "Lapangan Utama" }, { id: 2, name: "Lapangan Atas" }]
};
    const kategoriList = [
      { id: 1, name: "Elektronik", desc: "Barang-barang seperti proyektor, mic, dan laptop.", image: "elektronik.png" },
      { id: 2, name: "Kesenian", desc: "Alat untuk menggambar dan melukis.", image: "kesenian.png" },
      { id: 3, name: "Olahraga", desc: "Peralatan olahraga sekolah.", image: "olahraga.png" },
      { id: 4, name: "Aula", desc: "Tempat kegiatan sekolah (Aula 1 dan Aula 2).", image: "aula.png" },
      { id: 5, name: "Kelas Teori", desc: "Ruangan teori 1-26.", image: "kelas.png" },
      { id: 6, name: "Lab", desc: "Lab Bahasa, Kimia, dan Fisika.", image: "lab.png" },
      { id: 7, name: "Lapangan", desc: "Lapangan utama, atas, dan voli.", image: "lapangan.png" },
    ];

    const grid = document.getElementById("kategoriGrid");

    kategoriList.forEach(kategori => {
      const card = document.createElement("div");
      card.className = "card";
      card.innerHTML = `
        <img src="../img/${kategori.image}" alt="${kategori.name}">
        <div class="overlay">
          <h2>${kategori.name}</h2>
          <p>${kategori.desc}</p>
          <button class="pinjamBtn">Pinjam</button>
        </div>
      `;
      card.querySelector(".pinjamBtn").addEventListener("click", (e) => {
        e.stopPropagation();
        sessionStorage.setItem("kategoriDipilih", kategori.name);
        window.location.href = "../form/form.html";
      });
      grid.appendChild(card);
    });