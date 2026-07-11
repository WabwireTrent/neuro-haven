@extends('layouts.app')

@section('title', 'Assessment History')
@section('page', 'assessments-history')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Assessment History</h1>
    <p>Complete record of all your assessments</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('assessments.index') }}" class="btn btn-ghost btn-sm">Back</a>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Type</th>
          <th>Score</th>
          <th>Severity</th>
          <th>Completed</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($results as $result)
          <tr>
            <td><strong>{{ strtoupper($result->assessment_type) }}</strong></td>
            <td>{{ $result->score }}/{{ $result->assessment_type === 'phq-9' ? 27 : 21 }}</td>
            <td><span class="badge badge--{{ $result->severity === 'none' ? 'success' : ($result->severity === 'mild' ? 'warning' : 'danger') }}">{{ ucfirst($result->severity) }}</span></td>
            <td class="text-sm text-muted">{{ $result->completed_at->format('M j, Y g:i A') }}</td>
            <td class="actions-cell" style="display:flex;gap:0.35rem;">
              <a href="{{ route('assessments.report', $result) }}" class="btn btn-ghost btn-sm" title="Report">Report</a>
              <a href="{{ route('assessments.results', $result->assessment_type) }}" class="btn btn-ghost btn-sm">History</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="empty-cell">No assessments completed yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if(method_exists($results, 'hasPages') && $results->hasPages())
    <div style="padding: 1rem; border-top: 1px solid var(--color-border-light); display: flex; justify-content: center;">{{ $results->links() }}</div>
  @endif
</div>
@endsection
