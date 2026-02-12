<?php 
session_start();
include '../../main/connect.php';

if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Proses hapus - Menggunakan error suppression (@) atau check manual untuk foreign key
    $query = mysqli_query($conn, "DELETE FROM pelanggan WHERE PelangganID='$id'");

    if($query) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data pelanggan telah dihapus.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => { window.location.href = 'index.php'; });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Data tidak bisa dihapus karena masih terkait dengan transaksi!',
            }).then(() => { window.location.href = 'index.php'; });
        </script>";
    }
}
?>
</body>
</html>