<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $qrCodeService;

    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login-modern');
    }

    /**
     * Process login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if member is active
            if ($user->isMember() && $user->member) {
                if ($user->member->status !== 'active') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Your member account is pending approval or suspended.',
                    ]);
                }
            }

            // Redirect based on role
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('auth.register-modern');
    }

    /**
     * Process registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'birthdate' => 'nullable|date',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'member',
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_active' => true,
        ]);

        // Create member profile
        $member = Member::create([
            'user_id' => $user->id,
            'member_code' => Member::generateMemberCode(),
            'join_date' => now(),
            'birthdate' => $validated['birthdate'] ?? null,
            'status' => 'pending', // Needs admin approval
            'points' => 0,
        ]);

        // Generate QR Code for member
        $qrPath = $this->qrCodeService->generateMemberQR($member);
        $member->update(['qr_code' => $qrPath]);

        return redirect()->route('login')->with('success', 'Registration successful! Please wait for admin approval.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect based on user role
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isKasir()) {
            return redirect()->route('kasir.dashboard');
        } else {
            return redirect()->route('member.dashboard');
        }
    }
}
