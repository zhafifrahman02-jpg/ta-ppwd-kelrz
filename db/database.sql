-- 1. Membuat Database (jika belum ada)
CREATE DATABASE IF NOT EXISTS data_pesanan;
USE data_pesanan;

-- 2. Menghapus tabel lama jika sudah ada (mencegah error #1050)
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS pesanan;

-- 3. Membuat ulang tabel admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 4. Memasukkan data admin default
-- Password: admin123 (terenkripsi MD5)
INSERT INTO admin (username, password) VALUES ('admin', MD5('admin123'));

-- 5. Membuat ulang tabel pesanan
CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telepon VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL,
    kategori_pengguna VARCHAR(50),
    paket VARCHAR(20),
    jumlah_unit INT DEFAULT 1,
    catatan TEXT,
    total_bayar BIGINT DEFAULT 0,
    status VARCHAR(30) DEFAULT 'Belum Dikonfirmasi',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    no_hp VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);