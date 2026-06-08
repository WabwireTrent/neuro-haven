@extends('layouts.auth')

@section('title', 'Login')

@section('auth_content')
<a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.75rem 1.5rem; font-size: 0.8rem; font-weight: 600; color: var(--color-text-muted); text-decoration: none; border-bottom: 1px solid var(--color-border); transition: color var(--transition-fast);">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
  Back to Home
</a>

<nav style="display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid var(--color-border);">
  <a href="{{ route('register.choice') }}" style="display: flex; align-items: center; justify-content: center; padding: 0.85rem 1rem; font-weight: 700; font-size: 0.9rem; color: var(--color-text-muted); text-decoration: none; border-bottom: 2px solid transparent; transition: all var(--transition-fast);">Create Account</a>
  <a href="{{ route('login') }}" style="display: flex; align-items: center; justify-content: center; padding: 0.85rem 1rem; font-weight: 700; font-size: 0.9rem; color: var(--color-primary); text-decoration: none; border-bottom: 2px solid var(--color-primary); background: var(--color-surface);">Log In</a>
</nav>

<div style="padding: 1.5rem; display: grid; gap: 1.25rem;">
  <div>
    <h1 style="font-size: 1.35rem; font-weight: 800; margin: 0 0 0.25rem;">Welcome back</h1>
    <p class="text-muted" style="font-size: 0.85rem; margin: 0;">Sign in to continue your wellness journey.</p>
  </div>

  @if($errors->any())
    <div style="padding: 0.75rem; background: var(--color-danger-soft); border: 1px solid #fecaca; border-radius: var(--radius-lg); color: #991b1b; font-size: 0.85rem;">
      <ul style="margin: 0; padding-left: 1.25rem;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('login.post') }}" style="display: grid; gap: 1rem;" data-validate novalidate>
    @csrf

    <div class="form-group">
      <label class="form-label" for="login-email">Email Address</label>
      <div style="position: relative;">
        <svg style="position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); pointer-events: none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        <input class="form-input" id="login-email" name="email" type="email" value="{{ old('email') }}" placeholder="your@email.com" autocomplete="email" required style="padding-left: 2.5rem;">
      </div>
      @error('email')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
      <div style="display: flex; justify-content: space-between; align-items: baseline;">
        <label class="form-label" for="login-password">Password</label>
        <a href="#" style="font-size: 0.75rem; font-weight: 600; color: var(--color-primary);">Forgot password?</a>
      </div>
      <div class="pw-wrapper">
        <input class="pw-input" id="login-password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" minlength="8" required data-pw-field>
        <button type="button" class="pw-toggle" data-pw-toggle aria-label="Show password">
          <svg class="pw-eye-on" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          <svg class="pw-eye-off" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
        </button>
      </div>
      @error('password')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <label class="form-checkbox">
      <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
      Keep me signed in
    </label>

    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; height: 46px; font-size: 1rem;">Log In</button>
  </form>

  <p style="display: flex; align-items: center; justify-content: center; gap: 0.375rem; margin: 0; font-size: 0.75rem; color: var(--color-text-muted); font-weight: 600;">
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    Private, secure, and designed for your wellbeing.
  </p>
</div>

<div style="padding: 0.75rem 1.5rem; text-align: center; font-size: 0.8rem; color: var(--color-primary); background: var(--color-primary-soft); border-top: 1px solid rgba(59, 130, 246, 0.1); font-weight: 600; font-style: italic;">
  "Your mind matters. You are not alone."
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  setupPasswordToggles();
});
</script>
@endsection
