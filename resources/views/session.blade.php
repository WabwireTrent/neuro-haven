@extends('layouts.app')

@section('title', 'Session Setup')
@section('page', 'session')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Session Setup</h1>
    <p>Configure your VR therapy experience</p>
  </div>
</div>

<div class="widget-grid" style="margin-bottom: 1.5rem;">
  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Session Preview</h4>
        <span class="badge badge--secondary" id="session-env-title">Neuro Haven</span>
      </div>
      <div class="card-body">
        <div id="session-preview">
          <div style="height: 200px; background: linear-gradient(135deg, #1a2a4a, #3b82f6); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.4), transparent);"></div>
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2"/><path d="M10 16l2-2 2 2"/><path d="M8 12h8"/></svg>
          </div>
        </div>
        <div id="session-video-player" style="display:none;">
          <div style="position:relative;width:100%;padding-bottom:56.25%;background:#000;border-radius:var(--radius-xl);overflow:hidden;">
            <div id="session-iframe" style="position:absolute;top:0;left:0;width:100%;height:100%;"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title" id="session-title">Neuro Haven</h4>
        <span id="session-timer-header" class="badge badge--primary" style="display:none;font-size:0.85rem;font-variant-numeric:tabular-nums;">00:00</span>
      </div>
      <div class="card-body">
        <p class="text-muted" id="session-description" style="font-size: 0.9rem;">A tranquil digital sanctuary designed to synchronize your neural pathways through bio-adaptive soundscapes and lush visual geometry.</p>
        <div class="tabs" id="session-tags-section" style="margin-bottom: 0; margin-top: 0.75rem; border-bottom: none;">
          <span class="badge badge--secondary" style="margin-right: 0.375rem;">Calming</span>
          <span class="badge badge--secondary" style="margin-right: 0.375rem;">Nature</span>
          <span class="badge badge--secondary">Immersive</span>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Quick Stats</h4>
      </div>
      <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ Auth::user()->vrSessions()->count() }}</div>
            <div class="kpi-card__label">Completed</div>
          </div>
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">+{{ number_format(Auth::user()->moods()->avg('mood_scale') ?? 3 * 10, 0) }}%</div>
            <div class="kpi-card__label">Mood Lift</div>
          </div>
        </div>
      </div>
    </div>

    <div id="session-setup-sections">
      <div class="card" style="margin-bottom: 1.25rem;">
        <div class="card-header">
          <h4 class="card-header__title">Session Duration</h4>
        </div>
        <div class="card-body">
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
            <label class="session-choice" style="display:block;cursor:pointer;">
              <input type="radio" name="duration" value="5" checked style="position:absolute;opacity:0;pointer-events:none;">
              <span style="display:flex;align-items:center;justify-content:center;padding:0.75rem;border:2px solid var(--color-border);border-radius:var(--radius-lg);font-weight:600;font-size:0.85rem;transition:all var(--transition-fast);">5 min</span>
            </label>
            <label class="session-choice" style="display:block;cursor:pointer;">
              <input type="radio" name="duration" value="15" style="position:absolute;opacity:0;pointer-events:none;">
              <span style="display:flex;align-items:center;justify-content:center;padding:0.75rem;border:2px solid var(--color-border);border-radius:var(--radius-lg);font-weight:600;font-size:0.85rem;transition:all var(--transition-fast);">15 min</span>
            </label>
            <label class="session-choice" style="display:block;cursor:pointer;">
              <input type="radio" name="duration" value="30" style="position:absolute;opacity:0;pointer-events:none;">
              <span style="display:flex;align-items:center;justify-content:center;padding:0.75rem;border:2px solid var(--color-border);border-radius:var(--radius-lg);font-weight:600;font-size:0.85rem;transition:all var(--transition-fast);">30 min</span>
            </label>
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom: 1.25rem;">
        <div class="card-header">
          <h4 class="card-header__title">Output Device</h4>
        </div>
        <div class="card-body" style="display: grid; gap: 0.5rem;">
          <label class="session-choice" style="display:block;cursor:pointer;">
            <input type="radio" name="device" value="vr" checked style="position:absolute;opacity:0;pointer-events:none;">
            <span style="display:flex;align-items:center;gap:0.75rem;padding:0.875rem;border:2px solid var(--color-border);border-radius:var(--radius-lg);transition:all var(--transition-fast);">
              <span style="width:40px;height:40px;border-radius:var(--radius-lg);background:var(--color-surface-muted);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--color-text-secondary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9z"/><circle cx="8.5" cy="12" r="2"/><circle cx="15.5" cy="12" r="2"/></svg>
              </span>
              <span><strong style="display:block;font-size:0.85rem;">VR Headset</strong><span style="font-size:0.75rem;color:var(--color-text-muted);">Best Experience</span></span>
              <span data-vr-badge style="margin-left:auto;padding:0.175rem 0.5rem;background:var(--color-success);color:#fff;border-radius:var(--radius-full);font-size:0.65rem;font-weight:700;text-transform:uppercase;">Best</span>
              <span data-vr-status-badge class="badge badge--neutral" style="display:none;font-size:0.6rem;">Detecting...</span>
            </span>
          </label>
          <label class="session-choice" style="display:block;cursor:pointer;">
            <input type="radio" name="device" value="mobile" style="position:absolute;opacity:0;pointer-events:none;">
            <span style="display:flex;align-items:center;gap:0.75rem;padding:0.875rem;border:2px solid var(--color-border);border-radius:var(--radius-lg);transition:all var(--transition-fast);">
              <span style="width:40px;height:40px;border-radius:var(--radius-lg);background:var(--color-surface-muted);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--color-text-secondary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
              </span>
              <span><strong style="display:block;font-size:0.85rem;">Mobile 360</strong><span style="font-size:0.75rem;color:var(--color-text-muted);">Portable immersive view</span></span>
            </span>
          </label>
          <label class="session-choice" style="display:block;cursor:pointer;">
            <input type="radio" name="device" value="desktop" style="position:absolute;opacity:0;pointer-events:none;">
            <span style="display:flex;align-items:center;gap:0.75rem;padding:0.875rem;border:2px solid var(--color-border);border-radius:var(--radius-lg);transition:all var(--transition-fast);">
              <span style="width:40px;height:40px;border-radius:var(--radius-lg);background:var(--color-surface-muted);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--color-text-secondary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
              </span>
              <span><strong style="display:block;font-size:0.85rem;">Desktop Screen</strong><span style="font-size:0.75rem;color:var(--color-text-muted);">Guided visual mode</span></span>
            </span>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
  <div class="card-header">
    <h4 class="card-header__title">What to Expect</h4>
  </div>
  <div class="card-body">
    <div class="timeline">
      <div class="timeline__item">
        <div class="timeline__dot">1</div>
        <h5 style="margin: 0 0 0.125rem;">Neural Calibration</h5>
        <p class="text-muted text-sm" style="margin: 0;">First 2 minutes focus on breathing and eye tracking to sync the visuals with your heart rate.</p>
      </div>
      <div class="timeline__item">
        <div class="timeline__dot">2</div>
        <h5 style="margin: 0 0 0.125rem;">Deep Immersion</h5>
        <p class="text-muted text-sm" style="margin: 0;">Environment expansion. Audio shifts to spatial binaural beats for cognitive relaxation.</p>
      </div>
      <div class="timeline__item">
        <div class="timeline__dot">3</div>
        <h5 style="margin: 0 0 0.125rem;">Gentle Ascent</h5>
        <p class="text-muted text-sm" style="margin: 0;">Visuals fade to soft light as you are guided back to a focused, energized state.</p>
      </div>
    </div>
  </div>
