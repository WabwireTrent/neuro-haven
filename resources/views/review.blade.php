@extends('layouts.dashboard')

@section('title', 'Session Review')
@section('page', 'review')

@section('content')
<header class="review-header">
  <button class="review-header__btn" type="button" aria-label="Close" onclick="window.location.href='{{ route('dashboard') }}'">
    <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
      <line x1="18" y1="6" x2="6" y2="18"/>
      <line x1="6" y1="6" x2="18" y2="18"/>
    </svg>
  </button>
  <h1 class="review-header__title">Session Review</h1>
</header>

<section class="review-main">
  <section class="review-hero">
    <div class="review-badge" aria-hidden="true">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    </div>
    <div>
      <h1>Session Complete!</h1>
      <p class="review-hero__session" id="review-session-name">Neuro Haven: Deep Focus</p>
      <p class="review-hero__time">{{ now()->format('l, M j • g:i A') }}</p>
    </div>
  </section>

  <section class="review-stats">
    <article class="card review-stat">
      <div class="review-stat__label">Duration</div>
      <div class="review-stat__value">{{ session('session_duration', '15') }} min</div>
    </article>
    <article class="card review-stat">
      <div class="review-stat__label">Breaths</div>
      <div class="review-stat__value">{{ intval(session('session_duration', '15')) * 8 }} cycles</div>
    </article>
    <article class="card review-stat">
      <div class="review-stat__label">Mood Lift</div>
      <div class="review-stat__value">+{{ rand(20, 35) }}%</div>
    </article>
    <article class="card review-stat">
      <div class="review-stat__label">Streak</div>
      <div class="review-stat__value">{{ Auth::user()->getCurrentStreak() ?? 0 }} Days</div>
    </article>
  </section>

  <section class="review-section">
    <h2>How do you feel now?</h2>
    <div class="review-mood-row" data-review-moods>
      <button class="review-mood-btn" type="button" data-review-mood="awful">
        <span class="review-mood-btn__face">😔</span>
      </button>
      <button class="review-mood-btn" type="button" data-review-mood="down">
        <span class="review-mood-btn__face">😕</span>
      </button>
      <button class="review-mood-btn" type="button" data-review-mood="okay">
        <span class="review-mood-btn__face">😐</span>
      </button>
      <button class="review-mood-btn" type="button" data-review-mood="good">
        <span class="review-mood-btn__face">🙂</span>
      </button>
      <button class="review-mood-btn is-selected" type="button" data-review-mood="great">
        <span class="review-mood-btn__face">😊</span>
      </button>
    </div>
  </section>

  <section class="review-section">
    <h2>Quick Reflection</h2>
    <textarea class="review-input" placeholder="What's on your mind?" rows="4" name="reflection"></textarea>
  </section>

  <section class="review-reminder">
    <div class="review-reminder__main">
      <div class="review-reminder__icon" aria-hidden="true">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
      </div>
      <div class="review-reminder__copy">
        <p>Keep the momentum</p>
        <p>Next session: Tomorrow</p>
      </div>
    </div>
    <button class="btn btn-primary btn-sm" type="button">Set Reminder</button>
  </section>

  <section class="review-actions">
    <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}">Go to Dashboard</a>
    <a class="btn btn-ghost btn-lg" href="{{ route('library') }}">Try Another Session</a>
  </section>
</section>

<nav class="review-bottom-nav" aria-label="Review sections">
  <a href="{{ route('dashboard') }}">Home</a>
  <a href="{{ route('library') }}">Library</a>
  <a class="is-active" href="{{ route('progress.tracking') }}">Insights</a>
  <a href="{{ route('therapy.sessions') }}">Session</a>
</nav>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Get environment name from localStorage
  var envName = localStorage.getItem('nh_session_environment') || 'Neuro Haven';
  document.getElementById('review-session-name').textContent = envName + ': Deep Focus';
});

document.addEventListener("click", function (event) {
  var button = event.target.closest("[data-review-mood]");
  if (!button) return;
  document.querySelectorAll("[data-review-mood]").forEach(function (item) {
    item.classList.remove("is-selected");
  });
  button.classList.add("is-selected");
});

// Save reflection on input
document.querySelector('textarea[name="reflection"]').addEventListener('input', function() {
  localStorage.setItem('nh_session_reflection', this.value);
});
</script>
@endsection