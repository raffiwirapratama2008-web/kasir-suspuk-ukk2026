<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$pelanggan = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pelanggan WHERE PelangganID='$id'"));
$current_dir = 'pelanggan'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Histori Belanja - <?= $pelanggan['NamaPelanggan']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        <div class="container-fluid p-4">
            <div class="mb-3">
                <a href="index.php" class="btn btn-sm btn-secondary shadow-sm"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="m-0"><i class="fas fa-history me-2"></i>Histori Transaksi: <?= $pelanggan['NamaPelanggan']; ?></h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Total Belanja</th>
                                    <th>Produk yang Dibeli</th>
                                    <th class="text-center">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query = mysqli_query($conn, "SELECT * FROM penjualan WHERE PelangganID='$id' ORDER BY TanggalPenjualan DESC");
                                if(mysqli_num_rows($query) > 0) {
                                    while($t = mysqli_fetch_array($query)){
                                        $pID = $t['PenjualanID'];
                                ?>
                                <tr>
                                    <td class="ps-4"><?= date('d M Y, H:i', strtotime($t['TanggalPenjualan'])); ?></td>
                                    <td class="fw-bold text-primary">Rp <?= number_format($t['TotalHarga']); ?></td>
                                    <td>
                                        <ul class="small mb-0 ps-3">
                                            <?php 
                                            $detail = mysqli_query($conn, "SELECT detailpenjualan.*, produk.NamaProduk 
                                                                          FROM detailpenjualan 
                                                                          JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                                                          WHERE PenjualanID='$pID'");
                                            while($d = mysqli_fetch_array($detail)){
                                                echo "<li>".$d['NamaProduk']." <span class='text-muted'>(x".$d['JumlahProduk'].")</span></li>";
                                            }
                                            ?>
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        <a href="../penjualan/detail.php?id=<?= $pID; ?>" class="btn btn-sm btn-light border text-primary">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='4' class='text-center py-5 text-muted'>Pelanggan ini belum pernah melakukan transaksi.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>