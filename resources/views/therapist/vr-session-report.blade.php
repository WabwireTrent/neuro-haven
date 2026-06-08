@extends('layouts.app')

@section('title', 'VR Session Report')
@section('page', 'therapist')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>VR Session Report</h1>
    <p>{{ $patient->name }} &mdash; {{ $session->started_at->format('l, M j, Y · g:i A') }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('therapist.patient.details', $patient) }}" class="btn btn-ghost btn-sm">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Back to Patient
    </a>
  </div>
</div>

{{-- Patient Snapshot --}}
<div class="card" style="margin-bottom: 1.25rem;">
  <div class="card-body" style="display: flex; align-items: center; gap: 1rem;">
    <div class="topbar__avatar" style="width: 48px; height: 48px; font-size: 1.1rem;">
      {{ strtoupper(substr($patient->name, 0, 1)) }}
    </div>
    <div style="flex: 1;">
      <p style="font-weight: 700; margin: 0; font-size: 1.05rem;">{{ $patient->name }}</p>
      <p class="text-muted" style="margin: 0.125rem 0 0; font-size: 0.8rem;">{{ $patient->email }}</p>
    </div>
    <div style="display: flex; gap: 1.5rem;">
      <div style="text-align: center;">
        <p style="font-weight: 700; margin: 0; font-size: 1.1rem;">{{ $patient->vr_sessions_count }}</p>
        <p class="text-muted" style="margin: 0; font-size: 0.7rem;">Total Sessions</p>
      </div>
      <div style="text-align: center;">
        <p style="font-weight: 700; margin: 0; font-size: 1.1rem;">{{ $patient->moods_count }}</p>
        <p class="text-muted" style="margin: 0; font-size: 0.7rem;">Mood Entries</p>
      </div>
      <div style="text-align: center;">
        <p style="font-weight: 700; margin: 0; font-size: 1.1rem;">{{ $streak }}</p>
        <p class="text-muted" style="margin: 0; font-size: 0.7rem;">Day Streak</p>
      </div>
    </div>
  </div>
</div>

