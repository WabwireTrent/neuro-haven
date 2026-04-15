@extends('layouts.dashboard')

@section('title', 'Session Setup')
@section('page', 'session')

@section('content')
<header class="session-header">
  <button class="session-header__btn" type="button" aria-label="Back" onclick="window.location.href='{{ route('library') }}'">
    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6"/>
    </svg>
  </button>
  <h1 class="session-header__title">Session Setup</h1>
</header>

<section class="session-main">
  <!-- Video Player -->
  <div id="session-video-player" style="display: none;">
    <div style="position: relative; width: 100%; padding-bottom: 56.25%; background: #000; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
      <div id="session-iframe" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
    </div>
    <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 8px;">
      <p style="margin: 0 0 10px; font-size: 14px; color: #666;">Session Time Remaining</p>
      <p id="session-timer" style="margin: 0; font-size: 32px; font-weight: 700; color: #1d9f76;">15:00</p>
    </div>
  </div>

  <section class="session-preview" id="session-preview">
    <div class="session-preview__media" role="img" aria-label="Abstract emerald green flowing digital geometric patterns">
      <div class="session-preview__overlay" aria-hidden="true"></div>
      <div class="session-preview__label" aria-hidden="true">
        <span class="session-preview__tag">Immersive</span>
        <p class="session-preview__title" id="session-env-title">Neuro Haven</p>
      </div>
    </div>
  </section>

  <section class="session-tags" id="session-tags-section">
    <span class="pill">Calming</span>
    <span class="pill">Nature</span>
    <span class="pill">Immersive</span>
  </section>

  <section class="session-copy">
    <h1 id="session-title">Neuro Haven</h1>
    <p id="session-description">A tranquil digital sanctuary designed to synchronize your neural pathways through bio-adaptive soundscapes and lush visual geometry.</p>
  </section>

  <section class="session-stats">
    <article class="card session-stat">
      <p class="session-stat__label">Completed</p>
      <p class="session-stat__value">{{ Auth::user()->vrSessions()->count() }} Times</p>
    </article>
    <article class="card session-stat">
      <p class="session-stat__label">Mood Lift</p>
      <p class="session-stat__value">+{{ number_format(Auth::user()->moods()->avg('mood_scale') ?? 3 * 10, 0) }}%</p>
    </article>
  </section>

  <div id="session-setup-sections">
    <section class="session-section">
      <h2>Session Duration</h2>
      <div class="session-duration-grid">
        <label class="session-choice">
          <input type="radio" name="duration" value="5" checked>
          <span class="session-duration">5 min</span>
        </label>
        <label class="session-choice">
          <input type="radio" name="duration" value="15">
          <span class="session-duration">15 min</span>
        </label>
        <label class="session-choice">
          <input type="radio" name="duration" value="30">
          <span class="session-duration">30 min</span>
        </label>
      </div>
    </section>

    <section class="session-section">
      <h2>Output Device</h2>
      <div class="session-device-list">
      <label class="session-choice">
        <input type="radio" name="device" value="vr" checked>
        <span class="session-device">
          <span class="session-device__icon">
            <!-- VR headset -->
            <svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2 9a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9z"/>
              <circle cx="8.5" cy="12" r="2"/>
              <circle cx="15.5" cy="12" r="2"/>
            </svg>
          </span>
          <span class="session-device__copy">
            <strong>VR Headset</strong>
            <span>Best Experience</span>
          </span>
          <span class="session-device__badge">Best</span>
        </span>
      </label>

      <label class="session-choice">
        <input type="radio" name="device" value="mobile">
        <span class="session-device">
          <span class="session-device__icon">
            <!-- Mobile phone -->
            <svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
              <line x1="12" y1="18" x2="12.01" y2="18"/>
            </svg>
          </span>
          <span class="session-device__copy">
            <strong>Mobile 360</strong>
            <span>Portable immersive view</span>
          </span>
        </span>
      </label>

      <label class="session-choice">
        <input type="radio" name="device" value="desktop">
        <span class="session-device">
          <span class="session-device__icon">
            <!-- Desktop monitor -->
            <svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
              <line x1="8" y1="21" x2="16" y2="21"/>
              <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
          </span>
          <span class="session-device__copy">
            <strong>Desktop Screen</strong>
            <span>Guided visual mode</span>
          </span>
        </span>
      </label>
    </div>
  </section>

  <section class="session-section">
    <h2>What to expect</h2>
    <div class="session-timeline">
      <article class="session-timeline__item">
        <span class="session-timeline__index">1</span>
        <h3>Neural Calibration</h3>
        <p>First 2 minutes focus on breathing and eye tracking to sync the visuals with your heart rate.</p>
      </article>
      <article class="session-timeline__item">
        <span class="session-timeline__index">2</span>
        <h3>Deep Immersion</h3>
        <p>Environment expansion. Audio shifts to spatial binaural beats for cognitive relaxation.</p>
      </article>
      <article class="session-timeline__item">
        <span class="session-timeline__index">3</span>
        <h3>Gentle Ascent</h3>
        <p>Visuals fade to soft light as you are guided back to a focused, energized state.</p>
      </article>
    </div>
  </section>
  </div>
