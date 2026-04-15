@extends('layouts.app')

@section('title', 'Patient Assignments')
@section('page', 'therapist-assignments')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <h1>Patient Assignments</h1>
                <p class="dashboard-streak">Manage your patient roster</p>
            </header>

            <section class="dashboard-main">
                @if($message = session('success'))
                    <div class="card" style="background-color: #dcfce7; border-left: 4px solid #16a34a; padding: 1rem; margin-bottom: 2rem;">
                        <p style="margin: 0; color: #166534;">{{ $message }}</p>
                    </div>
                @endif

                @if($message = session('error'))
                    <div class="card" style="background-color: #fee2e2; border-left: 4px solid #dc2626; padding: 1rem; margin-bottom: 2rem;">
                        <p style="margin: 0; color: #991b1b;">{{ $message }}</p>
                    </div>
                @endif

                <!-- Assign New Patient Section -->
                <section class="card" style="padding: 2rem; margin-bottom: 2rem;">
                    <h2 style="margin-top: 0;">Assign New Patient</h2>
                    
                    @if($availablePatients->count() > 0)
                        <form action="{{ route('therapist.assignments.assign') }}" method="POST" style="display: flex; gap: 1rem; align-items: flex-end;">
                            @csrf
                            <div style="flex: 1;">
                                <label for="patient_id" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Select Patient</label>
                                <select name="patient_id" id="patient_id" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;">
                                    <option value="">Choose a patient...</option>
                                    @foreach($availablePatients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Assign Patient</button>
                        </form>
                    @else
                        <p class="text-muted">All available patients are already assigned to you.</p>
                    @endif
                </section>

                <!-- Current Assignments Section -->
                <section class="card" style="padding: 2rem;">
                    <h2 style="margin-top: 0;">Your Patients ({{ $assignedPatients->total() }})</h2>
                    
                    @if($assignedPatients->count() > 0)
                        <div class="patients-list" style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($assignedPatients as $patient)
                                <article class="surface p-4" style="display: flex; justify-content: space-between; align-items: center;">
                                    <div style="flex: 1;">
                                        <h3 style="margin: 0 0 0.5rem; font-size: 1.125rem;">{{ $patient->name }}</h3>
                                        <p style="margin: 0 0 0.25rem; color: var(--text-secondary); font-size: 0.875rem;">{{ $patient->email }}</p>
                                        <div style="display: flex; gap: 2rem; margin-top: 0.75rem; font-size: 0.875rem; color: var(--text-secondary);">
                                            <span>📊 {{ $patient->moods_count }} mood entries</span>
                                            <span>🎮 {{ $patient->vr_sessions_count }} VR sessions</span>
                                        </div>
                                        
                                        @if($patient->getRelationValue('assignedTherapists')[0]->pivot->notes ?? false)
                                            <p style="margin-top: 0.75rem; padding: 0.75rem; background-color: rgba(59, 130, 246, 0.05); border-radius: 0.25rem; font-size: 0.875rem;">
                                                📝 <strong>Notes:</strong> {{ $patient->getRelationValue('assignedTherapists')[0]->pivot->notes }}
                                            </p>
                                        @endif
                                    </div>

                                    <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-left: 1rem;">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="showNoteModal('{{ $patient->id }}', '{{ $patient->name }}')">
                                            Add Notes
                                        </button>
                                        <form action="{{ route('therapist.assignments.remove', $patient) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-secondary" style="width: 100%;" onclick="return confirm('Remove {{ $patient->name }} from your patient list?')">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div style="margin-top: 2rem;">
                            {{ $assignedPatients->links() }}
                        </div>
                    @else
                        <div style="padding: 2rem; text-align: center;">
                            <p class="text-muted">No patients assigned yet. Start by assigning a patient above.</p>
                        </div>
                    @endif
                </section>
            </section>
        </div>
    </div>
</div>

<!-- Notes Modal -->
<div id="noteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 100; justify-content: center; align-items: center;">
    <div class="card" style="padding: 2rem; max-width: 500px; width: 90%; border-radius: 0.75rem;">
        <h2 id="modalTitle" style="margin-top: 0;">Add Notes for Patient</h2>
        
        <form id="noteForm" method="POST" style="margin-top: 1.5rem;">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label for="notes" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Notes</label>
                <textarea 
                    name="notes" 
                    id="notes" 
                    rows="5" 
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; font-family: inherit;"
                    placeholder="Add any relevant notes about this patient..."
                ></textarea>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save Notes</button>
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeNoteModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function showNoteModal(patientId, patientName) {
    document.getElementById('modalTitle').textContent = `Add Notes for ${patientName}`;
    document.getElementById('noteForm').action = `/therapist/assignments/${patientId}/notes`;
    document.getElementById('noteForm').style.display = 'block';
    document.getElementById('noteModal').style.display = 'flex';
}

function closeNoteModal() {
    document.getElementById('noteModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('noteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNoteModal();
    }
});
</script>

<style>
    .surface {
        background-color: var(--background-secondary);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        padding: 1.5rem !important;
    }

    .surface p {
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        article.surface {
            flex-direction: column;
        }

        article.surface > div:last-child {
            margin-left: 0;
            margin-top: 1rem;
            width: 100%;
        }

        article.surface > div:last-child button {
            width: 100%;
        }
    }
</style>
@endsection
