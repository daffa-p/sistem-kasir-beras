<?php

namespace Tests\Unit;

use App\Models\Pengguna;
use App\Repositories\EloquentPenggunaRepository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EloquentPenggunaRepositoryTest extends TestCase
{
    private EloquentPenggunaRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('pengguna');
        Schema::create('pengguna', function (Blueprint $table): void {
            $table->id('id_pengguna');
            $table->string('nama', 100);
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('role', 20);
            $table->string('status_akun', 20)->default('aktif');
            $table->string('no_telp', 20)->nullable();
            $table->string('foto_profil')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        $this->repository = new EloquentPenggunaRepository;

        $this->seedPengguna();
    }

    public function test_pencarian_mendukung_nama_username_dan_no_telp_serta_filter_dan_pagination(): void
    {
        $byNama = $this->repository->paginateWithFilters(['search' => 'Siti'], 10);
        $this->assertSame(1, $byNama->total());
        $this->assertSame('siti_kasir', $byNama->items()[0]->username);

        $byUsername = $this->repository->paginateWithFilters(['search' => 'budi_pemilik'], 10);
        $this->assertSame(1, $byUsername->total());
        $this->assertSame('Budi Pemilik', $byUsername->items()[0]->nama);

        $byNoTelp = $this->repository->paginateWithFilters(['search' => '08999'], 10);
        $this->assertSame(1, $byNoTelp->total());
        $this->assertSame('rina_kasir', $byNoTelp->items()[0]->username);

        $kombinasi = $this->repository->paginateWithFilters([
            'search' => '08123',
            'role' => 'kasir',
            'status_akun' => 'aktif',
        ], 1);
        $this->assertSame(2, $kombinasi->total());
        $this->assertSame(1, $kombinasi->perPage());
        $this->assertCount(1, $kombinasi->items());

        // Pastikan kolom password tidak dipilih dalam hasil pagination
        $firstItem = $kombinasi->items()[0];
        $this->assertArrayNotHasKey('password', $firstItem->getAttributes());
    }

    private function seedPengguna(): void
    {
        $hash = Hash::make('password123');

        Pengguna::query()->create([
            'nama' => 'Admin Toko',
            'username' => 'admin_toko',
            'password' => $hash,
            'role' => 'admin',
            'status_akun' => 'aktif',
            'no_telp' => '08123000001',
        ]);

        Pengguna::query()->create([
            'nama' => 'Siti Kasir',
            'username' => 'siti_kasir',
            'password' => $hash,
            'role' => 'kasir',
            'status_akun' => 'aktif',
            'no_telp' => '08123000002',
        ]);

        Pengguna::query()->create([
            'nama' => 'Andi Kasir',
            'username' => 'andi_kasir',
            'password' => $hash,
            'role' => 'kasir',
            'status_akun' => 'aktif',
            'no_telp' => '08123000003',
        ]);

        Pengguna::query()->create([
            'nama' => 'Rina Kasir Nonaktif',
            'username' => 'rina_kasir',
            'password' => $hash,
            'role' => 'kasir',
            'status_akun' => 'nonaktif',
            'no_telp' => '08999000004',
        ]);

        Pengguna::query()->create([
            'nama' => 'Budi Pemilik',
            'username' => 'budi_pemilik',
            'password' => $hash,
            'role' => 'pemilik',
            'status_akun' => 'aktif',
            'no_telp' => '08123000005',
        ]);
    }
}
