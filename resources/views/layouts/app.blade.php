<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir Beras') - Sistem Kasir Toko Beras</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --rice-dark: #073b26;
            --rice-primary: #0b5335;
            --rice-accent: #16a34a;
            --rice-soft: #ecfdf5;
            --rice-border: #e2e8f0;
            --rice-bg: #f4f7f5;
        }

        body {
            background-color: var(--rice-bg);
            color: #1e293b;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Hijau */
        .rice-sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--rice-primary) 0%, var(--rice-dark) 100%);
            color: #ffffff;
            flex-shrink: 0;
            transition: all 0.25s ease;
        }

        .rice-brand {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .rice-brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.14);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #86efac;
        }

        .rice-nav-label {
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.55);
            padding: 0.9rem 1.25rem 0.35rem;
            font-weight: 700;
        }

        .rice-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.82);
            padding: 0.65rem 1.25rem;
            margin: 0.15rem 0.65rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .rice-sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .rice-sidebar .nav-link.active {
            color: #ffffff;
            background-color: var(--rice-accent);
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        /* Wrapper Konten */
        .rice-main-wrapper {
            flex-grow: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .rice-topbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--rice-border);
            padding: 0.85rem 1.5rem;
        }

        .rice-search-box {
            background-color: #f8fafc;
            border: 1px solid var(--rice-border);
            border-radius: 999px;
            padding: 0.35rem 1rem;
            width: 290px;
        }

        .rice-search-box input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 0.88rem;
            width: 100%;
        }

        .rice-card {
            background-color: #ffffff;
            border: 1px solid var(--rice-border);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        }

        .badge-soft-success {
            background-color: #dcfce7;
            color: #166534;
            font-weight: 600;
        }

        .badge-soft-warning {
            background-color: #fef3c7;
            color: #92400e;
            font-weight: 600;
        }

        .badge-soft-danger {
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: 600;
        }

        .btn-rice {
            background-color: var(--rice-primary);
            border-color: var(--rice-primary);
            color: #ffffff;
        }

        .btn-rice:hover {
            background-color: var(--rice-dark);
            border-color: var(--rice-dark);
            color: #ffffff;
        }

        @media (max-width: 767.98px) {
            .rice-sidebar {
                position: fixed;
                z-index: 1045;
                left: -250px;
            }
            .rice-sidebar.show {
                left: 0;
            }
            .rice-search-box {
                width: 180px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <aside id="riceSidebar" class="rice-sidebar d-flex flex-column justify-content-between">
        <div>
            <div class="rice-brand d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="rice-brand-icon">
                        <i class="bi bi-flower1"></i>
                    </span>
                    <div>
                        <div class="fw-bold lh-1 fs-6">Kasir Beras</div>
                        <small class="text-white-50" style="font-size: 0.72rem;">Toko Beras &amp; Inventori</small>
                    </div>
                </div>
                <button class="btn btn-sm text-white d-md-none" type="button" onclick="toggleSidebar()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            @include('layouts.partials.navigation')
        </div>

        <div class="p-3 border-top border-white border-opacity-10">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="rounded-circle bg-success d-flex align-items-center justify-content-center fw-bold text-white"
                     style="width: 34px; height: 34px; font-size: 0.85rem;">
                    {{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="small fw-semibold text-truncate">{{ Auth::user()->nama ?? 'Pengguna' }}</div>
                    <div class="text-white-50 text-capitalize" style="font-size: 0.72rem;">
                        {{ Auth::user()->role ?? '-' }}
                    </div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="rice-main-wrapper">
        <header class="rice-topbar d-flex align-items-center justify-content-between gap-3 sticky-top">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-success btn-sm d-md-none" type="button" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">@yield('header_title', 'Dashboard')</h5>
                    <small class="text-muted">@yield('header_subtitle', now()->translatedFormat('l, d F Y'))</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="rice-search-box d-none d-lg-flex align-items-center gap-2">
                    <i class="bi bi-search text-muted small"></i>
                    <input type="text" placeholder="Cari data beras atau transaksi...">
                </div>

                <span class="badge badge-soft-success px-3 py-2 d-none d-sm-inline-flex align-items-center gap-1">
                    <i class="bi bi-calendar3"></i> {{ now()->format('d M Y | H:i') }}
                </span>

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle"
                       data-bs-toggle="dropdown">
                        <span class="fw-semibold small d-none d-sm-inline">{{ Auth::user()->nama ?? 'Akun' }}</span>
                        <span class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center fw-bold"
                              style="width: 36px; height: 36px;">
                            {{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li class="px-3 py-1">
                            <div class="small fw-bold">{{ Auth::user()->nama ?? 'Pengguna' }}</div>
                            <div class="text-muted small text-capitalize">{{ Auth::user()->role ?? '' }}</div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profil.index') }}">
                                <i class="bi bi-person me-2 text-success"></i>Profil Pengguna
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="p-3 p-md-4 flex-grow-1">
            @include('layouts.partials.notification')
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('riceSidebar').classList.toggle('show');
    }
</script>
@stack('scripts')
</body>
</html>