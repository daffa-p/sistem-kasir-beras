<?php

namespace Tests\Unit;

use App\Contracts\Repositories\PenggunaRepositoryInterface;
use App\Contracts\Services\ImageStorageInterface;
use App\Models\Pengguna;
use App\Services\PenggunaService;
use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class PenggunaServiceTest extends TestCase
{
    private PenggunaRepositoryInterface $repository;

    private ImageStorageInterface $imageStorage;

    private PenggunaService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(PenggunaRepositoryInterface::class);
        $this->imageStorage = Mockery::mock(ImageStorageInterface::class);
        $this->service = new PenggunaService($this->repository, $this->imageStorage);
    }

    public function test_create_menghash_password_dan_menyimpan_foto_profil(): void
    {
        $file = UploadedFile::fake()->image('profil.jpg');

        $this->imageStorage
            ->shouldReceive('store')
            ->once()
            ->with($file, 'profil')
            ->andReturn('profil/foto-baru.jpg');

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->withArgs(function (array $payload): bool {
                return $payload['nama'] === 'Budi Santoso'
                    && $payload['username'] === 'budi_kasir'
                    && $payload['role'] === 'kasir'
                    && $payload['status_akun'] === 'aktif'
                    && $payload['foto_profil'] === 'profil/foto-baru.jpg'
                    && $payload['password'] !== 'rahasia123'
                    && Hash::check('rahasia123', $payload['password']);
            })
            ->andReturn($this->makePengguna(10, 'kasir', 'aktif', 'profil/foto-baru.jpg'));

        $created = $this->service->create([
            'nama' => 'Budi Santoso',
            'username' => 'budi_kasir',
            'password' => 'rahasia123',
            'role' => 'kasir',
            'status_akun' => 'aktif',
            'no_telp' => '08123456789',
        ], $file);

        $this->assertSame(10, $created->id_pengguna);
    }

    public function test_create_menghapus_foto_baru_jika_simpan_database_gagal(): void
    {
        $file = UploadedFile::fake()->image('profil.jpg');

        $this->imageStorage
            ->shouldReceive('store')
            ->once()
            ->with($file, 'profil')
            ->andReturn('profil/yatim.jpg');

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->andThrow(new RuntimeException('DB error'));

        $this->imageStorage
            ->shouldReceive('delete')
            ->once()
            ->with('profil/yatim.jpg')
            ->andReturnTrue();

        $this->expectException(RuntimeException::class);

        $this->service->create([
            'nama' => 'Budi',
            'username' => 'budi',
            'password' => 'rahasia123',
            'role' => 'kasir',
            'status_akun' => 'aktif',
        ], $file);
    }

    public function test_update_menolak_menonaktifkan_akun_sendiri(): void
    {
        $admin = $this->makePengguna(1, 'admin', 'aktif');

        $this->expectException(DomainException::class);

        $this->service->update($admin, [
            'nama' => 'Admin',
            'username' => 'admin',
            'role' => 'admin',
            'status_akun' => 'nonaktif',
        ], null, 1);
    }

    public function test_update_menolak_mengubah_role_akun_sendiri(): void
    {
        $admin = $this->makePengguna(1, 'admin', 'aktif');

        $this->imageStorage->shouldNotReceive('store');
        $this->repository->shouldNotReceive('update');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Admin tidak diperbolehkan mengubah role akunnya sendiri.');

        $this->service->update($admin, [
            'nama' => 'Admin',
            'username' => 'admin',
            'role' => 'kasir',
            'status_akun' => 'aktif',
        ], null, 1);
    }

    public function test_update_mengizinkan_admin_memperbarui_profil_dan_password_sendiri_tanpa_mengubah_role(): void
    {
        $admin = $this->makePengguna(1, 'admin', 'aktif', 'profil/admin-lama.jpg');
        $fotoBaru = UploadedFile::fake()->image('admin-baru.jpg');

        $this->imageStorage
            ->shouldReceive('store')
            ->once()
            ->with($fotoBaru, 'profil')
            ->andReturn('profil/admin-baru.jpg');

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->withArgs(function (Pengguna $model, array $payload): bool {
                return $payload['nama'] === 'Admin Utama'
                    && $payload['username'] === 'admin_utama'
                    && $payload['role'] === 'admin'
                    && $payload['status_akun'] === 'aktif'
                    && $payload['no_telp'] === '08111222333'
                    && $payload['foto_profil'] === 'profil/admin-baru.jpg'
                    && isset($payload['password'])
                    && Hash::check('passwordbaru123', $payload['password']);
            })
            ->andReturn($this->makePengguna(1, 'admin', 'aktif', 'profil/admin-baru.jpg'));

        $this->imageStorage
            ->shouldReceive('delete')
            ->once()
            ->with('profil/admin-lama.jpg')
            ->andReturnTrue();

        $updated = $this->service->update($admin, [
            'nama' => 'Admin Utama',
            'username' => 'admin_utama',
            'password' => 'passwordbaru123',
            'role' => 'admin',
            'status_akun' => 'aktif',
            'no_telp' => '08111222333',
        ], $fotoBaru, 1);

        $this->assertSame('admin', $updated->role);
        $this->assertSame('profil/admin-baru.jpg', $updated->foto_profil);
    }

    public function test_update_mengizinkan_admin_mengubah_role_pengguna_lain(): void
    {
        $kasir = $this->makePengguna(2, 'kasir', 'aktif');

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->withArgs(function (Pengguna $model, array $payload): bool {
                return $payload['role'] === 'pemilik'
                    && $payload['status_akun'] === 'aktif';
            })
            ->andReturn($this->makePengguna(2, 'pemilik', 'aktif'));

        $updated = $this->service->update($kasir, [
            'nama' => 'User 2',
            'username' => 'user_2',
            'role' => 'pemilik',
            'status_akun' => 'aktif',
            'password' => '',
        ], null, 1);

        $this->assertSame('pemilik', $updated->role);
    }

    public function test_update_tidak_menghapus_foto_lama_jika_update_database_gagal(): void
    {
        $pengguna = $this->makePengguna(2, 'kasir', 'aktif', 'profil/lama.jpg');
        $fileBaru = UploadedFile::fake()->image('baru.png');

        $this->imageStorage
            ->shouldReceive('store')
            ->once()
            ->with($fileBaru, 'profil')
            ->andReturn('profil/baru.png');

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->andThrow(new RuntimeException('DB update failed'));

        $this->imageStorage
            ->shouldReceive('delete')
            ->once()
            ->with('profil/baru.png')
            ->andReturnTrue();

        $this->imageStorage
            ->shouldNotReceive('delete')
            ->with('profil/lama.jpg');

        $this->expectException(RuntimeException::class);

        $this->service->update($pengguna, [
            'nama' => 'Kasir Baru',
            'username' => 'kasir_baru',
            'role' => 'kasir',
            'status_akun' => 'aktif',
            'password' => '',
        ], $fileBaru, 1);
    }

    public function test_update_menghapus_foto_lama_setelah_update_berhasil_dan_tidak_mengubah_password_kosong(): void
    {
        $pengguna = $this->makePengguna(2, 'kasir', 'aktif', 'profil/lama.jpg');
        $fileBaru = UploadedFile::fake()->image('baru.webp');

        $this->imageStorage
            ->shouldReceive('store')
            ->once()
            ->with($fileBaru, 'profil')
            ->andReturn('profil/baru.webp');

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->withArgs(function (Pengguna $model, array $payload): bool {
                return ! array_key_exists('password', $payload)
                    && $payload['foto_profil'] === 'profil/baru.webp';
            })
            ->andReturn($this->makePengguna(2, 'kasir', 'aktif', 'profil/baru.webp'));

        $this->imageStorage
            ->shouldReceive('delete')
            ->once()
            ->with('profil/lama.jpg')
            ->andReturnTrue();

        $updated = $this->service->update($pengguna, [
            'nama' => 'Kasir Update',
            'username' => 'kasir_update',
            'role' => 'kasir',
            'status_akun' => 'aktif',
            'password' => '',
        ], $fileBaru, 1);

        $this->assertSame('profil/baru.webp', $updated->foto_profil);
    }

    public function test_toggle_status_menolak_akun_sendiri(): void
    {
        $admin = $this->makePengguna(1, 'admin', 'aktif');

        $this->expectException(DomainException::class);

        $this->service->toggleStatus($admin, 1);
    }

    public function test_delete_menolak_jika_pengguna_memiliki_riwayat_transaksi_atau_stok(): void
    {
        $kasir = $this->makePengguna(5, 'kasir', 'aktif');

        $this->repository->shouldReceive('hasTransactionHistory')->once()->with(5)->andReturnTrue();
        $this->repository->shouldNotReceive('delete');

        $this->expectException(DomainException::class);

        $this->service->delete($kasir, 1);
    }

    public function test_delete_menghapus_pengguna_dan_foto_jika_tidak_ada_riwayat(): void
    {
        $kasir = $this->makePengguna(6, 'kasir', 'nonaktif', 'profil/kasir.jpg');

        $this->repository->shouldReceive('hasTransactionHistory')->once()->with(6)->andReturnFalse();
        $this->repository->shouldReceive('hasStockHistory')->once()->with(6)->andReturnFalse();
        $this->repository->shouldReceive('delete')->once()->with($kasir)->andReturnTrue();
        $this->imageStorage->shouldReceive('delete')->once()->with('profil/kasir.jpg')->andReturnTrue();

        $this->service->delete($kasir, 1);

        $this->assertTrue(true);
    }

    private function makePengguna(int $id, string $role, string $status, ?string $foto = null): Pengguna
    {
        $pengguna = new Pengguna([
            'nama' => 'User '.$id,
            'username' => 'user_'.$id,
            'role' => $role,
            'status_akun' => $status,
            'foto_profil' => $foto,
        ]);
        $pengguna->id_pengguna = $id;

        return $pengguna;
    }
}
