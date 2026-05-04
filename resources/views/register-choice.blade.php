@extends('layouts.auth')

@section('title', 'Choose Account Type')
@section('page', 'register-choice')

@section('content')
<!-- Floating back button — desktop only, top-left over video -->
<a class="auth-back-float" href="{{ route('home') }}" aria-label="Back to Home">
  <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="15 18 9 12 15 6"/>
  </svg>
  <span>Back to Home</span>
</a>

<!-- Left: branded panel (overlay only, video shows through) -->
<aside class="auth-panel" aria-hidden="true">
  <div class="auth-panel__overlay" aria-hidden="true"></div>
  <div class="auth-panel__inner">
    <div class="auth-panel__mark">NH</div>
    <p class="auth-panel__name">Neuro Haven</p>
    <h2 class="auth-panel__headline">Choose Your Role</h2>
    <p class="auth-panel__sub">Whether you're seeking therapeutic support or providing professional care, Neuro Haven welcomes you to join our community.</p>
  </div>
</aside>

<!-- Right: auth card -->
<div class="auth-wrap">
  <section class="auth-card" aria-labelledby="choice-heading">
    <a class="auth-back" href="{{ route('home') }}">
      <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
      Back to Home
    </a>

    <!-- mobile-only branding -->
    <div class="auth-mobile-brand" aria-hidden="true">
      <span class="auth-mark">NH</span>
      <span class="auth-mobile-brand__name">Neuro Haven</span>
    </div>

    <div class="auth-content">
      <div class="auth-heading">
        <h1 id="choice-heading">Join as a Patient or Therapist</h1>
        <p>Select the account type that best fits your needs.</p>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin: 2.5rem 0;">
        
        <!-- Patient Registration Card -->
        <a href="{{ route('register', 'patient') }}" style="text-decoration: none;">
          <div class="card" style="padding: 2rem; text-align: center; cursor: pointer; border: 2px solid #e5e7eb; border-radius: 0.75rem; transition: all 0.3s ease; display: flex; flex-direction: column; gap: 1rem;">
            <div style="font-size: 3rem;">🧘</div>
            <div>
              <h2 style="margin: 0.5rem 0; font-size: 1.25rem;">I'm a Patient</h2>
              <p style="margin: 0.5rem 0 0; color: #6b7280; font-size: 0.9rem;">Access VR therapy sessions, track your mood, and work with therapists to improve your mental health.</p>
            </div>
            <div style="margin-top: auto;">
              <button type="button" class="btn btn-primary" style="width: 100%;">Get Started</button>
            </div>
          </div>
        </a>

        <!-- Therapist Registration Card -->
        <a href="{{ route('register', 'therapist') }}" style="text-decoration: none;">
          <div class="card" style="padding: 2rem; text-align: center; cursor: pointer; border: 2px solid #3b82f6; border-radius: 0.75rem; background: rgba(59, 130, 246, 0.05); transition: all 0.3s ease; display: flex; flex-direction: column; gap: 1rem;">
            <div style="font-size: 3rem;">👨‍⚕️</div>
            <div>
              <h2 style="margin: 0.5rem 0; font-size: 1.25rem;">I'm a Therapist</h2>
              <p style="margin: 0.5rem 0 0; color: #6b7280; font-size: 0.9rem;">Create a professional profile, manage patient assignments, monitor progress, and provide support.</p>
            </div>
            <div style="margin-top: auto;">
              <button type="button" class="btn btn-primary" style="width: 100%;">Continue</button>
            </div>
          </div>
        </a>

      </div>

      <div class="auth-divider">
        <span>Already have an account?</span>
      </div>

      <a href="{{ route('login') }}" class="btn btn-secondary" style="display: block; text-align: center; text-decoration: none;">
        Log In
      </a>
    </div>
  </section>
</div>

<style>
  .card {
    background: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  .card:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border-color: #3b82f6;
    transform: translateY(-2px);
  }

  @media (max-width: 768px) {
    [style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>
@endsection
