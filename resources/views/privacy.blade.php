@extends('layouts.public')

@section('title', 'Privacy Policy')
@section('page', 'privacy')

@section('content')
<div class="page-hero" style="background-image: url('https://images.pexels.com/photos/60504/security-protection-anti-virus-software-60504.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');">
  <a class="page-hero__back" href="{{ route('home') }}">
    <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6"/>
    </svg>
    Back to Home
  </a>
  <div class="container page-hero__content">
    <h1>Privacy Policy</h1>
    <p class="landing-hero__lead">Your privacy is fundamental to everything we build. Here is exactly what we collect, why, and how we protect it.</p>
  </div>
</div>

<section class="container" style="max-width:720px; padding-top:3rem; padding-bottom:4rem;">
  <div style="margin-top:2.5rem; display:flex; flex-direction:column; gap:1.75rem;">
    <section class="card dashboard-widget">
      <h2>What We Collect</h2>
      <ul style="padding-left:1.25rem; display:flex; flex-direction:column; gap:0.5rem; margin-top:0.5rem;">
        <li><strong>Account information</strong> — display name, email address, district. You may use an anonymous display name.</li>
        <li><strong>Mood and session data</strong> — your mood check-ins, session history, and reflection notes. This stays on your device until you choose to sync.</li>
        <li><strong>Usage data</strong> — which environments you use and for how long, to improve recommendations. This is anonymised.</li>
      </ul>
    </section>

    <section class="card dashboard-widget">
      <h2>What We Never Do</h2>
      <ul style="padding-left:1.25rem; display:flex; flex-direction:column; gap:0.5rem; margin-top:0.5rem;">
        <li>We never sell your personal data to third parties.</li>
        <li>We never share your health information with employers, insurers, or government bodies.</li>
        <li>We never use your data for advertising profiling.</li>
        <li>We never require your real name — anonymous use is fully supported.</li>
      </ul>
    </section>

    <section class="card dashboard-widget">
      <h2>Data Storage & Security</h2>
      <p>Your mood and session data is currently stored locally on your device using browser localStorage. When cloud sync is introduced, all data will be encrypted in transit and at rest. We follow industry-standard security practices and conduct regular security reviews.</p>
    </section>

    <section class="card dashboard-widget">
      <h2>Your Rights</h2>
      <p>You have the right to access, correct, or delete your data at any time. To request data deletion, contact us at <a href="{{ route('contact') }}" style="color:var(--color-primary)">hello@neurohaven.ug</a>. We will process all requests within 30 days.</p>
    </section>

    <section class="card dashboard-widget">
      <h2>Changes to This Policy</h2>
      <p>We will notify you of any material changes to this policy via email and an in-app notice at least 14 days before changes take effect.</p>
    </section>
  </div>
</section>
@endsection