{{-- Session Stats --}}
<div class="stats-grid" style="margin-bottom: 1.5rem;">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Duration</p>
    <p class="stat-card__value">{{ floor($session->session_duration / 60) }}<span style="font-size:0.875rem;font-weight:600;color:var(--color-text-muted);">m</span> {{ $session->session_duration % 60 }}<span style="font-size:0.875rem;font-weight:600;color:var(--color-text-muted);">s</span></p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Device</p>
    <p class="stat-card__value" style="text-transform: capitalize;">{{ $session->device_type ?? 'N/A' }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Quality Rating</p>
    <p class="stat-card__value" style="color: var(--color-warning);">
      @if($session->session_quality)
        @for($i = 1; $i <= 5; $i++)
          <span style="color: {{ $i <= $session->session_quality ? 'var(--color-warning)' : 'var(--color-border)' }};">★</span>
        @endfor
      @else
        <span style="font-size:0.85rem;color:var(--color-text-muted);">Not rated</span>
      @endif
    </p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Completed At</p>
    <p class="stat-card__value" style="font-size:0.85rem;">{{ $session->completed_at ? $session->completed_at->format('g:i A') : 'N/A' }}</p>
  </div>
</div>

{{-- Main Report Grid --}}
<div class="widget-grid">
  {{-- Left Column --}}
  <div>
    {{-- Mood Impact --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Mood Impact</h4>
      </div>
      <div class="card-body">
        @if($session->mood_before && $session->mood_after)
          @php $change = $session->mood_after - $session->mood_before; @endphp
          <div style="display: flex; align-items: center; justify-content: center; gap: 2rem; padding: 1.25rem 0;">
            <div style="text-align: center;">
              <p class="text-muted" style="margin: 0 0 0.25rem; font-size: 0.75rem;">Before Session</p>
              <div style="font-size: 2rem; font-weight: 800; color: var(--color-text);">{{ $session->mood_before }}</div>
              <div style="font-size: 0.75rem; color: var(--color-text-muted);">/10</div>
            </div>
            <div style="font-size: 1.5rem; color: var(--color-text-muted);">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="5 12 19 12"/><polyline points="12 5 19 12 12 19"/></svg>
            </div>
            <div style="text-align: center;">
              <p class="text-muted" style="margin: 0 0 0.25rem; font-size: 0.75rem;">After Session</p>
              <div style="font-size: 2rem; font-weight: 800; color: {{ $change >= 0 ? 'var(--color-success)' : 'var(--color-danger)' }};">{{ $session->mood_after }}</div>
              <div style="font-size: 0.75rem; color: var(--color-text-muted);">/10</div>
            </div>
          </div>
          <div style="text-align: center; padding: 0.5rem; background: var(--color-surface-muted); border-radius: var(--radius-lg);">
            <span style="font-weight: 700; font-size: 1.1rem; color: {{ $change >= 0 ? 'var(--color-success)' : 'var(--color-danger)' }};">
              {{ $change >= 0 ? '+' : '' }}{{ $change }}
            </span>
            <span class="text-muted" style="font-size: 0.8rem;"> point {{ $change >= 0 ? 'improvement' : 'decline' }}</span>
          </div>
        @else
          <div class="empty-state" style="padding: 1.5rem;">
            <p class="text-muted">Mood data not recorded for this session.</p>
          </div>
        @endif
      </div>
    </div>

    {{-- Session Notes --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Patient Notes</h4>
      </div>
      <div class="card-body">
        @if($session->notes)
          <p style="margin: 0; line-height: 1.6; color: var(--color-text-secondary);">{{ $session->notes }}</p>
        @else
          <p class="text-muted" style="margin: 0;">No notes recorded for this session.</p>
        @endif
      </div>
    </div>
  </div>

  {{-- Right Column --}}
  <div>
    {{-- Asset Details --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">VR Asset Details</h4>
      </div>
      <div class="card-body">
        @if($asset)
          <div style="display: grid; gap: 0.75rem;">
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
              <span class="text-muted">Title</span>
              <span style="font-weight: 600;">{{ $asset->title }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
              <span class="text-muted">Category</span>
              <span class="badge badge--secondary">{{ $asset->category }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
              <span class="text-muted">Difficulty</span>
              <span>{{ $asset->difficulty_level }}/5</span>
            </div>
            @if($asset->therapeutic_benefits)
              <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
                <span class="text-muted">Benefits</span>
                <span style="font-size: 0.85rem; max-width: 180px; text-align: right;">
                  {{ is_string($asset->therapeutic_benefits) ? $asset->therapeutic_benefits : implode(', ', json_decode($asset->therapeutic_benefits) ?? []) }}
                </span>
              </div>
            @endif
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
              <span class="text-muted">Description</span>
              <span style="font-size: 0.85rem; max-width: 200px; text-align: right;">{{ $asset->description }}</span>
            </div>
          </div>
        @else
          <p class="text-muted" style="margin: 0;">{{ $session->vr_asset_title ?? 'Unknown asset' }}</p>
        @endif
      </div>
    </div>

    {{-- Patient Progress Summary --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Progress Overview</h4>
      </div>
      <div class="card-body" style="display: grid; gap: 0.75rem;">
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
          <span class="text-muted">Average Mood</span>
          <span style="font-weight: 700;">{{ $avg_mood }}/10</span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
          <span class="text-muted">Completed Sessions</span>
          <span style="font-weight: 700;">{{ $prev_completed + 1 }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
          <span class="text-muted">Total Therapy Time</span>
          <span style="font-weight: 700;">
            @php $mins = floor($total_duration_all / 60); $hours = floor($mins / 60); @endphp
            {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $mins % 60 }}m
          </span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
          <span class="text-muted">Average Quality</span>
          <span style="font-weight: 700;">
            @if($avg_quality_all > 0)
              @for($i = 1; $i <= 5; $i++)
                <span style="color: {{ $i <= round($avg_quality_all) ? 'var(--color-warning)' : 'var(--color-border)' }};">★</span>
              @endfor
            @else
              <span class="text-muted">N/A</span>
            @endif
          </span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
          <span class="text-muted">Current Streak</span>
          <span style="font-weight: 700;">{{ $streak }} days</span>
        </div>
      </div>
    </div>

    {{-- Mood Trend --}}
    @if($mood_trend->count() >= 2)
      <div class="card">
        <div class="card-header">
          <h4 class="card-header__title">Mood Trend (14 days)</h4>
        </div>
        <div class="card-body">
          <div style="display: flex; align-items: flex-end; gap: 4px; height: 100px; padding: 0.5rem 0;">
            @php $maxScale = 10; @endphp
            @foreach($mood_trend as $entry)
              @php
                $pct = ($entry->mood_scale / $maxScale) * 100;
                $color = $entry->mood_scale >= 7 ? 'var(--color-success)' : ($entry->mood_scale >= 4 ? 'var(--color-warning)' : 'var(--color-danger)');
              @endphp
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 2px;">
                <div style="width: 100%; height: {{ $pct }}px; background: {{ $color }}; border-radius: 3px 3px 0 0; min-height: 4px; transition: height 0.3s;"></div>
                <span style="font-size: 0.5rem; color: var(--color-text-muted); white-space: nowrap;">{{ $entry->mood_date->format('m/d') }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>
</div>

<div style="display: flex; justify-content: center; gap: 0.75rem; margin-top: 1.5rem;">
  <a href="{{ route('therapist.patient.details', $patient) }}" class="btn btn-primary">View Patient Profile</a>
  <a href="{{ route('therapist.reports') }}" class="btn btn-ghost">All Reports</a>
</div>
@endsection
