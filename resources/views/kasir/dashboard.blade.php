@extends('layouts.app')

@section('title', 'Dashboard Kasir')
@section('header_title', 'Dashboard Kasir')
@section('header_subtitle', 'Ringkasan transaksi harian, ketersediaan stok, dan akses kasir')

@section('content')
<!-- Ringkasan Transaksi Kasir -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-semibold">Jumlah Transaksi Hari Ini</div>
                    <div class="fs-3 fw-bold mt-1">42</div>
                    <span class="badge badge-soft-success mt-2">+12% dari kemarin</span>
                </div>
                <span class="p-2 rounded-3 bg-success bg-opacity-10 text-success fs-4">
                    <i class="bi bi-receipt"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-semibold">Pendapatan Hari Ini</div>
                    <div class="fs-3 fw-bold text-success mt-1">Rp 12.450.000</div>
                    <span class="badge badge-soft-success mt-2">Akumulasi transaksi berhasil</span>
                </div>
                <span class="p-2 rounded-3 bg-success bg-opacity-10 text-success fs-4">
                    <i class="bi bi-cash-stack"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-semibold">Transaksi Selesai &amp; Tertunda</div>
                    <div class="fs-3 fw-bold mt-1">40 <span class="fs-6 fw-normal text-muted">Selesai</span></div>
                    <span class="badge badge-soft-warning mt-2">2 Transaksi Tertunda</span>
                </div>
                <span class="p-2 rounded-3 bg-warning bg-opacity-10 text-warning fs-4">
                    <i class="bi bi-hourglass-split"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Akses Transaksi Baru & Notifikasi Stok Menipis -->
<div class="row g-3 mb-4">
    <div class="col-lg-5">
        <div class="rice-card p-4 h-100 d-flex flex-column justify-content-between"
             style="background: linear-gradient(135deg, #0b5335 0%, #073b26 100%); color: #fff;">
            <div>
                <span class="badge bg-success bg-opacity-75 mb-2">Point of Sale Beras</span>
                <h5 class="fw-bold">Mulai Transaksi Penjualan</h5>
                <p class="small text-white-50 mb-4">
                    Pilih produk beras, masukkan berat kilogram, hitung subtotal &amp; uang kembali, lalu cetak struk.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-light text-success fw-bold px-3">
                    <i class="bi bi-cart-plus me-1"></i> Transaksi Baru
                </a>
                <a href="#" class="btn btn-outline-light btn-sm align-self-center">
                    Riwayat Transaksi
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Notifikasi Stok Menipis
                </h6>
                <span class="badge badge-soft-danger">2 Produk</span>
            </div>

            <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 mb-2 bg-light">
                <div>
                    <div class="fw-bold small">Beras Merah Organik</div>
                    <div class="text-muted small">Stok saat ini: <strong class="text-danger">5 Kg</strong></div>
                </div>
                <span class="badge badge-soft-danger">Stok Rendah</span>
            </div>

            <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 bg-light">
                <div>
                    <div class="fw-bold small">Beras Pandan Wangi</div>
                    <div class="text-muted small">Stok saat ini: <strong class="text-warning">12 Kg</strong></div>
                </div>
                <span class="badge badge-soft-warning">Perlu Perhatian</span>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Transaksi Terbaru -->
<div class="rice-card p-3 p-md-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Riwayat Transaksi Terbaru</h6>
        <a href="#" class="btn btn-sm btn-outline-success">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>ID Transaksi</th>
                    <th>Waktu</th>
                    <th>Rincian Item</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-semibold">#TRX-88291</td>
                    <td>14:15 WIB</td>
                    <td>Beras Pandan Wangi (5 Kg)</td>
                    <td class="fw-semibold">Rp 85.000</td>
                    <td><span class="badge badge-soft-success">Selesai</span></td>
                </tr>
                <tr>
                    <td class="fw-semibold">#TRX-88290</td>
                    <td>13:58 WIB</td>
                    <td>Beras Merah (10 Kg)</td>
                    <td class="fw-semibold">Rp 175.000</td>
                    <td><span class="badge badge-soft-success">Selesai</span></td>
                </tr>
                <tr>
                    <td class="fw-semibold">#TRX-88289</td>
                    <td>13:42 WIB</td>
                    <td>Beras Setra Ramos (50 Kg)</td>
                    <td class="fw-semibold">Rp 650.000</td>
                    <td><span class="badge badge-soft-success">Selesai</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection