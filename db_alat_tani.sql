-- Database for Sistem Peminjaman Alat Pertanian
-- Import ke phpMyAdmin: Buat DB 'alat_tani', pilih file ini, import.
-- Asumsi: localhost, user root, no password (XAMPP default)

CREATE DATABASE IF NOT EXISTS alat_tani CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE alat_tani;

-- Tabel Users: admin & penyewa (user)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Kategori Alat
CREATE TABLE kategori (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL
);

-- Tabel Alat Pertanian
CREATE TABLE alat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    kategori_id INT,
    kondisi ENUM('Baik', 'Rusak', 'Maintenance') DEFAULT 'Baik',
    foto VARCHAR(255), -- path seperti 'uploads/foto1.jpg'
    stok INT DEFAULT 0,
    harga_sewa_per_hari DECIMAL(10,2) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL
);

-- Tabel Peminjaman/Sewa
CREATE TABLE peminjaman (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    alat_id INT NOT NULL,
    tgl_mulai DATE NOT NULL,
    tgl_selesai DATE NOT NULL,
    tgl_kembali_actual DATE NULL, -- diisi saat dikembalikan
    total_hari INT NOT NULL, -- calculated: DATEDIFF(tgl_selesai, tgl_mulai) + 1
    total_biaya DECIMAL(10,2) NOT NULL, -- total_hari * harga_sewa_per_hari
    denda DECIMAL(10,2) DEFAULT 0, -- auto-calc jika telat: (days_late * harga * 0.1)
    status ENUM('booking', 'dipinjam', 'dikembalikan', 'rejected') DEFAULT 'booking',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (alat_id) REFERENCES alat(id) ON DELETE CASCADE
);

-- Insert default admin (password: admin123 hashed)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); -- password_hash('admin123', PASSWORD_DEFAULT)

-- Sample data + expanded to 5 categories
INSERT INTO kategori (nama) VALUES 
('Traktor'), ('Cultivator'), ('Pompa Air'), ('Panen'), ('Irigasi');

INSERT INTO alat (nama, kategori_id, foto, stok, harga_sewa_per_hari, deskripsi) VALUES 
-- Existing 3
('Traktor Mini', 1, NULL, 5, 50000.00, 'Traktor untuk lahan kecil'),
('Cultivator Listrik', 2, NULL, 3, 25000.00, 'Alat pengaduk tanah'),
('Pompa Air Diesel', 3, NULL, 2, 30000.00, 'Pompa air bertenaga diesel'),
-- New 17 tools for total 20
('Sprayer Manual', 4, NULL, 8, 15000.00, 'Semprot pestisida manual'),
('Genset 1PK', 5, NULL, 4, 40000.00, 'Generator listrik kecil untuk irigasi'),
('Cangkul Besi Besar', 1, NULL, 10, 5000.00, 'Cangkul besi untuk penggalian'),
('Combine Harvester Mini', 4, NULL, 1, 120000.00, 'Panen gabah otomatis mini'),
('Pompa Air Listrik', 5, NULL, 6, 20000.00, 'Pompa air tenaga listrik'),
('Parang Tajam', 1, NULL, 12, 3000.00, 'Parang untuk memangkas rumput'),
('Mesin Pencabut Rumput', 2, NULL, 4, 35000.00, 'Weeder mekanik'),
('Traktor Besar', 1, NULL, 2, 80000.00, 'Traktor untuk lahan luas'),
('Rice Transplanter', 4, NULL, 3, 60000.00, 'Penanam padi otomatis'),
('Hand Tiller', 2, NULL, 7, 18000.00, 'Cultivator tangan'),
('Solar Pump', 5, NULL, 1, 45000.00, 'Pompa tenaga surya'),
('Sabit Panen', 4, NULL, 15, 4000.00, 'Sabit untuk panen padi'),
('Rotary Tiller', 2, NULL, 5, 28000.00, 'Rotator tanah bertenaga'),
('Water Tanker', 5, NULL, 3, 25000.00, 'Tangki air mobile'),
('Mattock', 1, NULL, 9, 7000.00, 'Kapak gali multifungsi'),
('Grain Dryer', 4, NULL, 2, 55000.00, 'Pengering gabah'),
('Drip Irrigation Kit', 5, NULL, 6, 22000.00, 'Sistem irigasi tetes'),
('Power Weeder', 2, NULL, 4, 32000.00, 'Pencabut rumput bertenaga');

-- Indexes untuk performa
CREATE INDEX idx_user_role ON users(role);
CREATE INDEX idx_alat_stok ON alat(stok);
CREATE INDEX idx_peminjaman_status ON peminjaman(status);
CREATE INDEX idx_peminjaman_user ON peminjaman(user_id);

-- Update for ACC system: Add 'rejected' status
ALTER TABLE peminjaman MODIFY COLUMN status ENUM('booking', 'dipinjam', 'dikembalikan', 'rejected') DEFAULT 'booking';

-- Tabel Settings
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    value VARCHAR(255) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Default settings
INSERT INTO settings (name, value) VALUES 
('max_days', '30'),
('denda_per_day', '10000'),
('approval_required', '1');

-- Tabel Log Aktivitas
CREATE TABLE log_aktivitas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_data JSON,
    new_data JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- End of script
-- Setelah import: Jalankan XAMPP Apache + MySQL, akses localhost/phpmyadmin, login root.

