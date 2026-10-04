<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function connect(){
    $conn = mysqli_connect("localhost", "root", "", "smartpo");
    if (!$conn) die("Koneksi database gagal: " . mysqli_connect_error());
    mysqli_set_charset($conn, "utf8mb4");
    return $conn;
}
function cek_data_post($jenis){ return $_POST[$jenis] ?? ''; }
function cek_data_get($jenis){ return $_GET[$jenis] ?? ''; }
function e($data){ return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8'); }
function require_login(){ if (!isset($_SESSION['sesi'])) { header('Location: ../login.php?status=no_akun'); exit; } }
function require_role($role){ require_login(); if (($_SESSION['role'] ?? '') !== $role) { header('Location: ../login.php?status=role'); exit; } }

function register_user(){
    $conn=connect();
    $username=trim(cek_data_post('user')); $email=trim(cek_data_post('email')); $pass=cek_data_post('pass'); $role=cek_data_post('role');
    if (!in_array($role,['admin','user'],true)) $role='user';
    $cek=$conn->prepare("SELECT id FROM pengguna WHERE username=? OR email=?"); $cek->bind_param('ss',$username,$email); $cek->execute();
    if($cek->get_result()->num_rows>0){ header('Location: sign_up.php?status=ada'); exit; }
    $hash=password_hash($pass,PASSWORD_DEFAULT); $q=$conn->prepare("INSERT INTO pengguna (username,email,password,role) VALUES (?,?,?,?)"); $q->bind_param('ssss',$username,$email,$hash,$role); $q->execute();
    header('Location: login.php?status=daftar'); exit;
}
function login_user(){
    $conn=connect(); $username=trim(cek_data_post('username')); $pass=cek_data_post('password');
    $q=$conn->prepare("SELECT * FROM pengguna WHERE username=?"); $q->bind_param('s',$username); $q->execute(); $data=$q->get_result()->fetch_assoc();
    if(!$data || !password_verify($pass,$data['password'])) { header('Location: login.php?status=salah'); exit; }
    $_SESSION['sesi']=$data['username']; $_SESSION['id']=$data['id']; $_SESSION['role']=$data['role'];
    if($data['role']==='admin') header('Location: admin/index.php'); else header('Location: user/index.php'); exit;
}
function tambah_produk(){
    require_role('admin'); $conn=connect();
    $nama=trim(cek_data_post('nama_produk')); $harga=(int)cek_data_post('harga'); $stock=(int)cek_data_post('stock'); $min=(int)cek_data_post('minimal_hari'); $lokasi=trim(cek_data_post('lokasi')); $deskripsi=trim(cek_data_post('deskripsi'));
    if($harga<0||$stock<0||$min<0) die('Data angka tidak valid.');
    $q=$conn->prepare("INSERT INTO produk (penjual_id,nama_produk,harga,stock,minimal_hari,lokasi,deskripsi) VALUES (?,?,?,?,?,?,?)"); $q->bind_param('isiisss',$_SESSION['id'],$nama,$harga,$stock,$min,$lokasi,$deskripsi); $q->execute();
    header('Location: ../Views/admin/index.php?status=produk'); exit;
}
function buat_pesanan(){
    require_role('user');
    $conn=connect();
    $seller=(int)cek_data_post('penjual_id');
    $tanggal=cek_data_post('tanggal_antar');
    $jumlah=$_POST['jumlah'] ?? [];
    $items=[]; $total=0; $maxMin=0;

    foreach($jumlah as $productId=>$qty){
        $qty=(int)$qty; $productId=(int)$productId;
        if($qty<=0) continue;
        $q=$conn->prepare("SELECT * FROM produk WHERE id=? AND penjual_id=?");
        $q->bind_param('ii',$productId,$seller); $q->execute();
        $p=$q->get_result()->fetch_assoc();
        if(!$p) continue;
        if($qty>$p['stock']){ header('Location: ../Views/user/index.php?status=stock'); exit; }
        $maxMin=max($maxMin,(int)$p['minimal_hari']);
        $items[]=['id'=>(int)$p['id'],'nama'=>$p['nama_produk'],'jumlah'=>$qty,'harga'=>(int)$p['harga']];
        $total += $qty*(int)$p['harga'];
    }

    if(empty($items)){ header('Location: ../Views/user/index.php?status=kosong'); exit; }
    $minimalDate=date('Y-m-d',strtotime('+'.$maxMin.' days'));
    if($tanggal < $minimalDate){ header('Location: ../Views/user/index.php?status=min&hari='.$maxMin); exit; }

    $conn->begin_transaction();
    try{
        foreach($items as $item){
            $q=$conn->prepare("UPDATE produk SET stock=stock-? WHERE id=? AND penjual_id=? AND stock>=?");
            $q->bind_param('iiii',$item['jumlah'],$item['id'],$seller,$item['jumlah']);
            $q->execute();
            if($q->affected_rows!==1) throw new Exception('Stok berubah.');
        }
        $detail=json_encode($items,JSON_UNESCAPED_UNICODE);
        $status='Menunggu';
        $q=$conn->prepare("INSERT INTO pesanan (pembeli_id,penjual_id,detail_produk,total,tanggal_pesan,tanggal_antar,status) VALUES (?,?,?,?,CURDATE(),?,?)");
        $q->bind_param('iisiss',$_SESSION['id'],$seller,$detail,$total,$tanggal,$status);
        $q->execute();
        $conn->commit();
        header('Location: ../Views/user/index.php?status=pesan'); exit;
    }catch(Exception $e){
        $conn->rollback();
        header('Location: ../Views/user/index.php?status=gagal'); exit;
    }
}

if(isset($_POST['dor'])){
    if($_POST['dor']==='Log In') login_user();
    elseif($_POST['dor']==='Daftar') register_user();
    elseif($_POST['dor']==='Tambah Produk') tambah_produk();
    elseif($_POST['dor']==='Buat Pesanan') buat_pesanan();
}
?>
