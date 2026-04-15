<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Neuro Haven | @yield('title', 'Dashboard')</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
  <link rel="alternate icon" href="{{ asset('assets/images/logo.png') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body class="dashboard-page" data-page="@yield('page', 'dashboard')">
  <div class="page-wrapper">
    <header class="site-header">
      <div class="container site-header__row">
        <a class="brand" href="{{ url('/') }}" aria-label="Neuro Haven home">
          <img src="{{ asset('assets/images/best logo.png') }}" alt="Neuro Haven" class="brand__logo">
        </a>

        <button class="nav-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" data-nav-toggle>
          <svg aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="5" x2="17" y2="5"/>
            <line x1="3" y1="10" x2="17" y2="10"/>
            <line x1="3" y1="15" x2="17" y2="15"/>
          </svg>
        </button>

        <div class="site-nav-right">
          <nav class="site-nav" aria-label="Primary">
            <a class="nav-link" href="{{ url('/') }}" data-nav-link="home">Home</a>
            <a class="nav-link" href="{{ route('dashboard') }}" data-nav-link="dashboard">Dashboard</a>
            <a class="nav-link" href="{{ route('library') }}" data-nav-link="library">Library</a>
            <a class="nav-link" href="{{ route('progress.tracking') }}" data-nav-link="progress">Progress</a>
            <a class="nav-link" href="{{ route('therapy.sessions') }}" data-nav-link="sessions">Sessions</a>
          </nav>
          <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-secondary">Logout</button>
          </form>
        </div>
      </div>

      <nav class="mobile-nav" data-mobile-menu aria-label="Mobile navigation">
        <div class="container mobile-nav__list">
          <a class="nav-link" href="{{ url('/') }}" data-nav-link="home">Home</a>
          <a class="nav-link" href="{{ route('dashboard') }}" data-nav-link="dashboard">Dashboard</a>
          <a class="nav-link" href="{{ route('library') }}" data-nav-link="library">Library</a>
          <a class="nav-link" href="{{ route('progress.tracking') }}" data-nav-link="progress">Progress</a>
          <a class="nav-link" href="{{ route('therapy.sessions') }}" data-nav-link="sessions">Sessions</a>
          <a class="nav-link" href="{{ route('settings') }}" data-nav-link="settings">Settings</a>
          <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-secondary" style="width: 100%; margin-top: 0.5rem;">Logout</button>
          </form>
        </div>
      </nav>
    </header>

    <main class="dashboard-shell">
      <div class="dashboard-app">
        @yield('content')
      </div>
    </main>
  </div>

  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/notifications.js') }}"></script>
  @yield('scripts')
</body>
</html>