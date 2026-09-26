<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Menampilkan form login untuk Super Admin & Petugas
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            return $user->isSuperAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('admin.events.index');
        }

        return view('auth.admin-login');
    }

    /**
     * Proses autentikasi Pengelola (Admin & Petugas)
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

            AuditLog::record('LOGIN', "Pengguna {$user->name} ({$user->role}) berhasil login ke sistem.", $user);

            if ($user->isSuperAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', "Selamat datang, {$user->name}.");
            }

            return redirect()->intended(route('admin.events.index'))->with('success', "Selamat datang, {$user->name}.");
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout Pengelola
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();
        if ($user) {
            AuditLog::record('LOGOUT', "Pengguna {$user->name} logout dari sistem.", $user);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
    }
}
