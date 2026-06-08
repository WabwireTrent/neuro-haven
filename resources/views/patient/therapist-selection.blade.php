@extends('layouts.app')

@section('title', 'My Therapist')
@section('page', 'patient-therapist')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>My Therapist</h1>
    <p>Choose the therapist you'd like to work with</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert--success">{{ session('success') }}</div>
@endif

{{-- Current Therapist --}}
@if($myTherapist)
  <div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
      <h4 class="card-header__title">Your Current Therapist</h4>
      <span class="badge badge--success">Active</span>
    </div>
    <div class="card-body">
      <div style="display: flex; align-items: center; gap: 1rem;">
        <div class="topbar__avatar" style="width: 52px; height: 52px; font-size: 1.2rem; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: #fff;">
          {{ strtoupper(substr($myTherapist->name, 0, 1)) }}
        </div>
        <div>
          <h3 style="margin: 0 0 0.25rem; font-size: 1.05rem;">{{ $myTherapist->name }}</h3>
          @if($myTherapist->specialization)
            <p class="text-muted text-sm" style="margin: 0 0 0.125rem;">{{ $myTherapist->specialization }}</p>
          @endif
          @if($myTherapist->bio)
            <p class="text-muted text-sm" style="margin: 0; max-width: 500px;">{{ $myTherapist->bio }}</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@else
  <div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
      <div class="empty-state" style="padding: 2rem;">
        <div class="empty-state__icon" style="width: 48px; height: 48px; background: var(--color-primary-soft); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <p class="empty-state__title">No Therapist Assigned</p>
        <p class="empty-state__desc">Select a therapist from the list below to start your guided therapy journey.</p>
      </div>
    </div>
  </div>
@endif

{{-- All Therapists --}}
<div class="card">
  <div class="card-header">
    <h4 class="card-header__title">Available Therapists</h4>
    <span class="badge badge--primary">{{ $therapists->count() }} available</span>
  </div>
  <div class="card-body" style="padding: 0;">
    @if($therapists->count() > 0)
      <div style="display: grid; gap: 0;">
        @foreach($therapists as $therapist)
          @php $isCurrent = $myTherapist && $myTherapist->id === $therapist->id; @endphp
          <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-bottom: 1px solid var(--color-border-light); {{ $isCurrent ? 'background: var(--color-primary-soft);' : '' }} transition: background var(--transition-fast);">
            <div style="display: flex; align-items: center; gap: 0.875rem;">
              <div class="topbar__avatar" style="width: 40px; height: 40px; font-size: 0.95rem; {{ $isCurrent ? 'background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); color: #fff;' : '' }}">
                {{ strtoupper(substr($therapist->name, 0, 1)) }}
              </div>
              <div>
                <p style="font-weight: 600; font-size: 0.9rem; margin: 0 0 0.125rem;">{{ $therapist->name }}</p>
                @if($therapist->specialization)
                  <p class="text-muted text-sm" style="margin: 0;">{{ $therapist->specialization }}</p>
                @else
                  <p class="text-muted text-sm" style="margin: 0;">Therapist</p>
                @endif
              </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
              @if($therapist->bio)
                <span class="text-muted text-sm" style="max-width: 200px; text-align: right; display: none;">{{ $therapist->bio }}</span>
              @endif
              @if($isCurrent)
                <span class="badge badge--success">Your Therapist</span>
              @else
                <form method="POST" action="{{ route('patient.therapist.select') }}" style="margin: 0;">
                  @csrf
                  <input type="hidden" name="therapist_id" value="{{ $therapist->id }}">
                  <button type="submit" class="btn btn-sm btn-primary">Choose</button>
                </form>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-state" style="padding: 2rem;">
        <p class="text-muted">No therapists available at this time.</p>
      </div>
    @endif
  </div>
</div>
@endsection
