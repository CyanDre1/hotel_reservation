PRD – Sistem Reservasi Hotel Berbasis Web 

1. Overview
Sistem reservasi hotel berbasis web yang memungkinkan tamu melakukan pemesanan kamar secara online, sekaligus menyediakan admin dashboard untuk mengelola data kamar, pemesanan, dan pengguna secara CRUD (Create, Read, Update, Delete). Dibangun dengan framework Laravel.

2. Latar Belakang & Tujuan
Mempermudah proses reservasi kamar hotel tanpa perlu datang langsung atau telepon.
Memberi admin hotel kontrol penuh atas data kamar, tipe kamar, ketersediaan, dan status booking.
Mengurangi kesalahan pencatatan manual (double booking, data tamu hilang, dsb).
3. Target Pengguna
Role	Deskripsi
Guest / Tamu	Pengunjung publik yang mencari & memesan kamar
Admin	Mengelola data kamar, tipe kamar, booking, dan user
(Opsional) Staff/Resepsionis	Role turunan admin, akses terbatas ke check-in/out
4. Ruang Lingkup (Scope)
4.1 Fitur Sisi User (Guest)
Registrasi & login (Laravel Breeze/Fortify/Jetstream)
Lihat daftar kamar + filter (tipe kamar, harga, tanggal ketersediaan)
Detail kamar (foto, fasilitas, harga, deskripsi)
Form reservasi (pilih tanggal check-in/out, jumlah tamu)
Cek ketersediaan kamar secara real-time (validasi tanggal bentrok)
Riwayat booking pribadi (status: pending, confirmed, checked-in, checked-out, cancelled)
Pembatalan booking (dengan aturan/kebijakan tertentu)
Notifikasi email konfirmasi booking (opsional, pakai Laravel Mail)
(Opsional) Integrasi payment gateway (Midtrans/Xendit) untuk pembayaran DP/full
4.2 Fitur Admin Dashboard (CRUD)
CRUD Tipe Kamar: nama, deskripsi, harga per malam, kapasitas, fasilitas
CRUD Kamar: nomor kamar, tipe kamar, status (tersedia/maintenance), upload foto
CRUD Booking/Reservasi: lihat semua booking, ubah status, konfirmasi/tolak, cek-in/cek-out manual
CRUD User/Tamu: kelola data user terdaftar
Manajemen Fasilitas Hotel (opsional): amenities, layanan tambahan
Laporan/Report: jumlah booking per periode, occupancy rate, pendapatan (grafik sederhana, bisa pakai Chart.js)
Manajemen Role/Permission (Admin vs Staff) — opsional pakai Spatie Laravel-Permission
5. Functional Requirements
Kode	Requirement
FR-01	Sistem harus memvalidasi tanggal check-in tidak boleh setelah check-out
FR-02	Sistem harus mencegah double booking pada kamar & tanggal yang sama
FR-03	Admin harus bisa CRUD data kamar, tipe kamar, dan booking
FR-04	User harus bisa melihat status booking miliknya
FR-05	Sistem mengirim notifikasi (email/in-app) saat status booking berubah
FR-06	Admin dapat melihat dashboard ringkasan (total booking, kamar tersedia, revenue)
6. Non-Functional Requirements
Security: hashing password (bcrypt), CSRF protection (default Laravel), validasi input server-side, role-based access control
Performance: query kamar tersedia harus efisien (index pada kolom tanggal & kamar_id)
Usability: responsive design (Tailwind/Bootstrap), dashboard admin intuitif (bisa pakai template AdminLTE / Filament / Livewire+Tailwind)
Maintainability: struktur MVC standar Laravel, gunakan Eloquent ORM & Form Request untuk validasi
7. Tech Stack Usulan
Backend: Laravel 11.x
Frontend: Blade + Tailwind CSS (atau Laravel Livewire/Filament kalau mau dashboard admin cepat jadi)
Database: MySQL
Auth: Laravel Breeze
Storage: Laravel Storage (untuk foto kamar)
Optional: Spatie Laravel-Permission (role), Midtrans/Xendit (payment)
8. Struktur Database (ERD Ringkas)

users

id, name, email, password, role, created_at

room_types

id, name, description, price_per_night, capacity, created_at

rooms

id, room_type_id (FK), room_number, status, image, created_at

bookings

id, user_id (FK), room_id (FK), check_in, check_out, total_price, status, created_at

booking_details (opsional kalau mau multi-room per booking)

id, booking_id (FK), room_id (FK), price
9. User Flow Singkat
Guest browse kamar → pilih tanggal → cek ketersediaan
Guest isi form booking → submit → status "pending"
Admin verifikasi booking di dashboard → ubah status jadi "confirmed"
Saat tamu datang → admin update status "checked-in"
Saat tamu pulang → admin update status "checked-out"
10. Milestone / Timeline Usulan
Fase	Deliverable	Estimasi
1	Setup project, auth, struktur DB	1 minggu
2	CRUD kamar & tipe kamar (admin)	1 minggu
3	Fitur booking sisi user + validasi bentrok tanggal	1-2 minggu
4	Dashboard admin (booking management, report)	1 minggu
5	Testing, polish UI, deployment	1 minggu
11. Kriteria Sukses
Admin bisa CRUD kamar/tipe kamar/booking tanpa error
User bisa booking dan tidak terjadi double booking
Dashboard admin menampilkan data real-time yang akurat