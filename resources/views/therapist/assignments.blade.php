@extends('layouts.app')

@section('title', 'Patient Assignments')
@section('page', 'therapist-assignments')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Patient Assignments</h1>
    <p>Manage your patient roster</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('therapist.dashboard') }}" class="btn btn-secondary btn-sm">Dashboard</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert--success slide-up">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert--danger slide-up">{{ session('error') }}</div>
@endif

<div class="card" style="margin-bottom: 1.25rem;">
  <div class="card-header">
    <h4 class="card-header__title">Assign New Patient</h4>
  </div>
  <div class="card-body">
    @if($availablePatients->count() > 0)
      <form action="{{ route('therapist.assignments.assign') }}" method="POST" style="display: flex; gap: 1rem; align-items: flex-end;">
        @csrf
        <div class="form-group" style="flex: 1;">
          <label for="patient_id" class="form-label">Select Patient</label>
          <select name="patient_id" id="patient_id" class="form-select" required>
            <option value="">Choose a patient...</option>
            @foreach($availablePatients as $patient)
              <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->email }})</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary" style="height: 38px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Assign Patient
        </button>
      </form>
    @else
      <p class="text-muted">All available patients are already assigned to you.</p>
    @endif
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h4 class="card-header__title">Your Patients</h4>
    <span class="badge badge--neutral">{{ $assignedPatients->total() }} total</span>
  </div>
  <div class="card-body" style="padding: 0;">
    @if($assignedPatients->count() > 0)
      <div style="display: flex; flex-direction: column;">
        @foreach($assignedPatients as $patient)
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.125rem 1.25rem; border-bottom: 1px solid var(--color-border-light); transition: background var(--transition-fast);"
               onmouseover="this.style.background='var(--color-surface-muted)'"
               onmouseout="this.style.background=''">
            <div style="flex: 1; min-width: 0;">
              <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                <div class="avatar-initials avatar-initials--sm" style="width: 32px; height: 32px; font-size: 0.7rem;">{{ strtoupper(substr($patient->name, 0, 1)) }}</div>
                <h5 style="margin: 0; font-size: 0.9rem;">{{ $patient->name }}</h5>
              </div>
              <p style="margin: 0 0 0.375rem; font-size: 0.8rem; color: var(--color-text-muted);">{{ $patient->email }}</p>
              <div style="display: flex; gap: 1.25rem; font-size: 0.75rem; color: var(--color-text-muted);">
                <span>{{ $patient->moods_count }} mood entries</span>
                <span>{{ $patient->vr_sessions_count }} VR sessions</span>
              </div>
              @if($patient->getRelationValue('assignedTherapists')[0]->pivot->notes ?? false)
                <div style="margin-top: 0.625rem; padding: 0.625rem; background: var(--color-primary-soft); border-radius: var(--radius-md); font-size: 0.8rem; color: var(--color-text-secondary);">
                  <strong>Notes:</strong> {{ $patient->getRelationValue('assignedTherapists')[0]->pivot->notes }}
                </div>
              @endif
            </div>
            <div style="display: flex; gap: 0.375rem; flex-shrink: 0; margin-left: 1rem;">
              <button type="button" class="btn btn-sm btn-ghost" onclick="showNoteModal('{{ $patient->id }}', '{{ $patient->name }}')">Notes</button>
              <form action="{{ route('therapist.assignments.remove', $patient) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Remove {{ $patient->name }} from your patient list?')">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
      @if(method_exists($assignedPatients, 'hasPages') && $assignedPatients->hasPages())
        <div class="card-footer">
          {{ $assignedPatients->links() }}
        </div>
      @endif
    @else
      <div class="empty-state">
        <div class="empty-state__icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <p class="empty-state__title">No Patients Assigned</p>
        <p class="empty-state__desc">Assign your first patient using the form above.</p>
      </div>
    @endif
  </div>
</div>

<div id="noteModal" class="modal-overlay" hidden>
  <div class="modal">
    <div class="modal-header">
      <h3 id="modalTitle">Add Notes for Patient</h3>
      <button class="modal-close" onclick="closeNoteModal()" aria-label="Close">&times;</button>
    </div>
    <form id="noteForm" method="POST">
      @csrf
      <div class="modal-body">
        <div class="form-group">
          <label for="notes" class="form-label">Notes</label>
          <textarea name="notes" id="notes" class="form-textarea" rows="5" placeholder="Add any relevant notes about this patient..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost" onclick="closeNoteModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Notes</button>
      </div>
    </form>
  </div>
</div>

<script>
function showNoteModal(patientId, patientName) {
  document.getElementById('modalTitle').textContent = 'Add Notes for ' + patientName;
  document.getElementById('noteForm').action = '/therapist/assignments/' + patientId + '/notes';
  document.getElementById('noteModal').hidden = false;
  document.body.style.overflow = 'hidden';
}

function closeNoteModal() {
  document.getElementById('noteModal').hidden = true;
  document.body.style.overflow = '';
}

document.getElementById('noteModal').addEventListener('click', function(e) {
  if (e.target === this) closeNoteModal();
});
</script>
@endsection
