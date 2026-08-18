# Desain UI/UX – Sistem Reservasi Hotel

## 1. Prinsip Desain
- **Clean & minimalis** — hindari elemen berlebihan, fokus ke konten (foto kamar, info booking)
- **Konsisten** — spacing, warna, dan komponen dipakai berulang, bukan ad-hoc per halaman
- **Beda gaya per zona**: sisi publik (guest) = visual/marketing-driven; sisi admin = data-driven & efisien

## 2. Design System

### 2.1 Palet Warna
| Peran | Warna | Contoh Hex |
|---|---|---|
| Primary (aksen) | Navy / Emerald / Gold (pilih salah satu sesuai branding hotel) | `#0F3D3E` (emerald tua) |
| Background | Putih / Abu sangat muda | `#FFFFFF`, `#F8F9FA` |
| Text utama | Abu gelap (bukan hitam pekat) | `#1F2937` |
| Text sekunder | Abu medium | `#6B7280` |
| Success (booking confirmed) | Hijau | `#22C55E` |
| Warning (pending) | Kuning/oranye | `#F59E0B` |
| Danger (cancelled) | Merah | `#EF4444` |

### 2.2 Tipografi
- Font: **Inter** atau **Poppins** (netral, mudah dibaca, gratis di Google Fonts)
- Heading: bold, ukuran besar khusus di landing page (hero text)
- Body: regular, ukuran 14-16px

### 2.3 Komponen Utama
- Card kamar (foto, nama tipe kamar, harga/malam, tombol "Lihat Detail")
- Badge status booking (warna sesuai status: pending/confirmed/checked-in/cancelled)
- Form input dengan label jelas + validasi inline (merah kalau error)
- Sidebar navigasi admin (ikon + label, collapsible)
- Data table admin (sortable, dengan aksi edit/hapus per baris)
- Toast/alert notifikasi (sukses/gagal aksi CRUD)

## 3. Struktur Halaman

### 3.1 Sisi Guest (Public)
| Halaman | Elemen Utama |
|---|---|
| **Landing/Home** | Hero section (foto hotel besar + CTA "Pesan Sekarang"), highlight tipe kamar, fasilitas hotel, testimoni (opsional) |
| **Daftar Kamar** | Grid card kamar + filter sidebar (tanggal, tipe, harga) |
| **Detail Kamar** | Galeri foto, deskripsi, fasilitas, kalender ketersediaan, form booking |
| **Form Booking/Checkout** | Ringkasan pesanan, input data tamu, tombol konfirmasi |
| **Riwayat Booking (Login)** | List booking dengan status, tombol batalkan (jika masih bisa) |
| **Login/Register** | Form simple, terpusat di tengah layar |

### 3.2 Sisi Admin (Dashboard)
| Halaman | Elemen Utama |
|---|---|
| **Dashboard/Overview** | Card statistik (total booking, kamar tersedia, revenue bulan ini), grafik occupancy |
| **Manajemen Kamar** | Data table + tombol tambah/edit/hapus, form modal atau halaman terpisah |
| **Manajemen Tipe Kamar** | Data table CRUD sederhana |
| **Manajemen Booking** | Data table dengan filter status, aksi konfirmasi/tolak/update status |
| **Manajemen User** | Data table user terdaftar |
| **Laporan** | Grafik (Chart.js) — booking per bulan, revenue, occupancy rate |

## 4. Layout

- **Guest**: navbar atas (logo, menu, tombol login) + footer (kontak, alamat hotel)
- **Admin**: sidebar kiri (fixed) + topbar (nama admin, notifikasi) + konten utama di kanan
- Responsive: sidebar admin collapse jadi hamburger di mobile; grid kamar jadi 1 kolom di mobile

## 5. Rekomendasi Tools
- **Tailwind CSS** — utility-first, cepat buat styling konsisten
- **Alpine.js / Livewire** — buat interaktivitas ringan (filter, modal) tanpa perlu SPA penuh
- **Chart.js** — grafik laporan admin
- Ikon: **Heroicons** atau **Lucide** (cocok dengan ekosistem Tailwind)

## 6. Catatan
Desain ini prioritasnya fungsional dan cepat dikerjakan untuk kebutuhan tugas/proyek, bukan pixel-perfect branding hotel komersial. Kalau ada logo/warna khas hotel yang mau dipakai, tinggal sesuaikan di bagian palet warna.