<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

<div class="text-center p-5 bg-white rounded shadow-sm border style="max-width: 500px;">
    <div class="mb-3 text-danger">
        <i class="bi bi-shield-lock-fill display-1"></i>
    </div>
    <h1 class="fw-bold text-dark">403</h1>
    <h4 class="mb-3 text-secondary">Akses Ditolak</h4>
    <p class="text-muted mb-4">
        {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk membuka halaman ini.' }}
    </p>
    <a href="javascript:history.back()" class="btn btn-primary me-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
        Halaman Utama
    </a>
</div>

</body>
</html>