</section>

<footer class="session-launch">
  <a class="btn btn-primary btn-lg" href="{{ route('review') }}">Launch Session</a>
</footer>
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
  // Get environment from URL parameter
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
      
      // Show video player instead of preview
      document.getElementById('session-preview').style.display = 'none';
      document.getElementById('session-tags-section').style.display = 'none';
      document.getElementById('session-video-player').style.display = 'block';
      document.getElementById('session-setup-sections').style.display = 'none';
      document.querySelector('.session-launch').style.display = 'none';
      
      // Create and play video
      createAndPlayVideo(env.videoUrl + '?autoplay=1');
      
      // Store session data
      localStorage.setItem('nh_session_duration', sessionDurationMinutes);
      localStorage.setItem('nh_session_environment', env.title);
      
      // Start timer
      startSessionTimer(sessionDurationMinutes * 60);
    }
  } else {
    // Show setup form
    const durationInputs = document.querySelectorAll('input[name="duration"]');
    const deviceInputs = document.querySelectorAll('input[name="device"]');

    const savedDuration = localStorage.getItem('nh_session_duration') || '5';
    const savedDevice = localStorage.getItem('nh_session_device') || 'vr';

    durationInputs.forEach(input => {
      if (input.value === savedDuration) input.checked = true;
    });

    deviceInputs.forEach(input => {
      if (input.value === savedDevice) input.checked = true;
    });

    durationInputs.forEach(input => {
      input.addEventListener('change', function() {
        localStorage.setItem('nh_session_duration', this.value);
      });
    });

    deviceInputs.forEach(input => {
      input.addEventListener('change', function() {
        localStorage.setItem('nh_session_device', this.value);
      });
    });
  }
});

function startSessionTimer(seconds) {
  sessionStartTime = Date.now();
  var endTime = sessionStartTime + (seconds * 1000);
  
  function updateTimer() {
    var now = Date.now();
    var remaining = Math.max(0, endTime - now);
    var mins = Math.floor(remaining / 60000);
    var secs = Math.floor((remaining % 60000) / 1000);
    
    document.getElementById('session-timer').textContent = 
      (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
    
    if (remaining <= 0) {
      clearInterval(timerInterval);
      endSession();
    }
  }
  
  updateTimer();
  timerInterval = setInterval(updateTimer, 1000);
}

function endSession() {
  clearInterval(timerInterval);
  // Save session completion
  localStorage.setItem('nh_session_completed', 'true');
  localStorage.setItem('nh_session_completed_at', new Date().toISOString());
  // Redirect to review
  window.location.href = '{{ route("review") }}';
}

function createAndPlayVideo(url) {
  var container = document.getElementById('session-iframe');
  // Remove old iframe if exists
  while (container.firstChild) {
    container.removeChild(container.firstChild);
  }
  
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