<?php

namespace App\Contracts\Repositories;

use App\Models\Pengguna;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PenggunaRepositoryInterface
{
    /**
     * Mengambil daftar pengguna dengan pencarian, filter, dan pagination tanpa kolom password.
     *
     * @param  array{search?: string|null, role?: string|null, status_akun?: string|null}  $filters
     */
    public function paginateWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * Mengambil ringkasan jumlah akun pengguna berdasarkan role dan status.
     *
     * @return array{total: int, admin_aktif: int, kasir_aktif: int, pemilik_aktif: int, nonaktif: int}
     */
    public function getSummaryCounts(): array;

    /**
     * Mencari pengguna berdasarkan primary key id_pengguna tanpa memuat hash password.
     */
    public function findById(int $idPengguna): ?Pengguna;

    /**
     * Menyimpan data pengguna baru ke database.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Pengguna;

    /**
     * Memperbarui data pengguna yang ada di database.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(Pengguna $pengguna, array $attributes): Pengguna;

    /**
     * Menghapus record pengguna secara permanen.
     */
    public function delete(Pengguna $pengguna): bool;

    /**
     * Memeriksa apakah pengguna pernah mencatat transaksi penjualan.
     */
    public function hasTransactionHistory(int $idPengguna): bool;

    /**
     * Memeriksa apakah pengguna pernah mencatat riwayat perubahan stok.
     */
    public function hasStockHistory(int $idPengguna): bool;
}
