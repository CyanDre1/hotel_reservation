# Task Instruction untuk OpenCode

**Proyek:** Reservasi Hotel

Saya telah menyimpan:

- Spesifikasi lengkap proyek → `prd.md`
- Panduan Design & UI/UX (jika ada) → `design.md`
- Dokumen pendukung lain (jika ada) → `[nama-file.md]`

## Instruksi Utama

1. **Selalu baca dan ikuti** semua file dokumentasi di atas sebelum mengerjakan apapun.
2. Ikuti panduan design di `design.md` secara ketat untuk semua hal yang berhubungan dengan UI/UX, warna, layout, dan komponen (skip kalau proyek non-UI, misal CLI tool / API-only / library).
3. Kerjakan task secara bertahap dan rapi, sesuai urutan kategori di tasklist.
4. Kalau ada instruksi di `prd.md`/`design.md` yang ambigu atau kontradiktif, tanya dulu sebelum lanjut — jangan asumsi sendiri.

---

## Struktur Task Checklist

Gunakan struktur kategori berikut (sesuaikan/pangkas sesuai kebutuhan proyek — nggak semua proyek butuh semua kategori):

### 1. Project Setup & Configuration
### 2. Database & Migration
### 3. Models & Relationships / Data Structures
### 4. Backend Logic & API / Core Logic
### 5. Security (Input Sanitization, Rate Limiting, Auth, XSS/CSRF Protection)
### 6. Frontend & Views (Ikuti design.md)
### 7. JavaScript & Realtime Features / Client-side Logic
### 8. Additional Features (Notifications, Sharing, Toast, System Message, dll — sesuaikan)
### 9. Testing & QA
### 10. Cleanup, Scripts & Scheduler
### 11. Deployment & Documentation
### 12. Final Polish & Bug Fixes

> Kategori 1–5 dan 11 biasanya wajib buat hampir semua jenis proyek (web, API, CLI, mobile, dll). Kategori 6–8 relevan buat proyek yang ada UI-nya. Tambah/hapus sesuai jenis proyek (misal: proyek data pipeline bisa tambah kategori "ETL & Data Validation").

## Aturan Wajib Update Tasklist (PENTING!)

Setiap kali selesai mengerjakan **satu task** atau **satu grup task**, kamu **WAJIB** melakukan langkah berikut **sebelum** melapor ke saya:

1. Update file `.agents/tasklist.md`
2. Tandai task yang selesai dengan `[x]`
3. Tambahkan emoji ✅ di depan task
4. Update progress keseluruhan proyek (contoh: `Progress: 35%`)
5. Tambahkan catatan singkat di bawah task yang selesai (file apa yang dibuat/diubah)

**Contoh format:**

```markdown
- [x] ✅ Task 2.3 - Membuat Room Migration `[Mudah]` (Selesai)
  > File: `database/migrations/xxxx_create_rooms_table.php`
```

## Aturan Tambahan (opsional, sesuaikan sesuai kebutuhan)

- Kalau ada bug atau task yang di-skip, catat di bagian `## Known Issues` di `tasklist.md`, jangan cuma dihapus diam-diam.
- Kalau butuh nambah dependency baru, sebutkan alasannya sebelum install.