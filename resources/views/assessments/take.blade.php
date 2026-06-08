@extends('layouts.app')

@section('title', 'Take Assessment')
@section('page', 'assessments-take')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>{{ $type === 'phq-9' ? 'PHQ-9' : 'GAD-7' }} Assessment</h1>
    <p>{{ $type === 'phq-9' ? 'Patient Health Questionnaire' : 'Generalized Anxiety Disorder Assessment' }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('assessments.index') }}" class="btn btn-ghost btn-sm">Back</a>
  </div>
</div>

@if($previous)
  <div class="card" style="margin-bottom: 1.25rem; border-left: 3px solid var(--color-primary);">
    <div class="card-body" style="display: flex; align-items: center; justify-content: space-between;">
      <div>
        <p style="font-weight: 600; margin: 0;">Previous Result</p>
        <p class="text-muted text-sm" style="margin: 0;">Score: {{ $previous->score }}/{{ $type === 'phq-9' ? 27 : 21 }} &middot; Severity: {{ ucfirst($previous->severity) }} &middot; {{ $previous->completed_at->format('M j, Y') }}</p>
      </div>
      <span class="badge badge--{{ $previous->severity === 'none' ? 'success' : ($previous->severity === 'mild' ? 'warning' : 'danger') }}">{{ ucfirst($previous->severity) }}</span>
    </div>
  </div>
@endif

<div class="card">
  <div class="card-header">
    <h4 class="card-header__title">{{ $type === 'phq-9' ? 'Over the last 2 weeks, how often have you been bothered by...' : 'Over the last 2 weeks, how often have you been bothered by...' }}</h4>
    <span class="badge badge--secondary">{{ count($questions) }} questions</span>
  </div>
  <div class="card-body">
    <p class="text-muted" style="font-size: 0.8rem; margin-bottom: 1.25rem; padding: 0.75rem; background: var(--color-surface-muted); border-radius: var(--radius-lg);">
      0 = Not at all &nbsp;&middot;&nbsp; 1 = Several days &nbsp;&middot;&nbsp; 2 = More than half the days &nbsp;&middot;&nbsp; 3 = Nearly every day
    </p>

    <form method="POST" action="{{ route('assessments.store') }}" id="assessment-form">
      @csrf
      <input type="hidden" name="assessment_type" value="{{ $type }}">

      <div style="display: grid; gap: 1rem;">
        @foreach($questions as $i => $question)
          <div style="padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); transition: all 0.2s ease;" data-q-row="{{ $i }}">
            <p style="margin: 0 0 0.75rem; font-weight: 600; font-size: 0.9rem;">{{ $i + 1 }}. {{ $question }}</p>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem;">
              @foreach([0 => 'Not at all', 1 => 'Several days', 2 => 'More than half', 3 => 'Nearly every day'] as $val => $label)
                <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.625rem; border: 1px solid var(--color-border); border-radius: var(--radius-lg); cursor: pointer; transition: all 0.15s ease; font-size: 0.8rem;" data-q-option>
                  <input type="radio" name="q_{{ $i }}" value="{{ $val }}" required style="width: 14px; height: 14px; accent-color: var(--color-primary); flex-shrink: 0;">
                  <span>{{ $label }}</span>
                </label>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>

      <div style="margin-top: 1.5rem; padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-lg);">
        <p style="font-size: 0.8rem; margin: 0; color: var(--color-text-muted);">
          <strong>Disclaimer:</strong> This assessment is a screening tool and not a diagnostic instrument. Results are shared with your therapist. If you're in crisis, contact your local emergency services or call 988 (Suicide & Crisis Lifeline).
        </p>
      </div>

      <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1.25rem; height: 46px;" id="submit-btn">
        Submit Assessment
      </button>
    </form>
  </div>
</div>

<script>
document.querySelectorAll('[data-q-option]').forEach(function(label) {
  label.addEventListener('click', function() {
    var row = this.closest('[data-q-row]');
    row.querySelectorAll('[data-q-option]').forEach(function(l) {
      l.style.borderColor = 'var(--color-border)';
      l.style.background = 'transparent';
    });
    this.style.borderColor = 'var(--color-primary)';
    this.style.background = 'var(--color-primary-soft)';
  });
});
</script>
@endsection
