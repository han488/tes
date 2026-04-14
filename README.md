# Sistem Peminjaman Alat Pertanian - UKK PHP Native

Aplikasi web lengkap dengan PHP Native + MySQL + Bootstrap 5. Fitur: CRUD alat, sewa dengan kalkulasi, dashboard admin/user, keamanan session/password_hash.

## Setup & Testing (XAMPP)

1. **Start XAMPP**: Apache + MySQL.

2. **Import Database**:
   - Buka http://localhost/phpmyadmin
   - Buat database `alat_tani`
   - Import `db_alat_tani.sql` (sudah include sample data + admin default).

3. **Buat folder uploads** (manual atau via file explorer):
   ```
   mkdir "c:/xampp1/htdocs/alat tani 2/uploads"
   ```
   Set permission write (untuk foto alat).

4. **Akses Aplikasi**:
   - http://localhost/alat%20tani%202/index.php
   - **Admin**: username `admin`, pass `admin123`
   - Register user baru via register.php

5. **Testing Fitur** (Updated with 20 tools + full CRUD):
   - **Admin Login**: admin/admin123 → Dashboard → Manajemen Alat (20 tools loaded).
   - **CRUD Alat**: Tambah new → Edit (populate auto + re-upload foto, keep old if no new) → Hapus.
   - **Image Upload**: uploads/ folder ready; test upload/view in list/dashboard.
   - **User Dashboard**: Lihat 20 alat (stok>0 shown), history pinjam.
   - **Pinjam**: Select alat → dates → auto calc.
   - **Keamanan**: Sessions protected.

6. **Image Upload Guide**:
   - Admin → alat.php → Tambah/Edit → Pilih file image → Submit → appears in uploads/, shown as thumbnail.
   - Path: uploads/alat_[timestamp].[ext]; old replaced on edit if new uploaded.

6. **Extend**:
   - Tambah admin approve peminjaman → update status 'dipinjam', kurangi stok.
   - Form kembali alat + calc denda (if tgl_kembali > tgl_selesai: days * harga * 10%).
   - Pagination/search di list.

## Struktur File
```
.
├── config/koneksi.php      # PDO connection
├── db_alat_tani.sql        # Schema + samples
├── index.php               # Login
├── register.php            # Register user
├── logout.php              # Logout
├── admin/
│   ├── dashboard.php       # Stats
    ├── alat.php            # CRUD alat + foto
    └── users.php           # CRUD users
└── user/
    ├── dashboard.php       # Alat & history
    └── pinjam.php          # Form sewa + calc
└── uploads/                # Foto alat
└── TODO.md                 # Progress
```

Kode bersih dengan komentar, responsif, siap presentasi UKK. Selamat!

**Note**: Pastikan URL browser encode space '%20' jika perlu.

