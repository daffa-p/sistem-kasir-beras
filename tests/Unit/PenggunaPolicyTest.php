<?php

namespace Tests\Unit;

use App\Models\Pengguna;
use App\Policies\PenggunaPolicy;
use PHPUnit\Framework\TestCase;

class PenggunaPolicyTest extends TestCase
{
    private PenggunaPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new PenggunaPolicy;
    }

    public function test_admin_aktif_diizinkan_mengakses_semua_operasi_pengguna(): void
    {
        $admin = $this->makePengguna(1, 'admin', 'aktif');
        $target = $this->makePengguna(2, 'kasir', 'aktif');

        $this->assertTrue($this->policy->viewAny($admin));
        $this->assertTrue($this->policy->view($admin, $target));
        $this->assertTrue($this->policy->create($admin));
        $this->assertTrue($this->policy->update($admin, $target));
        $this->assertTrue($this->policy->toggleStatus($admin, $target));
        $this->assertTrue($this->policy->delete($admin, $target));
    }

    public function test_admin_nonaktif_ditolak(): void
    {
        $adminNonaktif = $this->makePengguna(1, 'admin', 'nonaktif');
        $target = $this->makePengguna(2, 'kasir', 'aktif');

        $this->assertFalse($this->policy->viewAny($adminNonaktif));
        $this->assertFalse($this->policy->view($adminNonaktif, $target));
        $this->assertFalse($this->policy->create($adminNonaktif));
        $this->assertFalse($this->policy->update($adminNonaktif, $target));
        $this->assertFalse($this->policy->toggleStatus($adminNonaktif, $target));
        $this->assertFalse($this->policy->delete($adminNonaktif, $target));
    }

    public function test_kasir_dan_pemilik_ditolak(): void
    {
        $kasir = $this->makePengguna(2, 'kasir', 'aktif');
        $pemilik = $this->makePengguna(3, 'pemilik', 'aktif');
        $target = $this->makePengguna(4, 'kasir', 'aktif');

        foreach ([$kasir, $pemilik] as $actor) {
            $this->assertFalse($this->policy->viewAny($actor));
            $this->assertFalse($this->policy->view($actor, $target));
            $this->assertFalse($this->policy->create($actor));
            $this->assertFalse($this->policy->update($actor, $target));
            $this->assertFalse($this->policy->toggleStatus($actor, $target));
            $this->assertFalse($this->policy->delete($actor, $target));
        }
    }

    private function makePengguna(int $id, string $role, string $status): Pengguna
    {
        $pengguna = new Pengguna([
            'nama' => 'Tester',
            'username' => 'tester_'.$id,
            'role' => $role,
            'status_akun' => $status,
        ]);
        $pengguna->id_pengguna = $id;

        return $pengguna;
    }
}
