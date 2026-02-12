<?php
session_start();
include '../main/connect.php'; // Memanggil file koneksi yang sudah kamu buat

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$query = mysqli_query($conn, "SELECT * FROM user WHERE Username='$username' AND Password='$password'");
$cek = mysqli_num_rows($query);

if($cek > 0) {
    $data = mysqli_fetch_assoc($query);
    
    // Menyimpan data ke session
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $data['Role']; // Berisi 'admin' atau 'petugas' sesuai tabel user
    $_SESSION['status'] = "login";

    // Redirect berdasarkan Role
    if($data['Role'] == "admin") {
        header("location:../admin/dashboard/index.php");
    } else {
        header("location:../petugas/dashboard/index.php");
    }
} else {
    header("location:login.php?pesan=gagal");
}
?>