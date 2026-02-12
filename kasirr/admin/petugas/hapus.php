<?php 
session_start();
include '../../main/connect.php';

// Proteksi: Hanya admin yang boleh menghapus
if($_SESSION['role'] != "admin"){
    header("location:index.php");
    exit();
}

// Ambil ID dari URL
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Query Hapus
    $query = mysqli_query($conn, "DELETE FROM user WHERE UserID='$id'");

    if($query){
        header("location:index.php?pesan=hapus_sukses");
    } else {
        header("location:index.php?pesan=gagal");
    }
} else {
    header("location:index.php");
}
?>