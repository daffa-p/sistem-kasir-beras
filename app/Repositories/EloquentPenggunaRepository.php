<?php

namespace App\Repositories;

use App\Contracts\Repositories\PenggunaRepositoryInterface;
use App\Enums\AccountStatus;
use App\Enums\UserRole;
use App\Models\Pengguna;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EloquentPenggunaRepository implements PenggunaRepositoryInterface
{
    /**
     * Kolom aman untuk ditampilkan dan dikirim ke controller/view (tanpa password/remember_token).
     *
     * @var list<string>
     */
    private const SAFE_COLUMNS = [
        'id_pengguna',
        'nama',
        'username',
        'role',
        'status_akun',
        'no_telp',
        'foto_profil',
        'created_at',
        'updated_at',
    ];

    public function paginateWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Pengguna::query()->select(self::SAFE_COLUMNS);

        $search = isset($filters['search']) ? trim((string) $filters['search']) : '';
        if ($search !== '') {
            $query->where(function ($subQuery) use ($search): void {
                $subQuery
                    ->where('nama', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        $role = UserRole::fromNormalized($filters['role'] ?? null);
        if ($role !== null) {
            if ($role === UserRole::Pemilik) {
                $query->whereIn('role', ['pemilik', 'pemilik toko', 'pemilik_toko']);
            } else {
                $query->where('role', $role->value);
            }
        }

        $status = AccountStatus::fromNormalized($filters['status_akun'] ?? null);
        if ($status !== null) {
            $query->where('status_akun', $status->value);
        }

        return $query
            ->orderByDesc('id_pengguna')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getSummaryCounts(): array
    {
        return [
            'total' => Pengguna::query()->count(),
            'admin_aktif' => Pengguna::query()
                ->where('role', UserRole::Admin->value)
                ->where('status_akun', AccountStatus::Aktif->value)
                ->count(),
            'kasir_aktif' => Pengguna::query()
                ->where('role', UserRole::Kasir->value)
                ->where('status_akun', AccountStatus::Aktif->value)
                ->count(),
            'pemilik_aktif' => Pengguna::query()
                ->whereIn('role', ['pemilik', 'pemilik toko', 'pemilik_toko'])
                ->where('status_akun', AccountStatus::Aktif->value)
                ->count(),
            'nonaktif' => Pengguna::query()
                ->where('status_akun', AccountStatus::Nonaktif->value)
                ->count(),
        ];
    }

    public function findById(int $idPengguna): ?Pengguna
    {
        return Pengguna::query()
            ->select(self::SAFE_COLUMNS)
            ->where('id_pengguna', $idPengguna)
            ->first();
    }

    public function create(array $attributes): Pengguna
    {
        $pengguna = Pengguna::query()->create($attributes);

        return $this->findById((int) $pengguna->id_pengguna) ?? $pengguna->makeHidden(['password', 'remember_token']);
    }

    public function update(Pengguna $pengguna, array $attributes): Pengguna
    {
        $pengguna->update($attributes);

        return $this->findById((int) $pengguna->id_pengguna) ?? $pengguna->fresh()->makeHidden(['password', 'remember_token']);
    }

    public function delete(Pengguna $pengguna): bool
    {
        return (bool) $pengguna->delete();
    }

    public function hasTransactionHistory(int $idPengguna): bool
    {
        return DB::table('penjualan')
            ->where('id_pengguna', $idPengguna)
            ->exists();
    }

    public function hasStockHistory(int $idPengguna): bool
    {
        return DB::table('riwayat_stok')
            ->where('id_pengguna', $idPengguna)
            ->exists();
    }
}
