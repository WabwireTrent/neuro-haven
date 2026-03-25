@extends('layouts.app')

@section('title', 'Progress Tracking')
@section('page', 'progress')

@section('content')
<div class="container">
  <section class="stack p-4">
    <div class="surface p-6">
      <h1>Progress Tracking</h1>
      <p class="text-muted">Monitor your wellness trends and achievements over time.</p>
    </div>

    <div class="surface p-6">
      <h2>Weekly Stats</h2>
      <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem; margin-top: 1rem;">
        <article style="text-align: center;">
          <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Days Logged</p>
          <p style="margin: 0.5rem 0 0; font-size: 1.75rem; font-weight: 700; color: var(--color-primary);">{{ $weekMoods->count() }}/7</p>
        </article>
        <article style="text-align: center;">
          <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Avg Score</p>
          <p style="margin: 0.5rem 0 0; font-size: 1.75rem; font-weight: 700; color: var(--color-primary);">{{ round($weekMoods->avg('mood_scale') ?? 0) }}/10</p>
        </article>
        <article style="text-align: center;">
          <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Consistency</p>
          <p style="margin: 0.5rem 0 0; font-size: 1.75rem; font-weight: 700; color: var(--color-primary);">{{ round(($weekMoods->count() / 7) * 100) }}%</p>
        </article>
      </div>
    </div>

    <div class="surface p-6">
      <h2>Monthly Progress</h2>
      <div class="stack" style="gap: 0.75rem; margin-top: 1rem;">
        @php
          $moodCounts = $monthMoods->groupBy('mood')->map->count();
        @endphp
        @foreach (['excellent' => '😄 Excellent', 'happy' => '😊 Happy', 'calm' => '😌 Calm', 'anxious' => '😟 Anxious', 'sad' => '😢 Sad', 'stressed' => '😰 Stressed'] as $mood => $label)
          @php
            $count = $moodCounts->get($mood, 0);
            $percentage = $monthMoods->count() > 0 ? ($count / $monthMoods->count()) * 100 : 0;
          @endphp
          @if ($count > 0)
            <div style="display: flex; align-items: center; gap: 1rem;">
              <span style="min-width: 100px; font-size: 0.875rem;">{{ $label }}</span>
              <div style="flex: 1; height: 24px; background: var(--color-surface-muted); border-radius: var(--radius-md); overflow: hidden;">
                <div style="height: 100%; width: {{ $percentage }}%; background: var(--color-primary); transition: width 0.3s;"></div>
              </div>
              <span style="min-width: 40px; text-align: right; font-weight: 600;">{{ $count }}</span>
            </div>
          @endif
        @endforeach
      </div>
    </div>

    @if ($monthMoods->count() > 0)
      <div class="surface p-6">
        <h2>Recent Entries</h2>
        <div class="stack" style="gap: 0.75rem; margin-top: 1rem;">
          @foreach ($monthMoods->sortByDesc('mood_date')->take(15) as $mood)
            <div class="surface p-4" style="border-left: 4px solid var(--color-primary);">
              <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                  <p style="font-weight: 600; margin: 0;">{{ ucfirst($mood->mood) }}</p>
                  <p class="text-muted" style="font-size: 0.875rem; margin: 0.25rem 0;">{{ $mood->mood_date->format('M d, Y') }} • {{ $mood->mood_scale }}/10</p>
                  @if ($mood->note)
                    <p style="margin: 0.5rem 0 0; color: var(--color-text-muted); font-size: 0.875rem;">{{ $mood->note }}</p>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @else
      <div class="surface p-6">
        <p class="text-muted">Start logging your mood to track progress.</p>
      </div>
    @endif
  </section>
</div>

<style>
  .p-4 { padding: var(--space-4); }
  .p-6 { padding: var(--space-6); }
  .stack {
    display: flex;
    flex-direction: column;
  }
  .grid {
    display: grid;
  }
  .text-muted { color: var(--color-text-muted); }
</style>
@endsection