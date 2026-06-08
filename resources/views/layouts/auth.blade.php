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
<body class="auth-page-body">
  <div class="auth-page" style="min-height: 100dvh; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); display: flex; align-items: center; justify-content: center; padding: 1rem;">
    <div style="width: min(100%, 440px);">
      {{-- Brand --}}
      <div style="text-align: center; margin-bottom: 2rem;">
        <div style="display: inline-flex; align-items: center; gap: 0.75rem;">
          <div style="width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); display: flex; align-items: center; justify-content: center; color: #fff;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          </div>
          <span style="font-size: 1.5rem; font-weight: 800; color: #fff; letter-spacing: -0.02em;">Neuro Haven</span>
        </div>
        <p style="color: rgba(255,255,255,0.5); font-size: 0.875rem; margin: 0.5rem 0 0;">VR Mental Health Therapy Platform</p>
      </div>

      {{-- Auth Card --}}
      <div style="background: var(--color-surface); border-radius: var(--radius-2xl); border: 1px solid var(--color-border); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); overflow: hidden; animation: slideUp 0.3s ease;">
        @yield('auth_content')
      </div>

      <p style="text-align: center; margin-top: 1.5rem; font-size: 0.8rem; color: rgba(255,255,255,0.4);">
        &copy; {{ date('Y') }} Neuro Haven. All rights reserved.
      </p>
    </div>
  </div>

  <script src="{{ asset('js/app.js') }}"></script>
  @yield('scripts')
</body>
</html>
