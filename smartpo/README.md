# Smartpo

Smartpo adalah aplikasi MVC PHP sederhana untuk membantu proses pre-order produk.

## Konsep
- Penjual (role `admin`) dapat menambahkan produk.
- Penjual menentukan harga, stok, lokasi, dan minimal hari pengantaran.
- Pembeli (role `user`) dapat melihat produk dari banyak penjual.
- Dalam satu pesanan, pembeli hanya dapat memilih produk dari satu penjual.
- Dalam pesanan tersebut pembeli dapat memilih beberapa produk berbeda.
- Pembeli memilih tanggal pengantaran sesuai minimal hari yang ditentukan penjual.
- Stok otomatis berkurang ketika pesanan berhasil dibuat.

## Instalasi
1. Jalankan Laragon dan MySQL.
2. Buat/import database menggunakan file `smartpo.sql`.
3. Letakkan folder `smartpo` di `C:\laragon\www\`.
4. Buka `http://localhost/smartpo/`.
5. Daftar sebagai Penjual untuk menambah produk atau Pembeli untuk memesan.

## Struktur database
### pengguna
Menyimpan akun dan role pengguna.
### produk
Menyimpan produk milik penjual, harga, stok, minimal hari, dan lokasi.
### pesanan
Menyimpan pembeli, penjual, beberapa produk dalam satu pesanan, total, tanggal antar, dan status.

## Catatan
Role `admin` digunakan sebagai Penjual agar tetap memenuhi kebutuhan dashboard `admin & user` pada tugas sekolah.
