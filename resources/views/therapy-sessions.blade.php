@extends('layouts.app')

@section('title', 'Therapy Sessions')
@section('page', 'sessions')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Therapy Sessions</h1>
    <p>Browse guided sessions to improve sleep, focus, and emotional resilience.</p>
  </div>
</div>

<div id="session-grid">
  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
    @foreach([
      ['title' => 'Mindfulness Basics', 'desc' => 'Learn the fundamentals of mindfulness meditation.', 'duration' => 10, 'level' => 'Beginner', 'icon' => '🧘'],
      ['title' => 'Stress Release', 'desc' => 'Release tension and find calm through guided techniques.', 'duration' => 15, 'level' => 'Intermediate', 'icon' => '🌊'],
      ['title' => 'Sleep Preparation', 'desc' => 'Wind down with a soothing session designed for restful sleep.', 'duration' => 12, 'level' => 'Guided', 'icon' => '🌙'],
      ['title' => 'Anxiety Management', 'desc' => 'Tools and techniques to manage anxious thoughts.', 'duration' => 20, 'level' => 'Intermediate', 'icon' => '🫁'],
      ['title' => 'Focus & Clarity', 'desc' => 'Sharpen your concentration and mental clarity.', 'duration' => 15, 'level' => 'All Levels', 'icon' => '🎯'],
      ['title' => 'Emotional Balance', 'desc' => 'Find equilibrium and emotional stability.', 'duration' => 18, 'level' => 'All Levels', 'icon' => '⚖️'],
    ] as $i => $session)
      <div class="card" style="display: flex; flex-direction: column;">
        <div style="padding: 1.25rem 1.25rem 0;">
          <div style="width: 48px; height: 48px; border-radius: var(--radius-lg); background: var(--color-primary-soft); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 0.75rem;">{{ $session['icon'] }}</div>
          <h4 style="margin: 0 0 0.25rem;">{{ $session['title'] }}</h4>
          <p class="text-muted" style="font-size: 0.85rem; margin: 0 0 0.75rem;">{{ $session['desc'] }}</p>
        </div>
        <div style="padding: 0 1.25rem; margin-top: auto;">
          <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
            <span class="badge badge--primary">{{ $session['duration'] }} min</span>
            <span class="badge badge--secondary">{{ $session['level'] }}</span>
          </div>
          <button class="btn btn-primary" style="width: 100%; margin-bottom: 1.25rem;" onclick="startTherapySession({{ $i }})">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            Start Session
          </button>
        </div>
      </div>
    @endforeach
  </div>
</div>

{{-- Session Player --}}
<div id="therapy-player" style="display: none;">
  <div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-header">
      <h4 class="card-header__title" id="therapy-session-title">Session</h4>
      <span id="therapy-timer" class="badge badge--primary" style="font-size:0.85rem;font-variant-numeric:tabular-nums;">00:00</span>
    </div>
    <div class="card-body">
      <div style="position:relative;width:100%;padding-bottom:56.25%;background:#000;border-radius:var(--radius-xl);overflow:hidden;">
        <div id="therapy-iframe" style="position:absolute;top:0;left:0;width:100%;height:100%;"></div>
      </div>
    </div>
  </div>
  <div style="display: flex; justify-content: center;">
    <button class="btn btn-danger btn-lg" onclick="endTherapySession()" style="background:#ef4444;color:#fff;border:none;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
      End Session
    </button>
  </div>
</div>

<script>
var therapySessions = [
  { id: 'mindfulness-basics', title: 'Mindfulness Basics', description: 'Learn the fundamentals of mindfulness meditation.', durationMinutes: 10, videoUrl: 'https://www.youtube.com/embed/1ZYbU82FDwE' },
  { id: 'stress-release', title: 'Stress Release', description: 'Release tension and find calm through guided techniques.', durationMinutes: 15, videoUrl: 'https://www.youtube.com/embed/xXLcW8oGWh4' },
  { id: 'sleep-preparation', title: 'Sleep Preparation', description: 'Wind down with a soothing session designed for restful sleep.', durationMinutes: 12, videoUrl: 'https://www.youtube.com/embed/L1mKOq3o3so' },
  { id: 'anxiety-management', title: 'Anxiety Management', description: 'Tools and techniques to manage anxious thoughts.', durationMinutes: 20, videoUrl: 'https://www.youtube.com/embed/6r_OkTj9PIE' },
  { id: 'focus-clarity', title: 'Focus & Clarity', description: 'Sharpen your concentration and mental clarity.', durationMinutes: 15, videoUrl: 'https://www.youtube.com/embed/Io_pnSHB_k0' },
  { id: 'emotional-balance', title: 'Emotional Balance', description: 'Find equilibrium and emotional stability.', durationMinutes: 18, videoUrl: 'https://www.youtube.com/embed/l8QfxUDKWzY' },
];

var therapyInterval = null;

function startTherapySession(index) {
  var env = therapySessions[index];
  if (!env) return;

  document.getElementById('session-grid').style.display = 'none';
  document.getElementById('therapy-player').style.display = 'block';
  document.getElementById('therapy-session-title').textContent = env.title;
  document.querySelector('.page-header').style.display = 'none';

  var container = document.getElementById('therapy-iframe');
  while (container.firstChild) container.removeChild(container.firstChild);
  var iframe = document.createElement('iframe');
  iframe.src = env.videoUrl + '?autoplay=1';
  iframe.setAttribute('frameborder', '0');
  iframe.setAttribute('allow', 'autoplay; encrypted-media');
  iframe.setAttribute('allowfullscreen', 'true');
  iframe.style.width = '100%';
  iframe.style.height = '100%';
  iframe.style.border = 'none';
  container.appendChild(iframe);

  var seconds = env.durationMinutes * 60;
  var endTime = Date.now() + seconds * 1000;
  var timerEl = document.getElementById('therapy-timer');

  if (therapyInterval) clearInterval(therapyInterval);
  therapyInterval = setInterval(function() {
    var remaining = Math.max(0, endTime - Date.now());
    var mins = Math.floor(remaining / 60000);
    var secs = Math.floor((remaining % 60000) / 1000);
    timerEl.textContent = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
    if (remaining <= 0) endTherapySession();
  }, 1000);
}

function endTherapySession() {
  if (therapyInterval) clearInterval(therapyInterval);
  localStorage.setItem('nh_session_completed', 'true');
  localStorage.setItem('nh_session_completed_at', new Date().toISOString());
  localStorage.setItem('nh_session_environment', document.getElementById('therapy-session-title').textContent);
  window.location.href = '{{ route("review") }}';
}
</script>
@endsection
