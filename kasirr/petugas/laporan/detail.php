<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM penjualan 
           JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
           WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card-invoice { border-radius: 15px; border: none; overflow: hidden; }
        .line-dashed { border-top: 2px dashed #dee2e6; margin: 20px 0; }
        
        @media print {
            .no-print, .sidebar { display: none !important; }
            body { background-color: white; }
            .container-fluid { padding: 0 !important; }
            .card { box-shadow: none !important; border: none !important; }
            .mx-auto { width: 100% !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>
        
        <div class="container-fluid p-4">
            <div class="card shadow-sm card-invoice col-md-7 mx-auto">
                <div class="card-header bg-white py-4 px-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold m-0 text-primary">INVOICE</h4>
                        <small class="text-muted">ID Transaksi: #<?= $id; ?></small>
                    </div>
                    <div class="no-print">
                        <button onclick="window.print()" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-2">
                            <i class="fas fa-print me-1"></i> Cetak
                        </button>
                        <a href="index.php" class="btn btn-dark btn-sm rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-6">
                            <h6 class="text-muted small fw-bold text-uppercase">Tujuan Bayar:</h6>
                            <h5 class="fw-bold m-0"><?= $data['NamaPelanggan']; ?></h5>
                            <p class="text-muted small">Pelanggan Umum</p>
                        </div>
                        <div class="col-6 text-end">
                            <h6 class="text-muted small fw-bold text-uppercase">Tanggal:</h6>
                            <h5 class="fw-bold m-0"><?= date('d/m/Y', strtotime($data['TanggalPenjualan'])); ?></h5>
                            <small class="text-muted"><?= date('H:i', strtotime($data['TanggalPenjualan'])); ?> WIB</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="bg-light">
                                <tr class="text-muted small">
                                    <th class="ps-3">PRODUK</th>
                                    <th class="text-center">HARGA</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-end pe-3">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $detail = mysqli_query($conn, "SELECT * FROM detailpenjualan 
                                          JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                          WHERE PenjualanID = '$id'");
                                while($d = mysqli_fetch_array($detail)){
                                ?>
                                <tr>
                                    <td class="ps-3 fw-bold"><?= $d['NamaProduk']; ?></td>
                                    <td class="text-center text-muted small">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border fw-normal px-2"><?= $d['JumlahProduk']; ?></span>
                                    </td>
                                    <td class="text-end pe-3 fw-bold">Rp <?= number_format($d['Subtotal'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="line-dashed"></div>

                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Belanja</span>
                                <span class="fw-bold text-dark">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <span class="h6 fw-bold m-0">TOTAL AKHIR</span>
                                <span class="h4 fw-bold text-success m-0">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-center d-none d-print-block">
                        <p class="small text-muted italic">"Terima kasih telah berbelanja di Kasir SUSPUK!"</p>
                        <div class="mt-4 row">
                            <div class="col-6">
                                <small>Pelanggan</small><br><br><br>
                                <strong>( <?= $data['NamaPelanggan']; ?> )</strong>
                            </div>
                            <div class="col-6">
                                <small>Kasir</small><br><br><br>
                                <strong>( <?= $_SESSION['username'] ?? 'Admin'; ?> )</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>