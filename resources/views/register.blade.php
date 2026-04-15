@extends('layouts.auth')

@section('title', 'Create Account')
@section('page', 'register')

@section('content')
<!-- Floating back button — desktop only, top-left over video -->
<a class="auth-back-float" href="{{ route('home') }}" aria-label="Back to Home">
  <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="15 18 9 12 15 6"/>
  </svg>
  <span>Back to Home</span>
</a>

<!-- Left: branded panel (overlay only, video shows through) -->
<aside class="auth-panel" aria-hidden="true">
  <div class="auth-panel__overlay" aria-hidden="true"></div>
  <div class="auth-panel__inner">
    <div class="auth-panel__mark">NH</div>
    <p class="auth-panel__name">Neuro Haven</p>
    <h2 class="auth-panel__headline">Begin your journey to a calmer mind.</h2>
    <p class="auth-panel__sub">Join thousands of Ugandans finding peace, clarity, and support through guided VR wellness sessions.</p>
    <ul class="auth-panel__trust">
      <li>
        <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        Anonymous &amp; confidential
      </li>
      <li>
        <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        Free core access for everyone
      </li>
      <li>
        <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        Culturally designed for Uganda
      </li>
    </ul>
  </div>
</aside>

<!-- Right: auth card -->
<div class="auth-wrap">
  <section class="auth-card" aria-labelledby="register-heading">
    <a class="auth-back" href="{{ route('home') }}">
      <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
      Back to Home
    </a>

    <!-- mobile-only branding -->
    <div class="auth-mobile-brand" aria-hidden="true">
      <span class="auth-mark">NH</span>
      <span class="auth-mobile-brand__name">Neuro Haven</span>
    </div>

    <nav class="auth-tabs" aria-label="Authentication pages">
      <a class="auth-tab is-active" href="{{ route('register') }}" aria-current="page">Create Account</a>
      <a class="auth-tab" href="{{ route('login') }}">Log In</a>
    </nav>

    <div class="auth-content">
      <div class="auth-heading">
        <h1 id="register-heading">Create your account</h1>
        <p>Start your wellness journey today — it's free.</p>
      </div>

      @if($errors->any())
        <div class="alert alert-error" role="alert">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="auth-socials" aria-label="Social sign up options">
        <button class="auth-social auth-social--google" type="button">
          <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
          <span>Continue with Google</span>
        </button>
        <button class="auth-social auth-social--facebook" type="button">
          <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="#1877F2">
            <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.513c-1.491 0-1.956.93-1.956 1.886v2.267h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
          </svg>
          <span>Continue with Facebook</span>
        </button>
      </div>

      <div class="auth-divider">
        <span>or continue with email</span>
      </div>

      <form class="auth-form" method="POST" action="{{ route('register.post') }}" data-validate novalidate>
        @csrf

        <div class="input-group input-group--icon">
          <label for="register-name">Display Name</label>
          <div class="input-wrap">
            <span class="input-icon" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </span>
            <input class="input" id="register-name" name="name" type="text"
              value="{{ old('name') }}" placeholder="Keep it anonymous if you prefer" autocomplete="nickname" required>
          </div>
          @error('name')
            <p class="field-error" role="alert">{{ $message }}</p>
          @enderror
        </div>

        <div class="input-group input-group--icon">
          <label for="register-email">Email Address</label>
          <div class="input-wrap">
            <span class="input-icon" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
              </svg>
            </span>
            <input class="input" id="register-email" name="email" type="email"
              value="{{ old('email') }}" placeholder="your@email.com" autocomplete="email" required>
          </div>
          @error('email')
            <p class="field-error" role="alert">{{ $message }}</p>
          @enderror
        </div>

        <div class="input-group input-group--icon">
          <label for="register-password">Password</label>
          <div class="input-wrap">
            <span class="input-icon" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
            </span>
            <input class="input" id="register-password" name="password" type="password"
              placeholder="Create a secure password" autocomplete="new-password" minlength="8" required>
            <button class="input-eye" type="button" aria-label="Show password" data-pw-toggle="register-password">
              <svg class="eye-show" aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
              <svg class="eye-hide" aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
              </svg>
            </button>
          </div>
          @error('password')
            <p class="field-error" role="alert">{{ $message }}</p>
          @enderror
        </div>

        <div class="input-group input-group--icon">
          <label for="register-district">District (Uganda)</label>
          <div class="input-wrap">
            <span class="input-icon" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                <circle cx="12" cy="10" r="3"/>
              </svg>
            </span>
            <select class="select" id="register-district" name="district" required>
              <option value="">Select your district</option>
              <option value="kampala" {{ old('district') == 'kampala' ? 'selected' : '' }}>Kampala</option>
              <option value="wakiso" {{ old('district') == 'wakiso' ? 'selected' : '' }}>Wakiso</option>
              <option value="entebbe" {{ old('district') == 'entebbe' ? 'selected' : '' }}>Entebbe</option>
              <option value="jinja" {{ old('district') == 'jinja' ? 'selected' : '' }}>Jinja</option>
              <option value="mbarara" {{ old('district') == 'mbarara' ? 'selected' : '' }}>Mbarara</option>
              <option value="gulu" {{ old('district') == 'gulu' ? 'selected' : '' }}>Gulu</option>
              <option value="mukono" {{ old('district') == 'mukono' ? 'selected' : '' }}>Mukono</option>
            </select>
          </div>
          @error('district')
            <p class="field-error" role="alert">{{ $message }}</p>
          @enderror
        </div>

        <div class="auth-consent" data-field-group>
          <div class="auth-consent__row">
            <input id="privacy" name="privacy" type="checkbox" {{ old('privacy') ? 'checked' : '' }} required>
            <label for="privacy">I agree to the <a href="{{ route('privacy') }}">Privacy Policy</a> and understand my data is protected and confidential.</label>
          </div>
          @error('privacy')
            <p class="field-error" role="alert">{{ $message }}</p>
          @enderror
        </div>

        <button class="btn btn-primary btn-lg auth-submit" type="submit">Get Started</button>
      </form>

      <p class="auth-trust">
        <svg aria-hidden="true" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
        Private, secure, and designed for your wellbeing.
      </p>
    </div>

    <div class="auth-note">"Your mind matters. You are not alone."</div>
  </section>

  <footer class="auth-footer">
    <p>&copy; 2026 Neuro Haven Uganda. All rights reserved.</p>
  </footer>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Password toggle
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-pw-toggle]');
    if (!btn) return;
    var input = document.getElementById(btn.getAttribute('data-pw-toggle'));
    if (!input) return;
    var hide = input.type === 'password';
    input.type = hide ? 'text' : 'password';
    btn.setAttribute('aria-label', hide ? 'Hide password' : 'Show password');
    btn.querySelector('.eye-show').style.display = hide ? 'none' : '';
    btn.querySelector('.eye-hide').style.display = hide ? '' : 'none';
  });
});
</script>
@endsection