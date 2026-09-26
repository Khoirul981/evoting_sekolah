<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VoterAuthController extends Controller
{
    /**
     * Tampilkan halaman login Pemilih (Siswa & Guru)
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('voter')->check()) {
            return redirect()->route('voter.join');
        }

        return view('auth.voter-login');
    }

    /**
     * Autentikasi Pemilih menggunakan NIS / NIP
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:50'],
        ], [
            'identifier.required' => 'Nomor NIS / NIP wajib diisi.',
        ]);

        $identifier = trim($validated['identifier']);
        $voter = Voter::where('identifier', $identifier)->first();

        if (!$voter) {
            return back()->withErrors([
                'identifier' => 'Nomor NIS / NIP tidak terdaftar dalam Master Data Pemilih (DPT).',
            ])->onlyInput('identifier');
        }

        if (!$voter->is_active) {
            return back()->withErrors([
                'identifier' => 'Status hak akses pemilih Anda sedang dinonaktifkan. Silakan hubungi panitia / admin.',
            ])->onlyInput('identifier');
        }

        Auth::guard('voter')->login($voter);
        $request->session()->regenerate();

        return redirect()->route('voter.join')->with('success', "Identitas terverifikasi: {$voter->name} ({$voter->type}).");
    }

    /**
     * Logout Pemilih
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('voter')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('voter.login')->with('success', 'Anda telah keluar dari bilik suara digital.');
    }
}
