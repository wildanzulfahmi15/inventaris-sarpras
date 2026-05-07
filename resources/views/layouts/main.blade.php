<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>@yield('title')</title>

  <!-- CSS Navbar -->

  @stack('styles')
</head>
<body>

  <!-- Navbar Component -->
  <x-navbar />

  <!-- Halaman akan tampil di sini -->
  <div style="margin-top: 90px;">
      @yield('content')
  </div>

  <!-- Javascript Navbar -->

  @stack('scripts')
</body>

</html>
