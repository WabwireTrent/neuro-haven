@extends('layouts.app')

@section('title', 'My Patients')
@section('page', 'therapist-patients')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>My Patients</h1>
    <p>Monitor and manage your patients' therapy progress</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('therapist.dashboard') }}" class="btn btn-secondary btn-sm">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Dashboard
    </a>
  </div>
</div>

@if($patients->count() > 0)
  <div class="card-grid">
    @foreach($patients as $patient)
      <a href="{{ route('therapist.patient.details', $patient) }}" class="card card--hoverable" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
        <div class="card-body" style="flex: 1;">
          <div style="display: flex; align-items: center; gap: 0.875rem; margin-bottom: 1rem;">
            <div class="avatar-initials" style="width: 44px; height: 44px; font-size: 1rem;">{{ strtoupper(substr($patient->name, 0, 1)) }}</div>
            <div style="min-width: 0; flex: 1;">
              <h5 style="margin: 0 0 0.125rem; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $patient->name }}</h5>
              <p class="text-muted text-sm" style="margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $patient->email }}</p>
            </div>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
            <div class="kpi-card" style="margin: 0;">
              <div class="kpi-card__value" style="font-size: 1.25rem;">{{ $patient->moods_count }}</div>
              <div class="kpi-card__label">Moods</div>
            </div>
            <div class="kpi-card" style="margin: 0;">
              <div class="kpi-card__value" style="font-size: 1.25rem;">{{ $patient->vr_sessions_count }}</div>
              <div class="kpi-card__label">Sessions</div>
            </div>
          </div>
          @if($patient->moods->count() > 0)
            @php $latestMood = $patient->moods->first(); $moodEmoji = [1=>'😢',2=>'😞',3=>'😐',4=>'🙂',5=>'😊',6=>'😄',7=>'😄',8=>'😄',9=>'😄',10=>'🤩']; @endphp
            <div style="padding: 0.75rem; background: var(--color-primary-soft); border-radius: var(--radius-lg); display: flex; align-items: center; gap: 0.625rem;">
              <span style="font-size: 1.5rem;">{{ $moodEmoji[$latestMood->mood_scale] ?? '😐' }}</span>
              <div>
                <p style="margin: 0; font-weight: 700; font-size: 0.85rem;">{{ $latestMood->mood_scale }}/10</p>
                <p class="text-muted text-xs" style="margin: 0;">{{ $latestMood->mood_date->format('M j') }}</p>
              </div>
            </div>
          @endif
        </div>
      </a>
    @endforeach
  </div>

  @if(method_exists($patients, 'hasPages') && $patients->hasPages())
    <div class="card" style="margin-top: 1.25rem;">
      <div class="card-body" style="display: flex; justify-content: center;">
        {{ $patients->links() }}
      </div>
    </div>
  @endif
@else
  <div class="card">
    <div class="empty-state">
      <div class="empty-state__icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
      <p class="empty-state__title">No Patients Yet</p>
      <p class="empty-state__desc">Patients will appear here once they're assigned to you.</p>
    </div>
  </div>
@endif
@endsection
