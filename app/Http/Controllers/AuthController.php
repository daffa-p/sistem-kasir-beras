<?php
namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ]);

        // Cari pengguna berdasarkan username
        $pengguna = Pengguna::where(
            'username',
            $credentials['username']
        )->first();

        // Verifikasi status dan password
        if (
            !$pengguna ||
            strtolower($pengguna->status_akun) !== 'aktif' ||
            !Hash::check(
                $credentials['password'],
                $pengguna->password
            )
        ) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah, atau akun tidak aktif.',
            ]);
        }

        // Tentukan halaman berdasarkan role
        $role = strtolower(trim($pengguna->role));

        $dashboard = match ($role) {
            'admin' => '/admin/dashboard',
            'kasir' => '/kasir/dashboard',
            'pemilik', 'pemilik toko', 'pemilik_toko' => '/pemilik/dashboard',
            default => null,
        };

        if (!$dashboard) {
            throw ValidationException::withMessages([
                'username' => 'Role akun belum dikonfigurasi.',
            ]);
        }

        // Login menggunakan session Laravel
        Auth::guard('web')->login(
            $pengguna,
            $request->boolean('remember')
        );

        // Regenerasi session untuk keamanan
        $request->session()->regenerate();

        // Arahkan sesuai role
        return redirect()->intended($dashboard);
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
