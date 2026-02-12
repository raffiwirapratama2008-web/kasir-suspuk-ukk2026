<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$query_p = mysqli_query($conn, "SELECT * FROM pelanggan WHERE PelangganID='$id'");
$pelanggan = mysqli_fetch_array($query_p);
$current_dir = 'pelanggan'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Histori - <?= $pelanggan['NamaPelanggan']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card { border-radius: 15px; }
        .invoice-btn { border-radius: 8px; transition: 0.3s; }
        .invoice-btn:hover { background-color: #0d6efd; color: white !important; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        <div class="container-fluid p-4">
            <div class="mb-4">
                <a href="index.php" class="btn btn-white shadow-sm btn-sm px-3 border"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-history me-2"></i>Histori Belanja: <?= $pelanggan['NamaPelanggan']; ?></h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">TANGGAL</th>
                                    <th>TOTAL TRANSAKSI</th>
                                    <th>RINCIAN PRODUK</th>
                                    <th class="text-center">NOTA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT * FROM penjualan WHERE PelangganID='$id' ORDER BY TanggalPenjualan DESC";
                                $query = mysqli_query($conn, $sql);
                                if(mysqli_num_rows($query) > 0) {
                                    while($t = mysqli_fetch_array($query)){
                                        $pID = $t['PenjualanID'];
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold"><?= date('d M Y', strtotime($t['TanggalPenjualan'])); ?></div>
                                        <small class="text-muted"><?= date('H:i', strtotime($t['TanggalPenjualan'])); ?> WIB</small>
                                    </td>
                                    <td class="fw-bold text-success">Rp <?= number_format($t['TotalHarga']); ?></td>
                                    <td>
                                        <div class="p-2 bg-light rounded shadow-sm" style="font-size: 0.85rem;">
                                        <?php 
                                        $detail = mysqli_query($conn, "SELECT d.*, p.NamaProduk FROM detailpenjualan d JOIN produk p ON d.ProdukID = p.ProdukID WHERE d.PenjualanID='$pID'");
                                        while($d = mysqli_fetch_array($detail)){
                                            echo "<span class='badge bg-white text-dark border me-1 mb-1'>".$d['NamaProduk']." (".$d['JumlahProduk'].")</span>";
                                        }
                                        ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="../penjualan/detail.php?id=<?= $pID; ?>" class="btn btn-sm btn-outline-primary invoice-btn">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='4' class='text-center py-5 text-muted'>Belum ada histori transaksi.</td></tr>";
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