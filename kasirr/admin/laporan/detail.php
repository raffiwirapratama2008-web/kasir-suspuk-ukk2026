<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM penjualan 
         JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
         WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

// Jika ID tidak ditemukan
if (!$data) {
    header("location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi #<?= $id; ?> - Kasir SUSPUK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.5);
            --primary-gradient: linear-gradient(45deg, #2ecc71, #27ae60);
        }

        body { 
            background: radial-gradient(circle at top right, #e2e8f0, #cbd5e1), 
                        linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: #1e293b; 
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        }

        .invoice-head {
            border-bottom: 2px dashed #e2e8f0;
        }

        .table-custom thead {
            background-color: #f8fafc;
        }

        .table-custom th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            border: none;
        }

        /* Hover Effect untuk Baris Tabel */
        .table-custom tbody tr:hover {
            background-color: rgba(46, 204, 113, 0.03);
        }

        .btn-back-hover:hover {
            transform: translateX(-5px);
            background-color: #f8fafc !important;
        }

        .transition-all { transition: all 0.3s ease; }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="container-fluid p-4">
            <div class="col-md-8 mx-auto">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="index.php" class="btn btn-white shadow-sm border-0 rounded-pill px-4 fw-bold text-muted btn-back-hover transition-all bg-white">
                        <i class="fas fa-chevron-left me-2"></i>Kembali
                    </a>
                    <div class="text-end">
                        <h4 class="fw-extrabold m-0 text-dark">TRX-#<?= str_pad($id, 5, "0", STR_PAD_LEFT); ?></h4>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Transaksi Selesai</span>
                    </div>
                </div>

                <div class="glass-card overflow-hidden">
                    <div style="height: 8px; background: var(--primary-gradient);"></div>

                    <div class="card-body p-4 p-md-5">
                        <div class="row invoice-head pb-4 mb-4">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <small class="text-muted d-block fw-bold mb-2">DITAGIHKAN KEPADA:</small>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold m-0 text-dark"><?= $data['NamaPelanggan']; ?></h5>
                                        <small class="text-muted">Pelanggan ID: #PLG-<?= $data['PelangganID']; ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <small class="text-muted d-block fw-bold mb-2">WAKTU TRANSAKSI:</small>
                                <h5 class="fw-bold m-0 text-dark">
                                    <i class="far fa-calendar-alt me-1 text-muted"></i> 
                                    <?= date('d F Y', strtotime($data['TanggalPenjualan'])); ?>
                                </h5>
                                <small class="text-muted"><i class="far fa-clock me-1"></i> Pukul <?= date('H:i', strtotime($data['TanggalPenjualan'])); ?> WIB</small>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-custom align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-0">Item Produk</th>
                                        <th class="text-center">Harga Satuan</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end pe-0">Subtotal</th>
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
                                        <td class="ps-0 py-3">
                                            <div class="fw-bold text-dark"><?= $d['NamaProduk']; ?></div>
                                            <small class="text-muted">SKU: PROD-<?= $d['ProdukID']; ?></small>
                                        </td>
                                        <td class="text-center text-muted">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-light text-dark border px-3">x<?= $d['JumlahProduk']; ?></span>
                                        </td>
                                        <td class="text-end pe-0 fw-bold text-dark">Rp <?= number_format($d['Subtotal'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-end mt-4">
                            <div class="col-md-5">
                                <div class="bg-light p-4 rounded-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subtotal Produk</span>
                                        <span class="fw-bold">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">Pajak (0%)</span>
                                        <span class="fw-bold text-success">Rp 0</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-extrabold text-dark">TOTAL AKHIR</span>
                                        <h4 class="fw-extrabold text-success m-0">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 text-center p-3 border rounded-4 border-dashed bg-white bg-opacity-50">
                            <p class="small text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i> Terima kasih telah berbelanja di <strong>Kasir SUSPUK</strong>. 
                                Simpan bukti transaksi digital ini sebagai alat penukaran atau retur barang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>