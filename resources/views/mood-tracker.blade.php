@extends('layouts.app')

@section('title', 'Mood Tracker')
@section('page', 'mood')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Mood Tracker</h1>
    <p>Log your mood each day and track your emotional rhythm.</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert--success slide-up">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span>{{ session('success') }}</span>
  </div>
@endif

@if(session('suggested_session'))
  <div class="card" style="margin-bottom: 1.25rem; border: 2px solid var(--color-primary);">
    <div class="card-header">
      <h4 class="card-header__title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 0.375rem;"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        Recommended for You
      </h4>
    </div>
    <div class="card-body">
      <div style="display: flex; align-items: flex-start; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: var(--radius-lg); background: var(--color-primary-soft); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.5rem;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2"/></svg>
        </div>
        <div style="flex: 1;">
          <p style="font-weight: 700; margin: 0 0 0.125rem; font-size: 1rem;">{{ session('suggested_session')['title'] }}</p>
          <p style="font-size: 0.85rem; color: var(--color-text-muted); margin: 0 0 0.375rem;">{{ session('suggested_session')['category'] }} &middot; {{ session('suggested_session')['duration_minutes'] }} min</p>
          <p style="font-size: 0.85rem; margin: 0 0 0.75rem; font-style: italic;">{{ session('suggestion_reason') }}</p>
          <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('vr.assets.show', session('suggested_session')['id']) }}" class="btn btn-primary btn-sm" style="font-size: 0.8rem; padding: 0.5rem 1rem;">
              Start This Session
            </a>
            <a href="{{ route('patient.treatment-plans') }}" class="btn btn-ghost btn-sm" style="font-size: 0.8rem; padding: 0.5rem 1rem;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 0.25rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              View in Care Plans
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif

<div class="widget-grid">
  <div>
    {{-- Today's Check-in --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Today's Check-in</h4>
        @if($todayMood)
          <span class="badge badge--success">Logged</span>
        @endif
      </div>
      <div class="card-body">
        @if($todayMood)
          <p class="text-muted" style="margin-bottom: 0.75rem;">You already logged today:</p>
          <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--color-primary-soft); border-radius: var(--radius-lg);">
            <span style="font-size: 2rem; line-height: 1;">
              @php $emojis = ['excellent'=>'😄','happy'=>'😊','calm'=>'😌','anxious'=>'😟','sad'=>'😢','stressed'=>'😰']; @endphp
              {{ $emojis[$todayMood->mood] ?? '😐' }}
            </span>
            <div style="flex: 1;">
              <p style="font-weight: 700; margin: 0; font-size: 1.1rem;">{{ ucfirst($todayMood->mood) }}</p>
              <p class="text-muted" style="font-size: 0.85rem; margin: 0.125rem 0 0;">{{ $todayMood->mood_scale }}/10 intensity</p>
              @if($todayMood->note)
                <p style="margin: 0.5rem 0 0; font-size: 0.85rem; color: var(--color-text); border-left: 3px solid var(--color-primary); padding-left: 0.75rem;">{{ $todayMood->note }}</p>
              @endif
            </div>
          </div>
        @else
          <form method="POST" action="{{ route('mood.store') }}" style="display: grid; gap: 1.25rem;">
            @csrf
            <div>
              <label style="font-weight: 600; font-size: 0.9rem; display: block; margin-bottom: 0.75rem;">How do you feel today?</label>
              <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
                @foreach(['excellent'=>'😄 Excellent','happy'=>'😊 Happy','calm'=>'😌 Calm','anxious'=>'😟 Anxious','sad'=>'😢 Sad','stressed'=>'😰 Stressed'] as $value => $label)
                  <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem; border: 2px solid var(--color-border); border-radius: var(--radius-lg); cursor: pointer; transition: all var(--transition-fast); font-size: 0.85rem; font-weight: 600;">
                    <input type="radio" name="mood" value="{{ $value }}" required style="cursor: pointer;">
                    <span>{{ $label }}</span>
                  </label>
                @endforeach
              </div>
              @error('mood')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div>
              <label style="font-weight: 600; font-size: 0.9rem; display: block; margin-bottom: 0.75rem;">Intensity (1-10)</label>
              <div style="display: flex; align-items: center; gap: 1rem;">
                <input type="range" id="mood_scale" name="mood_scale" min="1" max="10" value="5" style="flex: 1; accent-color: var(--color-primary);">
                <span id="scale-display" style="font-weight: 700; min-width: 2rem; font-size: 1.1rem;">5</span>
              </div>
              @error('mood_scale')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
              <label class="form-label" for="note">
                Describe Your Mood
                <span style="font-weight: 400; color: var(--color-text-muted); font-size: 0.8rem;">(optional — shared with your therapist)</span>
              </label>
              <textarea id="note" name="note" class="form-textarea" rows="4" placeholder="What's on your mind? What triggered this mood? How are you really feeling? Your therapist will be able to read this to better understand your journey."></textarea>
            </div>
            <button class="btn btn-primary" type="submit" style="width: fit-content;">Save Mood</button>
          </form>
        @endif
      </div>
    </div>
  </div>

  <div>
    {{-- Recent Entries --}}
    @if($recentMoods->count() > 0)
      <div class="card">
        <div class="card-header">
          <h4 class="card-header__title">Recent Entries</h4>
          <span class="badge badge--primary">{{ $recentMoods->count() }} total</span>
        </div>
        <div class="card-body">
          <div class="activity-list">
            @foreach($recentMoods as $mood)
              <div class="activity-item">
                <div style="width: 40px; height: 40px; border-radius: var(--radius-lg); background: var(--color-primary-soft); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                  @php $emojis = ['excellent'=>'😄','happy'=>'😊','calm'=>'😌','anxious'=>'😟','sad'=>'😢','stressed'=>'😰']; @endphp
                  {{ $emojis[$mood->mood] ?? '😐' }}
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ ucfirst($mood->mood) }} — {{ $mood->mood_scale }}/10</p>
                  <p class="activity-item__time">{{ $mood->mood_date->format('M d, Y') }}</p>
                  @if($mood->note)
                    <p style="margin: 0.375rem 0 0; font-size: 0.8rem; color: var(--color-text-muted);">{{ $mood->note }}</p>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @else
      <div class="card">
        <div class="empty-state">
          <div class="empty-state__icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/></svg>
          </div>
          <p class="empty-state__title">No Mood Entries Yet</p>
          <p class="empty-state__desc">Log your first mood to start tracking your emotional journey.</p>
        </div>
      </div>
    @endif
  </div>
</div>

<script>
document.getElementById('mood_scale')?.addEventListener('input', function() {
  document.getElementById('scale-display').textContent = this.value;
});
</script>
@endsection
