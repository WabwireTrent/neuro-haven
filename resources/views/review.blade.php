@extends('layouts.app')

@section('title', 'Session Review')
@section('page', 'review')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Session Review</h1>
    <p id="review-session-name">Neuro Haven: Deep Focus &mdash; {{ now()->format('l, M j • g:i A') }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      Close
    </a>
  </div>
</div>

<div style="text-align: center; padding: 1.5rem 0;">
  <div style="width: 64px; height: 64px; border-radius: var(--radius-2xl); background: var(--color-success-soft); color: var(--color-success); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
  </div>
  <h2 style="margin: 0 0 0.25rem;">Session Complete!</h2>
  <p class="text-muted" style="margin: 0;">Great job completing your therapy session</p>
</div>

<div class="stats-grid" style="margin-bottom: 1.5rem;">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Duration</p>
    <p class="stat-card__value">{{ session('session_duration', '15') }} <span style="font-size: 0.875rem; font-weight: 600; color: var(--color-text-muted);">min</span></p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Breaths</p>
    <p class="stat-card__value">{{ intval(session('session_duration', '15')) * 8 }} <span style="font-size: 0.875rem; font-weight: 600; color: var(--color-text-muted);">cycles</span></p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Mood Lift</p>
    <p class="stat-card__value" style="color: var(--color-success);">+{{ rand(20, 35) }}%</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Streak</p>
    <p class="stat-card__value">{{ Auth::user()->getCurrentStreak() ?? 0 }} <span style="font-size: 0.875rem; font-weight: 600; color: var(--color-text-muted);">days</span></p>
  </div>
</div>

<div class="widget-grid">
  <div>
    <div class="card">
      <div class="card-header">
        <h4 class="card-header__title">How do you feel now?</h4>
      </div>
      <div class="card-body">
        <div style="display: flex; gap: 0.5rem; justify-content: space-between;">
          @php $moodOptions = ['awful'=>'😔', 'down'=>'😕', 'okay'=>'😐', 'good'=>'🙂', 'great'=>'😊']; @endphp
          @foreach($moodOptions as $key => $emoji)
            <button class="review-mood-btn" data-review-mood="{{ $key }}" style="flex:1;display:flex;flex-direction:column;align-items:center;gap:0.25rem;padding:0.75rem 0.25rem;border:2px solid var(--color-border);border-radius:var(--radius-xl);background:var(--color-surface);cursor:pointer;transition:all 0.2s ease;">
              <span style="font-size:1.75rem;line-height:1;">{{ $emoji }}</span>
              <span style="font-size:0.55rem;font-weight:600;color:var(--color-text-muted);text-transform:uppercase;">{{ $key }}</span>
            </button>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class="card">
      <div class="card-header">
        <h4 class="card-header__title">Quick Reflection</h4>
      </div>
      <div class="card-body">
        <textarea class="form-textarea" placeholder="What's on your mind?" rows="4" name="reflection" style="min-height: 100px;"></textarea>
      </div>
    </div>
  </div>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
  <div class="card-body" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
      <div style="width: 40px; height: 40px; border-radius: var(--radius-lg); background: var(--color-primary-soft); color: var(--color-primary); display: flex; align-items: center; justify-content: center;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      </div>
      <div>
        <p style="font-weight: 600; margin: 0; font-size: 0.9rem;">Keep the momentum</p>
        <p class="text-muted" style="margin: 0.1rem 0 0; font-size: 0.8rem;">Next session: Tomorrow</p>
      </div>
    </div>
    <button class="btn btn-primary btn-sm">Set Reminder</button>
  </div>
</div>

<div style="display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap;">
  <button class="btn btn-primary btn-lg" id="save-report-btn" onclick="submitSessionReport()">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    Save &amp; Send Report
  </button>
  <a class="btn btn-ghost btn-lg" href="{{ route('vr.assets') }}">Try Another Session</a>
</div>

<script>
var selectedMood = null;

document.addEventListener('DOMContentLoaded', function() {
  var envName = localStorage.getItem('nh_session_environment') || 'Neuro Haven';
  document.getElementById('review-session-name').textContent = envName + ': Deep Focus';

  document.querySelectorAll('.review-mood-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.review-mood-btn').forEach(function(b) {
        b.style.borderColor = 'var(--color-border)';
        b.style.background = 'var(--color-surface)';
      });
      this.style.borderColor = 'var(--color-primary)';
      this.style.background = 'var(--color-primary-soft)';
      selectedMood = this.getAttribute('data-review-mood');
    });
  });

  var reflection = localStorage.getItem('nh_session_reflection');
  if (reflection) {
    document.querySelector('textarea[name="reflection"]').value = reflection;
  }

  var sessionId = localStorage.getItem('nh_session_id');
  if (!sessionId) {
    var btn = document.getElementById('save-report-btn');
    if (btn) btn.textContent = 'Go to Dashboard';
  }
});

document.querySelector('textarea[name="reflection"]').addEventListener('input', function() {
  localStorage.setItem('nh_session_reflection', this.value);
});

function submitSessionReport() {
  var btn = document.getElementById('save-report-btn');
  var sessionId = localStorage.getItem('nh_session_id');
  var duration = parseInt(localStorage.getItem('nh_session_duration') || '5');
  var notes = document.querySelector('textarea[name="reflection"]')?.value || '';

  if (!sessionId) {
    window.location.href = '{{ route("dashboard") }}';
    return;
  }

  var moodScale = null;
  var moodMap = { awful: 2, down: 4, okay: 5, good: 7, great: 9 };
  if (selectedMood && moodMap[selectedMood]) {
    moodScale = moodMap[selectedMood];
  }

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner" style="width:16px;height:16px;border-width:2px;display:inline-block;margin-right:0.375rem;"></span> Saving...';

  fetch('/api/vr-sessions/end', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      session_id: parseInt(sessionId),
      session_duration: duration * 60,
      mood_after: moodScale,
      session_quality: moodScale ? Math.max(1, Math.min(5, Math.ceil(moodScale / 2))) : null,
      notes: notes
    })
  })
  .then(function(r) { return r.json(); })
  .then(function(data) {
    if (data.success) {
      localStorage.removeItem('nh_session_id');
      localStorage.removeItem('nh_session_completed');
      localStorage.removeItem('nh_session_completed_at');
      localStorage.removeItem('nh_session_environment');
      localStorage.removeItem('nh_session_duration');
      localStorage.removeItem('nh_session_device');
      localStorage.removeItem('nh_session_reflection');
      btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Report Sent!';
      btn.className = 'btn btn-success btn-lg';
      btn.disabled = false;
      setTimeout(function() { window.location.href = '{{ route("dashboard") }}'; }, 1500);
    } else {
      btn.disabled = false;
      btn.textContent = 'Save & Send Report';
      alert('Failed to save report. Please try again.');
    }
  })
  .catch(function() {
    btn.disabled = false;
    btn.textContent = 'Save & Send Report';
    alert('Network error. Please try again.');
  });
}
</script>
@endsection
