<?php

namespace App\Contracts\Services;

use Illuminate\Http\UploadedFile;

interface ImageStorageInterface
{
    /**
     * Menyimpan file gambar ke storage publik dan mengembalikan path relatifnya.
     */
    public function store(UploadedFile $file, string $directory): string;

    /**
     * Menghapus file gambar dari storage publik jika path tersedia.
     */
    public function delete(?string $path): bool;

    /**
     * Menghasilkan URL publik untuk path gambar tersimpan.
     */
    public function url(?string $path): ?string;
}
