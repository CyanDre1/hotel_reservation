# Sistem Reservasi Hotel

Aplikasi web reservasi hotel berbasis **Laravel 13** (PHP 8.4) dengan backend **MySQL** dan frontend **Tailwind CSS v4 + Alpine.js + Chart.js**. Dokumentasi proyek (PRD & desain UI/UX) ada di `.agents/prd.md` dan `.agents/design.md` — ditulis dalam Bahasa Indonesia.

## Fitur

### Sisi Tamu (Guest)
- Landing page (hero, highlight tipe kamar, fasilitas)
- Daftar kamar dengan **filter live client-side** (tanggal, tipe, harga) via Alpine.js
- Detail kamar + form reservasi (cek ketersediaan & anti double-booking)
- Riwayat booking + pembatalan (sesuai status) + badge status berwarna
- Autentikasi Breeze (login, register, verifikasi email, reset password, profil)

### Sisi Admin
- Dashboard statistik (total booking, pending, kamar tersedia, revenue bulan ini, total user)
- CRUD Manajemen Kamar (data table + upload foto)
- CRUD Manajemen Tipe Kamar (**modal tambah/edit tanpa reload** via fetch + toast)
- Manajemen Booking (filter status + update status berjenjang: pending → confirmed → checked_in → checked_out / cancelled)
- Manajemen User (edit role, hapus — dengan proteksi akun sendiri)
- Laporan dengan **Chart.js** (booking & revenue per bulan, occupancy rate)
- Proteksi role: semua halaman admin hanya untuk user `role = admin` (403 untuk guest)

## Persyaratan

- PHP ≥ 8.4 (dengan ekstensi `pdo_sqlite` & `sqlite3` aktif untuk menjalankan test)
- Composer, Node.js & npm
- MySQL (untuk dev) — test berjalan di SQLite `:memory:`

## Instalasi

```bash
composer setup
```

`composer setup` menjalankan: `composer install`, copy `.env.example` → `.env`, `php artisan key:generate`, migrate, npm install & build.

Kemudian:

```bash
php artisan storage:link   # agar foto kamar bisa diakses via /storage
php artisan db:seed        # seed tipe kamar, kamar, dan user admin
```

### Akun seed

- **Admin**: `admin@hotel.test` / `password`
- **User test**: `test@hotel.test` / `password`

## Menjalankan

```bash
composer dev        # server + Vite (mode dev)
npm run build       # build asset produksi
composer test       # test suite (SQLite :memory:)
vendor/bin/pint     # code style (Laravel Pint)
```

## Struktur Proyek

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/        # Dashboard, Room, RoomType, Booking, User, Report
│   │   ├── Auth/         # Breeze
│   │   └── BookingController.php, HomeController.php, RoomController.php, ProfileController.php
│   ├── Middleware/EnsureUserIsAdmin.php
│   └── Requests/         # Store/Update form request (CRUD)
├── Models/               # User, Room, RoomType, Booking
├── Policies/BookingPolicy.php
database/
├── migrations/           # room_types, rooms, bookings, add_role_to_users
└── seeders/              # DatabaseSeeder, RoomTypeSeeder, RoomSeeder
resources/views/
├── admin/                # dashboard, rooms, room-types, bookings, users, reports
├── auth/ booking/ profile/ rooms/ home.blade.php
└── components/layouts/   # admin.blade.php, public.blade.php, status-badge.blade.php
routes/web.php            # route guest + admin group
tests/Feature/            # BookingTest, SecurityTest, PublicPagesTest, AdminTest, Auth/*
```

## Status Booking

`pending` → `confirmed` → `checked_in` → `checked_out` (atau `cancelled`). User hanya bisa membatalkan booking `pending`/`confirmed` miliknya.

## Deployment (Production)

```bash
APP_ENV=production APP_DEBUG=false   # di .env production
npm run build                        # build asset (sudah otomatis di composer setup)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan storage:link
```

Pastikan juga memindahkan admin seed (`admin@hotel.test`) atau membuat user admin sendiri melalui database sebelum `APP_DEBUG=false`.

## Lisensi

Proyek ini bersifat open-source, dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).