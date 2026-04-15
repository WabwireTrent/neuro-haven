@extends('layouts.public')

@section('title', 'About Us')
@section('page', 'about')

@section('content')
<section class="page-hero" style="background-image: url('https://images.pexels.com/photos/3560044/pexels-photo-3560044.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');">
  <div class="page-hero__overlay"></div>
  <a class="page-hero__back" href="{{ route('home') }}">
    <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6"/>
    </svg>
    Back to Home
  </a>
  <div class="container page-hero__content">
    <h1>About Neuro Haven</h1>
    <p class="landing-hero__lead">We are a Ugandan mental health technology initiative building accessible, stigma-free care through virtual reality.</p>
  </div>
</section>

<section class="container" style="max-width:720px; padding-top:3rem; padding-bottom:4rem;">
  <div style="margin-top:2.5rem; display:flex; flex-direction:column; gap:2rem;">
    <section class="card dashboard-widget">
      <h2>Our Mission</h2>
      <p>Uganda faces a severe mental health treatment gap — over 35% of the population experiences psychological distress, yet fewer than 10% ever seek help. Stigma, cost, and lack of trained professionals are the biggest barriers.</p>
      <p style="margin-top:1rem;">Neuro Haven exists to break those barriers. We use immersive VR environments, guided breathing, and evidence-based therapeutic techniques to deliver accessible mental wellness support to every Ugandan — regardless of location or income.</p>
    </section>

    <section class="card dashboard-widget">
      <h2>Who We Are</h2>
      <p>We are a multidisciplinary team of mental health professionals, technologists, and community advocates based in Kampala. Our clinical advisors include licensed psychologists and counsellors with deep roots in Ugandan communities.</p>
      <p style="margin-top:1rem;">Our technology team builds with privacy and accessibility at the core — every design decision is made with our users' dignity and safety in mind.</p>
    </section>

    <section class="card dashboard-widget">
      <h2>Our Values</h2>
      <ul style="list-style:none; padding:0; display:flex; flex-direction:column; gap:0.75rem; margin-top:0.5rem;">
        <li style="display:flex; gap:0.75rem; align-items:flex-start;">
          <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          <span><strong>Dignity first</strong> — every person deserves compassionate, judgement-free care.</span>
        </li>
        <li style="display:flex; gap:0.75rem; align-items:flex-start;">
          <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          <span><strong>Radical accessibility</strong> — core features are free and work on low-end devices.</span>
        </li>
        <li style="display:flex; gap:0.75rem; align-items:flex-start;">
          <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          <span><strong>Cultural grounding</strong> — our environments and language are designed for Uganda, not imported.</span>
        </li>
        <li style="display:flex; gap:0.75rem; align-items:flex-start;">
          <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          <span><strong>Privacy by design</strong> — your data is yours. We never sell or share personal health information.</span>
        </li>
      </ul>
    </section>

    <div style="text-align:center; padding:1rem 0;">
      <a class="btn btn-primary btn-lg" href="{{ route('register') }}">Join Neuro Haven</a>
    </div>
  </div>
</section>
@endsection