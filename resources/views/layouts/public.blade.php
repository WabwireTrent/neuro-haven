<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Neuro Haven | @yield('title', 'Accessible VR Mental Health')</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body data-page="@yield('page', 'index')">
  <div class="page-shell">
    <header style="position: sticky; top: 0; z-index: 50; background: rgba(255,255,255,0.9); backdrop-filter: blur(12px); border-bottom: 1px solid var(--color-border);">
      <div class="container" style="display: flex; align-items: center; justify-content: space-between; min-height: 64px;">
        <a href="{{ url('/') }}" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; font-weight: 800; font-size: 1.1rem; color: var(--color-text); letter-spacing: -0.02em;">
          <span style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 10px; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: #fff;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          </span>
          Neuro Haven
        </a>

        <nav style="display: flex; align-items: center; gap: 0.25rem;">
          <a href="{{ url('/') }}" style="padding: 0.5rem 0.75rem; border-radius: var(--radius-md); color: var(--color-text-secondary); font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: all var(--transition-fast);">Home</a>
          <a href="{{ route('library') }}" style="padding: 0.5rem 0.75rem; border-radius: var(--radius-md); color: var(--color-text-secondary); font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: all var(--transition-fast);">Library</a>
          <a href="{{ route('dashboard') }}" style="padding: 0.5rem 0.75rem; border-radius: var(--radius-md); color: var(--color-text-secondary); font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: all var(--transition-fast);">Dashboard</a>
          <a href="{{ route('login') }}" style="padding: 0.5rem 0.75rem; border-radius: var(--radius-md); color: var(--color-text-secondary); font-weight: 600; font-size: 0.875rem; text-decoration: none; transition: all var(--transition-fast);">Login</a>
          <a href="{{ route('register') }}" class="btn btn-primary" style="margin-left: 0.5rem;">Get Started</a>
        </nav>
      </div>
    </header>

    <main class="page-main @yield('main-class', '')">
      @yield('content')
    </main>

    <footer style="background: var(--color-sidebar); color: rgba(255,255,255,0.6); padding: 3rem 0 1.5rem;">
      <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
          <div>
            <a href="{{ url('/') }}" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; font-weight: 800; font-size: 1.1rem; color: #fff;">
              <span style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 10px; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: #fff;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
              </span>
              Neuro Haven
            </a>
            <p style="margin: 1rem 0 0; font-size: 0.85rem; max-width: 320px;">Uganda's first VR-assisted mental wellness platform. Private, stigma-free, and built for you.</p>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
              <h4 style="color: #fff; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 1rem;">Company</h4>
              <div style="display: grid; gap: 0.5rem;">
                <a href="{{ route('about') }}" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; text-decoration: none;">About Us</a>
                <a href="{{ route('research') }}" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; text-decoration: none;">Research</a>
                <a href="{{ route('impact') }}" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; text-decoration: none;">Impact Report</a>
              </div>
            </div>
            <div>
              <h4 style="color: #fff; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 1rem;">Support</h4>
              <div style="display: grid; gap: 0.5rem;">
                <a href="{{ route('contact') }}" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; text-decoration: none;">Contact</a>
                <a href="{{ route('privacy') }}" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; text-decoration: none;">Privacy Policy</a>
                <a href="{{ route('terms') }}" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; text-decoration: none;">Terms of Use</a>
              </div>
            </div>
          </div>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 1.5rem; font-size: 0.8rem;">
          <p style="margin: 0;">&copy; {{ date('Y') }} Neuro Haven Uganda. All rights reserved.</p>
          <div style="display: flex; gap: 0.75rem;">
            <a href="#" aria-label="Website" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.5); transition: all var(--transition-fast);">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            </a>
            <a href="mailto:hello@neurohaven.ug" aria-label="Email" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.5); transition: all var(--transition-fast);">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
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
