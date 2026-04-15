@extends('layouts.public')

@section('title', 'Contact Us')
@section('page', 'contact')

@section('content')
<section class="page-hero" style="background-image: url('https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');">
  <a class="page-hero__back" href="{{ route('home') }}">
    <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="15 18 9 12 15 6"/>
    </svg>
    Back to Home
  </a>
  <div class="container page-hero__content">
    <h1>Contact Us</h1>
    <p class="landing-hero__lead">Have a question, partnership idea, or need support? We'd love to hear from you.</p>
  </div>
</section>

<section class="container" style="max-width:640px; padding-top:3rem; padding-bottom:4rem;">
  <div style="margin-top:2.5rem; display:flex; flex-direction:column; gap:2rem;">
    <!-- Contact form -->
    <section class="card dashboard-widget">
      <h2>Send a Message</h2>
      <form class="auth-form" style="margin-top:1rem;" data-validate novalidate>
        <div class="input-group">
          <label for="contact-name">Your Name</label>
          <input class="input" id="contact-name" name="name" type="text" placeholder="How should we address you?" required>
          <p class="field-error" data-error-for="name" role="alert"></p>
        </div>

        <div class="input-group">
          <label for="contact-email">Email Address</label>
          <input class="input" id="contact-email" name="email" type="email" placeholder="your@email.com" required>
          <p class="field-error" data-error-for="email" role="alert"></p>
        </div>

        <div class="input-group">
          <label for="contact-subject">Subject</label>
          <select class="select" id="contact-subject" name="subject" required>
            <option value="">Select a topic</option>
            <option value="support">Technical Support</option>
            <option value="research">Research Collaboration</option>
            <option value="partnership">Partnership</option>
            <option value="press">Press & Media</option>
            <option value="other">Other</option>
          </select>
          <p class="field-error" data-error-for="subject" role="alert"></p>
        </div>

        <div class="input-group">
          <label for="contact-message">Message</label>
          <textarea class="review-input" id="contact-message" name="message" rows="5" placeholder="Tell us how we can help..." required style="width:100%;"></textarea>
          <p class="field-error" data-error-for="message" role="alert"></p>
        </div>

        <button class="btn btn-primary btn-lg" type="submit" style="width:100%; margin-top:0.5rem;">Send Message</button>
      </form>
    </section>

    <!-- Direct contact info -->
    <section class="card dashboard-widget">
      <h2>Other Ways to Reach Us</h2>
      <div style="display:flex; flex-direction:column; gap:1rem; margin-top:0.75rem;">
        <div style="display:flex; gap:0.75rem; align-items:center;">
          <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
          </svg>
          <span>hello@neurohaven.ug</span>
        </div>
        <div style="display:flex; gap:0.75rem; align-items:center;">
          <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>
          <span>Kampala, Uganda</span>
        </div>
      </div>
    </section>
  </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var form = document.querySelector('.auth-form');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      form.innerHTML = '<div style="text-align:center;padding:2rem 0;"><p style="font-size:1.25rem;font-weight:600;color:var(--color-primary)">Message sent!</p><p style="margin-top:0.5rem;color:var(--color-text-muted)">We\'ll get back to you within 2 business days.</p></div>';
    });
  }
});
</script>
@endsection