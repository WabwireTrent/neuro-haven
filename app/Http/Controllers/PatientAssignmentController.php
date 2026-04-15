<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TherapistPatientAssignment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PatientAssignmentController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display the patient assignment management page
     */
    public function index()
    {
        $therapistId = auth()->id();

        // Get assigned patients
        $assignedPatients = User::where('role', 'patient')
            ->whereHas('assignedTherapists', function ($query) use ($therapistId) {
                $query->where('therapist_id', $therapistId);
            })
            ->with(['assignedTherapists' => function ($query) use ($therapistId) {
                $query->where('therapist_id', $therapistId);
            }])
            ->paginate(15);

        // Get unassigned patients (available to assign)
        $availablePatients = User::where('role', 'patient')
            ->whereDoesntHave('assignedTherapists', function ($query) use ($therapistId) {
                $query->where('therapist_id', $therapistId);
            })
            ->get();

        return view('therapist.assignments', compact('assignedPatients', 'availablePatients'));
    }

    /**
     * Assign a patient to the current therapist
     */
    public function assign(Request $request): RedirectResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
        ]);

        $therapistId = auth()->id();
        $patientId = $request->input('patient_id');

        // Verify patient exists and is a patient
        $patient = User::findOrFail($patientId);
        if ($patient->role !== 'patient') {
            return back()->with('error', 'User is not a patient.');
        }

        // Check if already assigned
        $existing = TherapistPatientAssignment::where('therapist_id', $therapistId)
            ->where('patient_id', $patientId)
            ->first();

        if ($existing) {
            return back()->with('error', 'Patient is already assigned to you.');
        }

        // Create assignment
        TherapistPatientAssignment::create([
            'therapist_id' => $therapistId,
            'patient_id' => $patientId,
            'status' => 'active'
        ]);

        $therapist = User::findOrFail($therapistId);

        // Send notifications
        $this->notificationService->notifyPatientAssignment($therapist, $patient, 'assigned');
        $this->notificationService->notifyTherapistAssignment($patient, $therapist, 'assigned');

        return back()->with('success', "{$patient->name} has been assigned to you.");
    }

    /**
     * Remove a patient from the current therapist
     */
    public function remove(User $patient): RedirectResponse
    {
        $therapistId = auth()->id();

        $assignment = TherapistPatientAssignment::where('therapist_id', $therapistId)
            ->where('patient_id', $patient->id)
            ->firstOrFail();

        $assignment->update(['status' => 'inactive']);

        $therapist = User::findOrFail($therapistId);

        // Send notifications
        $this->notificationService->notifyPatientAssignment($therapist, $patient, 'removed');
        $this->notificationService->notifyTherapistAssignment($patient, $therapist, 'removed');

        return back()->with('success', "{$patient->name} has been removed from your patient list.");
    }

    /**
     * Update assignment notes
     */
    public function updateNotes(Request $request, User $patient): RedirectResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        $therapistId = auth()->id();

        TherapistPatientAssignment::where('therapist_id', $therapistId)
            ->where('patient_id', $patient->id)
            ->update(['notes' => $request->input('notes')]);

        return back()->with('success', 'Notes updated successfully.');
    }
}
