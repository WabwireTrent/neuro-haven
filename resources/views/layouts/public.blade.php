<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Neuro Haven | @yield('title', 'Accessible VR Mental Health')</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
  <link rel="alternate icon" href="{{ asset('assets/images/logo.png') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body data-page="@yield('page', 'index')">
  <div class="page-shell">
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
            <a class="nav-link" href="{{ url('/') }}" data-nav-link="index">Home</a>
            <a class="nav-link" href="{{ route('library') }}" data-nav-link="library">Library</a>
            <a class="nav-link" href="{{ route('dashboard') }}" data-nav-link="dashboard">Dashboard</a>
            <a class="nav-link" href="{{ route('login') }}" data-nav-link="login">Login</a>
          </nav>
          <a class="btn btn-primary" href="{{ route('register') }}">Get Started</a>
        </div>
      </div>

      <nav class="mobile-nav" data-mobile-menu aria-label="Mobile navigation">
        <div class="container mobile-nav__list">
          <a class="nav-link" href="{{ url('/') }}" data-nav-link="index">Home</a>
          <a class="nav-link" href="{{ route('dashboard') }}" data-nav-link="dashboard">Dashboard</a>
          <a class="nav-link" href="{{ route('library') }}" data-nav-link="library">Library</a>
          <a class="nav-link" href="{{ route('onboarding') }}" data-nav-link="onboarding">Onboarding</a>
          <a class="nav-link" href="{{ route('session') }}" data-nav-link="session">Session</a>
          <a class="nav-link" href="{{ route('review') }}" data-nav-link="review">Review</a>
          <a class="nav-link" href="{{ route('login') }}" data-nav-link="login">Login</a>
          <a class="nav-link" href="{{ route('register') }}" data-nav-link="register">Sign Up</a>
        </div>
      </nav>
    </header>

    <main class="page-main @yield('main-class', 'landing-main')">
      @yield('content')
    </main>

    <footer class="site-footer-dark">
      <div class="container site-footer-dark__inner">
        <div class="site-footer-dark__brand">
          <a class="brand" href="{{ url('/') }}" aria-label="Neuro Haven home">
          <img src="{{ asset('assets/images/best logo.png') }}" alt="Neuro Haven" class="brand__logo">
        </a>
        </div>
        <div class="site-footer-dark__cols">
          <div class="site-footer-dark__col">
            <h3>Company</h3>
            <a href="{{ route('about') }}">About Us</a>
            <a href="{{ route('research') }}">Research</a>
            <a href="{{ route('impact') }}">Impact Report</a>
          </div>
          <div class="site-footer-dark__col">
            <h3>Support</h3>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('privacy') }}">Privacy Policy</a>
            <a href="{{ route('terms') }}">Terms of Use</a>
          </div>
        </div>
      </div>
      <div class="site-footer-dark__bottom">
        <div class="container site-footer-dark__bottom-row">
          <p>&copy; 2026 Neuro Haven Uganda. All rights reserved.</p>
          <div class="site-footer-dark__socials">
            <a href="#" aria-label="Website">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            </a>
            <a href="mailto:hello@neurohaven.ug" aria-label="Email">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
  <script src="{{ asset('js/app.js') }}"></script>
  @yield('scripts')
</body>
</html>