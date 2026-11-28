<?php

namespace App\Providers;

use App\Contracts\Repositories\PenggunaRepositoryInterface;
use App\Contracts\Services\ImageStorageInterface;
use App\Models\Pengguna;
use App\Policies\PenggunaPolicy;
use App\Repositories\EloquentPenggunaRepository;
use App\Services\PublicImageStorageService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PenggunaRepositoryInterface::class, EloquentPenggunaRepository::class);
        $this->app->bind(ImageStorageInterface::class, PublicImageStorageService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Pengguna::class, PenggunaPolicy::class);
    }
}
