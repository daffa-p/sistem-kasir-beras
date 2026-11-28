@php($role = strtolower(trim(Auth::user()->role ?? '')))

<nav class="nav flex-column mt-2">
    @if ($role === 'admin')
        <div class="rice-nav-label">Menu Admin</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard Admin
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-box-seam"></i> Data Beras
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-arrow-left-right"></i> Manajemen Stok
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-truck"></i> Data Supplier
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-people"></i> Manajemen Pengguna
        </a>
    @elseif ($role === 'kasir')
        <div class="rice-nav-label">Menu Kasir</div>
        <a href="{{ route('kasir.dashboard') }}" class="nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard Kasir
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-cart-plus"></i> Transaksi Penjualan
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-clock-history"></i> Riwayat Transaksi
        </a>
    @elseif (in_array($role, ['pemilik', 'pemilik toko', 'pemilik_toko']))
        <div class="rice-nav-label">Menu Pemilik Toko</div>
        <a href="{{ route('pemilik.dashboard') }}" class="nav-link {{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard Pemilik
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan Otomatis
        </a>
    @endif

    <div class="rice-nav-label">Akun</div>
    <a href="{{ route('profil.index') }}" class="nav-link {{ request()->routeIs('profil.*') ? 'active' : '' }}">
        <i class="bi bi-person-gear"></i> Profil Pengguna
    </a>
</nav>