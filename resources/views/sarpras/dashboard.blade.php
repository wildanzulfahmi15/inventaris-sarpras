@extends('layouts.sarpras')

@section('title', 'Dashboard Sarpras')

@section('content')

<style>
/* =======================
   DASHBOARD SARPRAS FINAL
======================= */

.dashboard-wrapper{
    max-width:1200px;
    margin:auto;
    padding:clamp(16px,3vw,28px);
    
}

/* ===== TITLE ===== */
.dashboard-title{
    font-weight:900;
    font-size:clamp(1.5rem,4vw,2rem);
    color:#1e3a8a;
    text-align:center;
    margin-bottom:26px;
}

/* ======================
   STATS
====================== */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:16px;
    margin-bottom:32px;
}

.stat-card{
    background:linear-gradient(135deg,#e6f0ff,#f8fbff);
    border-radius:20px;
    padding:20px 16px;
    box-shadow:0 12px 28px rgba(0,80,160,.15);
    text-align:center;
}

.stat-number{
    font-size:clamp(26px,6vw,34px);
    font-weight:900;
    color:#1e3a8a;
}

.stat-label{
    font-size:13px;
    color:#475569;
}

/* ======================
   QUICK MENU
====================== */
.quick-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:18px;
    margin-bottom:40px;
}

.quick-grid a{ text-decoration:none; }

.quick-card{
    background:#fff;
    border-radius:22px;
    padding:22px;
    box-shadow:0 14px 30px rgba(0,50,150,.12);
    min-height:150px;
    display:flex;
    align-items:center;
    gap:18px;
}

.quick-icon{
    width:54px;
    height:54px;
    border-radius:18px;
    background:#e0ecff;
    color:#2563eb;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
}

.quick-title{
    font-weight:800;
    color:#1e3a8a;
    font-size:16px;
}

.quick-desc{
    font-size:13px;
    color:#64748b;
}

/* ======================
   CHART CARD
====================== */
.chart-box{
    background:#ffffff;
    padding:22px;
    border-radius:22px;
    box-shadow:0 14px 30px rgba(0,50,150,.12);
}

.chart-box h5{
    font-weight:800;
    color:#1e3a8a;
    text-align:center;
}

.chart-subtitle{
    text-align:center;
    font-size:13px;
    color:#64748b;
    margin-bottom:14px;
}

.chart-box canvas{
    width:100%!important;
    height:300px!important;
}

/* ======================
   LEGEND OUTSIDE CHART
====================== */
.chart-legend-outside{
    margin-top:18px;
    display:flex;
    justify-content:center;
}

.chart-legend-inner{
    display:grid;
    grid-template-columns:repeat(4,auto);
    gap:18px 32px;
}

.chart-item{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:14px;
    font-weight:700;
    color:#1f2937;
}

.chart-item span{
    width:14px;
    height:14px;
    border-radius:4px;
}

.c-blue{ background:#2563eb; }
.c-cyan{ background:#0ea5e9; }
.c-indigo{ background:#6366f1; }
.c-green{ background:#22c55e; }

/* ======================
   MOBILE
====================== */
@media(max-width:480px){

    .stats-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .quick-grid{
        grid-template-columns:1fr;
    }

    .chart-box{
        padding:16px;
    }

    .chart-box canvas{
        height:240px!important;
    }

    .chart-legend-inner{
        grid-template-columns:1fr 1fr;
        gap:12px;
    }

    .chart-item{
        font-size:13px;
    }

    .stat-number{
        font-size:22px;
    }

    .stat-label{
        font-size:11px;
    }
}
@media(max-width:480px){

    .stats-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .quick-grid{
        grid-template-columns:1fr;
    }

    .chart-box{
        padding:16px;
    }

    .chart-box canvas{
        height:240px!important;
    }

    .chart-legend-inner{
        grid-template-columns:1fr 1fr;
        gap:10px;
    }

    /* 🔽 DIPERKECIL LAGI */
    .chart-item{
        font-size:11px;
        font-weight:600;
        gap:6px;
    }

    .chart-item span{
        width:10px;
        height:10px;
    }

    .stat-number{
        font-size:20px;
    }

    .stat-label{
        font-size:10px;
    }
}

</style>

<div class="dashboard-wrapper">

    <h1 class="dashboard-title">Dashboard Sarpras</h1>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $menungguPeminjaman }}</div>
            <div class="stat-label">Menunggu Peminjaman</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $menungguPengembalian }}</div>
            <div class="stat-label">Menunggu Pengembalian</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $sedangDipinjam }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $selesai }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>

    <!-- QUICK -->
    <div class="quick-grid">
        <a href="{{ route('sarpras.peminjaman') }}">
            <div class="quick-card">
                <div class="quick-icon"><i class="fa fa-box"></i></div>
                <div>
                    <div class="quick-title">Konfirmasi Peminjaman</div>
                    <div class="quick-desc">Permintaan peminjaman barang</div>
                </div>
            </div>
        </a>

        <a href="{{ route('sarpras.pengembalian') }}">
            <div class="quick-card">
                <div class="quick-icon"><i class="fa fa-rotate-left"></i></div>
                <div>
                    <div class="quick-title">Konfirmasi Pengembalian</div>
                    <div class="quick-desc">Barang yang dikembalikan</div>
                </div>
            </div>
        </a>

        <a href="{{ route('sarpras.riwayat') }}">
            <div class="quick-card">
                <div class="quick-icon"><i class="fa fa-clock-rotate-left"></i></div>
                <div>
                    <div class="quick-title">Riwayat</div>
                    <div class="quick-desc">Riwayat transaksi</div>
                </div>
            </div>
        </a>
    </div>

    <!-- CHART -->
    <div class="chart-box">
        <h5>Statistik Aktivitas</h5>
        <div class="chart-subtitle">Ringkasan status peminjaman</div>
        <canvas id="chartBar"></canvas>
    </div>

    <!-- LEGEND + ANGKA (DI LUAR CARD) -->
    <div class="chart-legend-outside">
        <div class="chart-legend-inner">
            <div class="chart-item"><span class="c-blue"></span> Menunggu: {{ $menungguPeminjaman }}</div>
            <div class="chart-item"><span class="c-cyan"></span> Pengembalian: {{ $menungguPengembalian }}</div>
            <div class="chart-item"><span class="c-indigo"></span> Dipinjam: {{ $sedangDipinjam }}</div>
            <div class="chart-item"><span class="c-green"></span> Selesai: {{ $selesai }}</div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('chartBar'),{
    type:'bar',
    data:{
        labels:['Menunggu','Pengembalian','Dipinjam','Selesai'],
        datasets:[{
            data:[
                {{ $menungguPeminjaman }},
                {{ $menungguPengembalian }},
                {{ $sedangDipinjam }},
                {{ $selesai }}
            ],
            backgroundColor:[
                '#2563eb',
                '#0ea5e9',
                '#6366f1',
                '#22c55e'
            ],
            borderRadius:14,
            maxBarThickness:42
        }]
    },
options:{
    responsive:true,
    maintainAspectRatio:false,
    plugins:{ legend:{ display:false } },
    scales:{
        x:{
            display:false,          // ❌ hilangkan teks kategori
            grid:{ display:false }  // ❌ hilangkan garis vertikal
        },
        y:{
            display:true,           // ✅ ANGKA TETAP ADA
            beginAtZero:true,
            ticks:{
                precision:0,
                font:{ size:11 }    // kecil & rapi
            },
            grid:{
                color:'#e5e7eb'     // ✅ garis tetap ada
            }
        }
    }
}

});
</script>

@endsection
