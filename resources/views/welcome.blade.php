@extends('layouts.public')

@section('title', 'Accessible VR Mental Health')
@section('page', 'index')
@section('main-class', 'landing-main')

@section('content')
<section class="container landing-hero-wrap">
  <div class="landing-hero">
    <div class="landing-hero__content">
      <p class="landing-kicker">Uganda's First VR Therapy</p>
      <h1>Accessible mental health through virtual reality</h1>
      <p class="landing-hero__lead">Stigma-free, affordable therapy for everyone. Breakthrough barriers with immersive care tailored for the Ugandan community.</p>
      <div class="landing-hero__actions">
        <a class="btn btn-primary btn-lg" href="{{ route('register') }}">Start free session</a>
        <a class="btn landing-demo-btn btn-lg" href="{{ route('library') }}">Browse Library</a>
      </div>
    </div>
  </div>
</section>

<section class="container grid grid--stats landing-stats" aria-label="Key impact statistics">
  <article class="card card-stat landing-stat-card">
    <p class="landing-stat-label">Distressed Ugandans</p>
    <strong class="landing-stat-value">35%</strong>
    <p class="landing-stat-note">High Need</p>
  </article>
  <article class="card card-stat landing-stat-card">
    <p class="landing-stat-label">Seek Help</p>
    <strong class="landing-stat-value">&lt;10%</strong>
    <p class="landing-stat-note landing-stat-note--warn">Treatment Gap</p>
  </article>
  <article class="card card-stat landing-stat-card">
    <p class="landing-stat-label">VR Spaces</p>
    <strong class="landing-stat-value">6</strong>
    <p class="landing-stat-note">Expanding</p>
  </article>
  <article class="card card-stat landing-stat-card">
    <p class="landing-stat-label">Core Access</p>
    <strong class="landing-stat-value">Free</strong>
    <p class="landing-stat-note">Open to All</p>
  </article>
</section>

<section class="landing-how">
  <div class="container">
    <h2>How it works</h2>
    <ol class="landing-steps">
      <li>
        <span class="landing-step-num">1</span>
        <div>
          <h3>Create profile</h3>
          <p>Secure, anonymous registration to protect your privacy and reduce stigma.</p>
        </div>
      </li>
      <li>
        <span class="landing-step-num">2</span>
        <div>
          <h3>Choose environment</h3>
          <p>Select from culturally sensitive, calming virtual spaces designed by experts.</p>
        </div>
      </li>
      <li>
        <span class="landing-step-num">3</span>
        <div>
          <h3>Begin session</h3>
          <p>Immerse yourself in guided meditation or therapeutic exercises in VR.</p>
        </div>
      </li>
      <li>
        <span class="landing-step-num">4</span>
        <div>
          <h3>Track progress</h3>
          <p>Monitor your emotional wellbeing over time with intuitive visual insights.</p>
        </div>
      </li>
    </ol>
  </div>
</section>

<section class="container landing-library-preview" aria-label="VR environment previews">
  <div class="landing-section-head">
    <h2>Environment Library</h2>
    <a class="landing-link-arrow" href="{{ route('library') }}">View all</a>
  </div>

  <div class="grid grid--cards landing-env-grid">
    <article class="card card-environment landing-env-card">
      <div class="landing-env-media" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC7dPGccNgg5s2XeuQ9PzuwpcFZXUYLHrgqMfp6usdFxXVQM5Ffa6iE-RhErVy4OMCCBUknUO0nAxSko-53AactPodGrUp-oO4MG6rFw4Rb4AbUnBrcYlFJqyFd4KctUlOZ-nOA5OVqskGlJPPm_BJzH6VkYg1GnA4erzJKDFdN3-0TtqdhXTVVUygHoH__stViO22d3pERgoz8NYaYv9b2qoebGOCFZf1cNqOuhjJL9mtCMmx5Dxc9hDAShbhosJT-4fNfkh_wqq0');" role="img" aria-label="Lush green tropical forest with sunbeams"></div>
      <div class="landing-env-body">
        <h3>Calm Forest</h3>
        <p>Soothing sounds of nature and dappled sunlight to reduce anxiety.</p>
        <div class="cluster">
          <span class="pill">Anti-Anxiety</span>
          <span class="pill">Free</span>
        </div>
      </div>
    </article>

    <article class="card card-environment landing-env-card">
      <div class="landing-env-media" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDwRowehgXCapx9SyxPAZX6h_ReweecNvsUMdwxHKcAOgcHoBcvuXQqKUA1r76MlmUuM40cUoEdU6zAGNWAugt3bFRruoR3rpy6VyMJtezcKQ_enucYuXhpKY4NC98DMIsdwl2LDBRKdlh7P2JvaF4gq_nDyUxYa3S5naL-58BohmE6KM7-_OFSJEtJ8ZYLqEH-I7LOqRNB-OC13OQE2Go8nDex_yNa7eyERtK9DR-URyFI9tOHidYQlY6kERJx74lW6AQpc-Os7UI');" role="img" aria-label="Gentle blue ocean waves under clear sky"></div>
      <div class="landing-env-body">
        <h3>Ocean Horizon</h3>
        <p>Endless blue vistas and gentle waves for deep meditative states.</p>
        <div class="cluster">
          <span class="pill">Mindfulness</span>
          <span class="pill">Popular</span>
        </div>
      </div>
    </article>

    <article class="card card-environment landing-env-card">
      <div class="landing-env-media" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAbIC2oUMTsWctqnrhxEhwNp9Tstw6ZGcw4iWWFqz-aIQgTSVIAL6lwHME1vek5DsZ5IXbjbNCt93O0OFLC_xkILNU819WWKHY-IB_Al8REPGJOkRqLSSXvLmFCe7RYqxP0WbazdxT29xUhaxfabzch-W4z_l2sRPrfT7ZcRwsO72SM_knI0NpwKFie8mks1CyI7NdEtwCz6ewvW38dYJxuKoC9wJQv4meTP-B9rqq-9TJyRdZWZbjBCdJfLFRM3lMXEC7aQzqJ_3Q');" role="img" aria-label="Stunning mountain peak during sunrise"></div>
      <div class="landing-env-body">
        <h3>Mountain Retreat</h3>
        <p>High-altitude clarity and crisp air to gain perspective and focus.</p>
        <div class="cluster">
          <span class="pill">Clarity</span>
          <span class="pill">Premium</span>
        </div>
      </div>
    </article>
  </div>
</section>

<section class="container landing-cta-wrap">
  <div class="landing-cta">
    <h2>Ready to find your haven?</h2>
    <p>Join thousands of Ugandans taking their first step towards mental wellness with VR.</p>
    <a class="btn btn-lg landing-cta-btn" href="{{ route('register') }}">Get Started Now</a>
  </div>
</section>
@endsection

@section('scripts')
<script>
// If already logged in, skip landing page and go straight to dashboard
(function () {
  try {
    var user = JSON.parse(localStorage.getItem('nh_user'));
    if (user) { window.location.replace('{{ route("dashboard") }}'); }
  } catch (e) {}
})();
</script>
@endsection