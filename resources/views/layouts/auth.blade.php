<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Neuro Haven | @yield('title')</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
  <link rel="alternate icon" href="{{ asset('assets/images/logo.png') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body class="auth-page" data-page="@yield('page')">

  <!-- Full-page background video -->
  <video
    class="auth-bg-video"
    autoplay muted loop playsinline
    poster="https://images.pexels.com/photos/1295138/pexels-photo-1295138.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
    aria-hidden="true"
  >
    <source src="https://videos.pexels.com/video-files/4833155/4833155-uhd_2560_1440_25fps.mp4" type="video/mp4">
  </video>

  <main class="auth-shell">
    @yield('content')
  </main>

  <script src="{{ asset('js/app.js') }}"></script>
  @yield('scripts')
</body>
</html>