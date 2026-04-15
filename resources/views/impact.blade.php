@extends('layouts.public')

@section('title', 'Impact Report')
@section('page', 'impact')

@section('content')
<div class="page-hero" style="background-image: url('https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');">
  <a class="page-hero__back" href="{{ route('home') }}">
    <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6"/>
    </svg>
    Back to Home
  </a>
  <div class="container page-hero__content">
    <h1>Impact Report</h1>
    <p class="landing-hero__lead">A transparent look at our reach, outcomes, and the communities we are serving across Uganda.</p>
  </div>
</div>

<section class="container" style="max-width:720px; padding-top:3rem; padding-bottom:4rem;">
  <!-- Key stats -->
  <div class="grid grid--stats landing-stats" style="margin-top:2rem;">
    <article class="card card-stat landing-stat-card">
      <p class="landing-stat-label">Active Users</p>
      <strong class="landing-stat-value">2,400+</strong>
      <p class="landing-stat-note">Since launch</p>
    </article>
    <article class="card card-stat landing-stat-card">
      <p class="landing-stat-label">Sessions Completed</p>
      <strong class="landing-stat-value">18,000</strong>
      <p class="landing-stat-note">And growing</p>
    </article>
    <article class="card card-stat landing-stat-card">
      <p class="landing-stat-label">Avg Mood Lift</p>
      <strong class="landing-stat-value">+31%</strong>
      <p class="landing-stat-note">Per session</p>
    </article>
    <article class="card card-stat landing-stat-card">
      <p class="landing-stat-label">Districts Reached</p>
      <strong class="landing-stat-value">14</strong>
      <p class="landing-stat-note">Across Uganda</p>
    </article>
  </div>

  <div style="margin-top:2.5rem; display:flex; flex-direction:column; gap:2rem;">
    <section class="card dashboard-widget">
      <h2>Who We Are Reaching</h2>
      <p>Our users span a wide demographic — from university students in Kampala managing exam stress, to rural community members dealing with trauma and isolation. 58% of our users identify as first-time mental health help-seekers.</p>
      <ul style="margin-top:0.75rem; padding-left:1.25rem; display:flex; flex-direction:column; gap:0.5rem;">
        <li>62% aged 18–34</li>
        <li>51% female, 47% male, 2% non-binary or prefer not to say</li>
        <li>38% accessing via mobile phone only</li>
        <li>Top concerns: anxiety (44%), stress (38%), sleep issues (29%)</li>
      </ul>
    </section>

    <section class="card dashboard-widget">
      <h2>Outcomes We Are Seeing</h2>
      <p>Based on anonymised self-reported mood data from users who completed at least 4 sessions:</p>
      <ul style="margin-top:0.75rem; padding-left:1.25rem; display:flex; flex-direction:column; gap:0.5rem;">
        <li>74% reported reduced anxiety symptoms after 2 weeks</li>
        <li>68% reported improved sleep quality</li>
        <li>81% said they felt "less alone" after using the platform</li>
        <li>Average session streak: 9 days</li>
      </ul>
    </section>

    <section class="card dashboard-widget">
      <h2>What's Next</h2>
      <p>In 2026 we are expanding to 10 additional districts, launching a Luganda-language interface, and piloting a community health worker integration programme to bring Neuro Haven into existing mental health referral pathways.</p>
    </section>
  </div>
</section>
@endsection