<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Dashboard Kampung Buay Bahuga</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>
<body>
<div class="bb-login-wrap">
    <div class="bb-login-visual">
        <div class="bb-login-eyebrow">SISTEM INFORMASI KAMPUNG</div>
        <div class="bb-login-headline">Satu dashboard untuk 9 kampung di Kecamatan Buay Bahuga.</div>
        <p class="bb-login-desc">Kelola data kependudukan, profil kampung, dan laporan bulanan setiap kampung secara terpusat, lalu terintegrasi otomatis dengan rekap Kecamatan Buay Bahuga, Kabupaten Way Kanan.</p>
        <div class="bb-login-villages">
            @foreach (['Bumiharjo','Lebung Lawe','Nuar Maju','Punjul Agung','Sri Tunggal','Suka Agung','Sukabumi','Sukadana','Way Agung'] as $desa)
                <span class="bb-village-chip">{{ $desa }}</span>
            @endforeach
        </div>
    </div>
    <div class="bb-login-card-wrap">
        <div class="bb-login-card">
            <h2>Masuk ke akun Anda</h2>
            <p class="bb-login-sub">Gunakan akun operator kampung atau admin kecamatan.</p>

            @if ($errors->any())
                <div class="alert-bb-error mb-3">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="mb-3">
                    <label class="bb-form-label">Email</label>
                    <input type="email" name="email" class="bb-form-control" placeholder="operator.bumiharjo@buaybahuga.go.id" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="bb-form-label">Kata sandi</label>
                    <input type="password" name="password" class="bb-form-control" placeholder="••••••••" required>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:13.5px;">Ingat saya</label>
                </div>
                <button type="submit" class="bb-btn bb-btn-primary w-100 justify-content-center">Masuk</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
