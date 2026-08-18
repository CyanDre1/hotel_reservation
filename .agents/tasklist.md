# Tasklist – Sistem Reservasi Hotel

> Progres keseluruhan proyek: **98%**

---

## 1. Project Setup & Configuration

- [x] ✅ Task 1.1 - Instalasi Laravel 13 + konfigurasi dasar (`.env`, key, MySQL `reservasi_hotel_db`) (Selesai)
  > Sudah terpasang Laravel 13.25; koneksi MySQL `reservasi_hotel_db` aktif; APP_KEY ada; `APP_NAME` diubah ke "Reservasi Hotel`.
  > File: `.env`
- [x] ✅ Task 1.2 - Setup Tailwind CSS v4 via Vite (`@tailwindcss/vite`, `resources/css/app.css`) (Selesai)
  > npm install + `npm run build` sukses; plugin `@tailwindcss/vite` di `vite.config.js`; `resources/css/app.css` pakai `@import "tailwindcss"` + `@source`.
  > File: `vite.config.js`, `resources/css/app.css`, `package.json`
- [x] ✅ Task 1.3 - Install & setup Laravel Breeze (auth: login, register, logout) (Selesai)
  > `composer require laravel/breeze --dev` + `breeze:install blade`. Breeze meng-override Tailwind ke v3 → dikembalikan ke v4 (hapus `tailwind.config.js` & `postcss.config.js`). 25 test default Breeze hijau.
  > File: `routes/web.php`, `routes/auth.php`, `resources/views/auth/*`, `resources/views/layouts/*`, `app/Http/Controllers/ProfileController.php`, `app/Http/Controllers/Auth/*`, `tests/Feature/Auth/*`
- [x] ✅ Task 1.4 - Setup font Inter/Poppins (Google Fonts) + palet warna dasar di CSS (Selesai)
  > Font **Inter** dibundle via `laravel-vite-plugin/fonts` (bunny). Palet warna dari `design.md` ditambahkan sebagai token di `@theme` (primary `#0F3D3E`, success/warning/danger, ink, muted, surface).
  > File: `vite.config.js`, `resources/css/app.css`
- [x] ✅ Task 1.5 - Setup Layout blade: guest layout (navbar + footer) & admin layout (sidebar + topbar) (Selesai)
  > Guest layout (`layouts/guest.blade.php` untuk auth) & `layouts/app.blade.php` (Breeze) + `layouts/navigation.blade.php`. Layout admin baru `layouts/admin.blade.php`: sidebar kiri fixed (bg-primary, collapsible di mobile via Alpine), topbar (avatar + dropdown), konten di kanan. Komponen `x-admin-nav-link`.
  > File: `resources/views/layouts/admin.blade.php`, `resources/views/components/admin-nav-link.blade.php`, `routes/web.php` (route `home`)

## 2. Database & Migration

- [x] ✅ Task 2.1 - Migration tabel `room_types` (name, description, price_per_night, capacity) (Selesai)
  > File: `database/migrations/2026_08_16_063402_create_room_types_table.php`
- [x] ✅ Task 2.2 - Migration tabel `rooms` (room_type_id FK, room_number, status, image) (Selesai)
  > File: `database/migrations/2026_08_16_063403_create_rooms_table.php`
- [x] ✅ Task 2.3 - Migration tabel `bookings` (user_id FK, room_id FK, check_in, check_out, total_price, status) (Selesai)
  > File: `database/migrations/2026_08_16_063404_create_bookings_table.php`
- [x] ✅ Task 2.4 - Tambah kolom `role` pada tabel `users` (Selesai)
  > Default `guest`; seed membuat user admin `admin@hotel.test`.
  > File: `database/migrations/2026_08_16_063405_add_role_to_users_table.php`
- [x] ✅ Task 2.5 - Index untuk cek ketersediaan kamar (kolom tanggal check_in/check_out & room_id) (Selesai)
  > Index tunggal `user_id`, `room_id`, `check_in`, `check_out` + composite `bookings_availability_index` (room_id, check_in, check_out). Terverifikasi di MySQL (`SHOW INDEX`).
  > File: `database/migrations/2026_08_16_063404_create_bookings_table.php`
