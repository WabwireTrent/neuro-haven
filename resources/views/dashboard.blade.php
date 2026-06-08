@extends('layouts.app')

@section('title', 'Dashboard')
@section('page', 'dashboard')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Dashboard</h1>
    <p>Welcome back, {{ Auth::user()->name }} &mdash; here's your therapy overview</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('mood.tracker') }}" class="btn btn-secondary btn-sm">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
      Log Mood
    </a>
    <a href="{{ route('session') }}" class="btn btn-primary">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
      New Session
    </a>
  </div>
</div>

{{-- Stats Overview --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Sessions This Week</p>
        <p class="stat-card__value">{{ Auth::user()->vrSessions()->where('created_at', '>=', now()->startOfWeek())->count() }}</p>
      </div>
      <div class="stat-card__icon stat-card__icon--primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
      </div>
    </div>
    <span class="stat-card__change stat-card__change--up">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
      +12% vs last week
    </span>
  </div>

  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Current Streak</p>
        <p class="stat-card__value">{{ Auth::user()->getCurrentStreak() ?? 0 }}</p>
      </div>
      <div class="stat-card__icon stat-card__icon--success">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      </div>
    </div>
    <span class="stat-card__change stat-card__change--up">days in a row</span>
  </div>

  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Average Mood</p>
        <p class="stat-card__value">{{ number_format(Auth::user()->moods()->avg('mood_scale') ?? 3, 1) }}</p>
      </div>
      <div class="stat-card__icon stat-card__icon--accent">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
      </div>
    </div>
    <span class="stat-card__change stat-card__change--up">out of 10</span>
  </div>

  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Total Sessions</p>
        <p class="stat-card__value">{{ Auth::user()->vrSessions()->count() }}</p>
      </div>
      <div class="stat-card__icon stat-card__icon--secondary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
    </div>
    <span class="stat-card__change stat-card__change--up">lifetime</span>
  </div>
</div>

{{-- My Therapist --}}
@php $myTherapist = Auth::user()->assignedTherapists()->first(); @endphp
@if($myTherapist)
  <div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-header">
      <h4 class="card-header__title">Your Therapist</h4>
      <a href="{{ route('patient.therapist') }}" class="section-header__link">Change</a>
    </div>
    <div class="card-body">
      <div style="display: flex; align-items: center; gap: 0.875rem;">
        <div class="topbar__avatar" style="width: 44px; height: 44px; font-size: 1rem; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: #fff;">
          {{ strtoupper(substr($myTherapist->name, 0, 1)) }}
        </div>
        <div>
          <p style="font-weight: 600; font-size: 0.9rem; margin: 0 0 0.125rem;">{{ $myTherapist->name }}</p>
          <p class="text-muted text-sm" style="margin: 0;">
            @if($myTherapist->specialization)
              {{ $myTherapist->specialization }}
            @else
              Therapist
            @endif
          </p>
        </div>
      </div>
    </div>
  </div>
@else
  <div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-header">
      <h4 class="card-header__title">Your Therapist</h4>
      <a href="{{ route('patient.therapist') }}" class="section-header__link">Choose</a>
    </div>
    <div class="card-body">
      <div class="empty-state" style="padding: 1rem;">
        <p class="text-muted text-sm">No therapist assigned yet. <a href="{{ route('patient.therapist') }}">Choose a therapist</a> to get started.</p>
      </div>
    </div>
  </div>
@endif

{{-- Main Widget Grid --}}
<div class="widget-grid">
  {{-- Left Column --}}
  <div>
    {{-- Mood Check-in --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">How are you feeling today?</h4>
        <span class="badge badge--primary">Daily Check-in</span>
      </div>
      <div class="card-body">
        <p class="text-muted text-sm" style="margin-bottom: 1rem;">Tap a mood to log your check-in and get personalised recommendations.</p>
        <div style="display: flex; gap: 0.5rem; justify-content: space-between;">
          @php
            $moods = [
              1 => ['emoji' => '😢', 'label' => 'Very Sad', 'color' => '#ef4444'],
              2 => ['emoji' => '😞', 'label' => 'Sad', 'color' => '#f59e0b'],
              3 => ['emoji' => '😐', 'label' => 'Neutral', 'color' => '#94a3b8'],
              4 => ['emoji' => '😊', 'label' => 'Happy', 'color' => '#3b82f6'],
              5 => ['emoji' => '😁', 'label' => 'Very Happy', 'color' => '#10b981'],
            ];
          @endphp
          @foreach ($moods as $val => $mood)
            <button class="dashboard-mood-btn" data-mood="{{ $val }}" aria-label="{{ $mood['label'] }}"
              style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.375rem; padding: 0.75rem 0.25rem; border: 2px solid var(--color-border); border-radius: var(--radius-xl); background: var(--color-surface); cursor: pointer; transition: all 0.2s ease;">
              <span style="font-size: 1.75rem; line-height: 1;">{{ $mood['emoji'] }}</span>
              <span style="font-size: 0.6rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.02em;">{{ $mood['label'] }}</span>
            </button>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Mood Chart --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Mood This Week</h4>
        <div class="card-header__action">
          <span class="badge badge--neutral">Mon - Sun</span>
        </div>
      </div>
      <div class="card-body">
        <div class="chart-container" style="height: 180px;">
          <div class="chart-placeholder" data-dashboard-chart>
            @php
              $weekMoods = Auth::user()->moods()->whereBetween('mood_date', [now()->startOfWeek(), now()->endOfWeek()])->get()->keyBy(function($m) { return $m->mood_date->format('w'); });
              $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            @endphp
            @foreach (range(1, 7) as $day)
              @php
                $dayOfWeek = $day % 7;
                $mood = $weekMoods->get((string)$dayOfWeek);
                $height = $mood ? ($mood->mood_scale / 10) * 100 : 0;
              @endphp
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; gap: 0.375rem;">
                <div class="chart-bar" style="height: {{ max($height, 4) }}%; width: 100%; max-width: 28px;"></div>
                <span style="font-size: 0.6rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase;">{{ $days[$day-1] }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    {{-- Recent Activity --}}
    <div class="card">
      <div class="card-header">
        <h4 class="card-header__title">Recent Sessions</h4>
        <a href="{{ route('therapy.sessions') }}" class="section-header__link">View All</a>
      </div>
      <div class="card-body">
        <div class="activity-list">
          @forelse(Auth::user()->vrSessions()->latest()->take(5) as $session)
            <div class="activity-item">
              <div class="activity-item__icon" style="background: var(--color-secondary-soft); color: var(--color-secondary);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </div>
              <div class="activity-item__content">
                <p class="activity-item__text">{{ $session->vr_asset_title ?? 'VR Therapy Session' }}</p>
                <p class="activity-item__time">{{ $session->created_at->diffForHumans() }}</p>
              </div>
              <div class="activity-item__action">
                <span class="badge badge--success">Completed</span>
              </div>
            </div>
          @empty
            <div class="empty-state" style="padding: 2rem 1rem;">
              <div class="empty-state__icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
              </div>
              <p class="empty-state__title">No Sessions Yet</p>
              <p class="empty-state__desc">Start your first VR therapy session to see activity here.</p>
              <a href="{{ route('session') }}" class="btn btn-primary btn-sm">Start Session</a>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  {{-- Right Column --}}
  <div>
    {{-- Wellness Snapshot --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Wellness Snapshot</h4>
      </div>
      <div class="card-body">
        <div style="display: grid; gap: 1rem;">
          <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.375rem;">
              <span style="font-size: 0.8rem; font-weight: 500;">Mood Consistency</span>
              <span style="font-size: 0.8rem; font-weight: 700;">{{ round(($weekMoods->count() / 7) * 100) }}%</span>
            </div>
            <div class="progress progress--sm">
              <div class="progress__bar progress__bar--success" style="width: {{ ($weekMoods->count() / 7) * 100 }}%"></div>
            </div>
          </div>
          <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.375rem;">
              <span style="font-size: 0.8rem; font-weight: 500;">Session Completion</span>
              <span style="font-size: 0.8rem; font-weight: 700;">
                @php $sessionCount = Auth::user()->vrSessions()->count(); $completedSessions = Auth::user()->vrSessions()->where('status', 'completed')->count(); @endphp
                {{ $sessionCount > 0 ? round(($completedSessions / $sessionCount) * 100) : 0 }}%
              </span>
            </div>
            <div class="progress progress--sm">
              <div class="progress__bar" style="width: {{ $sessionCount > 0 ? round(($completedSessions / $sessionCount) * 100) : 0 }}%"></div>
            </div>
          </div>
          <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.375rem;">
              <span style="font-size: 0.8rem; font-weight: 500;">Average Intensity</span>
              <span style="font-size: 0.8rem; font-weight: 700;">{{ number_format(Auth::user()->moods()->avg('mood_scale') ?? 0, 1) }}/10</span>
            </div>
            <div class="progress progress--sm">
              <div class="progress__bar progress__bar--warning" style="width: {{ (Auth::user()->moods()->avg('mood_scale') ?? 0) * 10 }}%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Calendar --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">{{ now()->format('F Y') }}</h4>
        <button class="btn btn-sm btn-ghost">Today</button>
      </div>
      <div class="card-body">
        <div class="calendar-grid">
          @foreach(['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'] as $day)
            <div class="calendar-day-header">{{ $day }}</div>
          @endforeach
          @php
            $firstDay = now()->startOfMonth()->dayOfWeek;
            $daysInMonth = now()->daysInMonth;
            $today = now()->day;
          @endphp
          @for($i = 0; $i < $firstDay; $i++)
            <div class="calendar-day calendar-day--other"></div>
          @endfor
          @for($d = 1; $d <= $daysInMonth; $d++)
            @php
              $classes = 'calendar-day';
              if ($d == $today) $classes .= ' calendar-day--today';
              $sessionCount = Auth::user()->vrSessions()->whereDate('created_at', now()->startOfMonth()->addDays($d-1))->count();
              if ($sessionCount > 0) $classes .= ' calendar-day--event';
            @endphp
            <div class="{{ $classes }}">{{ $d }}</div>
          @endfor
        </div>
      </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card">
      <div class="card-header">
        <h4 class="card-header__title">Quick Actions</h4>
      </div>
      <div class="card-body" style="display: grid; gap: 0.5rem;">
        <a href="{{ route('mood.tracker') }}" class="btn btn-secondary" style="justify-content: flex-start;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
          Log Mood
        </a>
        <a href="{{ route('vr.assets') }}" class="btn btn-secondary" style="justify-content: flex-start;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2"/><path d="M10 16l2-2 2 2"/><path d="M8 12h8"/></svg>
          Explore VR Library
        </a>
        <a href="{{ route('therapy.sessions') }}" class="btn btn-secondary" style="justify-content: flex-start;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
          View Sessions
        </a>
        <a href="{{ route('progress.tracking') }}" class="btn btn-secondary" style="justify-content: flex-start;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          View Progress
        </a>
      </div>
    </div>
  </div>
</div>

{{-- Badges & Achievements --}}
@php $userBadges = Auth::user()->badges ?? collect(); @endphp
@if($userBadges->count() > 0)
  <div class="card" style="margin-top: 1.25rem;">
    <div class="card-header">
      <h4 class="card-header__title">Badges & Achievements</h4>
      <span class="badge badge--primary">{{ $userBadges->count() }} earned</span>
    </div>
    <div class="card-body">
      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        @foreach($userBadges as $badge)
          <div style="display: flex; flex-direction: column; align-items: center; gap: 0.25rem; padding: 0.75rem; border-radius: var(--radius-lg); background: var(--color-surface-muted); min-width: 80px;" title="{{ $badge->description }}">
            <div style="font-size: 1.5rem; line-height: 1;">
              @if($badge->icon === 'star')
                <svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-warning)" stroke="var(--color-warning)" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              @elseif($badge->icon === 'flame')
                <svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-danger)" stroke="var(--color-danger)" stroke-width="1"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
              @elseif($badge->icon === 'crown')
                <svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-warning)" stroke="var(--color-warning)" stroke-width="1"><path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7z"/><path d="M3 20h18"/></svg>
              @else
                <svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-primary)" stroke="var(--color-primary)" stroke-width="1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
              @endif
            </div>
            <span style="font-size: 0.7rem; font-weight: 700; text-align: center; white-space: nowrap;">{{ $badge->name }}</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endif

{{-- VR Activity Monitor --}}
<div class="card">
  <div class="card-header">
    <h4 class="card-header__title">VR Therapy Activity</h4>
    <div class="card-header__action" style="display: flex; gap: 0.5rem;">
      <span class="badge badge--success badge--dot">Active</span>
      <span class="badge badge--primary">{{ Auth::user()->vrSessions()->where('created_at', '>=', now()->subDays(7))->count() }} this week</span>
    </div>
  </div>
  <div class="card-body">
    <div class="kpi-grid">
      <div class="kpi-card">
        <div class="kpi-card__value">
          @php
            $totalMinutes = Auth::user()->vrSessions()->sum('session_duration');
            $hours = floor($totalMinutes / 60);
            $mins = $totalMinutes % 60;
          @endphp
          {{ $hours }}h {{ $mins }}m
        </div>
        <div class="kpi-card__label">Total Time</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-card__value">
          @php $avgDuration = Auth::user()->vrSessions()->avg('session_duration'); @endphp
          {{ $avgDuration ? floor($avgDuration / 60) . 'm ' . ($avgDuration % 60) . 's' : '0m' }}
        </div>
        <div class="kpi-card__label">Avg Session</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-card__value">{{ number_format(Auth::user()->vrSessions()->avg('session_quality') ?? 0, 1) }}</div>
        <div class="kpi-card__label">Avg Quality</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-card__value" style="color: var(--color-success);">+1.2</div>
        <div class="kpi-card__label">Mood Impact</div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.dashboard-mood-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var mood = this.getAttribute('data-mood');
      document.querySelectorAll('.dashboard-mood-btn').forEach(function(b) {
        b.style.borderColor = 'var(--color-border)';
        b.style.background = 'var(--color-surface)';
        b.style.transform = 'scale(1)';
      });
      this.style.borderColor = 'var(--color-primary)';
      this.style.background = 'var(--color-primary-soft)';
      this.style.transform = 'scale(1.05)';

      var formData = new FormData();
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      formData.append('mood', ['very_sad', 'sad', 'neutral', 'happy', 'very_happy'][mood - 1]);
      formData.append('mood_scale', mood * 2);

      fetch('{{ route('mood.store') }}', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      }).then(function(r) { return r.json(); }).then(function(data) {
        if (data.success) {
          var orig = this.innerHTML;
          this.innerHTML = '<span style=\"font-size:1.5rem;\">✅</span>';
          var self = this;
          setTimeout(function() { self.innerHTML = orig; }, 1500);
        }
      }.bind(this)).catch(function() {});
    });
  });
});
</script>
@endsection