</div>

<div style="display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap;">
  <button class="btn btn-primary btn-lg" onclick="launchSession()">Launch Session</button>
  <a class="btn btn-ghost btn-lg" href="{{ route('vr.assets') }}">Browse Assets</a>
</div>

<style>
.session-choice input:checked + span,
.session-choice input:checked + span > span:first-of-type { border-color: var(--color-primary) !important; background: var(--color-primary-soft) !important; color: var(--color-primary) !important; }
</style>
@endsection

@section('scripts')
<script>
var environments = [
  { id: "calm-forest", title: "Calm Forest", durationMinutes: 15, videoUrl: "https://www.youtube.com/embed/1ZYbU82FDwE", description: "Immerse yourself in a serene forest sanctuary with gentle birdsong and rustling leaves." },
  { id: "ocean-horizon", title: "Ocean Horizon", durationMinutes: 20, videoUrl: "https://www.youtube.com/embed/xXLcW8oGWh4", description: "Watch soothing ocean waves under a peaceful horizon for deep relaxation." },
  { id: "mountain-retreat", title: "Mountain Retreat", durationMinutes: 10, videoUrl: "https://www.youtube.com/embed/L1mKOq3o3so", description: "Experience breathtaking alpine views and crisp mountain air." },
  { id: "colour-therapy", title: "Colour Therapy", durationMinutes: 30, videoUrl: "https://www.youtube.com/embed/6r_OkTj9PIE", description: "Harmonize your mind with vibrant color gradients and soothing transitions." },
  { id: "desert-sunrise", title: "Desert Sunrise", durationMinutes: 15, videoUrl: "https://www.youtube.com/embed/Io_pnSHB_k0", description: "Witness a golden desert sunrise awakening your senses and energy." },
  { id: "rainy-afternoon", title: "Rainy Afternoon", durationMinutes: 45, videoUrl: "https://www.youtube.com/embed/l8QfxUDKWzY", description: "Drift into calm with gentle rain sounds and peaceful atmospheric visuals." }
];

