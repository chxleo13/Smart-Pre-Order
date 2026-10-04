<?php
include 'Models/process.php';
$produk = mysqli_query(connect(), "SELECT produk.*, pengguna.username AS penjual FROM produk JOIN pengguna ON produk.penjual_id = pengguna.id WHERE produk.stock > 0 ORDER BY produk.id DESC LIMIT 6");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smartpo - Pre-order Produk</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;font-family:Arial,sans-serif}body{background:#f4fff7;color:#184d2a}nav{padding:18px 8%;display:flex;justify-content:space-between;align-items:center;background:#fff;box-shadow:0 3px 15px #0001;position:sticky;top:0;z-index:5}.logo{font-size:26px;font-weight:bold;color:#2e8b57}.menu{display:flex;gap:12px;align-items:center}.menu a{text-decoration:none;color:#245c36;font-weight:bold}.btn{padding:10px 18px;border-radius:10px;background:#43a047;color:#fff!important}.btn.light{background:#55c98a}.hero{padding:80px 8%;min-height:470px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,#dfffe8,#9de5b5)}.hero-text{max-width:650px}.hero h1{font-size:52px;line-height:1.1;margin-bottom:18px}.hero h1 span{color:#2e8b57}.hero p{font-size:18px;line-height:1.7;color:#476852;margin-bottom:25px}.hero-icon{font-size:150px}.section{padding:60px 8%}.title{text-align:center;margin-bottom:35px}.title h2{font-size:34px}.title p{color:#68806e;margin-top:8px}.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}.card{background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 7px 22px #0001}.card-top{padding:28px;background:#dcf8e4;font-size:55px;text-align:center}.card-body{padding:20px}.card-body h3{margin-bottom:8px}.card-body p{color:#617669;font-size:14px;line-height:1.5;margin:6px 0}.price{font-weight:bold;color:#2e8b57;font-size:18px;margin:12px 0}footer{background:#174d24;color:#fff;text-align:center;padding:30px}@media(max-width:800px){.hero{flex-direction:column;text-align:center;gap:30px}.cards{grid-template-columns:1fr}.hero h1{font-size:40px}.menu a:first-child{display:none}}
</style></head>
<body>
<nav><div class="logo">🛍️ Smartpo</div><div class="menu"><a href="#produk">Produk</a><a href="Views/sign_up.php" class="btn light">Daftar</a><a href="Views/login.php" class="btn">Login</a></div></nav>
<section class="hero"><div class="hero-text"><h1>Pesan Produk Jadi <span>Lebih Teratur.</span></h1><p>Smartpo membantu pembeli melakukan pre-order dari berbagai penjual. Lihat stok, lokasi penjual, dan minimal waktu pengantaran sebelum memesan.</p><a href="Views/login.php" class="btn">Mulai Memesan</a></div><div class="hero-icon">📦</div></section>
<section class="section" id="produk"><div class="title"><h2>Produk Tersedia</h2><p>Pembeli hanya dapat menggabungkan beberapa produk dari penjual yang sama dalam satu pesanan.</p></div><div class="cards">
<?php if($produk && mysqli_num_rows($produk)>0): while($p=mysqli_fetch_assoc($produk)): ?>
<div class="card"><div class="card-top">🛍️</div><div class="card-body"><h3><?= e($p['nama_produk']) ?></h3><p>Penjual: <?= e($p['penjual']) ?></p><p>Stok: <?= (int)$p['stock'] ?> | Minimal antar: <?= (int)$p['minimal_hari'] ?> hari</p><p>Lokasi: <?= e($p['lokasi']) ?></p><div class="price">Rp <?= number_format($p['harga'],0,',','.') ?></div></div></div>
<?php endwhile; else: ?><p>Belum ada produk. Silakan daftar sebagai penjual untuk menambahkan produk.</p><?php endif; ?></div></section>
<footer><h2>🛍️ Smartpo</h2><p>Platform sederhana untuk mengatur pre-order dan stok produk.</p><br><p>© 2026 Smartpo</p></footer>
</body></html>
