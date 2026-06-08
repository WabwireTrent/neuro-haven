@extends('layouts.public')

@section('title', 'VR Mental Health Therapy Platform')
@section('page', 'index')
@section('main-class', 'landing-main')

@section('content')
<section class="landing-hero-wrap">
  <div class="landing-hero-shapes">
    <span></span><span></span><span></span><span></span><span></span>
  </div>
  <div class="container">
    <div class="landing-hero">
      <p class="landing-kicker">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
        Uganda's First VR Therapy Platform
      </p>
      <h1>Mental healthcare that <span>meets you where you are</span></h1>
      <p class="landing-hero__lead">Stigma-free, affordable therapy through immersive virtual reality. Backed by clinical research, designed for the Ugandan community.</p>
      <div class="landing-hero__actions">
        <a class="btn btn-primary" href="{{ route('register.choice') }}">Start Your Free Session</a>
        <a class="btn-ghost-light" href="{{ route('library') }}">
          Browse Environments
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>

<section class="landing-stats" aria-label="Key impact statistics">
  <article class="landing-stat-card">
    <div class="landing-stat-icon landing-stat-icon--primary">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </div>
    <p class="landing-stat-label">Distressed Population</p>
    <strong class="landing-stat-value">35%</strong>
    <p class="landing-stat-note">of Ugandans experiencing mental health challenges</p>
  </article>
  <article class="landing-stat-card">
    <div class="landing-stat-icon landing-stat-icon--accent">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <p class="landing-stat-label">Treatment Gap</p>
    <strong class="landing-stat-value">&lt;10%</strong>
    <p class="landing-stat-note">of those in need currently receive care</p>
  </article>
  <article class="landing-stat-card">
    <div class="landing-stat-icon landing-stat-icon--success">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
    </div>
    <p class="landing-stat-label">VR Environments</p>
    <strong class="landing-stat-value">12+</strong>
    <p class="landing-stat-note">therapeutic immersive spaces and growing</p>
  </article>
  <article class="landing-stat-card">
    <div class="landing-stat-icon landing-stat-icon--warning">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
    </div>
    <p class="landing-stat-label">Access</p>
    <strong class="landing-stat-value">Free</strong>
    <p class="landing-stat-note">open to all, no referral needed</p>
  </article>
</section>

<section class="landing-how">
  <div class="container">
    <h2>How it works</h2>
    <ol class="landing-steps">
      <li class="landing-step">
        <span class="landing-step-num">1</span>
        <h3>Create your profile</h3>
        <p>Secure, anonymous registration designed to protect your privacy and reduce stigma.</p>
      </li>
      <li class="landing-step">
        <span class="landing-step-num">2</span>
        <h3>Choose your space</h3>
        <p>Select from culturally sensitive virtual environments crafted by clinical experts.</p>
      </li>
      <li class="landing-step">
        <span class="landing-step-num">3</span>
        <h3>Begin your session</h3>
        <p>Immerse yourself in guided therapy, meditation, or breathing exercises.</p>
      </li>
      <li class="landing-step">
        <span class="landing-step-num">4</span>
        <h3>Track your progress</h3>
        <p>Monitor your emotional wellbeing over time with intuitive charts and insights.</p>
      </li>
    </ol>
  </div>
</section>

<section class="landing-library-preview" aria-label="VR environment previews">
  <div class="landing-section-head">
    <h2>Explore therapeutic environments</h2>
    <a class="landing-link-arrow" href="{{ route('library') }}">View all environments</a>
  </div>
  <div class="landing-env-grid">
    <article class="landing-env-card">
      <div class="landing-env-media" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC7dPGccNgg5s2XeuQ9PzuwpcFZXUYLHrgqMfp6usdFxXVQM5Ffa6iE-RhErVy4OMCCBUknUO0nAxSko-53AactPodGrUp-oO4MG6rFw4Rb4AbUnBrcYlFJqyFd4KctUlOZ-nOA5OVqskGlJPPm_BJzH6VkYg1GnA4erzJKDFdN3-0TtqdhXTVVUygHoH__stViO22d3pERgoz8NYaYv9b2qoebGOCFZf1cNqOuhjJL9mtCMmx5Dxc9hDAShbhosJT-4fNfkh_wqq0');" role="img" aria-label="Lush green tropical forest with sunbeams"></div>
      <div class="landing-env-body">
        <h3>Calm Forest</h3>
        <p>Soothing sounds of nature and dappled sunlight designed to reduce anxiety and promote calm.</p>
        <div class="landing-env-pills">
          <span class="pill--anxiety">Anti-Anxiety</span>
          <span class="pill--free">Free</span>
        </div>
      </div>
    </article>
    <article class="landing-env-card">
      <div class="landing-env-media" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDwRowehgXCapx9SyxPAZX6h_ReweecNvsUMdwxHKcAOgcHoBcvuXQqKUA1r76MlmUuM40cUoEdU6zAGNWAugt3bFRruoR3rpy6VyMJtezcKQ_enucYuXhpKY4NC98DMIsdwl2LDBRKdlh7P2JvaF4gq_nDyUxYa3S5naL-58BohmE6KM7-_OFSJEtJ8ZYLqEH-I7LOqRNB-OC13OQE2Go8nDex_yNa7eyERtK9DR-URyFI9tOHidYQlY6kERJx74lW6AQpc-Os7UI');" role="img" aria-label="Gentle blue ocean waves under clear sky"></div>
      <div class="landing-env-body">
        <h3>Ocean Horizon</h3>
        <p>Endless blue vistas and gentle wave sounds for deep meditative states and mindfulness.</p>
        <div class="landing-env-pills">
          <span class="pill--mindfulness">Mindfulness</span>
          <span class="pill--popular">Popular</span>
        </div>
      </div>
    </article>
    <article class="landing-env-card">
      <div class="landing-env-media" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAbIC2oUMTsWctqnrhxEhwNp9Tstw6ZGcw4iWWFqz-aIQgTSVIAL6lwHME1vek5DsZ5IXbjbNCt93O0OFLC_xkILNU819WWKHY-IB_Al8REPGJOkRqLSSXvLmFCe7RYqxP0WbazdxT29xUhaxfabzch-W4z_l2sRPrfT7ZcRwsO72SM_knI0NpwKFie8mks1CyI7NdEtwCz6ewvW38dYJxuKoC9wJQv4meTP-B9rqq-9TJyRdZWZbjBCdJfLFRM3lMXEC7aQzqJ_3Q');" role="img" aria-label="Stunning mountain peak during sunrise"></div>
      <div class="landing-env-body">
        <h3>Mountain Retreat</h3>
        <p>High-altitude clarity and crisp mountain air to help you gain perspective and focus.</p>
        <div class="landing-env-pills">
          <span class="pill--clarity">Clarity</span>
          <span class="pill--free">Free</span>
        </div>
      </div>
    </article>
  </div>
