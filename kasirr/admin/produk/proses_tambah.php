<?php 
include '../../main/connect.php';
$nama  = $_POST['NamaProduk'];
$harga = $_POST['Harga'];
$stok  = $_POST['Stok'];

$query = mysqli_query($conn, "INSERT INTO produk (NamaProduk, Harga, Stok) VALUES ('$nama', '$harga', '$stok')");

if($query) {
    header("location:index.php?pesan=sukses");
} else {
    header("location:index.php?pesan=gagal");
}
?>