# Dashboard Manajemen Data 9 Kampung — Kecamatan Buay Bahuga

Aplikasi web untuk mengelola data kependudukan, profil kampung, dan laporan
bulanan dari **9 kampung** di Kecamatan Buay Bahuga, Kabupaten Way Kanan,
Provinsi Lampung, yang terintegrasi dengan rekap resmi tingkat kecamatan.

**9 kampung yang dikelola:** Bumiharjo, Lebung Lawe, Nuar Maju, Punjul Agung,
Sri Tunggal, Suka Agung, Sukabumi, Sukadana, dan Way Agung.

## Arsitektur

```
┌─────────────────────┐        HTTP (REST/JSON)        ┌──────────────────────┐
│   Laravel (PHP)      │ ───────────────────────────▶  │  Python / Flask       │
│   - Dashboard web    │ ◀───────────────────────────  │  - Agregasi 9 kampung │
│   - CRUD kampung     │        rekap kecamatan         │  - Rekap kecamatan    │
│   - CRUD penduduk    │                                │  - Endpoint publik    │
│   - Laporan bulanan  │                                │    /api/rekap-kecamatan│
│   - MySQL/MariaDB    │                                └──────────────────────┘
└─────────────────────┘
```

Laravel adalah aplikasi utama (dashboard, CRUD, autentikasi, database).
Layanan Python (Flask) berjalan terpisah sebagai **mesin integrasi & analitik**
yang menerima snapshot data dari Laravel, mengagregasinya menjadi rekap resmi
tingkat kecamatan, dan mengembalikannya untuk ditampilkan di menu
**Integrasi Kecamatan**.

## Struktur folder

```
dashboard-kampung-buaybahuga/
├── app/                    # Model & Controller Laravel
├── database/
│   ├── migrations/         # Struktur tabel: kampungs, penduduks, laporans
│   └── seeders/            # Seeder 9 kampung + akun default
├── resources/views/        # Tampilan Blade (Bootstrap 5, responsif)
├── routes/                 # web.php & api.php
├── public/assets/          # CSS tema kustom
├── python-service/         # Layanan Flask (integrasi & rekap kecamatan)
│   ├── app.py
│   ├── requirements.txt
│   └── .env.example
├── composer.json
└── .env.example
```

## 1. Instalasi Backend Laravel

### Kebutuhan server
- PHP >= 8.2 dengan ekstensi: mbstring, pdo_mysql, openssl, tokenizer, xml, ctype, json, bcmath
- Composer 2.x
- MySQL/MariaDB 8/10.x
- Web server: Nginx atau Apache (atau `php artisan serve` untuk pengujian)

### Langkah instalasi

```bash
# 1. Masuk ke folder project
cd dashboard-kampung-buaybahuga

# 2. Install dependency PHP
composer install --optimize-autoloader --no-dev

# 3. Salin file environment & generate key
cp .env.example .env
php artisan key:generate

# 4. Atur koneksi database di .env
#    DB_DATABASE=db_kampung_buaybahuga
#    DB_USERNAME=... DB_PASSWORD=...

# 5. Buat database lalu jalankan migrasi + seeder 9 kampung
php artisan migrate --seed

# 6. Buat symlink storage (jika memakai upload file)
php artisan storage:link

# 7. Cache konfigurasi untuk produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Jalankan (mode pengujian)
php artisan serve --host=0.0.0.0 --port=8000
```

### Deploy produksi (Nginx + PHP-FPM)
Arahkan document root ke folder `public/` pada Nginx, contoh konfigurasi:

```nginx
server {
    listen 80;
    server_name dashboard.buaybahuga.go.id;
    root /var/www/dashboard-kampung-buaybahuga/public;

    index index.php;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### Akun default (setelah `migrate --seed`)
| Peran | Email | Password |
|---|---|---|
| Admin Kecamatan | admin@buaybahuga.go.id | BuayBahuga#2026 |
| Operator per kampung | operator.<namakampung>@buaybahuga.go.id | Operator#2026 |

> **Wajib** ganti seluruh password default segera setelah instalasi di server produksi.

## 2. Instalasi Layanan Python (Integrasi Kecamatan)

Layanan ini opsional untuk menjalankan dashboard dasar, tetapi **wajib**
untuk fitur agregasi/rekap resmi tingkat kecamatan di menu
**Integrasi Kecamatan**.

```bash
cd python-service
python3 -m venv venv
source venv/bin/activate        # Windows: venv\Scripts\activate

pip install -r requirements.txt
cp .env.example .env
# Atur PYTHON_SERVICE_TOKEN dengan token rahasia yang SAMA
# dengan PYTHON_SERVICE_TOKEN di file .env Laravel

python app.py
# berjalan di http://127.0.0.1:5000
```

### Deploy produksi (disarankan gunakan gunicorn + systemd)

```bash
gunicorn --workers 2 --bind 0.0.0.0:5000 app:app
```

Contoh service systemd (`/etc/systemd/system/rekap-kecamatan.service`):

```ini
[Unit]
Description=Layanan Rekap Kecamatan Buay Bahuga (Flask)
After=network.target

[Service]
WorkingDirectory=/var/www/dashboard-kampung-buaybahuga/python-service
Environment="PATH=/var/www/dashboard-kampung-buaybahuga/python-service/venv/bin"
ExecStart=/var/www/dashboard-kampung-buaybahuga/python-service/venv/bin/gunicorn --workers 2 --bind 127.0.0.1:5000 app:app
Restart=always

[Install]
WantedBy=multi-user.target
```

Lalu hubungkan Laravel ke layanan ini melalui `.env`:

```
PYTHON_SERVICE_URL=http://127.0.0.1:5000
PYTHON_SERVICE_TOKEN=token_rahasia_yang_sama
```

## 3. Fitur Utama

| Modul | Deskripsi |
|---|---|
| **Ringkasan** | Statistik total penduduk, KK, grafik jumlah penduduk per kampung, komposisi gender |
| **Data Kampung** | CRUD profil 9 kampung: kepala kampung, RT/RW, luas wilayah, status definitif/PJS |
| **Data Penduduk** | CRUD kependudukan per kampung (NIK, KK, jenis kelamin, pekerjaan, status kawin) |
| **Laporan Bulanan** | Operator kampung mengajukan laporan (lahir/meninggal/pindah); admin kecamatan memverifikasi |
| **Integrasi Kecamatan** | Sinkronisasi data ke layanan Python untuk rekap resmi tingkat kecamatan |
| **Role & akses** | 2 peran: `admin_kecamatan` (akses penuh + verifikasi) dan `operator_kampung` (input data kampungnya sendiri) |

## 4. Screenshot

Lihat folder `screenshots/` — hasil render nyata tampilan aplikasi (desktop & mobile),
membuktikan tema, layout, dan data 9 kampung sudah terpasang dan berfungsi.

## 5. Keamanan sebelum go-live

- Ganti seluruh password default seeder
- Set `APP_DEBUG=false` dan `APP_ENV=production` di `.env`
- Gunakan HTTPS (Let's Encrypt) di depan Nginx
- Ganti `PYTHON_SERVICE_TOKEN` dengan string acak yang kuat, samakan di kedua `.env`
- Batasi akses port 5000 (layanan Python) hanya dari server Laravel (firewall/`127.0.0.1`)
