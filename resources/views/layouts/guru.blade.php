<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') - Dashboard Guru</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
/* ================= GLOBAL ================= */
*, *::before, *::after { box-sizing: border-box; }
body {
    margin: 0;
    background: #eef5ff;
    font-family: "Segoe UI", sans-serif;
    overflow-x: hidden;
}

/* ================= LAYOUT ================= */
.layout {
    display: flex;
    align-items: stretch;
    min-height: 100vh;
}

/* ================= SIDEBAR ================= */
.sidebar {
    width: 240px;
    min-width: 240px;
    background: linear-gradient(180deg, #3b82f6, #2563eb);
    color: white;
    padding-top: 20px;
    box-shadow: 4px 0 20px rgba(0,0,0,.18);
    transition: width .3s ease, min-width .3s ease;
    flex-shrink: 0;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow: visible;
    display: flex;
    flex-direction: column;
}
.sidebar.collapsed { width: 72px; min-width: 72px; }

.sidebar-header {
    padding: 0 16px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}
.sidebar-header h5 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    transition: opacity .2s, width .3s;
}
.sidebar.collapsed .sidebar-header h5 { opacity: 0; width: 0; }

.sidebar-menu {
    flex: 1;
    overflow-y: auto;
    overflow-x: visible;
    padding-bottom: 16px;
}
.sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 20px;
    margin: 4px 12px;
    border-radius: 10px;
    color: #f3f4f6;
    text-decoration: none;
    transition: .2s;
    white-space: nowrap;
    overflow: hidden;
}
.sidebar-menu a:hover  { background: rgba(255,255,255,.25); }
.sidebar-menu a.active {
    background: rgba(255,255,255,.32);
    box-shadow: 0 0 12px rgba(255,255,255,.45);
}
.sidebar-menu a i { flex-shrink: 0; width: 20px; text-align: center; font-size: 15px; }

.sidebar.collapsed .menu-text,
.sidebar.collapsed .logout-text { opacity: 0; width: 0; overflow: hidden; }

.toggle-btn {
    background: rgba(255,255,255,.25);
    border: none;
    border-radius: 6px;
    padding: 6px 10px;
    color: white;
    cursor: pointer;
    flex-shrink: 0;
}

/* ================= LOGOUT SIDEBAR ================= */
.sidebar-logout-wrap { padding: 8px 12px 4px; flex-shrink: 0; }
.sidebar-logout-wrap button {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 10px;
    white-space: nowrap;
    overflow: hidden;
    font-size: 14px;
}

/* ================= NOTIF TRIGGER (SIDEBAR) ================= */
.notif-wrap {
    position: relative;
    margin: 4px 12px;
}
.notif-trigger {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 20px;
    border-radius: 10px;
    color: #f3f4f6;
    text-decoration: none;
    cursor: pointer;
    transition: .2s;
    white-space: nowrap;
    overflow: hidden;
    position: relative;
    user-select: none;
}
.notif-trigger:hover { background: rgba(255,255,255,.25); }
.notif-trigger i { flex-shrink: 0; width: 20px; text-align: center; font-size: 15px; }

/* Bell shake animation */
@keyframes bellShake {
    0%,100% { transform: rotate(0); }
    15%      { transform: rotate(15deg); }
    30%      { transform: rotate(-12deg); }
    45%      { transform: rotate(10deg); }
    60%      { transform: rotate(-8deg); }
    75%      { transform: rotate(5deg); }
}
.notif-trigger.has-notif .fa-bell {
    animation: bellShake 1.2s ease infinite;
    transform-origin: top center;
    display: inline-block;
}

/* Badge */
.notif-badge {
    position: absolute;
    top: 6px;
    left: 30px;
    background: #ef4444;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    border-radius: 20px;
    padding: 1px 5px;
    min-width: 16px;
    text-align: center;
    line-height: 14px;
    border: 2px solid #2563eb;
    transition: transform .3s;
}
.notif-trigger:hover .notif-badge { transform: scale(1.15); }