- [x] ✅ Task 2.6 - Seeder: tipe kamar & kamar contoh (harus jalan di MySQL & SQLite test) (Selesai)
  > `RoomTypeSeeder` (4 tipe: Standard/Deluxe/Family/Suite) + `RoomSeeder` (14 kamar, nomor 101+). `DatabaseSeeder` tambah user admin & test user. Migrate+fresh+seed sukses di MySQL; `composer test` (SQLite :memory:) 25/25 hijau.
  > File: `database/seeders/RoomTypeSeeder.php`, `database/seeders/RoomSeeder.php`, `database/seeders/DatabaseSeeder.php`

## 3. Models & Relationships / Data Structures

- [x] ✅ Task 3.1 - Model `RoomType` + relasi hasMany `rooms` (Selesai)
  > Fillable via attribute `#[Fillable]`; casts `price_per_night` (decimal:2) & `capacity` (integer); relasi `rooms()`.
  > File: `app/Models/RoomType.php`
- [x] ✅ Task 3.2 - Model `Room` + relasi belongsTo `roomType`, hasMany `bookings` (Selesai)
  > File: `app/Models/Room.php`
- [x] ✅ Task 3.3 - Model `Booking` + relasi belongsTo `user` & `room` (Selesai)
  > Casts `check_in`/`check_out` (date) & `total_price` (decimal:2).
  > File: `app/Models/Booking.php`
- [x] ✅ Task 3.4 - Model `User` update relasi hasMany `bookings` (Selesai)
  > `role` ditambahkan ke fillable + helper `isAdmin()`; relasi `bookings()`.
  > File: `app/Models/User.php`

Verifikasi: relasi tinker OK (RoomType→5 kamar, Room→Standard Room, isAdmin=true); `composer test` 25/25 hijau.

## 4. Backend Logic & API / Core Logic

- [x] ✅ Task 4.1 - Validasi FR-01: tanggal check-in tidak boleh setelah check-out (Selesai)
  > Rule `check_out` => `after:check_in` + `check_in` => `after_or_equal:today` di `StoreBookingRequest`.
  > File: `app/Http/Requests/StoreBookingRequest.php`
- [x] ✅ Task 4.2 - Validasi FR-02: cegah double booking (cek bentrok tanggal pada kamar yang sama) (Selesai)
  > `withValidator` mengecek overlap booking aktif (non cancelled/checked_out) pada room; scope `Room::availableBetween()` untuk listing. Test `test_prevents_double_booking_for_overlapping_dates` & `test_room_is_available_for_non_overlapping_dates`.
  > File: `app/Http/Requests/StoreBookingRequest.php`, `app/Models/Room.php`
- [x] ✅ Task 4.3 - Controller booking sisi guest: form reservasi, submit, cek ketersediaan (Selesai)
  > `BookingController::create/store`; validasi via `StoreBookingRequest`; setelah submit redirect ke `bookings.show` dengan flash success. View `booking/create.blade.php`.
  > File: `app/Http/Controllers/BookingController.php`, `resources/views/booking/create.blade.php`, `routes/web.php`
- [x] ✅ Task 4.4 - Controller riwayat booking user (status: pending, confirmed, checked-in, checked-out, cancelled) (Selesai)
  > `BookingController::index/show` + `booking/index.blade.php` & `booking/show.blade.php` (badge status berwarna). Akses dibatasi ke pemilik booking (403).
  > File: `app/Http/Controllers/BookingController.php`, `resources/views/booking/index.blade.php`, `resources/views/booking/show.blade.php`
- [x] ✅ Task 4.5 - Pembatalan booking oleh user (sesuai kebijakan status) (Selesai)
  > `BookingController::cancel` hanya untuk status `pending`/`confirmed`; selain itu tolak dengan flash error. Test `test_user_can_cancel_pending_booking` & `test_user_cannot_cancel_checked_in_booking`.
  > File: `app/Http/Controllers/BookingController.php`
- [x] ✅ Task 4.6 - Perhitungan total_price (harga per malam x jumlah malam) (Selesai)
  > `calculateTotalPrice()` = `price_per_night × diffInDays(check_in, check_out)`. Test `test_total_price_is_calculated_from_nights` (3 malam × 500.000 = 1.500.000).
  > File: `app/Http/Controllers/BookingController.php`

