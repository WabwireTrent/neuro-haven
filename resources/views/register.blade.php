@extends('layouts.auth')

@section('title', 'Create Account')

@section('auth_content')
<a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.75rem 1.5rem; font-size: 0.8rem; font-weight: 600; color: var(--color-text-muted); text-decoration: none; border-bottom: 1px solid var(--color-border);">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
  Back to Home
</a>

<nav style="display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid var(--color-border);">
  <a href="{{ route('register.choice') }}" style="display: flex; align-items: center; justify-content: center; padding: 0.85rem 1rem; font-weight: 700; font-size: 0.9rem; color: var(--color-primary); text-decoration: none; border-bottom: 2px solid var(--color-primary); background: var(--color-surface);">Create Account</a>
  <a href="{{ route('login') }}" style="display: flex; align-items: center; justify-content: center; padding: 0.85rem 1rem; font-weight: 700; font-size: 0.9rem; color: var(--color-text-muted); text-decoration: none; border-bottom: 2px solid transparent;">Log In</a>
</nav>

<div style="padding: 1.5rem; display: grid; gap: 1.25rem;">
  <div>
    <h1 style="font-size: 1.35rem; font-weight: 800; margin: 0 0 0.25rem;">Create your account</h1>
    <p class="text-muted" style="font-size: 0.85rem; margin: 0;">Start your wellness journey with Neuro Haven.</p>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; padding: 0.5rem; background: var(--color-surface-muted); border-radius: var(--radius-xl);" data-role-selector>
    <button type="button" class="btn btn-ghost" data-role="patient" style="padding: 0.625rem; font-size: 0.85rem; font-weight: 700; border-radius: var(--radius-lg); background: var(--color-surface); border: 1px solid var(--color-primary); color: var(--color-primary);">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.375rem;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      I'm a Patient
    </button>
    <button type="button" class="btn btn-ghost" data-role="therapist" style="padding: 0.625rem; font-size: 0.85rem; font-weight: 700; border-radius: var(--radius-lg);">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; vertical-align: middle; margin-right: 0.375rem;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      I'm a Therapist
    </button>
  </div>

  @if($errors->any())
    <div style="padding: 0.75rem; background: var(--color-danger-soft); border: 1px solid #fecaca; border-radius: var(--radius-lg); color: #991b1b; font-size: 0.85rem;">
      <ul style="margin: 0; padding-left: 1.25rem;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('register.post') }}" style="display: grid; gap: 1rem;" data-validate novalidate>
    @csrf
    <input type="hidden" name="type" value="{{ $type ?? 'patient' }}">

    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="reg-name">Full Name</label>
        <input class="form-input" id="reg-name" name="name" type="text" value="{{ old('name') }}" placeholder="Your name" required>
        @error('name')<p class="field-error">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="reg-email">Email Address</label>
        <input class="form-input" id="reg-email" name="email" type="email" value="{{ old('email') }}" placeholder="your@email.com" autocomplete="email" required>
        @error('email')<p class="field-error">{{ $message }}</p>@enderror
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label" for="reg-password">Password</label>
        <div class="pw-wrapper">
          <input class="pw-input" id="reg-password" name="password" type="password" placeholder="Min. 8 characters" minlength="8" required data-pw-field data-pw-strength>
          <button type="button" class="pw-toggle" data-pw-toggle aria-label="Show password">
            <svg class="pw-eye-on" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <svg class="pw-eye-off" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
          </button>
        </div>
        <div class="pw-strength" id="reg-pw-strength" data-pw-strength-meter>
          <div class="pw-strength__bar">
            <div class="pw-strength__bar-fill"></div>
          </div>
          <span class="pw-strength__label"></span>
        </div>
        @error('password')<p class="field-error">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="reg-password-confirm">Confirm Password</label>
        <div class="pw-wrapper">
          <input class="pw-input" id="reg-password-confirm" name="password_confirmation" type="password" placeholder="Confirm password" minlength="8" required data-pw-field data-pw-match="reg-password">
          <button type="button" class="pw-toggle" data-pw-toggle aria-label="Show password">
            <svg class="pw-eye-on" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <svg class="pw-eye-off" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
          </button>
        </div>
        <div class="pw-match" id="reg-pw-match" data-pw-match-indicator>
          <svg class="pw-match__icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          <span class="pw-match__text"></span>
        </div>
      </div>
    </div>

    <div id="therapist-fields" style="display: {{ $type === 'therapist' ? 'grid' : 'none' }}; gap: 1rem;">
      <div class="form-group">
        <label class="form-label" for="reg-license">Professional License Number</label>
        <input class="form-input" id="reg-license" name="license_number" type="text" value="{{ old('license_number') }}" placeholder="e.g. UPM-2024-01234" {{ $type === 'therapist' ? 'required' : '' }}>
        @error('license_number')<p class="field-error">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="reg-specialization">Specialty</label>
        <select class="form-select" id="reg-specialization" name="specialization">
          <option value="">Select specialty</option>
          <option value="clinical_psychology" {{ old('specialization') === 'clinical_psychology' ? 'selected' : '' }}>Clinical Psychology</option>
          <option value="counseling" {{ old('specialization') === 'counseling' ? 'selected' : '' }}>Counseling</option>
          <option value="psychiatry" {{ old('specialization') === 'psychiatry' ? 'selected' : '' }}>Psychiatry</option>
          <option value="social_work" {{ old('specialization') === 'social_work' ? 'selected' : '' }}>Social Work</option>
          <option value="occupational_therapy" {{ old('specialization') === 'occupational_therapy' ? 'selected' : '' }}>Occupational Therapy</option>
        </select>
        @error('specialization')<p class="field-error">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="reg-experience">Years of Experience</label>
        <input class="form-input" id="reg-experience" name="years_of_experience" type="number" min="0" max="70" value="{{ old('years_of_experience') }}" placeholder="e.g. 5" {{ $type === 'therapist' ? 'required' : '' }}>
        @error('years_of_experience')<p class="field-error">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="reg-bio">Professional Bio</label>
        <textarea class="form-input" id="reg-bio" name="bio" rows="3" placeholder="Tell us about your background and approach...">{{ old('bio') }}</textarea>
        @error('bio')<p class="field-error">{{ $message }}</p>@enderror
      </div>
    </div>

    <div style="display: grid; gap: 0.5rem;">
      <label class="form-checkbox">
        <input type="checkbox" name="terms" required {{ old('terms') ? 'checked' : '' }}>
        I agree to the <a href="{{ route('terms') }}" target="_blank" style="font-weight: 600;">Terms of Use</a> and <a href="{{ route('privacy') }}" target="_blank" style="font-weight: 600;">Privacy Policy</a>
      </label>
      @error('terms')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; height: 46px; font-size: 1rem;">Create Account</button>
  </form>

  <p style="text-align: center; margin: 0; font-size: 0.8rem; color: var(--color-text-muted);">
    Already have an account? <a href="{{ route('login') }}" style="font-weight: 700;">Log In</a>
  </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  setupPasswordToggles();
  setupPasswordStrength();
  setupPasswordMatch();

  var selector = document.querySelector('[data-role-selector]');
  if (!selector) return;
  var typeInput = document.querySelector('input[name="type"]');
  var therapistFields = document.getElementById('therapist-fields');
  var btns = selector.querySelectorAll('[data-role]');
  var therapistRequired = ['license_number', 'years_of_experience'];

  function setRole(role) {
    typeInput.value = role;
    btns.forEach(function (b) {
      var isActive = b.getAttribute('data-role') === role;
      b.style.background = isActive ? 'var(--color-surface)' : 'transparent';
      b.style.border = isActive ? '1px solid var(--color-primary)' : '1px solid transparent';
      b.style.color = isActive ? 'var(--color-primary)' : 'var(--color-text-muted)';
    });
    if (therapistFields) {
      therapistFields.style.display = role === 'therapist' ? 'grid' : 'none';
    }
    therapistRequired.forEach(function (name) {
      var el = document.querySelector('[name="' + name + '"]');
      if (el) el.required = role === 'therapist';
    });
  }

  btns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      setRole(btn.getAttribute('data-role'));
    });
  });

  setRole('{{ $type }}');
});
</script>
@endsection
