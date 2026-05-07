<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') | Sarpras Panel</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ================= TOKENS ================= */
:root{
    --sidebar-bg: #1E1E2C;
    --sidebar-w: 240px;
    --sidebar-collapsed-w: 72px;
    --topbar-h: 62px;
    --accent: #4F6EF7;
    --accent-soft: #dbeafe;
    --accent-dark: #1e3a8a;
    --surface: #ffffff;
    --bg: #f4f6fb;
    --text: #1a1d2e;
    --muted: #64748b;
    --border: rgba(255,255,255,0.07);
    --danger: #ef4444;
    --success: #10b981;
    --shadow-sm: 0 2px 12px rgba(0,0,0,.08);
    --shadow-md: 0 8px 32px rgba(0,0,0,.14);
    --shadow-lg: 0 20px 56px rgba(0,0,0,.22);
    --radius: 14px;
    --transition: .28s cubic-bezier(.4,0,.2,1);
}

/* ================= RESET ================= */
*, *::before, *::after { box-sizing: border-box; }

body{
    margin: 0;
    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    background: var(--bg);
    color: var(--text);
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
}

/* ================= WRAPPER ================= */
.nav-app-wrap{
    display: flex;
    min-height: 100vh;
}

/* ================= SIDEBAR ================= */
.nav-sidebar{
    width: var(--sidebar-w);
    height: 100vh;
    background: var(--sidebar-bg);
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    transition: width var(--transition);
    overflow: hidden;
}

.nav-sidebar.nav-collapsed{ width: var(--sidebar-collapsed-w); }

/* header */
.nav-sidebar-header{
    padding: 0 16px;
    height: var(--topbar-h);
    font-weight: 700;
    font-size: 17px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
    border-bottom: 1px solid var(--border);
    gap: 10px;
}

.nav-sidebar-logo{
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap;
    overflow: hidden;
    opacity: 1;
    transition: opacity var(--transition);
}

.nav-sidebar.nav-collapsed .nav-sidebar-logo{
    opacity: 0;
    pointer-events: none;
    width: 0;
}

.nav-toggle-btn{
    background: rgba(255,255,255,.1);
    border: none;
    border-radius: 8px;
    padding: 7px 10px;
    cursor: pointer;
    color: #fff;
    flex-shrink: 0;
    transition: background var(--transition), transform var(--transition);
}
.nav-toggle-btn:hover{ background: rgba(255,255,255,.2); transform: scale(1.08); }

/* menu */
.nav-sidebar-menu{
    flex: 1;
    overflow-y: auto;
    padding: 10px 0 10px;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.15) transparent;
}

.nav-sidebar-menu::-webkit-scrollbar{ width: 4px; }
.nav-sidebar-menu::-webkit-scrollbar-thumb{ background: rgba(255,255,255,.15); border-radius: 99px; }

.nav-sidebar a{
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 11px 18px;
    margin: 3px 10px;
    border-radius: 10px;
    color: rgba(255,255,255,.72);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    transition: background var(--transition), color var(--transition), transform var(--transition);
    position: relative;
}

.nav-sidebar a i{
    width: 20px;
    text-align: center;
    flex-shrink: 0;
    font-size: 15px;
    transition: transform var(--transition);
}

.nav-sidebar a:hover{
    background: rgba(255,255,255,.1);
    color: #fff;
    transform: translateX(3px);
}

.nav-sidebar a.active{
    background: var(--accent);
    color: #fff;
    font-weight: 700;
    box-shadow: 0 4px 16px rgba(79,110,247,.4);
}

.nav-sidebar a.active i{ transform: scale(1.15); }

.nav-text{
    opacity: 1;
    transition: opacity var(--transition);
}

.nav-sidebar.nav-collapsed .nav-text,
.nav-sidebar.nav-collapsed .nav-logout-text{
    opacity: 0;
    pointer-events: none;
    width: 0;
    overflow: hidden;
}

/* logout desktop */
.nav-logout-wrap{
    border-top: 1px solid var(--border);
    padding: 12px;
    flex-shrink: 0;
}

.nav-logout-btn{
    width: 100%;
    border-radius: 10px;
    background: rgba(239,68,68,.15);
    border: 1px solid rgba(239,68,68,.3);
    color: #fca5a5;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 10px;
    font-weight: 600;
    transition: background var(--transition), color var(--transition);
    overflow: hidden;
    white-space: nowrap;
}
.nav-logout-btn:hover{
    background: rgba(239,68,68,.3);
    color: #fff;
}

/* ================= TOPBAR ================= */
.nav-topbar{
    position: fixed;
    top: 0;
    left: var(--sidebar-w);
    right: 0;
    height: var(--topbar-h);
    background: var(--surface);
    border-bottom: 1px solid #e8ecf3;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    z-index: 900;
    box-shadow: var(--shadow-sm);
    transition: left var(--transition);
    gap: 12px;
}

.nav-topbar.nav-collapsed{
    left: var(--sidebar-collapsed-w);
}

.topbar-left{
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}

.topbar-title{
    font-weight: 700;
    font-size: 16px;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.topbar-right{
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

/* ===== NOTIF TOPBAR ===== */
.notif-btn{
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--bg);
    border: 1px solid #e3e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--muted);
    transition: background var(--transition), color var(--transition), transform var(--transition), box-shadow var(--transition);
}

.notif-btn:hover{
    background: var(--accent-soft);
    color: var(--accent-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79,110,247,.2);
}

.notif-btn.active-btn{
    background: var(--accent-soft);
    color: var(--accent);
}

/* bell shake animation */
@keyframes bellShake {
    0%,100%{ transform: rotate(0); }
    15%{ transform: rotate(14deg); }
    30%{ transform: rotate(-10deg); }
    45%{ transform: rotate(8deg); }
    60%{ transform: rotate(-6deg); }
    75%{ transform: rotate(4deg); }
}

.notif-btn.has-unread .fa-bell{
    animation: bellShake 2.4s ease infinite;
    transform-origin: 50% 0;
}

.notif-badge{
    position: absolute;
    top: 5px;
    right: 5px;
    min-width: 17px;
    height: 17px;
    background: var(--danger);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    border-radius: 99px;
    padding: 0 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    border: 2px solid var(--surface);

    /* pop-in animation */
    animation: badgePop .35s cubic-bezier(.34,1.56,.64,1) both;
}

@keyframes badgePop{
    from{ transform: scale(0); opacity: 0; }
    to{ transform: scale(1); opacity: 1; }
}

/* ===== NOTIF DROPDOWN ===== */
.notif-dropdown{
    position: fixed;
    top: calc(var(--topbar-h) + 8px);
    right: 16px;
    width: 380px;
    max-width: calc(100vw - 24px);
    background: var(--surface);
    border-radius: 18px;
    box-shadow: var(--shadow-lg);
    border: 1px solid #e8ecf3;
    z-index: 1100;

    /* animation states */
    opacity: 0;
    transform: translateY(-12px) scale(.97);
    pointer-events: none;
    transition:
        opacity .22s cubic-bezier(.4,0,.2,1),
        transform .22s cubic-bezier(.4,0,.2,1);
    transform-origin: top right;
}

.notif-dropdown.show{
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: auto;
}

.notif-dd-header{
    padding: 14px 16px 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f0f3fa;
}

.notif-dd-title{
    font-weight: 700;
    font-size: 14px;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 7px;
}

.notif-dd-title i{
    color: var(--accent);
}

.mark-all{
    background: none;
    border: none;
    color: var(--accent);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 6px;
    transition: background var(--transition);
}
.mark-all:hover{ background: var(--accent-soft); }

/* list */
.notif-list{
    max-height: 360px; /* Tambah tinggi sedikit untuk muat tombol */
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

.notif-list::-webkit-scrollbar{ width: 4px; }
.notif-list::-webkit-scrollbar-thumb{ background: #cbd5e1; border-radius: 99px; }

.notif-item-link{
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    text-decoration: none;
    color: var(--text);
    border-bottom: 1px solid #f4f6fb;
    transition: background var(--transition), transform var(--transition);
    position: relative;
    cursor: default; /* Ubah cursor jadi default karena ada tombol di dalam */

    /* staggered slide-in */
    opacity: 0;
    transform: translateX(10px);
    animation: notifSlideIn .3s ease forwards;
}

@keyframes notifSlideIn{
    to{ opacity: 1; transform: translateX(0); }
}

.notif-item-link:last-child{ border-bottom: none; }
/* Hover effect dihapus agar tidak mengganggu klik tombol, atau bisa dibuat lebih halus */
.notif-item-link:hover{ background: #fafbfc; }

.notif-item-link.unread{
    background: #f8faff;
}

.notif-item-link.unread::before{
    content: '';
    position: absolute;
    left: 6px;
    top: 20px; /* Sesuaikan posisi dot */
    width: 5px;
    height: 5px;
    background: var(--accent);
    border-radius: 50%;
}

.notif-icon-wrap{
    width: 36px;
    height: 36px;
    background: var(--accent-soft);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--accent);
    font-size: 14px;
}

.notif-item-body{ flex: 1; min-width: 0; }

.notif-item-title{
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.notif-item-msg{
    font-size: 12px;
    color: var(--muted);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}

.notif-item-time{
    font-size: 11px;
    color: #94a3b8;
    margin-top: 6px;
}

/* ================= PERBAIKAN TOMBOL NOTIFIKASI ================= */
.notif-actions {
    margin-top: 10px;
    display: flex;
    gap: 8px;
    width: 100%;
}

.btn-notif-action {
    flex: 1;
    border: none;
    padding: 6px 0;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
    font-family: inherit;
}

/* Style Tombol Setuju */
.btn-setuju {
    background-color: #ecfdf5;
    color: #047857;
}
.btn-setuju:hover {
    background-color: #10b981;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
}
.btn-setuju:active { transform: translateY(0); }

/* Style Tombol Tolak */
.btn-tolak {
    background-color: #fef2f2;
    color: #b91c1c;
}
.btn-tolak:hover {
    background-color: #ef4444;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
}
.btn-tolak:active { transform: translateY(0); }

/* Loading state */
.btn-notif-action.loading {
    opacity: 0.7;
    cursor: wait;
    pointer-events: none;
}
.btn-notif-action.loading i {
    animation: spin 1s linear infinite;
}
@keyframes spin { 100% { transform: rotate(360deg); } }


/* footer */
.notif-dd-footer{
    padding: 10px 16px;
    border-top: 1px solid #f0f3fa;
    text-align: center;
    background: #fafbfc;
    border-bottom-left-radius: 18px;
    border-bottom-right-radius: 18px;
}

.notif-dd-footer a{
    font-size: 13px;
    color: var(--accent);
    font-weight: 600;
    text-decoration: none;
    transition: opacity var(--transition);
}
.notif-dd-footer a:hover{ opacity: .7; }

.notif-empty{
    padding: 32px 16px;
    text-align: center;
    color: var(--muted);
    font-size: 13px;
}

.notif-empty i{
    font-size: 28px;
    color: #cbd5e1;
    display: block;
    margin-bottom: 8px;
}

/* user avatar */
.topbar-avatar{
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--accent-soft);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-dark);
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
    border: 1px solid #dde4f5;
}

/* ================= CONTENT ================= */
.nav-content{
    margin-left: var(--sidebar-w);
    padding-top: calc(var(--topbar-h) + 24px);
    padding-left: 24px;
    padding-right: 24px;
    padding-bottom: 24px;
    width: 100%;
    min-width: 0;
    transition: margin-left var(--transition);
}

.nav-content.nav-collapsed{
    margin-left: var(--sidebar-collapsed-w);
}

/* ================= MOBILE ================= */
.nav-bottom{ display: none; }

@media (max-width: 768px){

    .nav-sidebar{ display: none; }

    .nav-topbar{
        left: 0 !important;
        padding: 0 14px;
    }

    .nav-content,
    .nav-content.nav-collapsed{
        margin-left: 0;
        padding-bottom: 110px;
        padding-left: 14px;
        padding-right: 14px;
    }

    .nav-bottom{
        display: flex;
        position: fixed;
        left: 50%;
        bottom: 14px;
        transform: translateX(-50%);
        width: calc(100% - 28px);
        max-width: 420px;
        height: 66px;
        background: var(--surface);
        border-radius: 24px;
        box-shadow: var(--shadow-lg);
        z-index: 2000;
        padding: 0 6px;
    }

    .nav-bottom a{
        flex: 1;
        text-align: center;
        font-size: 10px;
        font-weight: 500;
        color: var(--muted);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        text-decoration: none;
        border-radius: 18px;
        transition: color var(--transition), background var(--transition), transform var(--transition);
    }

    .nav-bottom a i{ font-size: 20px; }

    .nav-bottom a.active{
        color: var(--accent);
        background: var(--accent-soft);
        font-weight: 700;
        transform: scale(1.06);
    }

    .nav-bottom a:hover:not(.active){
        color: var(--accent-dark);
        background: #f4f6fb;
    }

    /* notif dropdown on mobile goes full-width from top */
    .notif-dropdown{
        top: calc(var(--topbar-h) + 6px);
        right: 8px;
        left: 8px;
        width: auto;
        max-width: 100%;
        border-radius: 16px;
    }
}

/* ================= KELOLA PANEL ================= */
.nav-manage-overlay{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    backdrop-filter: blur(3px);
    z-index: 2500;
    display: none;
    opacity: 0;
    transition: opacity var(--transition);
}

.nav-manage-overlay.show{
    display: block;
    opacity: 1;
}

.nav-manage-panel{
    position: fixed;
    bottom: 90px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    width: calc(100% - 32px);
    max-width: 420px;
    background: var(--surface);
    border-radius: 24px;
    padding: 18px;
    z-index: 3000;
    display: none;
    box-shadow: var(--shadow-lg);
    opacity: 0;
    transition: transform var(--transition), opacity var(--transition);
}

.nav-manage-panel.show{
    display: block;
    transform: translateX(-50%) translateY(0);
    opacity: 1;
}

.nav-manage-title{
    text-align: center;
    font-weight: 700;
    font-size: 15px;
    margin-bottom: 14px;
    color: var(--accent-dark);
}

.nav-manage-grid{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.nav-manage-item{
    background: var(--bg);
    border-radius: 14px;
    padding: 14px 8px;
    text-align: center;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    color: var(--text);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
}

.nav-manage-item i{
    font-size: 20px;
    color: var(--accent);
}

.nav-manage-item:hover{
    background: var(--accent-soft);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79,110,247,.15);
}

/* logout popup */
.nav-logout-panel{
    position: fixed;
    bottom: 90px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    width: calc(100% - 40px);
    max-width: 360px;
    background: var(--surface);
    border-radius: 24px;
    padding: 18px;
    z-index: 3000;
    display: none;
    box-shadow: var(--shadow-lg);
    opacity: 0;
    transition: transform var(--transition), opacity var(--transition);
}

.nav-logout-panel.show{
    display: block;
    transform: translateX(-50%) translateY(0);
    opacity: 1;
}

/* ===== STAGGER NOTIF ITEMS ===== */
.notif-item-link:nth-child(1){ animation-delay: .05s; }
.notif-item-link:nth-child(2){ animation-delay: .10s; }
.notif-item-link:nth-child(3){ animation-delay: .15s; }
.notif-item-link:nth-child(4){ animation-delay: .20s; }
.notif-item-link:nth-child(5){ animation-delay: .25s; }
</style>
</head>

<body>

<div class="nav-app-wrap">

<!-- ===== SIDEBAR DESKTOP ===== -->
<div class="nav-sidebar d-none d-md-flex" id="navSidebar">

    <div class="nav-sidebar-header">
        <div class="nav-sidebar-logo">
            <i class="fa fa-cubes" style="color:var(--accent);font-size:18px;"></i>
            <span>Sarpras</span>
        </div>
        <button class="nav-toggle-btn" id="navToggle" title="Toggle Sidebar">
            <i class="fa fa-bars"></i>
        </button>
    </div>

    <div class="nav-sidebar-menu">
        <a href="{{ route('sarpras.dashboard') }}" class="{{ request()->routeIs('sarpras.dashboard')?'active':'' }}"><i class="fa fa-home"></i><span class="nav-text">Dashboard</span></a>
        <a href="{{ route('sarpras.peminjaman') }}" class="{{ request()->routeIs('sarpras.peminjaman')?'active':'' }}"><i class="fa fa-box"></i><span class="nav-text">Peminjaman</span></a>
        <a href="{{ route('sarpras.pengembalian') }}" class="{{ request()->routeIs('sarpras.pengembalian')?'active':'' }}"><i class="fa fa-rotate-left"></i><span class="nav-text">Pengembalian</span></a>
        <a href="{{ route('sarpras.riwayat') }}" class="{{ request()->routeIs('sarpras.riwayat')?'active':'' }}"><i class="fa fa-clock-rotate-left"></i><span class="nav-text">Riwayat</span></a>
        <a href="{{ route('sarpras.barang.index') }}" class="{{ request()->routeIs('sarpras.barang.index')?'active':'' }}"><i class="fa fa-boxes-stacked"></i><span class="nav-text">Barang</span></a>
        <a href="{{ route('sarpras.jurusan.kelas') }}" class="{{ request()->routeIs('sarpras.jurusan.kelas')?'active':'' }}"><i class="fa fa-layer-group"></i><span class="nav-text">Jurusan & Kelas</span></a>
        <a href="{{ route('sarpras.siswa.index') }}" class="{{ request()->routeIs('sarpras.siswa.index')?'active':'' }}"><i class="fa fa-user-graduate"></i><span class="nav-text">Siswa</span></a>
        <a href="{{ route('sarpras.mapel.index') }}" class="{{ request()->routeIs('sarpras.mapel.index')?'active':'' }}"><i class="fa fa-book"></i><span class="nav-text">Mapel</span></a>
        <a href="{{ route('sarpras.user.index') }}" class="{{ request()->routeIs('sarpras.user.index')?'active':'' }}"><i class="fa fa-users"></i><span class="nav-text">Akun</span></a>
        <a href="{{ route('sarpras.tempat.index') }}" class="{{ request()->routeIs('sarpras.tempat.*') ? 'active' : '' }}">
            <i class="fa fa-door-open"></i>
            <span class="nav-text">Ruangan</span>
        </a>
    </div>

    <form action="{{ route('logout') }}" method="POST" class="nav-logout-wrap">
        @csrf
        <button type="submit" class="nav-logout-btn">
            <i class="fa fa-sign-out-alt"></i>
            <span class="nav-logout-text">Logout</span>
        </button>
    </form>
</div>

<!-- ===== TOPBAR ===== -->
<header class="nav-topbar" id="navTopbar">
    <div class="topbar-left">
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
    </div>

    <div class="topbar-right">

        <!-- NOTIF BUTTON -->
        <button
            class="notif-btn {{ auth()->user()->unreadNotifications->count() > 0 ? 'has-unread' : '' }}"
            id="notifToggle"
            aria-label="Notifikasi"
        >
            <i class="fa fa-bell"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="notif-badge" id="notifBadge">
                    {{ auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </button>


    </div>
</header>

<!-- ===== NOTIF DROPDOWN ===== -->
<div class="notif-dropdown" id="notifDropdown" role="dialog" aria-label="Notifikasi">

    <div class="notif-dd-header">
        <div class="notif-dd-title">
            <i class="fa fa-bell"></i> Notifikasi
        </div>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <button class="mark-all" id="markAllRead">Tandai semua</button>
        @endif
    </div>

    <div class="notif-list" id="notifList">
@forelse(auth()->user()->unreadNotifications as $notif)

    @php
        $type = $notif->data['type'] ?? 'peminjaman';
        $id   = $notif->data['id_detail'] ?? null;
        $notifId = $notif->id; // ID notifikasi database
    @endphp

    <!-- Item Notifikasi -->
    <div class="notif-item-link unread" data-id="{{ $notifId }}">
        <!-- StopPropagation added in JS to handle buttons correctly -->

        <div class="notif-icon-wrap">
            <i class="fa fa-bell"></i>
        </div>

        <div class="notif-item-body">
            <div class="notif-item-title">{{ $notif->data['title'] }}</div>
            <div class="notif-item-msg">{{ $notif->data['message'] }}</div>

            @if($id)
            <!-- TOMBOL AKSI -->
            <div class="notif-actions">
                <button 
                    class="btn-notif-action btn-setuju" 
                    data-id="{{ $id }}" 
                    data-type="{{ $type }}"
                    data-notif-id="{{ $notifId }}"
                    onclick="handleNotifAction(event, 'setuju', this)">
                    <i class="fa fa-check"></i> Setuju
                </button>

                <button 
                    class="btn-notif-action btn-tolak" 
                    data-id="{{ $id }}" 
                    data-type="{{ $type }}"
                    data-notif-id="{{ $notifId }}"
                    onclick="handleNotifAction(event, 'tolak', this)">
                    <i class="fa fa-xmark"></i> Tolak
                </button>
            </div>
            @endif

            <div class="notif-item-time">{{ $notif->created_at->diffForHumans() }}</div>
        </div>
    </div>

@empty
    <div class="notif-empty">
        <i class="fa fa-bell-slash"></i>
        Tidak ada notifikasi baru
    </div>
@endforelse
    </div>

    <div class="notif-dd-footer">
        <a href="{{ route('sarpras.riwayat') }}">Lihat semua riwayat &rarr;</a>
    </div>
</div>

<!-- ===== CONTENT ===== -->
<div class="nav-content" id="navContent">
    @yield('content')
</div>

</div><!-- /nav-app-wrap -->

<!-- ===== BOTTOM NAV MOBILE ===== -->
<nav class="nav-bottom d-md-none" aria-label="Navigasi Bawah">

    <a href="{{ route('sarpras.dashboard') }}" class="{{ request()->routeIs('sarpras.dashboard')?'active':'' }}">
        <i class="fa fa-home"></i><span>Home</span>
    </a>

    <a href="{{ route('sarpras.peminjaman') }}" class="{{ request()->routeIs('sarpras.peminjaman')?'active':'' }}">
        <i class="fa fa-box"></i><span>Pinjam</span>
    </a>

    <a href="{{ route('sarpras.pengembalian') }}" class="{{ request()->routeIs('sarpras.pengembalian')?'active':'' }}">
        <i class="fa fa-rotate-left"></i><span>Kembali</span>
    </a>

    <a href="javascript:void(0)" id="btnKelola">
        <i class="fa fa-gear"></i><span>Kelola</span>
    </a>

    <a href="javascript:void(0)" id="btnLogout">
        <i class="fa fa-right-from-bracket"></i><span>Logout</span>
    </a>
</nav>

<!-- PANEL KELOLA -->
<div class="nav-manage-overlay" id="kelolaOverlay"></div>

<div class="nav-manage-panel" id="kelolaPanel">
    <div class="nav-manage-title">Menu Kelola</div>
    <div class="nav-manage-grid">
        <a href="{{ route('sarpras.barang.index') }}" class="nav-manage-item"><i class="fa fa-boxes-stacked"></i>Barang</a>
        <a href="{{ route('sarpras.jurusan.kelas') }}" class="nav-manage-item"><i class="fa fa-layer-group"></i>Jurusan</a>
        <a href="{{ route('sarpras.siswa.index') }}" class="nav-manage-item"><i class="fa fa-user-graduate"></i>Siswa</a>
        <a href="{{ route('sarpras.mapel.index') }}" class="nav-manage-item"><i class="fa fa-book"></i>Mapel</a>
        <a href="{{ route('sarpras.user.index') }}" class="nav-manage-item"><i class="fa fa-users"></i>Akun</a>
        <a href="{{ route('sarpras.riwayat') }}" class="nav-manage-item"><i class="fa fa-clock-rotate-left"></i>Riwayat</a>
        <a href="{{ route('sarpras.tempat.index') }}" class="nav-manage-item"><i class="fa fa-door-open"></i>Ruangan</a>
    </div>
</div>

<!-- LOGOUT POPUP -->
<div class="nav-manage-overlay" id="logoutOverlay"></div>

<div class="nav-logout-panel" id="logoutPanel">
    <p style="text-align:center;color:var(--muted);font-size:14px;margin-bottom:14px;">Yakin ingin keluar?</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger w-100 py-3 fw-bold rounded-4">
            <i class="fa fa-right-from-bracket me-2"></i>Keluar dari Akun
        </button>
    </form>
</div>
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging.js";

const firebaseConfig = {
  apiKey: "AIzaSyBu8UWsZ4UZDrFLhsmGNgxvoWyuETK_Bdw",
  authDomain: "sarpras-72ec3.firebaseapp.com",
  projectId: "sarpras-72ec3",
  messagingSenderId: "219024370680",
  appId: "1:219024370680:web:de096934d6d1970eee440d"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

async function initFCM() {
  try {
    const token = await getToken(messaging, {
      vapidKey: "BA18xDnqB70O92b3n5a88BJ30nnBVI0HIFjGS571ZaBaOUD-vYi7U-_Mb1KJbB-YtMmMEujav9UZ6gLMEJvV8WA"
    });

    if (!token) return;


    await fetch('/save-token', {
    method: 'POST',
    credentials: 'same-origin', // 🔥 INI WAJIB
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ token })
    });
  } catch (err) {
    console.error("FCM ERROR:", err);
  }
}

initFCM();

onMessage(messaging, (payload) => {

    console.log('FOREGROUND MESSAGE:', payload);

    const title =
        payload.data?.title ||
        payload.notification?.title ||
        'Notifikasi';

    const body =
        payload.data?.body ||
        payload.notification?.body ||
        '';

    new Notification(title, {
        body: body,
        icon: '/favicon.ico'
    });

});

</script>
<script>
/* ===== SIDEBAR COLLAPSE ===== */
const navSidebar = document.getElementById("navSidebar");
const navContent = document.getElementById("navContent");
const navTopbar  = document.getElementById("navTopbar");
const navToggle  = document.getElementById("navToggle");

if (localStorage.getItem("sidebar") === "collapsed") {
    navSidebar.classList.add("nav-collapsed");
    navContent.classList.add("nav-collapsed");
    navTopbar.classList.add("nav-collapsed");
}

navToggle?.addEventListener("click", () => {
    const collapsed = navSidebar.classList.toggle("nav-collapsed");
    navContent.classList.toggle("nav-collapsed", collapsed);
    navTopbar.classList.toggle("nav-collapsed", collapsed);
    localStorage.setItem("sidebar", collapsed ? "collapsed" : "open");
});

/* ===== NOTIF DROPDOWN ===== */
const notifToggle   = document.getElementById("notifToggle");
const notifDropdown = document.getElementById("notifDropdown");

notifToggle?.addEventListener("click", (e) => {
    e.stopPropagation();
    const isOpen = notifDropdown.classList.toggle("show");
    notifToggle.classList.toggle("active-btn", isOpen);
});

document.addEventListener("click", (e) => {
    if (!notifDropdown.contains(e.target) && e.target !== notifToggle) {
        notifDropdown.classList.remove("show");
        notifToggle?.classList.remove("active-btn");
    }
});

/* close on Escape */
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        notifDropdown.classList.remove("show");
        notifToggle?.classList.remove("active-btn");
    }
});