Tambahan: layout public (`layouts/public.blade.php` - navbar+footer sesuai design.md) sebagai basis halaman guest; `tests/Feature/BookingTest.php` (8 test). Semua 33 test hijau.

## 5. Security (Auth, Validation, Role)

- [x] ✅ Task 5.1 - Middleware auth untuk halaman booking & riwayat (Selesai)
  > Sudah aktif sejak Task 4: semua route `bookings.*` dalam grup `Route::middleware('auth')`. Test `test_guest_cannot_access_booking_pages` & `test_unauthenticated_user_is_redirected_to_login`.
  > File: `routes/web.php`
- [x] ✅ Task 5.2 - Middleware admin (role check) untuk dashboard (Selesai)
  > `EnsureUserIsAdmin` (403 jika bukan admin) + alias `admin` di `bootstrap/app.php`; dipasang di route `/dashboard`. Test: guest 403, admin 200.
  > File: `app/Http/Middleware/EnsureUserIsAdmin.php`, `bootstrap/app.php`, `routes/web.php`
- [x] ✅ Task 5.3 - Form Request / validasi server-side untuk semua input CRUD (Selesai)
  > `StoreRoomTypeRequest`, `UpdateRoomTypeRequest`, `StoreRoomRequest`, `UpdateRoomRequest`, `UpdateBookingStatusRequest`, `UpdateUserRequest` (semua `authorize()` = isAdmin; unique ignore-id di update). Siap dipakai oleh controller admin (Task 7).
  > File: `app/Http/Requests/StoreRoomTypeRequest.php`, `UpdateRoomTypeRequest.php`, `StoreRoomRequest.php`, `UpdateRoomRequest.php`, `UpdateBookingStatusRequest.php`, `UpdateUserRequest.php`
- [x] ✅ Task 5.4 - Proteksi akses: user hanya bisa lihat/batalkan booking miliknya (Selesai)
  > `BookingPolicy` (view/update: owner atau admin; cancel: owner; delete: admin) + `BookingController` pakai `$this->authorize()`. Base `Controller` kini `use AuthorizesRequests`. Test kepemilikan di BookingTest & SecurityTest.
  > File: `app/Policies/BookingPolicy.php`, `app/Http/Controllers/Controller.php`, `app/Http/Controllers/BookingController.php`
- [x] ✅ Task 5.5 - Hashing password (bcrypt) & CSRF protection (default Laravel) (Selesai)
  > Cast `password => hashed` (bcrypt) di User; CSRF aktif default (`PreventRequestForgery` di web group). `UserFactory` tambah state `admin()`. Test `test_password_is_hashed_with_bcrypt`.
  > File: `app/Models/User.php`, `database/factories/UserFactory.php`

Tambahan: `tests/Feature/SecurityTest.php` (5 test). Total 38 test hijau; Pint clean.

## 6. Frontend & Views (Sisi Guest - ikuti design.md)

- [x] ✅ Task 6.1 - Landing/Home (hero + CTA "Pesan Sekarang", highlight tipe kamar, fasilitas) (Selesai)
  > `HomeController@home` + `home.blade.php`: hero section bg-primary + CTA, highlight 4 tipe kamar (card), fasilitas hotel (4 kartu ikon). Route `/` kini ke HomeController.
  > File: `app/Http/Controllers/HomeController.php`, `resources/views/home.blade.php`, `routes/web.php`
- [x] ✅ Task 6.2 - Halaman daftar kamar (grid card + filter: tanggal, tipe, harga) (Selesai)
  > `RoomController@index` + `rooms/index.blade.php`: grid card kamar + sidebar filter (check-in/out, tipe, harga maks). Filter tanggal memakai scope `availableBetween`. Test `test_rooms_index_filters_by_availability`.
  > File: `app/Http/Controllers/RoomController.php`, `resources/views/rooms/index.blade.php`
- [x] ✅ Task 6.3 - Halaman detail kamar (galeri foto, deskripsi, fasilitas, form booking) (Selesai)
  > `RoomController@show` + `rooms/show.blade.php`: foto/gambar kamar, deskripsi, fasilitas, form pilih tanggal → `bookings.create`.
  > File: `app/Http/Controllers/RoomController.php`, `resources/views/rooms/show.blade.php`
