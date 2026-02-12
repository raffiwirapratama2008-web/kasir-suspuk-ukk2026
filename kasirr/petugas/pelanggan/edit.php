<?php 
session_start();
include '../../main/connect.php';

if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pelanggan WHERE PelangganID='$id'"));

$update_success = false; // Variabel penanda untuk memicu popup

if(isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['NamaPelanggan']);
    $alamat = mysqli_real_escape_string($conn, $_POST['Alamat']);
    $telp = mysqli_real_escape_string($conn, $_POST['NomorTelepon']);

    $query = mysqli_query($conn, "UPDATE pelanggan SET NamaPelanggan='$nama', Alamat='$alamat', NomorTelepon='$telp' WHERE PelangganID='$id'");
    
    if($query) {
        $update_success = true; // Tandai sukses
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan - Kasir SUSPUK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-warning text-dark fw-bold py-3" style="border-radius: 15px 15px 0 0;">
                        <i class="fas fa-user-edit me-2"></i>Edit Data Pelanggan
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">NAMA PELANGGAN</label>
                                <input type="text" name="NamaPelanggan" class="form-control" value="<?= $data['NamaPelanggan']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">NOMOR TELEPON</label>
                                <input type="text" name="NomorTelepon" class="form-control" value="<?= $data['NomorTelepon']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">ALAMAT</label>
                                <textarea name="Alamat" class="form-control" rows="3" required><?= $data['Alamat']; ?></textarea>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="index.php" class="btn btn-light px-4">Batal</a>
                                <button type="submit" name="update" class="btn btn-primary px-4">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($update_success): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data pelanggan telah diperbarui.',
            showConfirmButton: false,
            timer: 2000
        }).then(function() {
            window.location.href = 'index.php';
        });
    </script>
    <?php endif; ?>
</body>
</html>