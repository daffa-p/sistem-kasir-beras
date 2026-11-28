<?php

namespace App\Enums;

enum AccountStatus: string
{
    case Aktif = 'aktif';
    case Nonaktif = 'nonaktif';

    /**
     * Label tampilan untuk status akun.
     */
    public function label(): string
    {
        return match ($this) {
            self::Aktif => 'Aktif',
            self::Nonaktif => 'Nonaktif',
        };
    }

    /**
     * Mengembalikan status kebalikan untuk fitur ubah status cepat.
     */
    public function toggled(): self
    {
        return $this === self::Aktif ? self::Nonaktif : self::Aktif;
    }

    /**
     * Daftar nilai string status akun yang valid.
     *
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $status): string => $status->value, self::cases());
    }

    /**
     * Peta nilai => label untuk pilihan form.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }

    /**
     * Normalisasi string status akun ke enum AccountStatus.
     */
    public static function fromNormalized(?string $status): ?self
    {
        if ($status === null) {
            return null;
        }

        return self::tryFrom(strtolower(trim($status)));
    }
}
