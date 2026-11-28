@extends('layouts.app')

@section('title', 'Dashboard Pemilik Toko')
@section('header_title', 'Dashboard Pemilik Toko')
@section('header_subtitle', 'Pemantauan performa penjualan, volume beras terjual, dan laporan')

@section('content')
<!-- Filter Periode & Aksi Laporan -->
<div class="rice-card p-3 mb-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div class="btn-group" role="group" aria-label="Periode Laporan">
        <button type="button" class="btn btn-sm btn-rice">Harian</button>
        <button type="button" class="btn btn-sm btn-outline-success">Mingguan</button>
        <button type="button" class="btn btn-sm btn-outline-success">Bulanan</button>
        <button type="button" class="btn btn-sm btn-outline-success">Tahunan</button>
    </div>

    <div class="d-flex gap-2">
        <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-success">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </button>
        <a href="#" class="btn btn-sm btn-rice">
            <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF / Laporan
        </a>
    </div>
</div>

<!-- Ringkasan Kinerja Penjualan -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="text-muted small fw-semibold">Total Penjualan</div>
            <div class="fs-4 fw-bold text-success mt-1">Rp 128.450.000</div>
            <span class="badge badge-soft-success mt-2">+12.4% vs periode lalu</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="text-muted small fw-semibold">Jumlah Transaksi</div>
            <div class="fs-4 fw-bold mt-1">1.420</div>
            <span class="badge badge-soft-success mt-2">Transaksi berhasil</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="text-muted small fw-semibold">Jumlah Beras Terjual</div>
            <div class="fs-4 fw-bold mt-1">8.450 Kg</div>
            <span class="text-muted small d-block mt-2">Total stok keluar terjual</span>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="text-muted small fw-semibold">Produk Terlaris</div>
            <div class="fs-5 fw-bold text-success mt-1">Pandan Wangi</div>
            <span class="badge badge-soft-success mt-2">Volume penjualan tertinggi</span>
        </div>
    </div>
</div>

<!-- Grafik Tren & Daftar Produk Terlaris -->
<div class="row g-3">
    <div class="col-lg-7">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Tren Penjualan &amp; Pendapatan</h6>
                <span class="text-muted small">Rekap periode terpilih</span>
            </div>
            <canvas id="ownerTrendChart" height="195"></canvas>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Produk Terlaris</h6>
                <a href="#" class="small text-success text-decoration-none fw-semibold">Detail Laporan</a>
            </div>

            <div class="list-group list-group-flush">
                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold small">1. Beras Pandan Wangi</div>
                        <small class="text-muted">Rp 18.500 / Kg</small>
                    </div>
                    <span class="badge badge-soft-success">2.450 Kg</span>
                </div>
                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold small">2. Beras Merah Organik</div>
                        <small class="text-muted">Rp 22.000 / Kg</small>
                    </div>
                    <span class="badge badge-soft-success">1.820 Kg</span>
                </div>
                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold small">3. Beras Basmati Premium</div>
                        <small class="text-muted">Rp 35.000 / Kg</small>
                    </div>
                    <span class="badge badge-soft-success">1.200 Kg</span>
                </div>
                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold small">4. Beras Setra Ramos</div>
                        <small class="text-muted">Rp 14.500 / Kg</small>
                    </div>
                    <span class="badge badge-soft-success">980 Kg</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('ownerTrendChart'), {
        type: 'bar',
        data: {
            labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
            datasets: [{
                label: 'Pendapatan (Juta Rp)',
                data: [26.5, 34.2, 38.0, 29.75],
                backgroundColor: '#0b5335',
                borderRadius: 8
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>
@endpush