/* ===== MARK ALL READ ===== */
document.getElementById("markAllRead")?.addEventListener("click", () => {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(() => {
        document.querySelectorAll('.notif-item-link').forEach(el => el.remove());

document.querySelectorAll('.notif-badge').forEach(b => {
    b.remove();
});

document.getElementById('notifToggle')?.classList.remove('has-unread');
        document.querySelectorAll('.notif-badge').forEach(b => {
            b.style.transform = 'scale(0)';
            b.style.opacity   = '0';
            setTimeout(() => b.remove(), 300);
        });
        notifToggle?.classList.remove("has-unread");
    });
});

/* ===== FUNGSI UTAMA: TOMBOL SETUJU / TOLAK ===== */
/* ===== FUNGSI UTAMA: TOMBOL SETUJU / TOLAK ===== */
window.handleNotifAction = function(event, action, btnElement) {
    event.stopPropagation();
    event.preventDefault();

    const id     = btnElement.dataset.id;
    const type   = btnElement.dataset.type;
    const notifId = btnElement.dataset.notifId;
    const notifItem = btnElement.closest('.notif-item-link');

    const originalIcon = btnElement.innerHTML;
    btnElement.classList.add('loading');
    btnElement.innerHTML = '<i class="fa fa-circle-notch fa-spin"></i> Proses...';

    // ✅ Sesuaikan dengan route di web.php
    let url = '';
    if (type === 'pengembalian') {
        url = action === 'setuju'
            ? `/sarpras/pengembalian/${id}/setuju`
            : `/sarpras/pengembalian/${id}/tolak`;
    } else {
        // peminjaman
        url = action === 'setuju'
            ? `/sarpras/peminjaman/konfirmasi/${id}`
            : `/sarpras/peminjaman/tolak/${id}`;
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal');
        return response.json();
    })
    .then(() => {
        // Tandai notif sebagai read
        fetch(`/notifications/${notifId}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });

        notifItem.style.transition = 'all 0.3s ease';
        notifItem.style.transform = 'translateX(20px)';
        notifItem.style.opacity = '0';

        setTimeout(() => {
            notifItem.remove();

            const list = document.getElementById('notifList');
            if (list.querySelectorAll('.notif-item-link').length === 0) {
                list.innerHTML = `<div class="notif-empty">
                    <i class="fa fa-bell-slash"></i>
                    Tidak ada notifikasi baru
                </div>`;
            }

            const badge = document.getElementById('notifBadge');
            if (badge) {
                let count = parseInt(badge.textContent) || 0;
                count--;
                if (count <= 0) {
                    badge.remove();
                    document.getElementById('notifToggle')?.classList.remove('has-unread');
                } else {
                    badge.textContent = count > 99 ? '99+' : count;
                }
            }
        }, 300);
    })
    .catch(error => {
        console.error('Error:', error);
        btnElement.classList.remove('loading');
        btnElement.innerHTML = originalIcon;
        btnElement.style.outline = '2px solid red';
        setTimeout(() => btnElement.style.outline = '', 1000);
    });
};

/* ===== MARK SINGLE (Legacy support for clicking outside buttons) ===== */
document.querySelectorAll('.notif-item-link').forEach(el => {
    el.addEventListener('click', function (e) {
        // Jangan hapus jika user mengklik tombol (sudah ditangani handleNotifAction)
        if(e.target.closest('.btn-notif-action')) return;

        const id = this.dataset.id;

        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        // 🔥 HAPUS LANGSUNG DARI UI
        this.remove();

        // 🔥 UPDATE BADGE
        const badge = document.getElementById('notifBadge');
        if (badge) {
            let count = parseInt(badge.textContent) || 0;
            count--;

            if (count <= 0) {
                badge.remove();
                document.getElementById('notifToggle')?.classList.remove('has-unread');
            } else {
                badge.textContent = count > 99 ? '99+' : count;
            }
        }
    });
});

/* ===== KELOLA ===== */
const btnKelola    = document.getElementById("btnKelola");
const kelolaPanel  = document.getElementById("kelolaPanel");
const kelolaOverlay= document.getElementById("kelolaOverlay");

btnKelola?.addEventListener("click", () => {
    kelolaPanel.classList.add("show");
    kelolaOverlay.classList.add("show");
});
kelolaOverlay?.addEventListener("click", () => {
    kelolaPanel.classList.remove("show");
    kelolaOverlay.classList.remove("show");
});

/* ===== LOGOUT ===== */
const btnLogout    = document.getElementById("btnLogout");
const logoutPanel  = document.getElementById("logoutPanel");
const logoutOverlay= document.getElementById("logoutOverlay");

btnLogout?.addEventListener("click", () => {
    logoutPanel.classList.add("show");
    logoutOverlay.classList.add("show");
});
logoutOverlay?.addEventListener("click", () => {
    logoutPanel.classList.remove("show");
    logoutOverlay.classList.remove("show");
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>