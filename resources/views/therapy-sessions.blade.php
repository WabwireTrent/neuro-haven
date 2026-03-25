@extends('layouts.app')

@section('title', 'Therapy Sessions')
@section('page', 'therapy')

@section('content')
<div class="container">
  <section class="stack p-4">
    <div class="surface p-6">
      <h1>Therapy Sessions</h1>
      <p class="text-muted">Browse guided sessions to improve sleep, focus, and emotional resilience.</p>
    </div>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
      <article class="surface p-4">
        <h2>Mindfulness Basics</h2>
        <p class="text-muted">10 min • Beginner</p>
        <a href="#" class="btn btn-secondary mt-2">Start</a>
      </article>
      <article class="surface p-4">
        <h2>Stress Release</h2>
        <p class="text-muted">15 min • Intermediate</p>
        <a href="#" class="btn btn-secondary mt-2">Start</a>
      </article>
      <article class="surface p-4">
        <h2>Sleep Preparation</h2>
        <p class="text-muted">12 min • Guided</p>
        <a href="#" class="btn btn-secondary mt-2">Start</a>
      </article>
    </div>
  </section>
</div>
@endsection