<?php 
include '../../main/connect.php';
$id    = $_POST['ProdukID'];
$nama  = $_POST['NamaProduk'];
$harga = $_POST['Harga'];
$stok  = $_POST['Stok'];

$query = mysqli_query($conn, "UPDATE produk SET NamaProduk='$nama', Harga='$harga', Stok='$stok' WHERE ProdukID='$id'");

if($query) {
    header("location:index.php?pesan=update");
} else {
    header("location:index.php?pesan=gagal");
}
?>