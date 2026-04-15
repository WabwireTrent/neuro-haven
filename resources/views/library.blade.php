@extends('layouts.public')

@section('title', 'Environment Library')
@section('page', 'library')
@section('main-class', 'library-shell')

@section('content')
<div class="library-app">
  <header class="library-header">
    <button class="library-header__btn" type="button" aria-label="Back" onclick="window.location.href='{{ route('dashboard') }}'">
      <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
    </button>
    <h1 class="library-header__title">Neuro Haven</h1>
    <div class="notif-anchor">
      <button class="library-header__btn" type="button" aria-label="Notifications" aria-expanded="false" data-notif-toggle>
        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <span class="notif-badge" data-notif-badge aria-hidden="true"></span>
      </button>
      <div class="notif-panel" data-notif-panel aria-label="Notifications" hidden>
        <div class="notif-panel__head">
          <span class="notif-panel__title">Notifications</span>
          <button class="notif-panel__clear" type="button" data-notif-clear>Mark all read</button>
        </div>
        <ul class="notif-list" data-notif-list></ul>
      </div>
    </div>
  </header>

  <section class="library-main">
    <section class="library-hero">
      <h1>Environment Library</h1>
      <p data-library-count>0 calming spaces available</p>
    </section>

    <section class="card dashboard-widget">
      <div class="library-search-wrap">
        <input class="input" id="library-search" type="search" placeholder="Search environments..." data-library-search>
      </div>

      <div class="library-filter-grid">
        <div class="library-filter">
          <label for="library-mood">Mood Type</label>
          <select class="select" id="library-mood" data-library-mood>
            <option value="">Any Mood</option>
            <option value="calm">Calm</option>
            <option value="focus">Focus</option>
            <option value="energy">Energy</option>
          </select>
        </div>

        <div class="library-filter">
          <label for="library-duration">Duration</label>
          <select class="select" id="library-duration" data-library-duration>
            <option value="">Any Time</option>
            <option value="short">5-10 min</option>
            <option value="medium">15-30 min</option>
            <option value="long">1 hour+</option>
          </select>
        </div>
      </div>
    </section>

    <section class="library-pills" data-library-tags aria-label="Filter by concern"></section>

    <section class="library-grid" data-library-grid aria-live="polite"></section>

    <section class="library-empty" data-library-empty hidden>
      <h2>No matches found</h2>
      <p>Try a different mood, duration, or concern tag.</p>
    </section>
  </section>

  <nav class="library-bottom-nav" aria-label="Library sections">
    <a href="{{ route('dashboard') }}">Home</a>
    <a class="is-active" href="{{ route('library') }}">Library</a>
    <a href="#insights">Insights</a>
    <a href="#session">Session</a>
  </nav>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/library.js') }}"></script>
@endsection