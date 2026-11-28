<?php

namespace App\Services;

use App\Contracts\Services\ImageStorageInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PublicImageStorageService implements ImageStorageInterface
{
    private const DISK = 'public';

    public function store(UploadedFile $file, string $directory): string
    {
        $cleanDirectory = trim($directory, '/');
        $path = $file->store($cleanDirectory, self::DISK);

        if ($path === false) {
            throw new RuntimeException('Gagal menyimpan file gambar ke storage publik.');
        }

        return $path;
    }

    public function delete(?string $path): bool
    {
        if ($path === null || trim($path) === '') {
            return false;
        }

        $disk = Storage::disk(self::DISK);

        if (! $disk->exists($path)) {
            return false;
        }

        return $disk->delete($path);
    }

    public function url(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        return Storage::disk(self::DISK)->url($path);
    }
}