/* ================= NOTIF DROPDOWN (DESKTOP SIDEBAR) ================= */
.notif-dropdown {
    position: absolute;
    left: calc(100% + 12px);
    top: 0;
    width: 320px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 20px 60px rgba(0,0,0,.18);
    z-index: 9999;
    overflow: hidden;

    /* Animation */
    opacity: 0;
    transform: translateX(-8px) scale(.97);
    pointer-events: none;
    transition: opacity .22s ease, transform .22s ease;
}
.notif-dropdown.show {
    opacity: 1;
    transform: translateX(0) scale(1);
    pointer-events: all;
}

/* Arrow pointer */
.notif-dropdown::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 18px;
    width: 0; height: 0;
    border-top: 8px solid transparent;
    border-bottom: 8px solid transparent;
    border-right: 8px solid #fff;
    filter: drop-shadow(-2px 0 3px rgba(0,0,0,.08));
}

.notif-dd-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px 12px;
    border-bottom: 1px solid #f0f0f0;
}
.notif-dd-header span {
    font-weight: 700;
    font-size: 14px;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.notif-dd-header .mark-all {
    font-size: 11px;
    color: #2563eb;
    cursor: pointer;
    background: none;
    border: none;
    padding: 4px 8px;
    border-radius: 6px;
    transition: .2s;
}
.notif-dd-header .mark-all:hover { background: #eff6ff; }

.notif-list { max-height: 340px; overflow-y: auto; }
.notif-list::-webkit-scrollbar { width: 4px; }
.notif-list::-webkit-scrollbar-track { background: #f8fafc; }
.notif-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

.notif-item-link {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 18px;
    text-decoration: none;
    color: inherit;
    border-bottom: 1px solid #f8fafc;
    transition: background .18s;
    position: relative;
}
.notif-item-link:hover { background: #f5f9ff; }
.notif-item-link.unread { background: #eef4ff; }
.notif-item-link.unread:hover { background: #e0ecff; }

/* Unread dot */
.notif-item-link.unread::after {
    content: '';
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    width: 8px; height: 8px;
    background: #2563eb;
    border-radius: 50%;
}

.notif-icon-wrap {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}
.notif-icon-wrap i { color: #fff; font-size: 14px; }

.notif-item-body { flex: 1; min-width: 0; }
.notif-item-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.notif-item-msg {
    font-size: 12px;
    color: #64748b;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.notif-item-time {
    font-size: 10px;
    color: #94a3b8;
    margin-top: 4px;
}
.notif-dropdown {
    left: auto !important;
    right: 0;
    top: 110%;
}

.notif-dropdown::before {
    left: auto;
    right: 20px;
    border-right: none;
    border-left: 8px solid #fff;
}
.notif-empty {
    text-align: center;
    padding: 32px 16px;
    color: #94a3b8;
    font-size: 13px;
}
.notif-empty i { font-size: 32px; display: block; margin-bottom: 8px; color: #cbd5e1; }

.notif-dd-footer {
    padding: 10px;
    border-top: 1px solid #f0f0f0;
    text-align: center;
}
.notif-dd-footer a {
    font-size: 12px;
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
    padding: 6px 16px;
    border-radius: 8px;
    display: inline-block;
    transition: .2s;
}
.notif-dd-footer a:hover { background: #eff6ff; }

/* ================= CONTENT ================= */
.content {
    flex: 1;
    padding: 30px;
    min-width: 0;
    overflow-x: auto;
    transition: .3s;
}

/* ================= BOTTOM BAR MOBILE ================= */
.guru-bottom { display: none; }

/* ================= MOBILE POPUP ================= */
.mobile-popup {
    position: fixed;
    left: 50%;
    bottom: 92px;
    transform: translateX(-50%) translateY(10px);
    width: calc(100% - 32px);
    max-width: 400px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0,0,0,.22);
    z-index: 3000;
    overflow: hidden;
    opacity: 0;
    pointer-events: none;
    transition: opacity .22s ease, transform .22s ease;
}
.mobile-popup.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
    pointer-events: all;
}

/* Notif popup header */
.mobile-popup .notif-dd-header {
    padding: 14px 16px 12px;
}
.mobile-popup .notif-list { max-height: 260px; }

/* Logout popup */
.mobile-popup .logout-popup-btn {
    width: 100%;
    background: none;
    border: none;
    padding: 18px 20px;
    color: #ef4444;
    font-weight: 700;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: .2s;
}
.mobile-popup .logout-popup-btn:hover { background: #fff5f5; }

/* ================= RESPONSIVE ================= */
@media (max-width: 1024px) and (min-width: 769px) {
    .sidebar { width: 72px; min-width: 72px; }
    .sidebar .menu-text,
    .sidebar .sidebar-header h5,
    .sidebar .logout-text { opacity: 0; width: 0; overflow: hidden; }
    .notif-dropdown { left: calc(100% + 8px); }
}

@media (max-width: 768px) {
    .layout { display: block; }
    .sidebar { display: none !important; }
    .content { padding: 16px; padding-bottom: 110px; }

    .guru-bottom {
        display: flex;
        position: fixed;
        left: 50%;
        bottom: 14px;
        transform: translateX(-50%);
        width: calc(100% - 28px);
        max-width: 430px;
        height: 66px;
        background: #fff;
        border-radius: 26px;
        box-shadow: 0 16px 40px rgba(0,0,0,.22);
        z-index: 2000;
        padding: 0 6px;
    }
    .guru-bottom a {
        flex: 1;
        text-decoration: none;
        color: #64748b;
        font-size: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        transition: .25s;
        position: relative;
        border-radius: 18px;
    }
    .guru-bottom a i { font-size: 21px; transition: transform .25s; }
    .guru-bottom a.active { color: #2563eb; font-weight: 700; }
    .guru-bottom a.active i { transform: translateY(-4px) scale(1.12); }
    .guru-bottom a.active::after {
        content: "";
        position: absolute;
        bottom: 6px;
        width: 5px; height: 5px;
        background: #2563eb;
        border-radius: 50%;
    }

    /* Bell badge mobile */
    .mobile-notif-badge {
        position: absolute;
        top: 3px;
        right: calc(50% - 20px);
        background: #ef4444;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        border-radius: 20px;
        padding: 1px 4px;
        min-width: 14px;
        text-align: center;
        line-height: 13px;
        border: 2px solid #fff;
    }

    @keyframes bellShake {
        0%,100% { transform: rotate(0); }
        15%      { transform: rotate(15deg); }
        30%      { transform: rotate(-12deg); }
        45%      { transform: rotate(10deg); }
        60%      { transform: rotate(-8deg); }
        75%      { transform: rotate(5deg); }
    }
    .guru-bottom #notifMobileBtn .fa-bell {
        animation: bellShake 1.4s ease infinite;
        transform-origin: top center;
    }
}

@media (max-width: 360px) {
    .guru-bottom a { font-size: 9px; }
    .guru-bottom a i { font-size: 18px; }
}
</style>
</head>
<body>
<div class="layout">

<!-- ================= SIDEBAR DESKTOP ================= -->
<div class="sidebar d-none d-md-flex" id="sidebar">
    <div class="sidebar-header">
        <h5>Guru Panel</h5>
        <button class="toggle-btn" id="desktopToggle"><i class="fa fa-bars"></i></button>
    </div>

    <div class="sidebar-menu">

        {{-- ===== NOTIFIKASI ===== --}}
        <!-- <div class="notif-wrap">
            <div class="notif-trigger {{ auth()->user()->unreadNotifications->count() > 0 ? 'has-notif' : '' }}"
                 id="notifToggle">
                <i class="fa fa-bell"></i>
                <span class="menu-text">Notifikasi</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="notif-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </div>

            {{-- DROPDOWN --}}
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-dd-header">
                    <span><i class="fa fa-bell" style="color:#2563eb"></i> Notifikasi</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <button class="mark-all" id="markAllRead">Tandai semua dibaca</button>
                    @endif
                </div>
                <div class="notif-list" id="notifList">
                    @forelse(auth()->user()->unreadNotifications as $notif)
                        <a href="{{ $notif->data['url'] ?? '#' }}"
                           class="notif-item-link unread"
                           data-id="{{ $notif->id }}">
                            <div class="notif-icon-wrap">
                                <i class="fa fa-bell"></i>
                            </div>
                            <div class="notif-item-body">
                                <div class="notif-item-title">{{ $notif->data['title'] }}</div>
                                <div class="notif-item-msg">{{ $notif->data['message'] }}</div>
                                <div class="notif-item-time">
                                    {{ $notif->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="notif-empty">
                            <i class="fa fa-bell-slash"></i>
                            Tidak ada notifikasi baru
                        </div>
                    @endforelse
                </div>
                <div class="notif-dd-footer">
                    <a href="{{ route('guru.riwayat') }}">Lihat semua riwayat →</a>
                </div>
            </div>
        </div> -->
        {{-- ===== END NOTIFIKASI ===== --}}

        <a href="{{ route('guru.dashboard') }}" class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
            <i class="fa fa-home"></i><span class="menu-text">Home</span>
        </a>
        <a href="{{ route('peminjaman.pilihBarang') }}" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
            <i class="fa fa-box"></i><span class="menu-text">Peminjaman</span>
        </a>
        <a href="{{ route('guru.pengembalian') }}" class="{{ request()->routeIs('guru.pengembalian') ? 'active' : '' }}">
            <i class="fa fa-rotate-left"></i><span class="menu-text">Pengembalian</span>
        </a>
        <a href="{{ route('guru.riwayat') }}" class="{{ request()->routeIs('guru.riwayat') ? 'active' : '' }}">
            <i class="fa fa-clock-rotate-left"></i><span class="menu-text">Riwayat</span>
        </a>
    </div>

    <div class="sidebar-logout-wrap">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="fa fa-sign-out-alt"></i>
                <span class="logout-text">Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- ================= CONTENT ================= -->
<div class="content">

    <!-- 🔥 NOTIF PINDAHAN -->
    <div style="display:flex; justify-content:flex-end; margin-bottom:20px;">
        <div class="notif-wrap">

            <div class="notif-trigger {{ auth()->user()->unreadNotifications->count() > 0 ? 'has-notif' : '' }}"
                 id="notifToggle"
                 style="background:#3b82f6; color:white; border-radius:12px;">
                <i class="fa fa-bell"></i>
                <span class="menu-text">Notifikasi</span>

                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="notif-badge">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </div>

            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-dd-header">
                    <span><i class="fa fa-bell" style="color:#2563eb"></i> Notifikasi</span>

                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <button class="mark-all" id="markAllRead">
                            Tandai semua dibaca
                        </button>
                    @endif
                </div>

                <div class="notif-list" id="notifList">
                    @forelse(auth()->user()->unreadNotifications as $notif)
                        <a href="{{ $notif->data['url'] ?? '#' }}"
                           class="notif-item-link unread"
                           data-id="{{ $notif->id }}">
                            <div class="notif-icon-wrap">
                                <i class="fa fa-bell"></i>
                            </div>
                            <div class="notif-item-body">
                                <div class="notif-item-title">{{ $notif->data['title'] }}</div>
                                <div class="notif-item-msg">{{ $notif->data['message'] }}</div>
                                <div class="notif-item-time">
                                    {{ $notif->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="notif-empty">
                            <i class="fa fa-bell-slash"></i>
                            Tidak ada notifikasi baru
                        </div>
                    @endforelse
                </div>

                <div class="notif-dd-footer">
                    <a href="{{ route('guru.riwayat') }}">Lihat semua riwayat →</a>
                </div>
            </div>

        </div>
    </div>
    <!-- 🔥 END -->

    @yield('content')

</div>
</div>

<!-- ================= BOTTOM BAR MOBILE ================= -->
<div class="guru-bottom d-md-none">
    <a href="{{ route('guru.dashboard') }}" class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
        <i class="fa fa-home"></i><span>Home</span>
    </a>
    <a href="{{ route('peminjaman.pilihBarang') }}" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
        <i class="fa fa-box"></i><span>Pinjam</span>
    </a>
    <a href="{{ route('guru.pengembalian') }}" class="{{ request()->routeIs('guru.pengembalian') ? 'active' : '' }}">
        <i class="fa fa-rotate-left"></i><span>Kembali</span>
    </a>
    <a href="{{ route('guru.riwayat') }}" class="{{ request()->routeIs('guru.riwayat') ? 'active' : '' }}">
        <i class="fa fa-clock-rotate-left"></i><span>Riwayat</span>
    </a>
    <a href="#" id="openLogoutBtn">
        <i class="fa fa-ellipsis"></i><span>Lainnya</span>
    </a>
</div>

<!-- ================= MOBILE POPUP: NOTIFIKASI ================= -->
<div class="mobile-popup" id="mobileNotifPopup">
    <div class="notif-dd-header">
        <span><i class="fa fa-bell" style="color:#2563eb"></i> Notifikasi</span>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <button class="mark-all" id="markAllReadMobile">Tandai semua dibaca</button>
        @endif
    </div>
    <div class="notif-list">
        @forelse(auth()->user()->unreadNotifications as $notif)
            <a href="{{ $notif->data['url'] ?? '#' }}"
               class="notif-item-link unread"
               data-id="{{ $notif->id }}">
                <div class="notif-icon-wrap">
                    <i class="fa fa-bell"></i>
                </div>
                <div class="notif-item-body">
                    <div class="notif-item-title">{{ $notif->data['title'] }}</div>
                    <div class="notif-item-msg">{{ $notif->data['message'] }}</div>
                    <div class="notif-item-time">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @empty
            <div class="notif-empty">
                <i class="fa fa-bell-slash"></i>
                Tidak ada notifikasi baru
            </div>
        @endforelse
    </div>
    <div class="notif-dd-footer">
        <a href="{{ route('guru.riwayat') }}">Lihat semua riwayat →</a>
    </div>
</div>

<!-- ================= MOBILE POPUP: LOGOUT ================= -->
<div class="mobile-popup" id="mobileLogoutPopup">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-popup-btn">
            <i class="fa fa-sign-out-alt"></i> Logout
        </button>
    </form>
</div>
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getMessaging, getToken, onMessage } 
from "https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging.js";

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
      credentials:'include',
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
(function () {
    /* ===== REFS ===== */
    var notifToggle       = document.getElementById('notifToggle');
    var notifDropdown     = document.getElementById('notifDropdown');
    var notifMobileBtn    = document.getElementById('notifMobileBtn');
    var mobileNotifPopup  = document.getElementById('mobileNotifPopup');
    var openLogoutBtn     = document.getElementById('openLogoutBtn');
    var mobileLogoutPopup = document.getElementById('mobileLogoutPopup');
    var desktopToggle     = document.getElementById('desktopToggle');
    var sidebar           = document.getElementById('sidebar');

    /* ===== CLOSE ALL POPUPS ===== */
    function closeAll() {
        notifDropdown    && notifDropdown.classList.remove('show');
        mobileNotifPopup && mobileNotifPopup.classList.remove('show');
        mobileLogoutPopup&& mobileLogoutPopup.classList.remove('show');
    }

    function toggle(el) {
        var isOpen = el.classList.contains('show');
        closeAll();
        if (!isOpen) el.classList.add('show');
    }

    /* ===== DESKTOP NOTIF ===== */
    notifToggle && notifToggle.addEventListener('click', function (e) {
        e.preventDefault(); e.stopPropagation();
        toggle(notifDropdown);
    });

    /* ===== MOBILE NOTIF ===== */
    notifMobileBtn && notifMobileBtn.addEventListener('click', function (e) {
        e.preventDefault(); e.stopPropagation();
        toggle(mobileNotifPopup);
    });

    /* ===== MOBILE LOGOUT ===== */
    openLogoutBtn && openLogoutBtn.addEventListener('click', function (e) {
        e.preventDefault(); e.stopPropagation();
        toggle(mobileLogoutPopup);
    });

    /* Stop clicks inside popups from bubbling to document */
    [notifDropdown, mobileNotifPopup, mobileLogoutPopup].forEach(function (el) {
        el && el.addEventListener('click', function (e) { e.stopPropagation(); });
    });

    /* Close on outside click */
    document.addEventListener('click', closeAll);

    /* Close popups on other bottom-nav links */
    document.querySelectorAll('.guru-bottom a').forEach(function (link) {
        if (link.id !== 'openLogoutBtn' && link.id !== 'notifMobileBtn') {
            link.addEventListener('click', closeAll);
        }
    });

    /* ===== MARK ALL READ — Desktop ===== */
    var markAllBtn = document.getElementById('markAllRead');
    markAllBtn && markAllBtn.addEventListener('click', function (e) {
        e.preventDefault(); e.stopPropagation();
        markAllRead('notifList', markAllBtn);
    });

    /* ===== MARK ALL READ — Mobile ===== */
    var markAllMobileBtn = document.getElementById('markAllReadMobile');
    markAllMobileBtn && markAllMobileBtn.addEventListener('click', function (e) {
        e.preventDefault(); e.stopPropagation();
        markAllRead(null, markAllMobileBtn);
    });

    function markAllRead(listId, btn) {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {

            // 🔥 HAPUS SEMUA NOTIF DARI UI
            document.querySelectorAll('.notif-item-link').forEach(el => el.remove());

            // 🔥 TAMPILKAN EMPTY STATE
            const list = document.getElementById('notifList');
            if (list) {
                list.innerHTML = `
                    <div class="notif-empty">
                        <i class="fa fa-bell-slash"></i>
                        Tidak ada notifikasi baru
                    </div>
                `;
            }

            // 🔴 HILANGKAN BADGE
            document.querySelectorAll('.notif-badge, .mobile-notif-badge').forEach(b => {
                b.style.display = 'none';
            });

            // 🔕 STOP ANIMASI BELL
            notifToggle && notifToggle.classList.remove('has-notif');

            // 🔘 HIDE BUTTON
            if (btn) btn.style.display = 'none';
        });
    }

    /* ===== MARK SINGLE READ on click ===== */
document.querySelectorAll('.notif-item-link').forEach(function (link) {
    link.addEventListener('click', function () {

        const id = this.dataset.id;

        fetch('/notifications/' + id + '/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {

            this.remove();

            const badge = document.querySelector('.notif-badge');
            if (badge) {
                let count = parseInt(badge.innerText);
                count = Math.max(0, count - 1);

                if (count === 0) {
                    badge.style.display = 'none';
                } else {
                    badge.innerText = count;
                }
            }

            // 🔥 CEK KALAU KOSONG
            if (document.querySelectorAll('.notif-item-link').length === 0) {
                document.getElementById('notifList').innerHTML = `
                    <div class="notif-empty">
                        <i class="fa fa-bell-slash"></i>
                        Tidak ada notifikasi baru
                    </div>
                `;
            }

        });

    });
});



    /* ===== SIDEBAR TOGGLE (DESKTOP) ===== */
    if (localStorage.getItem('guru_sidebar') === 'collapsed') {
        sidebar && sidebar.classList.add('collapsed');
    }
    desktopToggle && desktopToggle.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('guru_sidebar',
            sidebar.classList.contains('collapsed') ? 'collapsed' : 'open');
    });

    if (window.innerWidth <= 768) { localStorage.removeItem('guru_sidebar'); }

})();
</script>
</body>
</html>