</section>

<section class="landing-testimonials">
  <h2>What our community says</h2>
  <div class="landing-testimonial-grid">
    <article class="landing-testimonial-card">
      <blockquote>I never thought I could access therapy without fear of judgment. This platform changed my life.</blockquote>
      <div class="landing-testimonial-author">
        <div class="landing-testimonial-avatar">S</div>
        <div>
          <div class="landing-testimonial-name">Sarah K.</div>
          <div class="landing-testimonial-role">Kampala</div>
        </div>
      </div>
    </article>
    <article class="landing-testimonial-card">
      <blockquote>The VR forest environment helps me manage my anxiety better than any medication I've tried.</blockquote>
      <div class="landing-testimonial-author">
        <div class="landing-testimonial-avatar">J</div>
        <div>
          <div class="landing-testimonial-name">James M.</div>
          <div class="landing-testimonial-role">Jinja</div>
        </div>
      </div>
    </article>
    <article class="landing-testimonial-card">
      <blockquote>As a therapist, I've seen remarkable progress in patients who struggled with traditional talk therapy.</blockquote>
      <div class="landing-testimonial-author">
        <div class="landing-testimonial-avatar">D</div>
        <div>
          <div class="landing-testimonial-name">Dr. Nambi</div>
          <div class="landing-testimonial-role">Clinical Psychologist</div>
        </div>
      </div>
    </article>
  </div>
</section>

<section class="landing-features">
  <h2>Built for clinical excellence</h2>
  <p class="landing-features-sub">Evidence-based tools that empower both patients and healthcare providers.</p>
  <div class="landing-feature-grid">
    <article class="landing-feature-card">
      <div class="landing-feature-icon" style="background: rgba(59,130,246,0.12); color: var(--color-primary-light);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
      </div>
      <h3>Clinical Assessments</h3>
      <p>Validated PHQ-9 and GAD-7 screening tools with automated severity scoring and progress tracking.</p>
    </article>
    <article class="landing-feature-card">
      <div class="landing-feature-icon" style="background: rgba(6,182,212,0.12); color: var(--color-secondary);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      </div>
      <h3>Outcome Analytics</h3>
      <p>Comprehensive dashboards showing mood trends, session completion rates, and therapeutic progress.</p>
    </article>
    <article class="landing-feature-card">
      <div class="landing-feature-icon" style="background: rgba(139,92,246,0.12); color: var(--color-accent);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      <h3>HIPAA-Grade Security</h3>
      <p>Enterprise-level encryption and privacy controls ensuring your data stays protected and confidential.</p>
    </article>
    <article class="landing-feature-card">
      <div class="landing-feature-icon" style="background: rgba(16,185,129,0.12); color: var(--color-success);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
      <h3>Therapist Portal</h3>
      <p>Dedicated provider dashboard with patient assignments, treatment plans, and crisis alert monitoring.</p>
    </article>
    <article class="landing-feature-card">
      <div class="landing-feature-icon" style="background: rgba(245,158,11,0.12); color: var(--color-warning);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <h3>VR Therapy Sessions</h3>
      <p>Immersive therapeutic environments calibrated for anxiety, depression, trauma, and stress reduction.</p>
    </article>
    <article class="landing-feature-card">
      <div class="landing-feature-icon" style="background: rgba(59,130,246,0.12); color: var(--color-primary-light);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
      </div>
      <h3>Care Plans</h3>
      <p>Structured treatment plans with milestones, progress tracking, and therapist-guided interventions.</p>
    </article>
  </div>
</section>

<section class="landing-cta-wrap">
  <div class="landing-cta">
    <h2>Ready to find your haven?</h2>
    <p>Join thousands of Ugandans taking their first step towards mental wellness with VR-assisted therapy.</p>
    <a class="landing-cta-btn" href="{{ route('register') }}">
      Get Started Now
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
  </div>
</section>
@endsection
