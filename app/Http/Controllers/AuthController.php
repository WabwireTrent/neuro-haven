<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private function redirectBasedOnRole()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->isTherapist()) {
            return redirect()->route('therapist.dashboard');
        }
        return redirect()->route('dashboard');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole()->with('success', 'Welcome back!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Invalid credentials.']);
    }

    public function showRegisterChoice()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('register-choice');
    }

    public function showRegister($type = 'patient')
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        
        if (!in_array($type, ['patient', 'therapist'])) {
            $type = 'patient';
        }

        return view('register', compact('type'));
    }

    public function register(Request $request)
    {
        $type = $request->input('type', 'patient');

        // Base validation for all users
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'type' => 'required|in:patient,therapist',
        ]);

        // Therapist-specific validation
        if ($type === 'therapist') {
            $validated = array_merge($validated, $request->validate([
                'license_number' => 'required|string|max:50|unique:users',
                'specialization' => 'required|string|max:255',
                'years_of_experience' => 'required|integer|min:0|max:70',
                'bio' => 'nullable|string|max:1000',
            ]));
        }

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $type,
        ];

        // Add therapist-specific fields
        if ($type === 'therapist') {
            $userData['license_number'] = $validated['license_number'];
            $userData['specialization'] = $validated['specialization'];
            $userData['years_of_experience'] = $validated['years_of_experience'];
            $userData['bio'] = $validated['bio'] ?? null;
        }

        $user = User::create($userData);

        Auth::login($user);

        $message = $type === 'therapist' 
            ? 'Welcome to Neuro Haven! Your therapist profile is ready.' 
            : 'Account created! Welcome to Neuro Haven.';

        return $this->redirectBasedOnRole()->with('success', $message);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('auth.logout');
    }
}
