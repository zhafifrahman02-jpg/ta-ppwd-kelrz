CREATE DATABASE IF NOT EXISTS data_pesanan;
USE data_pesanan;


DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS pesanan;


CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);


INSERT INTO admin (username, password) VALUES ('admin', MD5('admin123'));


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

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    no_hp   VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);