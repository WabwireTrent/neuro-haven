@extends('layouts.auth')

@section('title', 'Choose Account Type')

@section('auth_content')
<a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.75rem 1.5rem; font-size: 0.8rem; font-weight: 600; color: var(--color-text-muted); text-decoration: none; border-bottom: 1px solid var(--color-border);">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
  Back to Home
</a>

<div style="padding: 2rem 1.5rem; display: grid; gap: 1.5rem; text-align: center;">
  <div>
    <h1 style="font-size: 1.35rem; font-weight: 800; margin: 0 0 0.25rem;">Choose your account type</h1>
    <p class="text-muted" style="font-size: 0.85rem; margin: 0;">Select how you'll use Neuro Haven</p>
  </div>

  <div style="display: grid; gap: 1rem;">
    <a href="{{ route('register', 'patient') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem; border: 2px solid var(--color-border); border-radius: var(--radius-xl); text-decoration: none; color: inherit; transition: all var(--transition-fast); text-align: left;">
      <div style="width: 48px; height: 48px; border-radius: var(--radius-lg); background: var(--color-primary-soft); color: var(--color-primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </div>
      <div style="flex: 1;">
        <p style="font-weight: 700; margin: 0; font-size: 1rem;">I'm a Patient</p>
        <p class="text-muted" style="margin: 0.125rem 0 0; font-size: 0.8rem;">Access VR therapy sessions and track your wellness journey</p>
      </div>
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-text-muted); flex-shrink: 0;"><polyline points="9 18 15 12 9 6"/></svg>
    </a>

    <a href="{{ route('register', 'therapist') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem; border: 2px solid var(--color-border); border-radius: var(--radius-xl); text-decoration: none; color: inherit; transition: all var(--transition-fast); text-align: left;">
      <div style="width: 48px; height: 48px; border-radius: var(--radius-lg); background: var(--color-secondary-soft); color: var(--color-secondary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
      <div style="flex: 1;">
        <p style="font-weight: 700; margin: 0; font-size: 1rem;">I'm a Therapist</p>
        <p class="text-muted" style="margin: 0.125rem 0 0; font-size: 0.8rem;">Monitor patient progress and manage therapy assignments</p>
      </div>
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-text-muted); flex-shrink: 0;"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
  </div>

  <p style="margin: 0; font-size: 0.8rem; color: var(--color-text-muted);">
    Already have an account? <a href="{{ route('login') }}" style="font-weight: 700;">Log In</a>
  </p>
</div>
@endsection
