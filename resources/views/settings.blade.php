@extends('layouts.app')

@section('title', 'Settings')
@section('page', 'settings')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Settings</h1>
    <p>Manage your account preferences</p>
  </div>
</div>

<div class="widget-grid--3col" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 1.25rem;">
  {{-- Profile --}}
  <div class="card">
    <div class="card-header">
      <h4 class="card-header__title">Profile</h4>
    </div>
    <div class="card-body">
      <div style="display: flex; align-items: center; gap: 1rem;">
        <div class="topbar__avatar" style="width: 48px; height: 48px; font-size: 1.1rem;">
          {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>
        <div>
          <p style="font-weight: 600; margin: 0;">{{ Auth::user()->name ?? 'User' }}</p>
          <p class="text-muted" style="font-size: 0.8rem; margin: 0.125rem 0 0;">{{ Auth::user()->email ?? '' }}</p>
          <p class="text-muted" style="font-size: 0.75rem; margin: 0.125rem 0 0;">Member since {{ Auth::user()->created_at->format('M Y') ?? 'Recently' }}</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Notifications --}}
  <div class="card">
    <div class="card-header">
      <h4 class="card-header__title">Notifications</h4>
    </div>
    <div class="card-body" style="display: grid; gap: 1rem;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
          <p style="font-weight: 600; margin: 0; font-size: 0.9rem;">Wellness reminders</p>
          <p class="text-muted" style="font-size: 0.8rem; margin: 0;">Gentle check-ins and encouragement</p>
        </div>
        <label class="form-checkbox" style="gap: 0.5rem;">
          <input type="checkbox" id="notif-enabled" data-settings-notif-toggle checked>
        </label>
      </div>
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
          <p style="font-weight: 600; margin: 0; font-size: 0.9rem;">Frequency</p>
          <p class="text-muted" style="font-size: 0.8rem; margin: 0;">How often to receive reminders</p>
        </div>
        <select class="form-select" id="notif-frequency" data-settings-notif-freq style="width: auto; min-width: 140px;">
          <option value="low">Every 2 hours</option>
          <option value="medium" selected>Every hour</option>
          <option value="high">Every 30 min</option>
        </select>
      </div>
      <p class="text-muted" style="font-size: 0.8rem;" data-settings-notif-status></p>
    </div>
  </div>

  {{-- Appearance --}}
  <div class="card">
    <div class="card-header">
      <h4 class="card-header__title">Appearance</h4>
    </div>
    <div class="card-body" style="display: grid; gap: 1rem;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
          <p style="font-weight: 600; margin: 0; font-size: 0.9rem;">Theme</p>
          <p class="text-muted" style="font-size: 0.8rem; margin: 0;">Choose your preferred look</p>
        </div>
        <select class="form-select" id="theme-select" data-settings-theme style="width: auto; min-width: 140px;">
          <option value="auto">System default</option>
          <option value="light">Light</option>
          <option value="dark">Dark</option>
        </select>
      </div>
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
          <p style="font-weight: 600; margin: 0; font-size: 0.9rem;">Reduced motion</p>
          <p class="text-muted" style="font-size: 0.8rem; margin: 0;">Minimise animations</p>
        </div>
        <label class="form-checkbox">
          <input type="checkbox" id="reduced-motion" data-settings-reduced-motion>
        </label>
      </div>
    </div>
  </div>

  {{-- Session Defaults --}}
  <div class="card">
    <div class="card-header">
      <h4 class="card-header__title">Session Defaults</h4>
    </div>
    <div class="card-body" style="display: grid; gap: 1rem;">
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
          <p style="font-weight: 600; margin: 0; font-size: 0.9rem;">Default duration</p>
          <p class="text-muted" style="font-size: 0.8rem; margin: 0;">Pre-selected session length</p>
        </div>
        <select class="form-select" data-settings-duration style="width: auto; min-width: 140px;">
          <option value="5">5 minutes</option>
          <option value="15" selected>15 minutes</option>
          <option value="30">30 minutes</option>
        </select>
      </div>
      <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
          <p style="font-weight: 600; margin: 0; font-size: 0.9rem;">Default device</p>
          <p class="text-muted" style="font-size: 0.8rem; margin: 0;">Your preferred output</p>
        </div>
        <div style="display:flex;align-items:center;gap:0.5rem;">
          <span data-settings-vr-status class="badge badge--neutral" style="display:none;font-size:0.6rem;">Detecting...</span>
          <select class="form-select" data-settings-device style="width: auto; min-width: 140px;">
            <option value="vr">VR Headset</option>
            <option value="mobile" selected>Mobile 360</option>
            <option value="desktop">Desktop Screen</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  {{-- Password --}}
  <div class="card">
    <div class="card-header">
      <h4 class="card-header__title">Change Password</h4>
    </div>
    <div class="card-body">
      @if(session('success'))
        <div style="padding: 0.75rem; background: var(--color-success-soft); border: 1px solid #a7f3d0; border-radius: var(--radius-lg); color: #065f46; font-size: 0.85rem; margin-bottom: 1rem;">
          {{ session('success') }}
        </div>
      @endif
      <form method="POST" action="{{ route('settings.password') }}" style="display: grid; gap: 1rem;">
        @csrf
        <div class="form-group">
          <label class="form-label" for="set-current-pw">Current Password</label>
          <div class="pw-wrapper">
            <input class="pw-input" id="set-current-pw" name="current_password" type="password" placeholder="Enter current password" required data-pw-field>
            <button type="button" class="pw-toggle" data-pw-toggle aria-label="Show password">
              <svg class="pw-eye-on" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg class="pw-eye-off" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
          @error('current_password')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
          <label class="form-label" for="set-new-pw">New Password</label>
          <div class="pw-wrapper">
            <input class="pw-input" id="set-new-pw" name="password" type="password" placeholder="Min. 8 characters" minlength="8" required data-pw-field data-pw-strength>
            <button type="button" class="pw-toggle" data-pw-toggle aria-label="Show password">
              <svg class="pw-eye-on" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg class="pw-eye-off" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
          <div class="pw-strength" id="set-pw-strength" data-pw-strength-meter>
            <div class="pw-strength__bar">
              <div class="pw-strength__bar-fill"></div>
            </div>
            <span class="pw-strength__label"></span>
          </div>
          @error('password')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
          <label class="form-label" for="set-confirm-pw">Confirm New Password</label>
          <div class="pw-wrapper">
            <input class="pw-input" id="set-confirm-pw" name="password_confirmation" type="password" placeholder="Confirm new password" minlength="8" required data-pw-field data-pw-match="set-new-pw">
            <button type="button" class="pw-toggle" data-pw-toggle aria-label="Show password">
              <svg class="pw-eye-on" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg class="pw-eye-off" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
          <div class="pw-match" id="set-pw-match" data-pw-match-indicator>
            <svg class="pw-match__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="pw-match__text"></span>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Password</button>
      </form>
    </div>
  </div>

  {{-- Account --}}
  <div class="card">
    <div class="card-header">
      <h4 class="card-header__title">Account</h4>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Log Out
        </button>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  setupPasswordToggles();
  setupPasswordStrength();
  setupPasswordMatch();

  // Theme
  var themeSelect = document.querySelector('[data-settings-theme]');
  var savedTheme = localStorage.getItem('nh_theme') || 'auto';
  if (themeSelect) {
    themeSelect.value = savedTheme;
    themeSelect.addEventListener('change', function () {
      localStorage.setItem('nh_theme', themeSelect.value);
      if (themeSelect.value === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
      } else if (themeSelect.value === 'light') {
        document.documentElement.removeAttribute('data-theme');
      } else {
        var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (prefersDark) document.documentElement.setAttribute('data-theme', 'dark');
        else document.documentElement.removeAttribute('data-theme');
      }
    });
  }

  // Reduced motion
  var motionToggle = document.querySelector('[data-settings-reduced-motion]');
  if (motionToggle) {
    motionToggle.checked = localStorage.getItem('nh_reduced_motion') === '1';
    motionToggle.addEventListener('change', function () {
      localStorage.setItem('nh_reduced_motion', motionToggle.checked ? '1' : '0');
    });
  }

  // Session defaults
  var durationSelect = document.querySelector('[data-settings-duration]');
  var deviceSelect = document.querySelector('[data-settings-device]');
  if (durationSelect) {
    durationSelect.value = localStorage.getItem('nh_default_duration') || '15';
    durationSelect.addEventListener('change', function () {
      localStorage.setItem('nh_default_duration', durationSelect.value);
    });
  }
  if (deviceSelect) {
    deviceSelect.value = localStorage.getItem('nh_default_device') || 'mobile';
    deviceSelect.addEventListener('change', function () {
      localStorage.setItem('nh_default_device', deviceSelect.value);
    });
  }

  updateSettingsVRStatus();
  if (window.VRDetector) {
    VRDetector.addEventListener(updateSettingsVRStatus);
  }
});

function updateSettingsVRStatus() {
  var badge = document.querySelector('[data-settings-vr-status]');
  if (!badge || !window.VRDetector) return;

  var state = VRDetector.getState();

  if (state.status === 'connected') {
    badge.style.display = 'inline-flex';
    badge.className = 'badge badge--success';
    badge.textContent = state.headsetName ? state.headsetName : 'Connected';
  } else if (state.status === 'not-connected') {
    badge.style.display = 'none';
  } else if (state.status === 'unsupported') {
    badge.style.display = 'inline-flex';
    badge.className = 'badge badge--danger';
    badge.textContent = 'VR N/A';
  } else {
    badge.style.display = 'none';
  }
}
</script>
@endsection
