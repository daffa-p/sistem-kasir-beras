<?php

namespace App\Http\Controllers;

use App\Enums\AccountStatus;
use App\Enums\UserRole;
use App\Http\Requests\Pengguna\StorePenggunaRequest;
use App\Http\Requests\Pengguna\UpdatePenggunaRequest;
use App\Models\Pengguna;
use App\Services\PenggunaService;
use DomainException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly PenggunaService $penggunaService,
    ) {}

    /**
     * Menampilkan daftar pengguna dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Pengguna::class);

        $filters = [
            'search' => $request->string('search')->toString(),
            'role' => $request->string('role')->toString(),
            'status_akun' => $request->string('status_akun')->toString(),
        ];

        $perPage = max(1, min(50, $request->integer('per_page', 10)));

        return view('admin.pengguna.index', [
            'penggunaList' => $this->penggunaService->getPaginatedList($filters, $perPage),
            'summary' => $this->penggunaService->getSummary(),
            'filters' => $filters,
            'roleOptions' => UserRole::options(),
            'statusOptions' => AccountStatus::options(),
        ]);
    }

    /**
     * Menampilkan form tambah pengguna baru.
     */
    public function create(): View
    {
        $this->authorize('create', Pengguna::class);

        return view('admin.pengguna.create', [
            'roleOptions' => UserRole::options(),
            'statusOptions' => AccountStatus::options(),
        ]);
    }

    /**
     * Menyimpan data pengguna baru ke database.
     */
    public function store(StorePenggunaRequest $request): RedirectResponse
    {
        $this->authorize('create', Pengguna::class);

        try {
            $this->penggunaService->create(
                $request->validated(),
                $request->file('foto_profil'),
            );
        } catch (DomainException $exception) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pengguna tanpa menyertakan password.
     */
    public function show(Pengguna $pengguna): View
    {
        $this->authorize('view', $pengguna);

        $safePengguna = $this->penggunaService->getSafeDetail((int) $pengguna->id_pengguna)
            ?? $pengguna->makeHidden(['password', 'remember_token']);

        return view('admin.pengguna.show', [
            'pengguna' => $safePengguna,
            'fotoProfilUrl' => $this->penggunaService->getPhotoUrl($safePengguna->foto_profil),
            'memilikiRiwayat' => $this->penggunaService->hasActivityHistory($safePengguna),
        ]);
    }

    /**
     * Menampilkan form edit data pengguna.
     */
    public function edit(Pengguna $pengguna): View
    {
        $this->authorize('update', $pengguna);

        $safePengguna = $this->penggunaService->getSafeDetail((int) $pengguna->id_pengguna)
            ?? $pengguna->makeHidden(['password', 'remember_token']);

        return view('admin.pengguna.edit', [
            'pengguna' => $safePengguna,
            'fotoProfilUrl' => $this->penggunaService->getPhotoUrl($safePengguna->foto_profil),
            'roleOptions' => UserRole::options(),
            'statusOptions' => AccountStatus::options(),
        ]);
    }

    /**
     * Memperbarui data pengguna yang sudah ada.
     */
    public function update(UpdatePenggunaRequest $request, Pengguna $pengguna): RedirectResponse
    {
        $this->authorize('update', $pengguna);

        try {
            $this->penggunaService->update(
                $pengguna,
                $request->validated(),
                $request->file('foto_profil'),
                $request->user()?->id_pengguna,
            );
        } catch (DomainException $exception) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Mengubah status akun pengguna antara aktif dan nonaktif.
     */
    public function toggleStatus(Request $request, Pengguna $pengguna): RedirectResponse
    {
        $this->authorize('toggleStatus', $pengguna);

        try {
            $updated = $this->penggunaService->toggleStatus(
                $pengguna,
                $request->user()?->id_pengguna,
            );
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $statusLabel = AccountStatus::fromNormalized($updated->status_akun)?->label() ?? $updated->status_akun;

        return back()->with('success', "Status akun {$updated->nama} berhasil diubah menjadi {$statusLabel}.");
    }

    /**
     * Menghapus pengguna jika tidak dipakai akun sendiri dan tidak memiliki histori.
     */
    public function destroy(Request $request, Pengguna $pengguna): RedirectResponse
    {
        $this->authorize('delete', $pengguna);

        try {
            $this->penggunaService->delete(
                $pengguna,
                $request->user()?->id_pengguna,
            );
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil dihapus.');
    }
}
