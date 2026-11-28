<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Kasir = 'kasir';
    case Pemilik = 'pemilik';

    /**
     * Label tampilan untuk setiap role pengguna.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Kasir => 'Kasir',
            self::Pemilik => 'Pemilik Toko',
        };
    }

    /**
     * Daftar nilai string role yang diizinkan oleh aturan bisnis.
     *
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $role): string => $role->value, self::cases());
    }

    /**
     * Peta nilai => label untuk pilihan form.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $role) {
            $options[$role->value] = $role->label();
        }

        return $options;
    }

    /**
     * Normalisasi string role (termasuk variasi penamaan pemilik) ke enum UserRole.
     */
    public static function fromNormalized(?string $role): ?self
    {
        if ($role === null) {
            return null;
        }

        return match (strtolower(trim($role))) {
            'admin' => self::Admin,
            'kasir' => self::Kasir,
            'pemilik', 'pemilik toko', 'pemilik_toko' => self::Pemilik,
            default => null,
        };
    }
}
