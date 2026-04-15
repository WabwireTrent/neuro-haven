@extends('layouts.public')

@section('title', 'Terms of Use')
@section('page', 'terms')

@section('content')
<div class="page-hero" style="background-image: url('https://images.pexels.com/photos/5668858/pexels-photo-5668858.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');">
  <a class="page-hero__back" href="{{ route('home') }}">
    <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6"/>
    </svg>
    Back to Home
  </a>
  <div class="container page-hero__content">
    <h1>Terms of Use</h1>
    <p class="landing-hero__lead">By using Neuro Haven, you agree to these terms. Please read them carefully.</p>
  </div>
</div>

<section class="container" style="max-width:720px; padding-top:3rem; padding-bottom:4rem;">
  <div style="margin-top:2.5rem; display:flex; flex-direction:column; gap:1.75rem;">
    <section class="card dashboard-widget">
      <h2>1. Not a Crisis Service</h2>
      <p>Neuro Haven is a wellness and self-help platform. It is <strong>not</strong> a substitute for professional mental health treatment, crisis intervention, or emergency services. If you are in crisis or danger, please contact:</p>
      <ul style="padding-left:1.25rem; margin-top:0.75rem; display:flex; flex-direction:column; gap:0.5rem;">
        <li>Uganda Police: <strong>999</strong></li>
        <li>Butabika Hospital Crisis Line: <strong>+256 414 505 000</strong></li>
        <li>Mental Health Uganda Helpline: <strong>0800 212 121</strong> (toll-free)</li>
      </ul>
    </section>

    <section class="card dashboard-widget">
      <h2>2. Eligibility</h2>
      <p>You must be at least 16 years old to use Neuro Haven. Users under 18 should have parental or guardian awareness of their use of the platform.</p>
    </section>

    <section class="card dashboard-widget">
      <h2>3. Acceptable Use</h2>
      <p>You agree not to misuse the platform, attempt to access other users' data, reverse-engineer the software, or use the service for any unlawful purpose. We reserve the right to suspend accounts that violate these terms.</p>
    </section>

    <section class="card dashboard-widget">
      <h2>4. Content & Intellectual Property</h2>
      <p>All VR environments, audio, and content on Neuro Haven are owned by or licensed to Neuro Haven Uganda Ltd. You may not reproduce, distribute, or create derivative works without written permission.</p>
    </section>

    <section class="card dashboard-widget">
      <h2>5. Limitation of Liability</h2>
      <p>Neuro Haven is provided "as is". We make no guarantees about specific therapeutic outcomes. We are not liable for any indirect, incidental, or consequential damages arising from your use of the platform.</p>
    </section>

    <section class="card dashboard-widget">
      <h2>6. Changes to Terms</h2>
      <p>We may update these terms from time to time. Continued use of the platform after changes constitutes acceptance. We will notify you of significant changes via email.</p>
    </section>

    <section class="card dashboard-widget">
      <h2>7. Contact</h2>
      <p>Questions about these terms? <a href="{{ route('contact') }}" style="color:var(--color-primary)">Contact us</a> and we'll respond within 5 business days.</p>
    </section>
  </div>
</section>
@endsection