<?php

namespace App\Policies;

use App\Enums\AccountStatus;
use App\Enums\UserRole;
use App\Models\Pengguna;

class PenggunaPolicy
{
    /**
     * Hanya Admin berstatus aktif yang berhak mengakses manajemen data pengguna.
     */
    private function isActiveAdmin(Pengguna $actor): bool
    {
        return AccountStatus::fromNormalized($actor->status_akun) === AccountStatus::Aktif
            && UserRole::fromNormalized($actor->role) === UserRole::Admin;
    }

    public function viewAny(Pengguna $actor): bool
    {
        return $this->isActiveAdmin($actor);
    }

    public function view(Pengguna $actor, Pengguna $target): bool
    {
        return $this->isActiveAdmin($actor);
    }

    public function create(Pengguna $actor): bool
    {
        return $this->isActiveAdmin($actor);
    }

    public function update(Pengguna $actor, Pengguna $target): bool
    {
        return $this->isActiveAdmin($actor);
    }

    public function toggleStatus(Pengguna $actor, Pengguna $target): bool
    {
        return $this->isActiveAdmin($actor);
    }

    public function delete(Pengguna $actor, Pengguna $target): bool
    {
        return $this->isActiveAdmin($actor);
    }
}
