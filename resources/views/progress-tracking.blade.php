@extends('layouts.app')

@section('title', 'Progress Tracking')
@section('page', 'progress')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Progress Tracking</h1>
    <p>Monitor your wellness trends and achievements over time.</p>
  </div>
</div>

{{-- Weekly Stats --}}
<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Days Logged</p>
    <p class="stat-card__value">{{ $weekMoods->count() }}/7</p>
    <span class="stat-card__change stat-card__change--up">{{ round(($weekMoods->count() / 7) * 100) }}% this week</span>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Avg Score</p>
    <p class="stat-card__value">{{ round($weekMoods->avg('mood_scale') ?? 0) }}/10</p>
    <span class="stat-card__change stat-card__change--up">Weekly average</span>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Consistency</p>
    <p class="stat-card__value">{{ round(($weekMoods->count() / 7) * 100) }}%</p>
    <div class="progress" style="margin-top: 0.75rem;">
      <div class="progress__bar progress__bar--success" style="width: {{ ($weekMoods->count() / 7) * 100 }}%"></div>
    </div>
  </div>
</div>

<div class="widget-grid">
  <div>
    {{-- Monthly Progress --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Monthly Progress</h4>
        <span class="badge badge--secondary">{{ $monthMoods->count() }} entries</span>
      </div>
      <div class="card-body">
        @php $moodCounts = $monthMoods->groupBy('mood')->map->count(); @endphp
        <div style="display: grid; gap: 0.75rem;">
          @foreach(['excellent'=>'😄 Excellent','happy'=>'😊 Happy','calm'=>'😌 Calm','anxious'=>'😟 Anxious','sad'=>'😢 Sad','stressed'=>'😰 Stressed'] as $mood => $label)
            @php
              $count = $moodCounts->get($mood, 0);
              $percentage = $monthMoods->count() > 0 ? ($count / $monthMoods->count()) * 100 : 0;
            @endphp
            @if($count > 0)
              <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.375rem;">
                  <span style="font-size: 0.85rem; font-weight: 600;">{{ $label }}</span>
                  <span style="font-size: 0.85rem; font-weight: 700;">{{ $count }} ({{ round($percentage) }}%)</span>
                </div>
                <div class="progress">
                  <div class="progress__bar" style="width: {{ $percentage }}%;"></div>
                </div>
              </div>
            @endif
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <div>
    {{-- Recent Entries --}}
    @if($monthMoods->count() > 0)
      <div class="card">
        <div class="card-header">
          <h4 class="card-header__title">Recent Entries</h4>
        </div>
        <div class="card-body">
          <div class="activity-list">
            @foreach($monthMoods->sortByDesc('mood_date')->take(10) as $mood)
              <div class="activity-item">
                <div style="width: 36px; height: 36px; border-radius: var(--radius-md); background: var(--color-primary-soft); display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;">
                  @php $emojis = ['excellent'=>'😄','happy'=>'😊','calm'=>'😌','anxious'=>'😟','sad'=>'😢','stressed'=>'😰']; @endphp
                  {{ $emojis[$mood->mood] ?? '😐' }}
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ ucfirst($mood->mood) }} — {{ $mood->mood_scale }}/10</p>
                  <p class="activity-item__time">{{ $mood->mood_date->format('M d, Y') }}</p>
                  @if($mood->note)
                    <p style="font-size: 0.8rem; color: var(--color-text-muted); margin: 0.25rem 0 0;">{{ $mood->note }}</p>
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
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          </div>
          <p class="empty-state__title">No Data Yet</p>
          <p class="empty-state__desc">Start logging your mood to track progress.</p>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
