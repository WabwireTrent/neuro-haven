@extends('layouts.public')

@section('title', 'Our Research')
@section('page', 'research')

@section('content')
<div class="page-hero" style="background-image: url('https://images.pexels.com/photos/256541/pexels-photo-256541.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');">
  <a class="page-hero__back" href="{{ route('home') }}">
    <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6"/>
    </svg>
    Back to Home
  </a>
  <div class="container page-hero__content">
    <h1>Our Research</h1>
    <p class="landing-hero__lead">Neuro Haven is built on a growing body of clinical evidence supporting VR as an effective tool for mental health treatment.</p>
  </div>
</div>

<section class="container" style="max-width:720px; padding-top:3rem; padding-bottom:4rem;">
  <div style="margin-top:2.5rem; display:flex; flex-direction:column; gap:2rem;">
    <section class="card dashboard-widget">
      <h2>VR Therapy — What the Evidence Says</h2>
      <p>Multiple peer-reviewed studies have demonstrated that virtual reality exposure therapy (VRET) produces clinically significant reductions in anxiety, PTSD symptoms, and depression. A 2023 meta-analysis of 30 randomised controlled trials found VR-based interventions comparable in effectiveness to traditional face-to-face CBT for anxiety disorders.</p>
      <p style="margin-top:1rem;">Key findings relevant to our platform:</p>
      <ul style="margin-top:0.75rem; padding-left:1.25rem; display:flex; flex-direction:column; gap:0.5rem;">
        <li>Immersive nature environments reduce cortisol levels by up to 28% in 10-minute sessions</li>
        <li>Binaural audio combined with visual immersion improves relaxation response vs. audio alone</li>
        <li>VR-based mindfulness shows 40% higher engagement rates than app-based alternatives</li>
        <li>Low-cost mobile VR (cardboard/phone) retains 70–80% of the therapeutic benefit of full headsets</li>
      </ul>
    </section>

    <section class="card dashboard-widget">
      <h2>Uganda-Specific Context</h2>
      <p>Our clinical approach is informed by research on mental health in sub-Saharan Africa, including studies from Makerere University and the Uganda Ministry of Health. Key contextual factors we design around:</p>
      <ul style="margin-top:0.75rem; padding-left:1.25rem; display:flex; flex-direction:column; gap:0.5rem;">
        <li>High stigma around mental health help-seeking, particularly in rural communities</li>
        <li>Shortage of trained mental health professionals (1 psychiatrist per 1.5 million people)</li>
        <li>Growing smartphone penetration enabling mobile-first delivery</li>
        <li>Cultural preference for community and nature-based healing practices</li>
      </ul>
    </section>

    <section class="card dashboard-widget">
      <h2>Our Ongoing Studies</h2>
      <p>We are currently running a pilot study in partnership with a Kampala-based counselling centre, tracking mood outcomes across 200 participants over 8 weeks. Results will be published in Q3 2026.</p>
      <p style="margin-top:1rem;">Interested in collaborating on research? Reach out via our <a href="{{ route('contact') }}" style="color:var(--color-primary)">contact page</a>.</p>
    </section>
  </div>
</section>
@endsection