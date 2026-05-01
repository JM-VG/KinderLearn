<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerificationEmail;
use App\Models\User;
use App\Models\Section;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:4',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // If email is not yet verified, send a fresh code and redirect to verify
            if (!$user->email_verified_at) {
                try {
                    $this->sendVerificationCode($user);
                } catch (\Throwable $e) {
                    // Mail failure — still redirect to verify page; user can resend manually
                }
                Auth::logout();
                $request->session()->put('verification_user_id', $user->id);
                $request->session()->put('verification_email', $this->maskEmail($user->email));
                return redirect()->route('auth.verify-email')
                    ->with('success', 'Please verify your email before logging in. A new code has been sent.');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The email or password is incorrect. Please try again.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6|confirmed',
            'role'      => 'required|in:teacher,student',
            'join_code' => 'nullable|string',
        ]);

        $sectionId = null;
        if ($request->role === 'student' && $request->join_code) {
            $section = Section::where('join_code', $request->join_code)->first();
            if (!$section) {
                return back()->withErrors(['join_code' => 'That class code is not valid. Please check with your teacher.']);
            }
            $sectionId = $section->id;
        }

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'section_id' => $sectionId,
            'avatar'     => 'avatar1.png',
        ]);

        // Send verification email
        try {
            $this->sendVerificationCode($user);
        } catch (\Exception $e) {
            // Mail failure — user can request resend on the verify page
        }

        // Store user ID in session (not logged in yet)
        $request->session()->put('verification_user_id', $user->id);
        $request->session()->put('verification_email', $this->maskEmail($user->email));

        return redirect()->route('auth.verify-email')
            ->with('success', 'Account created! Check your email for the verification code.');
    }

    public function showVerifyEmail()
    {
        if (!session('verification_user_id')) {
            return redirect()->route('register');
        }

        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $userId = session('verification_user_id');
        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Session expired. Please register again.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'Account not found. Please register again.');
        }

        // Check expiry
        if (!$user->email_verification_expires_at || now()->isAfter($user->email_verification_expires_at)) {
            return back()->with('error', 'This code has expired. Please request a new one.');
        }

        // Check code (case-insensitive)
        if (strtoupper($request->code) !== strtoupper($user->email_verification_code)) {
            return back()->with('error', 'Incorrect code. Please try again.');
        }

        // Mark as verified and clear code
        $user->email_verified_at         = now();
        $user->email_verification_code   = null;
        $user->email_verification_expires_at = null;
        $user->save();

        // Clear verification session, log the user in
        $request->session()->forget(['verification_user_id', 'verification_email']);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Email verified! Welcome to KinderLearn 🎉');
    }

    public function resendVerification(Request $request)
    {
        $userId = session('verification_user_id');
        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Session expired. Please register again.');
        }

        $user = User::find($userId);
        if (!$user || $user->email_verified_at) {
            return redirect()->route('login');
        }

        // Rate-limit: only resend if last code was sent more than 60 seconds ago
        if ($user->email_verification_expires_at &&
            now()->isBefore($user->email_verification_expires_at->subMinutes(14))) {
            return back()->with('error', 'Please wait at least 60 seconds before requesting a new code.');
        }

        try {
            $this->sendVerificationCode($user);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email. Please try again in a moment.');
        }

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ── Helpers ──────────────────────────────────────────

    private function sendVerificationCode(User $user): void
    {
        $code = strtoupper(Str::random(6));

        $user->email_verification_code        = $code;
        $user->email_verification_expires_at  = now()->addMinutes(15);
        $user->save();

        Mail::to($user->email)->send(new VerificationEmail($user->name, $code));
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email);
        $masked = substr($local, 0, 2) . str_repeat('*', max(0, strlen($local) - 2));
        return $masked . '@' . $domain;
    }
}
