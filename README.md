# KasApp

Aplikasi Kas berbasis Laravel 11.

## Fitur

- Autentikasi dengan Laravel Breeze (Blade)
- Manajemen Transaksi (Pemasukan & Pengeluaran)
- Manajemen Kategori
- Dashboard dengan grafik dan ringkasan
- Laporan Keuangan Bulanan
- Export Laporan PDF (DomPDF)
- Export Laporan Excel (maatwebsite/excel)
- Activity Log
- Role Admin

## Persyaratan

- PHP >= 8.3
- Composer
- MySQL 8.0+

## Instalasi

```bash
git clone https://github.com/iqbalhilmi085/KasApp-.git
cd KasApp-

composer install
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env, lalu:
php artisan migrate
php artisan db:seed

php artisan serve
```

## Login Default

- **Email:** admin@kasapp.test
- **Password:** password

## Package

| Package | Fungsi |
|---------|--------|
| Laravel Breeze | Auth scaffolding |
| barryvdh/laravel-dompdf | Export PDF |
| maatwebsite/excel | Export Excel |

## Lisensi

Hak Cipta © 2026. All rights reserved.