var sessionActive = false;
var sessionStartTime = null;
var sessionDurationMinutes = 5;
var timerInterval = null;

document.addEventListener('DOMContentLoaded', function () {
  var searchParams = new URLSearchParams(window.location.search);
  var environmentId = searchParams.get('environment');

  if (environmentId) {
    sessionActive = true;
    var env = environments.find(function(e) { return e.id === environmentId; });
    if (env) {
      document.getElementById('session-env-title').textContent = env.title;
      document.getElementById('session-title').textContent = env.title;
      document.getElementById('session-description').textContent = env.description;
      sessionDurationMinutes = env.durationMinutes;
      document.getElementById('session-preview').style.display = 'none';
      document.getElementById('session-tags-section').style.display = 'none';
      document.getElementById('session-video-player').style.display = 'block';
      document.getElementById('session-setup-sections').style.display = 'none';
      document.querySelector('.page-header').style.display = 'none';
      document.querySelector('[style*="justify-content: center"]').style.display = 'none';
      createAndPlayVideo(env.videoUrl + '?autoplay=1');
      localStorage.setItem('nh_session_duration', sessionDurationMinutes);
      localStorage.setItem('nh_session_environment', env.title);
      startSessionTimer(sessionDurationMinutes * 60);
    }
  } else {
    const durationInputs = document.querySelectorAll('input[name="duration"]');
    const deviceInputs = document.querySelectorAll('input[name="device"]');
    const savedDuration = localStorage.getItem('nh_session_duration') || '5';
    const savedDevice = localStorage.getItem('nh_session_device') || 'vr';
    durationInputs.forEach(input => { if (input.value === savedDuration) input.checked = true; });
    deviceInputs.forEach(input => { if (input.value === savedDevice) input.checked = true; });
    durationInputs.forEach(input => {
      input.addEventListener('change', function() { localStorage.setItem('nh_session_duration', this.value); });
    });
    deviceInputs.forEach(input => {
      input.addEventListener('change', function() { localStorage.setItem('nh_session_device', this.value); });
    });

    updateVRStatusBadge();
    if (window.VRDetector) {
      VRDetector.addEventListener(updateVRStatusBadge);
    }
  }
});

function updateVRStatusBadge() {
  var badge = document.querySelector('[data-vr-status-badge]');
  var bestBadge = document.querySelector('[data-vr-badge]');
  var vrInput = document.querySelector('input[name="device"][value="vr"]');
  if (!badge || !vrInput) return;

  if (!window.VRDetector) { badge.style.display = 'none'; return; }

  var state = VRDetector.getState();

  if (state.status === 'connected') {
    bestBadge.style.display = 'none';
    badge.style.display = 'inline-flex';
    badge.className = 'badge badge--success';
    badge.textContent = state.headsetName ? state.headsetName + ' Connected' : 'Headset Connected';
    vrInput.checked = true;
    localStorage.setItem('nh_session_device', 'vr');
  } else if (state.status === 'not-connected') {
    badge.style.display = 'inline-flex';
    badge.className = 'badge badge--neutral';
    badge.textContent = 'No Headset Detected';
    bestBadge.style.display = 'inline-flex';
  } else if (state.status === 'unsupported') {
    badge.style.display = 'inline-flex';
    badge.className = 'badge badge--danger';
    badge.textContent = 'VR Not Supported';
    bestBadge.style.display = 'inline-flex';
  } else {
    badge.style.display = 'inline-flex';
    badge.className = 'badge badge--neutral';
    badge.textContent = 'Detecting...';
    bestBadge.style.display = 'inline-flex';
  }
}

var currentSessionId = null;

