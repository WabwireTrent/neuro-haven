@extends('layouts.app')

@section('title', 'Mood Tracker')
@section('page', 'mood')

@section('content')
<div class="container">
  <section class="stack p-4">
    <div class="surface p-6">
      <h1>Mood Tracker</h1>
      <p class="text-muted">Log your mood each day and track your emotional rhythm.</p>
    </div>

    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <div class="surface p-6">
      <h2>Today's Check-in</h2>
      @if ($todayMood)
        <p class="text-muted">You already logged today:</p>
        <p style="font-size: 1.25rem; font-weight: 600; margin-top: 0.5rem;">
          {{ ucfirst($todayMood->mood) }} - {{ $todayMood->mood_scale }}/10
        </p>
      @else
        <form method="POST" action="{{ route('mood.store') }}" class="stack">
          @csrf
          <div class="mb-4">
            <label for="mood" class="block font-weight-6">How do you feel today?</label>
            <div class="cluster" style="gap: 0.75rem; margin-top: 1rem; flex-wrap: wrap;">
              @foreach (['excellent' => '😄 Excellent', 'happy' => '😊 Happy', 'calm' => '😌 Calm', 'anxious' => '😟 Anxious', 'sad' => '😢 Sad', 'stressed' => '😰 Stressed'] as $value => $label)
                <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem; border: 2px solid var(--color-border); border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s;">
                  <input type="radio" name="mood" value="{{ $value }}" required style="cursor: pointer;">
                  <span>{{ $label }}</span>
                </label>
              @endforeach
            </div>
            @error('mood')
              <p class="error-message">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="mood_scale" class="block">Intensity (1-10)</label>
            <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.5rem;">
              <input type="range" id="mood_scale" name="mood_scale" min="1" max="10" value="5" style="flex: 1;">
              <span id="scale-display" style="font-weight: 600; min-width: 2rem;">5</span>
            </div>
            @error('mood_scale')
              <p class="error-message">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="note" class="block">Notes (optional)</label>
            <textarea id="note" name="note" rows="4" class="w-full p-3 border rounded" placeholder="What's on your mind? What triggered this mood?"></textarea>
          </div>
          <button class="btn btn-primary" type="submit">Save Mood</button>
        </form>
      @endif
    </div>

    @if ($recentMoods->count() > 0)
      <div class="surface p-6">
        <h2>Recent Entries</h2>
        <div class="stack" style="gap: 0.75rem; margin-top: 1rem;">
          @foreach ($recentMoods as $mood)
            <div class="surface p-4" style="border-left: 4px solid var(--color-primary);">
              <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                  <p style="font-weight: 600; margin: 0;">{{ ucfirst($mood->mood) }}</p>
                  <p class="text-muted" style="font-size: 0.875rem;">{{ $mood->mood_date->format('M d, Y') }} • {{ $mood->mood_scale }}/10</p>
                  @if ($mood->note)
                    <p style="margin-top: 0.5rem; color: var(--color-text-muted);">{{ $mood->note }}</p>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </section>
</div>

<script>
  document.getElementById('mood_scale')?.addEventListener('input', function() {
    document.getElementById('scale-display').textContent = this.value;
  });
</script>

<style>
  .alert {
    padding: var(--space-3);
    margin-bottom: var(--space-4);
    border-radius: var(--radius-md);
  }
  .alert-success {
    background: rgba(29, 159, 118, 0.1);
    color: var(--color-primary-strong);
    border: 1px solid var(--color-primary);
  }
  .w-full { width: 100%; }
  .p-3 { padding: var(--space-3); }
  .border { border: 1px solid var(--color-border); }
  .rounded { border-radius: var(--radius-md); }
</style>
@endsection