- [x] ✅ Task 6.4 - Halaman form booking/checkout (ringkasan pesanan, data tamu) (Selesai)
  > `BookingController@create` terima `?check_in=&check_out=` + hitung malam & estimasi total; `booking/create.blade.php` = ringkasan pesanan + data tamu (readonly) + tombol konfirmasi. Test `test_booking_form_shows_summary_for_authenticated_user`.
  > File: `app/Http/Controllers/BookingController.php`, `resources/views/booking/create.blade.php`
- [x] ✅ Task 6.5 - Halaman riwayat booking (list + badge status + tombol batalkan) (Selesai)
  > `booking/index.blade.php` & `booking/show.blade.php` pakai komponen `x-status-badge` (label & warna sesuai status, mengikuti palet design.md).
  > File: `resources/views/booking/index.blade.php`, `resources/views/booking/show.blade.php`, `resources/views/components/status-badge.blade.php`
- [x] ✅ Task 6.6 - Halaman login/register (form terpusat) (Selesai)
  > Sudah disediakan Breeze (`layouts/guest.blade.php` — kartu terpusat di tengah layar). Test `test_login_and_register_pages_render`.
  > File: `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`, `resources/views/layouts/guest.blade.php`

Tambahan: layout guest public dipindah ke `resources/views/components/layouts/public.blade.php` agar bisa dipakai `<x-layouts.public>` (layout admin juga pindah ke `components/layouts/admin.blade.php`). `tests/Feature/PublicPagesTest.php` (7 test). Total 45 test hijau.

## 7. Frontend & Views (Sisi Admin - ikuti design.md)

- [x] ✅ Task 7.1 - Dashboard overview (card statistik: total booking, kamar tersedia, revenue) (Selesai)
  > `AdminDashboardController@index` + `admin/dashboard.blade.php`: 4 kartu statistik (Total Booking, Booking Pending, Kamar Tersedia/total, Revenue Bulan Ini) + Total User.
  > File: `app/Http/Controllers/Admin/AdminDashboardController.php`, `resources/views/admin/dashboard.blade.php`
- [x] ✅ Task 7.2 - CRUD Manajemen Kamar (data table + form tambah/edit/hapus, upload foto) (Selesai)
  > `admin/rooms/{index,create,edit}.blade.php`: data table (nomor, tipe, status badge, foto, aksi), form tambah/edit dengan upload foto (disk public `rooms/`), hapus diblokir jika ada booking aktif. Storage link dibuat.
  > File: `resources/views/admin/rooms/index.blade.php`, `create.blade.php`, `edit.blade.php`
- [x] ✅ Task 7.3 - CRUD Manajemen Tipe Kamar (data table + form) (Selesai)
  > `admin/room-types/{index,create,edit}.blade.php`: table (nama, kapasitas, harga, jumlah kamar, aksi) + form; hapus diblokir jika masih punya kamar.
  > File: `resources/views/admin/room-types/index.blade.php`, `create.blade.php`, `edit.blade.php`
- [x] ✅ Task 7.4 - CRUD Manajemen Booking (filter status, konfirmasi/tolak, cek-in/cek-out) (Selesai)
  > `admin/bookings/index.blade.php`: table booking (tamu, kamar, tanggal, total, badge status) + filter status (dropdown auto-submit) + update status (pending→confirmed/cancelled, confirmed→checked_in/cancelled, checked_in→checked_out). Controller kirim `$nextStatuses`.
  > File: `resources/views/admin/bookings/index.blade.php`, `app/Http/Controllers/Admin/AdminBookingController.php`
- [x] ✅ Task 7.5 - CRUD Manajemen User/Tamu (Selesai)
  > `admin/users/{index,edit}.blade.php`: table user (nama, email, role badge, terdaftar, aksi) + form edit (nama/email/role). Hapus diri sendiri diblokir.
  > File: `resources/views/admin/users/index.blade.php`, `edit.blade.php`
- [x] ✅ Task 7.6 - Halaman Laporan (Chart.js: booking per bulan, occupancy, revenue) (Selesai)
  > Chart.js diinstall (`npm install chart.js`), `resources/js/charts.js` (bar chart booking + line chart revenue, data via data-attribute), ditambah ke input Vite. `admin/reports/index.blade.php` + occupancy rate. `sum(fn)` dengan Closure di query builder diganti pakai collection.
  > File: `resources/views/admin/reports/index.blade.php`, `resources/js/charts.js`, `vite.config.js`, `app/Http/Controllers/Admin/AdminReportController.php`
