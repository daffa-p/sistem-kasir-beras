<?php

namespace App\Http\Requests\Pengguna;

use App\Enums\AccountStatus;
use App\Enums\UserRole;
use App\Models\Pengguna;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePenggunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Pengguna::class) ?? false;
    }

    /**
     * Aturan validasi bersama untuk pembuatan dan pembaruan pengguna.
     *
     * @return array<string, array<int, mixed>>
     */
    public static function commonRules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'role' => ['required', 'string', Rule::in(UserRole::values())],
            'status_akun' => ['required', 'string', Rule::in(AccountStatus::values())],
            'no_telp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];
    }

    /**
     * Pesan validasi bersama dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public static function commonMessages(): array
    {
        return [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'nama.max' => 'Nama pengguna maksimal 100 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, titik, garis bawah, atau tanda hubung.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role pengguna hanya boleh admin, kasir, atau pemilik.',
            'status_akun.required' => 'Status akun wajib dipilih.',
            'status_akun.in' => 'Status akun hanya boleh aktif atau nonaktif.',
            'no_telp.max' => 'Nomor telepon maksimal 20 karakter.',
            'no_telp.regex' => 'Format nomor telepon tidak valid.',
            'foto_profil.image' => 'File foto profil harus berupa gambar.',
            'foto_profil.mimes' => 'Foto profil harus berformat JPEG, JPG, PNG, atau WebP.',
            'foto_profil.max' => 'Ukuran foto profil maksimal 2 MB.',
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return array_merge(self::commonRules(), [
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9_.-]+$/',
                Rule::unique('pengguna', 'username'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return self::commonMessages();
    }
}
