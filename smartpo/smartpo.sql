CREATE DATABASE IF NOT EXISTS smartpo;
USE smartpo;

DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS pengguna;

CREATE TABLE pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);

CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    penjual_id INT NOT NULL,
    nama_produk VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stock INT NOT NULL,
    minimal_hari INT NOT NULL,
    lokasi VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    FOREIGN KEY (penjual_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pembeli_id INT NOT NULL,
    penjual_id INT NOT NULL,
    detail_produk TEXT NOT NULL,
    total INT NOT NULL,
    tanggal_pesan DATE NOT NULL,
    tanggal_antar DATE NOT NULL,
    status VARCHAR(30) NOT NULL,
    FOREIGN KEY (pembeli_id) REFERENCES pengguna(id) ON DELETE CASCADE,
    FOREIGN KEY (penjual_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

-- Akun demo (password: 12345)
INSERT INTO pengguna (username,email,password,role) VALUES
('penjual1','penjual1@smartpo.test','$2y$10$7gJ4J9p2b4m0e5o1VYj1eOe7yZl4wXqg4c6GxG4V7e6d8f7M0sK9G','admin'),
('pembeli1','pembeli1@smartpo.test','$2y$10$7gJ4J9p2b4m0e5o1VYj1eOe7yZl4wXqg4c6GxG4V7e6d8f7M0sK9G','user');