- [x] ✅ Task 7.7 - Badge status booking (warna sesuai status) + toast notifikasi CRUD (Selesai)
  > `x-status-badge` dipakai di semua table admin; toast flash success/error (auto-hide 4 detik via Alpine) ditambahkan di `components/layouts/admin.blade.php`.
  > File: `resources/views/components/status-badge.blade.php`, `resources/views/components/layouts/admin.blade.php`

Tambahan: `tests/Feature/AdminTest.php` (12 test: dashboard, akses role 403, CRUD room/room-type/user, update status booking, proteksi hapus). Total 56 test hijau; Pint clean.

## 8. JavaScript & Realtime Features / Client-side Logic

- [x] ✅ Task 8.1 - Filter kamar di sisi client (Alpine.js) — tanggal, tipe, harga (Selesai)
  > `rooms/index.blade.php` di-refactor jadi single Alpine component: filter sidebar (`x-model` pada tanggal/tipe/harga) memfilter kartu secara live tanpa reload. Data kamar + `availability` (rentang booking aktif per kamar) dikirim via `@json` dari `RoomController`; pengecekan overlap tanggal dilakukan client-side. Reset via Alpine.
  > File: `resources/views/rooms/index.blade.php`, `app/Http/Controllers/RoomController.php`
- [x] ✅ Task 8.2 - Modal/form interaktif admin (tambah/edit) tanpa reload (Selesai)
  > CRUD Tipe Kamar diubah jadi modal Alpine (tambah/edit) + `fetch` FormData dengan CSRF, tanpa reload; validasi error 422 ditampilkan inline; delete via `fetch DELETE` + konfirmasi; tabel di-update reaktif + toast. `AdminRoomTypeController` kini mendukung respons JSON (`wantsJson`). Kamar/booking/user tetap halaman penuh (pattern yang sama bisa diterapkan).
  > File: `resources/views/admin/room-types/index.blade.php`, `app/Http/Controllers/Admin/AdminRoomTypeController.php`
- [x] ✅ Task 8.3 - Chart.js untuk grafik laporan admin (Selesai di Task 7.6)
  > `npm install chart.js` + `resources/js/charts.js` (bar & line chart) masuk input Vite; dipakai `admin/reports/index.blade.php`.
- [x] ✅ Task 8.4 - Toast/alert notifikasi aksi CRUD (sukses/gagal) (Selesai di Task 7.7)
  > Flash session (layout admin & public) + toast modal di `admin/room-types/index.blade.php` (auto-hide 4 detik).

Tambahan: test `test_admin_can_create_room_type_via_json_for_modal`, `test_admin_room_type_json_delete_blocked_when_has_rooms`, `test_rooms_index_exposes_client_side_filter_data`. Total 59 test hijau; Pint clean.

## 9. Testing & QA

- [x] ✅ Task 9.1 - Test validasi FR-01 (tanggal check-in/out) (Selesai)
  > `BookingTest::test_check_in_must_be_before_check_out` (dibuat saat Task 4.1).
- [x] ✅ Task 9.2 - Test pencegahan double booking (FR-02) (Selesai)
  > `BookingTest::test_prevents_double_booking_for_overlapping_dates` & `test_room_is_available_for_non_overlapping_dates` (Task 4.2).
- [x] ✅ Task 9.3 - Test CRUD admin (kamar, tipe kamar, booking) (Selesai)
  > `tests/Feature/AdminTest.php` (14 test: CRUD room/room-type/user, update status booking, proteksi hapus, endpoint JSON modal).
- [x] ✅ Task 9.4 - Test alur user: registrasi, login, booking, lihat riwayat (Selesai)
  > Breeze `Auth/*` (registrasi, login, logout, verifikasi, password) + `BookingTest` (buat booking, cancel, lihat riwayat) + `PublicPagesTest`.
- [x] ✅ Task 9.5 - Test pembatasan akses role (guest vs admin) (Selesai)
  > `SecurityTest` (5 test) + `AdminTest::test_guest_cannot_access_admin_pages` (403 untuk semua halaman admin).
