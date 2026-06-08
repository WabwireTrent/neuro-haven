<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="refresh" content="3;url={{ route('login') }}">
  <title>Logged Out — Neuro Haven</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      min-height: 100dvh;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .logout-card {
      width: min(100%, 400px);
      background: var(--color-surface);
      border-radius: var(--radius-2xl);
      border: 1px solid var(--color-border);
      box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
      padding: 2.5rem 2rem;
      text-align: center;
      animation: slideUp 0.4s ease;
    }
    .logo {
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1.5rem;
    }
    .logo-icon {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
    }
    .logo-text {
      font-size: 1.35rem;
      font-weight: 800;
      color: var(--color-text);
      letter-spacing: -0.02em;
    }
    .checkmark-ring {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: var(--color-success-soft);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      animation: popIn 0.5s ease 0.15s both;
    }
    .checkmark-ring svg {
      width: 32px;
      height: 32px;
      color: var(--color-success);
      stroke-dasharray: 30;
      stroke-dashoffset: 30;
      animation: drawCheck 0.5s ease 0.5s forwards;
    }
    h1 {
      font-size: 1.25rem;
      font-weight: 800;
      color: var(--color-text);
      margin-bottom: 0.5rem;
    }
    p {
      font-size: 0.875rem;
      color: var(--color-text-muted);
      margin-bottom: 2rem;
      line-height: 1.5;
    }
    .spinner {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      color: var(--color-text-muted);
      font-size: 0.8rem;
      font-weight: 500;
    }
    .spinner-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--color-primary);
      animation: bounce 1s ease infinite;
    }
    .spinner-dot:nth-child(2) { animation-delay: 0.15s; }
    .spinner-dot:nth-child(3) { animation-delay: 0.3s; }
    .fallback {
      margin-top: 1.5rem;
      font-size: 0.78rem;
      color: var(--color-text-muted);
    }
    .fallback a {
      color: var(--color-primary);
      font-weight: 600;
      text-decoration: none;
    }
    .fallback a:hover { text-decoration: underline; }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes popIn {
      0% { opacity: 0; transform: scale(0.5); }
      70% { transform: scale(1.1); }
      100% { opacity: 1; transform: scale(1); }
    }
    @keyframes drawCheck {
      to { stroke-dashoffset: 0; }
    }
    @keyframes bounce {
      0%, 80%, 100% { transform: translateY(0); }
      40% { transform: translateY(-6px); }
    }
  </style>
</head>
<body>
  <div class="logout-card">
    <div class="logo">
      <div class="logo-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      </div>
      <span class="logo-text">Neuro Haven</span>
    </div>

    <div class="checkmark-ring">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>

    <h1>You have successfully logged out.</h1>
    <p>Your session has been securely closed.</p>

    <div class="spinner">
      Redirecting to login
      <span class="spinner-dot"></span>
      <span class="spinner-dot"></span>
      <span class="spinner-dot"></span>
    </div>

    <div class="fallback">
      Not redirected? <a href="{{ route('login') }}">Log in again</a>
    </div>
  </div>

  <script>
    setTimeout(function () {
      window.location.href = "{{ route('login') }}";
    }, 3000);
  </script>
</body>
</html>