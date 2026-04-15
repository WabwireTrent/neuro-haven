@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page', 'settings')

@section('content')
<header class="settings-header">
  <button class="settings-header__btn" type="button" aria-label="Back" onclick="window.location.href='{{ route('dashboard') }}'">
    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6"/>
    </svg>
  </button>
  <h1 class="settings-header__title">Settings</h1>
</header>

<section class="settings-main">

  <!-- Profile -->
  <section class="settings-section">
    <h2 class="settings-section__title">Profile</h2>
    <div class="card settings-card">
      <div class="settings-row">
        <div class="settings-row__avatar" aria-hidden="true">{{ strtoupper(substr(Auth::user()->name ?? 'Friend', 0, 1)) }}</div>
        <div class="settings-row__info">
          <p class="settings-row__name">{{ Auth::user()->name ?? 'Friend' }}</p>
          <p class="settings-row__meta">Member since {{ Auth::user()->created_at->format('M Y') ?? 'Recently' }}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Notifications -->
  <section class="settings-section">
    <h2 class="settings-section__title">Notifications</h2>
    <div class="card settings-card">

      <div class="settings-row settings-row--between">
        <div>
          <p class="settings-row__label">Wellness reminders</p>
          <p class="settings-row__sub">Gentle check-ins and encouragement</p>
        </div>
        <label class="settings-toggle" aria-label="Toggle notifications">
          <input type="checkbox" id="notif-enabled" data-settings-notif-toggle>
          <span class="settings-toggle__track"></span>
        </label>
      </div>

      <div class="settings-divider"></div>

      <div class="settings-row settings-row--between" id="notif-frequency-row">
        <div>
          <p class="settings-row__label">Frequency</p>
          <p class="settings-row__sub">How often to receive reminders</p>
        </div>
        <select class="select settings-select" id="notif-frequency" data-settings-notif-freq>
          <option value="low">Every 2 hours</option>
          <option value="medium" selected>Every hour</option>
          <option value="high">Every 30 min</option>
        </select>
      </div>

      <p class="settings-notif-status" data-settings-notif-status></p>

    </div>
  </section>

  <!-- Theme -->
  <section class="settings-section">
    <h2 class="settings-section__title">Appearance</h2>
    <div class="card settings-card">

      <div class="settings-row settings-row--between">
        <div>
          <p class="settings-row__label">Theme</p>
          <p class="settings-row__sub">Choose your preferred look</p>
        </div>
        <select class="select settings-select" id="theme-select" data-settings-theme>
          <option value="auto">System default</option>
          <option value="light">Light</option>
          <option value="dark">Dark</option>
        </select>
      </div>

      <div class="settings-divider"></div>

      <div class="settings-row settings-row--between">
        <div>
          <p class="settings-row__label">Reduced motion</p>
          <p class="settings-row__sub">Minimise animations</p>
        </div>
        <label class="settings-toggle" aria-label="Toggle reduced motion">
          <input type="checkbox" id="reduced-motion" data-settings-reduced-motion>
          <span class="settings-toggle__track"></span>
        </label>
      </div>

    </div>
  </section>

  <!-- Session defaults -->
  <section class="settings-section">
    <h2 class="settings-section__title">Session Defaults</h2>
    <div class="card settings-card">

      <div class="settings-row settings-row--between">
        <div>
          <p class="settings-row__label">Default duration</p>
          <p class="settings-row__sub">Pre-selected session length</p>
        </div>
        <select class="select settings-select" data-settings-duration>
          <option value="5">5 minutes</option>
          <option value="15" selected>15 minutes</option>
          <option value="30">30 minutes</option>
        </select>
      </div>

      <div class="settings-divider"></div>

      <div class="settings-row settings-row--between">
        <div>
          <p class="settings-row__label">Default device</p>
          <p class="settings-row__sub">Your preferred output</p>
        </div>
        <select class="select settings-select" data-settings-device>
          <option value="vr">VR Headset</option>
          <option value="mobile" selected>Mobile 360</option>
          <option value="desktop">Desktop Screen</option>
        </select>
      </div>

    </div>
  </section>

  <!-- Account -->
  <section class="settings-section">
    <h2 class="settings-section__title">Account</h2>
    <div class="card settings-card">
      <div class="settings-row">
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
          @csrf
          <button class="btn btn-ghost settings-danger-btn" type="submit">
            <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Log Out
          </button>
        </form>
      </div>
    </div>
  </section>