- [x] ✅ Task 9.6 - Jalankan seluruh test suite (`composer test`) hingga hijau (Selesai)
  > **59 test hijau** (assertions 147); Pint clean.

## 10. Cleanup, Scripts & Scheduler

- [x] ✅ Task 10.1 - Pint formatting (`vendor/bin/pint`) (Selesai)
  > `vendor/bin/pint --test` pass (0 file perlu perbaikan).
- [x] ✅ Task 10.2 - Cleanup kode & hapus skeleton default yang tidak terpakai (Selesai)
  > Dihapus `resources/views/dashboard.blade.php` (digantikan `admin/dashboard.blade.php`) & `resources/views/welcome.blade.php` (route `/` kini `home.blade.php`). File Breeze lain (`layouts/app.blade.php`, `layouts/navigation.blade.php`, `layouts/guest.blade.php`, komponen form) **dipertahankan** karena masih dipakai halaman auth & profile.
- [ ] ~~Task 10.3 - (Opsional) Scheduler: auto-update status booking checked-in/out~~ (Skip — opsional, tidak diminta PRD; dicatat di Known Issues)

## 11. Deployment & Documentation

- [x] ✅ Task 11.1 - Update README (setup, command, struktur proyek) (Selesai)
  > `README.md` ditulis ulang: fitur guest & admin, persyaratan, instalasi (`composer setup`), akun seed, command, struktur proyek, status booking.
  > File: `README.md`
- [x] ✅ Task 11.2 - Persiapan production (env production, asset build) (Selesai)
  > Asset production dibuild (`npm run build`). Ditambahkan bagian "Deployment (Production)" di README (config:cache, route:cache, view:cache, migrate --force, storage:link).
  > File: `README.md`

## 12. Final Polish & Bug Fixes

- [x] ✅ Task 12.1 - Review responsivitas (guest & admin di mobile) (Selesai)
  > Review semua view: tabel admin pakai `overflow-x-auto` (scroll horizontal di mobile), grid responsif (`sm:`/`lg:`/`xl:`), sidebar admin ada toggle + overlay mobile, navbar public sticky responsif, form max-w-xl.
- [x] ✅ Task 12.2 - Review konsistensi desain (spacing, warna, komponen) (Selesai)
  > Semua halaman memakai token design.md (`primary`, `ink`, `muted`, `surface`, `success`, `warning`, `danger`), konsisten `rounded-lg` + `border-gray-200` + `shadow-sm`, spacing `gap-6`/`p-5`/`mb-6`.
- [x] ✅ Task 12.3 - Bug fix & final QA menyeluruh (Selesai)
  > Fix: `sum(Closure)` di query builder → collection (`AdminReportController`); helper `actingAsAdmin()`; asersi path upload foto. Verifikasi akhir: 59 test hijau, Pint clean, `npm run build` sukses, tidak ada referensi ke view skeleton yang dihapus.
- [x] ✅ Task 12.4 - Fix posisi & warna dot slider hero di home + tampilkan gambar kamar (Selesai)
  > Dot slider dipindah dari overlay dalam hero ke bawah section hero (dipusatkan), warna diubah jadi abu-abu (`gray-300`/`gray-600`). Fix scope `x-data` Alpine (hero + dots satu wrapper) + rebuild asset karena CSS build stale sehingga class abu-abu baru tidak ter-generate. Gambar kamar di home diambil dari room pertama per tipe yang punya image (karena `room_types` tak punya kolom image); hanya tipe Standard yang tampil gambar (room 101/102).
  > File: `resources/views/home.blade.php`, `app/Http/Controllers/HomeController.php`

---

## Known Issues

- Driver `pdo_sqlite` & `sqlite3` tidak aktif di PHP (`C:\php-8.4.12\php.ini`) sehingga test gagal `could not find driver` — sudah diperbaiki dengan uncomment `extension=pdo_sqlite` & `extension=sqlite3` di php.ini. Test suite (`composer test`) hijau (25 tests).
- Task 10.3 (Scheduler auto-update status booking) di-skip — bersifat opsional dan tidak diminta di PRD. Status booking hanya berubah via aksi manual (admin/guest).

<!-- Format update: tandai selesai dengan [x], tambahkan ✅, dan catat file yang dibuat/diubah di bawah task -->
