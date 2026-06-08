@extends('layouts.app')

@section('title', 'Therapist Dashboard')
@section('page', 'therapist')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Therapist Dashboard</h1>
    <p>Monitor your patients' progress</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('therapist.patients') }}" class="btn btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      View Patients
    </a>
  </div>
</div>

{{-- Stats Overview --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Total Patients</p>
        <p class="stat-card__value">{{ $analytics['total_patients'] }}</p>
      </div>
      <div class="stat-card__icon stat-card__icon--primary">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Active This Week</p>
        <p class="stat-card__value">{{ $analytics['active_patients_week'] }}</p>
      </div>
      <div class="stat-card__icon stat-card__icon--success">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      </div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Avg Mood Today</p>
        <p class="stat-card__value">{{ $analytics['avg_mood_today'] }}/10</p>
      </div>
      <div class="stat-card__icon stat-card__icon--accent">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/></svg>
      </div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Avg Mood Week</p>
        <p class="stat-card__value">{{ $analytics['avg_mood_week'] }}/10</p>
      </div>
      <div class="stat-card__icon stat-card__icon--secondary">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      </div>
    </div>
  </div>
</div>

{{-- Main Grid --}}
<div class="widget-grid">
  {{-- Left Column --}}
  <div>
    {{-- Most Used VR Assets --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Most Used VR Assets</h4>
        <span class="badge badge--secondary">By Your Patients</span>
      </div>
      <div class="card-body">
        @if($analytics['popular_assets']->count() > 0)
          <div class="activity-list">
            @foreach($analytics['popular_assets'] as $asset)
              <div class="activity-item">
                <div class="activity-item__icon" style="background: var(--color-secondary-soft); color: var(--color-secondary);">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2"/></svg>
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ $asset->vr_asset_title }}</p>
                  <p class="activity-item__time">{{ $asset->sessions_count }} sessions</p>
                </div>
                @if($asset->avg_quality)
                  <div style="text-align: right;">
                    <span style="font-size: 0.85rem; font-weight: 700;">{{ round($asset->avg_quality, 1) }}/5</span>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        @else
          <div class="empty-state" style="padding: 1.5rem;">
            <p class="text-muted">No VR session data available yet.</p>
          </div>
        @endif
      </div>
    </div>

    {{-- Patients List --}}
    <div class="card">
      <div class="card-header">
        <h4 class="card-header__title">Patients</h4>
        <a href="{{ route('therapist.patients') }}" class="section-header__link">View All</a>
      </div>
      <div class="card-body">
        @if($patients->count() > 0)
          <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.75rem;">
            @foreach($patients->take(6) as $patient)
              <a href="{{ route('therapist.patient.details', $patient) }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem; border: 1px solid var(--color-border); border-radius: var(--radius-lg); text-decoration: none; color: inherit; transition: all var(--transition-fast);">
                <div class="topbar__avatar" style="width: 40px; height: 40px; font-size: 0.85rem;">
                  {{ strtoupper(substr($patient->name, 0, 1)) }}
                </div>
                <div style="min-width: 0; flex: 1;">
                  <p style="margin: 0; font-weight: 600; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $patient->name }}</p>
                  <p class="text-muted" style="margin: 0.125rem 0 0; font-size: 0.75rem;">{{ $patient->moods_count }} moods • {{ $patient->vr_sessions_count }} sessions</p>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-text-muted); flex-shrink: 0;"><polyline points="9 18 15 12 9 6"/></svg>
              </a>
            @endforeach
          </div>
        @else
          <div class="empty-state" style="padding: 1.5rem;">
            <p class="text-muted">No patients assigned yet.</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Right Column --}}
  <div>
    {{-- Recent Patient Activity --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Recent Activity</h4>
      </div>
      <div class="card-body">
        {{-- Latest Moods --}}
        <h5 style="margin: 0 0 0.75rem; font-size: 0.85rem;">Latest Mood Entries</h5>
        @if($analytics['recent_moods']->count() > 0)
          <div class="activity-list" style="margin-bottom: 1rem;">
            @foreach($analytics['recent_moods']->take(5) as $mood)
              <div class="activity-item">
                <div class="activity-item__icon" style="background: var(--color-primary-soft); color: var(--color-primary); font-size: 1.1rem;">
                  @php $emojis = [1=>'😢',2=>'😞',3=>'😐',4=>'🙂',5=>'😊',6=>'😄',7=>'😄',8=>'😄',9=>'😄',10=>'🤩']; @endphp
                  {{ $emojis[$mood->mood_scale] ?? '😐' }}
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ $mood->user->name }} <span style="font-weight: 400; color: var(--color-text-muted);">{{ $mood->mood_scale }}/10</span></p>
                  <p class="activity-item__time">{{ $mood->mood_date->format('M j, g:i A') }}</p>
                  @if($mood->note)
                    <p style="margin: 0.25rem 0 0; font-size: 0.8rem; color: var(--color-text-muted); font-style: italic;">{{ \Illuminate\Support\Str::limit($mood->note, 80) }}</p>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1rem;">No mood entries yet.</p>
        @endif

        {{-- Latest VR Sessions --}}
        <h5 style="margin: 0 0 0.75rem; font-size: 0.85rem;">Latest VR Sessions</h5>
        @if($analytics['recent_vr_sessions']->count() > 0)
          <div class="activity-list">
            @foreach($analytics['recent_vr_sessions']->take(5) as $session)
              <a href="{{ route('therapist.vr-session.report', $session) }}" class="activity-item" style="text-decoration: none; color: inherit;">
                <div class="activity-item__icon" style="background: var(--color-secondary-soft); color: var(--color-secondary);">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ $session->user->name }} — {{ $session->vr_asset_title }}</p>
                  <p class="activity-item__time">{{ $session->started_at->diffForHumans() }}</p>
                </div>
                <div class="activity-item__action" style="display:flex;align-items:center;gap:0.375rem;">
                  <span class="badge badge--{{ $session->is_completed ? 'success' : 'warning' }}">{{ $session->is_completed ? 'Done' : 'Active' }}</span>
                  @if($session->is_completed)
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-muted)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                  @endif
                </div>
              </a>
            @endforeach
          </div>
        @else
          <p class="text-muted" style="font-size: 0.85rem;">No VR sessions yet.</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