</section><!-- /.settings-main -->

<nav class="settings-bottom-nav" aria-label="App sections">
  <a href="{{ route('dashboard') }}">Home</a>
  <a href="{{ route('library') }}">Library</a>
  <a href="{{ route('progress.tracking') }}">Insights</a>
  <a href="{{ route('therapy.sessions') }}">Session</a>
</nav>
@endsection

@section('scripts')
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/notifications.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var prefs = window.NHNotifications ? window.NHNotifications.getPrefs() : {};

  // Notification toggle
  var notifToggle = document.getElementById('notif-enabled');
  var notifFreq = document.getElementById('notif-frequency');
  var notifStatus = document.querySelector('[data-settings-notif-status]');
  var freqRow = document.getElementById('notif-frequency-row');

  function updateNotifStatus() {
    if (!window.NHNotifications) return;
    var perm = window.NHNotifications.getPermission();
    if (perm === 'denied') {
      notifStatus.textContent = 'Notifications are blocked in your browser settings.';
      notifToggle.disabled = true;
    } else if (perm === 'granted' && prefs.enabled) {
      notifStatus.textContent = 'Notifications are active.';
    } else {
      notifStatus.textContent = '';
    }
    freqRow.style.opacity = prefs.enabled ? '1' : '0.4';
    freqRow.style.pointerEvents = prefs.enabled ? '' : 'none';
  }

  if (notifToggle) {
    notifToggle.checked = !!prefs.enabled;
    if (notifFreq) notifFreq.value = prefs.frequency || 'medium';
    updateNotifStatus();

    notifToggle.addEventListener('change', function () {
      if (!window.NHNotifications) return;
      if (notifToggle.checked) {
        window.NHNotifications.requestPermission(function (result) {
          if (result === 'granted') {
            prefs.enabled = true;
            prefs.frequency = notifFreq ? notifFreq.value : 'medium';
            window.NHNotifications.savePrefs(prefs);
            window.NHNotifications.registerSW(function (err) {
              if (!err) window.NHNotifications.startNotifications(prefs.frequency);
            });
          } else {
            notifToggle.checked = false;
            prefs.enabled = false;
            window.NHNotifications.savePrefs(prefs);
          }
          updateNotifStatus();
        });
      } else {
        prefs.enabled = false;
        window.NHNotifications.savePrefs(prefs);
        window.NHNotifications.stopNotifications();
        updateNotifStatus();
      }
    });

    if (notifFreq) {
      notifFreq.addEventListener('change', function () {
        prefs.frequency = notifFreq.value;
        window.NHNotifications.savePrefs(prefs);
        if (prefs.enabled) window.NHNotifications.startNotifications(prefs.frequency);
      });
    }
  }

  // Theme
  var themeSelect = document.querySelector('[data-settings-theme]');
  var savedTheme = localStorage.getItem('nh_theme') || 'auto';
  if (themeSelect) {
    themeSelect.value = savedTheme;
    themeSelect.addEventListener('change', function () {
      localStorage.setItem('nh_theme', themeSelect.value);
      applyTheme(themeSelect.value);
    });
  }

  function applyTheme(theme) {
    document.documentElement.removeAttribute('data-theme');
    if (theme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    else if (theme === 'light') document.documentElement.setAttribute('data-theme', 'light');
  }
  applyTheme(savedTheme);

  // Reduced motion
  var motionToggle = document.querySelector('[data-settings-reduced-motion]');
  if (motionToggle) {
    motionToggle.checked = localStorage.getItem('nh_reduced_motion') === '1';
    motionToggle.addEventListener('change', function () {
      localStorage.setItem('nh_reduced_motion', motionToggle.checked ? '1' : '0');
      document.documentElement.classList.toggle('reduce-motion', motionToggle.checked);
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
});
</script>
@endsection