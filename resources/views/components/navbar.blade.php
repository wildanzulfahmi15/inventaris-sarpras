<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script src="{{ asset('js/script.js') }}" defer></script>

<nav class="navbar" id="navbar">
  <div class="nav-logo">
    <img src="{{ asset('img/logo.png') }}" alt="Logo SMKN 1 Cibinong" class="logo" />
    <div class="nav-title">
      <h1>SMKN 1 Cibinong</h1>
      <p>Inventaris Sarpras</p>
    </div>
  </div>

  <div class="nav-menu" id="navMenu">
    <a href="{{ route('home') }}">Beranda</a>
    <a href="{{ route('login') }}">Login</a>
  </div>

  <button class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
  </button>
</nav>

<div class="sidebar" id="sidebar">
  <a href="{{ route('home') }}">Beranda</a>
  <a href="{{ route('login') }}">Login</a>
</div>
<div id="overlay"></div>