function launchSession() {
  var durationInput = document.querySelector('input[name="duration"]:checked');
  var deviceInput = document.querySelector('input[name="device"]:checked');
  var duration = durationInput ? parseInt(durationInput.value) : 5;
  var device = deviceInput ? deviceInput.value : 'vr';

  // Check VR headset if VR device is selected
  if (device === 'vr' && window.VRDetector) {
    var state = VRDetector.getState();
    if (state.status === 'not-connected' || state.status === 'unsupported') {
      showSessionHeadsetWarning(state);
      return;
    }
  }

  localStorage.setItem('nh_session_duration', duration);
  localStorage.setItem('nh_session_device', device);

  var env = environments[0];
  document.getElementById('session-env-title').textContent = env.title;
  document.getElementById('session-title').textContent = env.title;
  document.getElementById('session-description').textContent = env.description;

  document.getElementById('session-preview').style.display = 'none';
  document.getElementById('session-tags-section').style.display = 'none';
  document.getElementById('session-setup-sections').style.display = 'none';
  document.querySelector('.page-header').style.display = 'none';

  var actions = document.querySelector('[style*="justify-content: center"]');
  if (actions) actions.style.display = 'none';

  createAndPlayVideo(env.videoUrl + '?autoplay=1');
  document.getElementById('session-video-player').style.display = 'block';

  localStorage.setItem('nh_session_environment', env.title);

  fetch('/api/vr-sessions/start', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      vr_asset_id: 1,
      vr_asset_title: env.title,
      device_type: device
    })
  })
  .then(function(r) { return r.json(); })
  .then(function(data) {
    if (data.id) currentSessionId = data.id;
  })
  .catch(function(e) { console.error('Failed to create session record', e); });

  startSessionTimer(duration * 60);
}

function startSessionTimer(seconds) {
  sessionStartTime = Date.now();
  var endTime = sessionStartTime + (seconds * 1000);

  var timerEl = document.getElementById('session-timer-header');
  if (timerEl) timerEl.style.display = 'inline-flex';

  function updateTimer() {
    var now = Date.now();
    var remaining = Math.max(0, endTime - now);
    var mins = Math.floor(remaining / 60000);
    var secs = Math.floor((remaining % 60000) / 1000);
    var formatted = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
    if (timerEl) timerEl.textContent = formatted;
    if (remaining <= 0) { clearInterval(timerInterval); endSession(); }
  }
  updateTimer();
  timerInterval = setInterval(updateTimer, 1000);
}

function showSessionHeadsetWarning(state) {
    var existing = document.getElementById('headset-warning-modal');
    if (existing) existing.remove();

    var isUnsupported = state.status === 'unsupported';

    var warning = document.createElement('div');
    warning.id = 'headset-warning-modal';
    warning.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:9999;display:flex;align-items:center;justify-content:center;';

    warning.innerHTML = `
        <div style="background:var(--color-surface);border-radius:var(--radius-2xl);padding:2rem;max-width:480px;width:90%;text-align:center;">
            <div style="font-size:3.5rem;margin-bottom:1rem;">${isUnsupported ? '🚫' : '🖥️'}</div>
            <h3 style="margin:0 0 0.5rem;">${isUnsupported ? 'VR Not Supported' : 'Headset Not Detected'}</h3>
            <p style="color:var(--color-text-secondary);margin:0 0 1.5rem;">
                ${isUnsupported
                    ? 'Your browser does not support WebXR. Please use a WebXR-enabled browser.'
                    : 'We could not detect your VR headset. Please ensure it is connected and powered on, then try again.'
                }
            </p>
            <div style="padding:1rem;background:var(--color-surface-muted);border-radius:var(--radius-xl);margin-bottom:1.5rem;text-align:left;">
                <p style="font-weight:600;margin:0 0 0.5rem;font-size:0.85rem;">Quick tips:</p>
                <ul style="margin:0;padding-left:1.25rem;font-size:0.8rem;color:var(--color-text-secondary);display:flex;flex-direction:column;gap:0.25rem;">
                    <li>Connect your headset via USB Link cable or Air Link</li>
                    <li>Make sure the headset is powered on</li>
                    <li>Enable Oculus Link from the headset menu</li>
                    <li>Restart the Oculus app on your PC</li>
                </ul>
            </div>
            <button onclick="document.getElementById('headset-warning-modal').remove()" class="btn btn-primary">OK</button>
        </div>
    `;

    document.body.appendChild(warning);
}

function endSession() {
  clearInterval(timerInterval);
  localStorage.setItem('nh_session_completed', 'true');
  localStorage.setItem('nh_session_completed_at', new Date().toISOString());
  if (currentSessionId) localStorage.setItem('nh_session_id', currentSessionId);
  window.location.href = '{{ route("review") }}';
}

function createAndPlayVideo(url) {
  var container = document.getElementById('session-iframe');
  while (container.firstChild) { container.removeChild(container.firstChild); }
  var iframe = document.createElement('iframe');
  iframe.src = url;
  iframe.setAttribute('frameborder', '0');
  iframe.setAttribute('allow', 'autoplay; encrypted-media');
  iframe.setAttribute('allowfullscreen', 'true');
  iframe.style.width = '100%';
  iframe.style.height = '100%';
  iframe.style.border = 'none';
  container.appendChild(iframe);
}
</script>
@endsection
