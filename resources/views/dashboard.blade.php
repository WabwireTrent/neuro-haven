@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page', 'dashboard')

@section('content')
<header class="dashboard-header">
  <div class="dashboard-avatar" aria-hidden="true">{{ substr(Auth::user()->name, 0, 1) }}</div>
  <div class="dashboard-greeting">
    <h1>Hello, {{ Auth::user()->name }}</h1>
    <p class="dashboard-streak">🔥 Keep up the great work!</p>
  </div>
  <div class="notif-anchor">
    <button class="dashboard-icon-btn" type="button" aria-label="Notifications" aria-expanded="false" data-notif-toggle>
      <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
      <span class="notif-badge" data-notif-badge aria-hidden="true"></span>
    </button>
    <div class="notif-panel" data-notif-panel aria-label="Notifications" hidden>
      <div class="notif-panel__head">
        <span class="notif-panel__title">Notifications</span>
        <button class="notif-panel__clear" type="button" data-notif-clear>Mark all read</button>
      </div>
      <ul class="notif-list" data-notif-list></ul>
    </div>
  </div>
  <a class="dashboard-icon-btn" href="{{ route('settings') }}" aria-label="Settings">
    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="3"/>
      <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
    </svg>
  </a>
  <form method="POST" action="{{ route('logout') }}" style="display: inline;">
    @csrf
    <button class="dashboard-icon-btn" type="submit" aria-label="Log out">
      <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
    </button>
  </form>
</header>

<section class="dashboard-main">
  <!-- Mood check-in first — most important action -->
  <section class="card dashboard-widget">
    <div class="dashboard-widget__head">
      <h2>How are you feeling?</h2>
      <span class="dashboard-widget__eyebrow">Today</span>
    </div>
    <p class="dashboard-mood-prompt">Tap to log your mood and get personalised sessions.</p>
    <div class="dashboard-mood-row" data-dashboard-mood>
      <button class="dashboard-mood-btn" data-mood="1" aria-label="Very sad">
        <span class="dashboard-mood-emoji">😢</span>
        <span class="dashboard-mood-label">Very Sad</span>
      </button>
      <button class="dashboard-mood-btn" data-mood="2" aria-label="Sad">
        <span class="dashboard-mood-emoji">😞</span>
        <span class="dashboard-mood-label">Sad</span>
      </button>
      <button class="dashboard-mood-btn" data-mood="3" aria-label="Neutral">
        <span class="dashboard-mood-emoji">😐</span>
        <span class="dashboard-mood-label">Neutral</span>
      </button>
      <button class="dashboard-mood-btn" data-mood="4" aria-label="Happy">
        <span class="dashboard-mood-emoji">😊</span>
        <span class="dashboard-mood-label">Happy</span>
      </button>
      <button class="dashboard-mood-btn" data-mood="5" aria-label="Very happy">
        <span class="dashboard-mood-emoji">😁</span>
        <span class="dashboard-mood-label">Very Happy</span>
      </button>
    </div>
  </section>

  <section class="dashboard-stats" data-dashboard-stats aria-label="Your progress stats">
    <article class="card card-stat dashboard-stat-card">
      <p class="dashboard-stat-label">Sessions This Week</p>
      <strong class="dashboard-stat-value">{{ Auth::user()->vrSessions()->where('created_at', '>=', now()->startOfWeek())->count() }}</strong>
      <p class="dashboard-stat-note">Keep it up!</p>
    </article>
    <article class="card card-stat dashboard-stat-card">
      <p class="dashboard-stat-label">Current Streak</p>
      <strong class="dashboard-stat-value">{{ Auth::user()->getCurrentStreak() ?? 0 }}</strong>
      <p class="dashboard-stat-note">Days in a row</p>
    </article>
    <article class="card card-stat dashboard-stat-card">
      <p class="dashboard-stat-label">Avg Mood</p>
      <strong class="dashboard-stat-value">{{ number_format(Auth::user()->moods()->avg('mood_scale') ?? 3, 1) }}</strong>
      <p class="dashboard-stat-note">Out of 5</p>
    </article>
  </section>

  <section class="card dashboard-widget">
    <div class="dashboard-widget__head">
      <h2>Mood This Week</h2>
      <span class="dashboard-widget__eyebrow">Mon - Sun</span>
    </div>
    <div class="dashboard-chart" data-dashboard-chart aria-label="Weekly mood chart">
      <!-- Chart will be populated by JavaScript -->
    </div>
    <div class="dashboard-chart-labels" data-dashboard-chart-labels>
      <span>Mon</span>
      <span>Tue</span>
      <span>Wed</span>
      <span>Thu</span>
      <span>Fri</span>
      <span>Sat</span>
      <span>Sun</span>
    </div>
  </section>

  <section>
    <div class="dashboard-section-head">
      <h2>For Your Mood</h2>
      <a class="dashboard-link" href="{{ route('library') }}">See All</a>
    </div>
    <div class="dashboard-environments" data-dashboard-environments>
      <!-- Recommended environments will be populated by JavaScript -->
    </div>
  </section>

  <section>
    <div class="dashboard-section-head">
      <h2>Recent Activity</h2>
    </div>
    <div class="dashboard-activity-list" data-dashboard-activity>
      @foreach(Auth::user()->vrSessions()->latest()->take(5) as $session)
        <article class="dashboard-activity-item">
          <div class="dashboard-activity-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
          </div>
          <div class="dashboard-activity-content">
            <p class="dashboard-activity-title">VR Session Completed</p>
            <p class="dashboard-activity-meta">{{ $session->created_at->diffForHumans() }}</p>
          </div>
        </article>
      @endforeach
    </div>
  </section>
</section>

<nav class="dashboard-bottom-nav" aria-label="Dashboard sections">
  <a class="is-active" href="{{ route('dashboard') }}">Home</a>
  <a href="{{ route('library') }}">Library</a>
  <a href="{{ route('progress.tracking') }}">Insights</a>
  <a href="{{ route('therapy.sessions') }}">Session</a>
</nav>
@endsection

@section('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Initialize dashboard functionality
  if (typeof NHDashboard !== 'undefined') {
    NHDashboard.init();
  }

  // Apply saved theme
  var theme = localStorage.getItem('nh_theme');
  if (theme && theme !== 'auto') document.documentElement.setAttribute('data-theme', theme);
  if (localStorage.getItem('nh_reduced_motion') === '1') document.documentElement.classList.add('reduce-motion');
});
</script>
@endsection
