<?php

namespace App\Services;

use App\Contracts\Repositories\PenggunaRepositoryInterface;
use App\Contracts\Services\ImageStorageInterface;
use App\Enums\AccountStatus;
use App\Enums\UserRole;
use App\Models\Pengguna;
use DomainException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Throwable;

class PenggunaService
{
    private const PROFILE_PHOTO_DIRECTORY = 'profil';

    public function __construct(
        private readonly PenggunaRepositoryInterface $penggunaRepository,
        private readonly ImageStorageInterface $imageStorage,
    ) {}

    /**
     * Mengambil daftar pengguna terpaginasi beserta filter pencarian, role, dan status.
     *
     * @param  array{search?: string|null, role?: string|null, status_akun?: string|null}  $filters
     */
    public function getPaginatedList(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->penggunaRepository->paginateWithFilters($filters, $perPage);
    }

    /**
     * Mengambil ringkasan statistik akun pengguna.
     *
     * @return array{total: int, admin_aktif: int, kasir_aktif: int, pemilik_aktif: int, nonaktif: int}
     */
    public function getSummary(): array
    {
        return $this->penggunaRepository->getSummaryCounts();
    }

    /**
     * Mengambil detail pengguna aman tanpa field password.
     */
    public function getSafeDetail(int $idPengguna): ?Pengguna
    {
        return $this->penggunaRepository->findById($idPengguna);
    }

    /**
     * Membuat pengguna baru dengan password ter-hash dan foto profil opsional.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?UploadedFile $fotoProfil = null): Pengguna
    {
        $uploadedPath = $fotoProfil !== null
            ? $this->imageStorage->store($fotoProfil, self::PROFILE_PHOTO_DIRECTORY)
            : null;

        $payload = [
            'nama' => trim((string) $data['nama']),
            'username' => trim((string) $data['username']),
            'password' => Hash::make((string) $data['password']),
            'role' => $this->normalizeRole((string) $data['role']),
            'status_akun' => $this->normalizeStatus($data['status_akun'] ?? AccountStatus::Aktif->value),
            'no_telp' => $this->normalizePhone($data['no_telp'] ?? null),
            'foto_profil' => $uploadedPath,
        ];

        try {
            return $this->penggunaRepository->create($payload);
        } catch (Throwable $exception) {
            $this->imageStorage->delete($uploadedPath);
            throw $exception;
        }
    }

    /**
     * Memperbarui data pengguna dengan proteksi foto lama, password opsional, dan larangan nonaktif diri sendiri.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(
        Pengguna $pengguna,
        array $data,
        ?UploadedFile $fotoProfil = null,
        int|string|null $actorId = null,
    ): Pengguna {
        $targetRole = $this->normalizeRole((string) $data['role']);
        $targetStatus = $this->normalizeStatus((string) $data['status_akun']);

        if ($this->isSelfTarget($pengguna, $actorId)) {
            $currentRole = UserRole::fromNormalized($pengguna->role)?->value;

            if ($currentRole !== null && $targetRole !== $currentRole) {
                throw new DomainException('Admin tidak diperbolehkan mengubah role akunnya sendiri.');
            }

            if ($targetStatus === AccountStatus::Nonaktif->value) {
                throw new DomainException('Admin tidak diperbolehkan menonaktifkan akunnya sendiri.');
            }
        }

        $oldPhotoPath = $pengguna->foto_profil;
        $newPhotoPath = $fotoProfil !== null
            ? $this->imageStorage->store($fotoProfil, self::PROFILE_PHOTO_DIRECTORY)
            : null;

        $payload = [
            'nama' => trim((string) $data['nama']),
            'username' => trim((string) $data['username']),
            'role' => $targetRole,
            'status_akun' => $targetStatus,
            'no_telp' => $this->normalizePhone($data['no_telp'] ?? null),
        ];

        if (isset($data['password']) && trim((string) $data['password']) !== '') {
            $payload['password'] = Hash::make((string) $data['password']);
        }

        if ($newPhotoPath !== null) {
            $payload['foto_profil'] = $newPhotoPath;
        }

        try {
            $updated = $this->penggunaRepository->update($pengguna, $payload);
        } catch (Throwable $exception) {
            $this->imageStorage->delete($newPhotoPath);
            throw $exception;
        }

        if ($newPhotoPath !== null && $oldPhotoPath !== null && $oldPhotoPath !== $newPhotoPath) {
            $this->imageStorage->delete($oldPhotoPath);
        }

        return $updated;
    }

    /**
     * Mengubah status akun pengguna antara aktif dan nonaktif.
     */
    public function toggleStatus(Pengguna $pengguna, int|string|null $actorId = null): Pengguna
    {
        if ($this->isSelfTarget($pengguna, $actorId)) {
            throw new DomainException('Admin tidak diperbolehkan mengubah atau menonaktifkan status akunnya sendiri.');
        }

        $currentStatus = AccountStatus::fromNormalized($pengguna->status_akun) ?? AccountStatus::Aktif;
        $nextStatus = $currentStatus->toggled();

        return $this->penggunaRepository->update($pengguna, [
            'status_akun' => $nextStatus->value,
        ]);
    }

    /**
     * Menghapus pengguna jika tidak dipakai akun sendiri dan tidak memiliki histori transaksi/stok.
     */
    public function delete(Pengguna $pengguna, int|string|null $actorId = null): void
    {
        if ($this->isSelfTarget($pengguna, $actorId)) {
            throw new DomainException('Admin tidak diperbolehkan menghapus akunnya sendiri.');
        }

        if ($this->hasActivityHistory($pengguna)) {
            throw new DomainException(
                'Pengguna sudah memiliki riwayat transaksi atau riwayat stok dan tidak dapat dihapus permanen. Silakan nonaktifkan akun ini.'
            );
        }

        $photoPath = $pengguna->foto_profil;
        $deleted = $this->penggunaRepository->delete($pengguna);

        if ($deleted) {
            $this->imageStorage->delete($photoPath);
        }
    }

    /**
     * Memeriksa apakah pengguna sudah tercatat pada penjualan atau riwayat stok.
     */
    public function hasActivityHistory(Pengguna $pengguna): bool
    {
        $idPengguna = (int) $pengguna->id_pengguna;

        return $this->penggunaRepository->hasTransactionHistory($idPengguna)
            || $this->penggunaRepository->hasStockHistory($idPengguna);
    }

    /**
     * Mengambil URL publik foto profil pengguna jika tersedia.
     */
    public function getPhotoUrl(?string $path): ?string
    {
        return $this->imageStorage->url($path);
    }

    private function isSelfTarget(Pengguna $pengguna, int|string|null $actorId): bool
    {
        return $actorId !== null && (int) $pengguna->id_pengguna === (int) $actorId;
    }

    private function normalizeRole(string $role): string
    {
        return (UserRole::fromNormalized($role) ?? throw new DomainException('Role pengguna tidak valid.'))->value;
    }

    private function normalizeStatus(string $status): string
    {
        return (AccountStatus::fromNormalized($status) ?? throw new DomainException('Status akun tidak valid.'))->value;
    }

    private function normalizePhone(mixed $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $trimmed = trim((string) $phone);

        return $trimmed === '' ? null : $trimmed;
    }
}
