@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('header_title', 'Dashboard Admin')
@section('header_subtitle', 'Ringkasan operasional, stok beras, transaksi, dan data master')

@section('content')
<!-- Ringkasan Utama -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl">
        <div class="rice-card p-3 h-100">
            <div class="text-muted small fw-semibold">Penjualan Hari Ini</div>
            <div class="fs-4 fw-bold text-success mt-1">Rp 4.250.000</div>
            <span class="badge badge-soft-success mt-2">+12% dari kemarin</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl">
        <div class="rice-card p-3 h-100">
            <div class="text-muted small fw-semibold">Jumlah Transaksi</div>
            <div class="fs-4 fw-bold mt-1">42</div>
            <span class="badge badge-soft-success mt-2">Transaksi tercatat</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl">
        <div class="rice-card p-3 h-100">
            <div class="text-muted small fw-semibold">Jumlah Jenis Beras</div>
            <div class="fs-4 fw-bold mt-1">18 Jenis</div>
            <span class="text-muted small d-block mt-2">Varian aktif di katalog</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl">
        <div class="rice-card p-3 h-100">
            <div class="text-muted small fw-semibold">Total Stok</div>
            <div class="fs-4 fw-bold mt-1">2.450 Kg</div>
            <span class="badge badge-soft-warning mt-2">Akumulasi kilogram</span>
        </div>
    </div>
    <div class="col-sm-12 col-xl">
        <div class="rice-card p-3 h-100">
            <div class="text-muted small fw-semibold">Pendapatan Bulan Ini</div>
            <div class="fs-4 fw-bold text-success mt-1">Rp 88.400.000</div>
            <span class="badge badge-soft-success mt-2">+8.2% periode berjalan</span>
        </div>
    </div>
</div>

<!-- Grafik Penjualan & Grafik Stok -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Grafik Penjualan</h6>
                <span class="badge badge-soft-success">7 Hari Terakhir</span>
            </div>
            <canvas id="adminSalesChart" height="190"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Grafik Stok per Jenis / Kategori</h6>
                <span class="text-muted small">Satuan (Kg)</span>
            </div>
            <canvas id="adminStockChart" height="190"></canvas>
        </div>
    </div>
</div>

<!-- Riwayat Aktivitas / Transaksi Terbaru & Notifikasi Stok Menipis -->
<div class="row g-3">
    <div class="col-lg-8">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Riwayat Aktivitas &amp; Transaksi Terbaru</h6>
                <a href="#" class="btn btn-sm btn-outline-success">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Waktu</th>
                            <th>Keterangan / Aktivitas</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-semibold">#TRX-00124</td>
                            <td>10:45 WIB</td>
                            <td>Penjualan beras oleh Kasir</td>
                            <td class="fw-semibold">Rp 450.000</td>
                            <td><span class="badge badge-soft-success">Berhasil</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">#STK-00089</td>
                            <td>09:12 WIB</td>
                            <td>Penambahan stok Beras Pandan Wangi</td>
                            <td>+100 Kg</td>
                            <td><span class="badge badge-soft-success">Tercatat</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">#TRX-00122</td>
                            <td>08:05 WIB</td>
                            <td>Penjualan beras oleh Kasir</td>
                            <td class="fw-semibold">Rp 1.250.000</td>
                            <td><span class="badge badge-soft-success">Berhasil</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="rice-card p-3 p-md-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Notifikasi Stok Menipis
                </h6>
                <span class="badge badge-soft-danger">2 Produk</span>
            </div>

            <div class="border rounded-3 p-3 mb-2 bg-light">
                <div class="fw-bold small">Beras Merah Organik</div>
                <div class="text-muted small mb-2">Sisa stok: <strong class="text-danger">5 Kg</strong></div>
                <a href="#" class="btn btn-sm btn-rice w-100">Kelola Stok</a>
            </div>

            <div class="border rounded-3 p-3 mb-3 bg-light">
                <div class="fw-bold small">Beras Pandan Wangi</div>
                <div class="text-muted small mb-2">Sisa stok: <strong class="text-warning">12 Kg</strong></div>
                <a href="#" class="btn btn-sm btn-rice w-100">Kelola Stok</a>
            </div>

            <div class="d-grid gap-2">
                <a href="#" class="btn btn-sm btn-outline-success">Data Beras</a>
                <a href="#" class="btn btn-sm btn-outline-success">Data Supplier &amp; Pengguna</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('adminSalesChart'), {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Penjualan (Juta Rp)',
                data: [3.2, 4.5, 4.0, 5.2, 4.8, 6.5, 7.2],
                borderColor: '#0b5335',
                backgroundColor: 'rgba(22, 163, 74, 0.14)',
                fill: true,
                tension: 0.35
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('adminStockChart'), {
        type: 'bar',
        data: {
            labels: ['Setra Ramos', 'Pandan Wangi', 'Beras Merah', 'Beras Ketan', 'Beras Hitam'],
            datasets: [{
                label: 'Stok (Kg)',
                data: [850, 620, 150, 340, 95],
                backgroundColor: ['#0b5335', '#15803d', '#dc2626', '#16a34a', '#4ade80'],
                borderRadius: 6
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>
@endpush