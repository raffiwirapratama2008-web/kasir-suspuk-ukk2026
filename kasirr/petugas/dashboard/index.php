<?php
// ===== AUTENTIKASI & SETUP =====
session_start();
include '../../main/connect.php';

// Cek login
if ($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Proteksi: hanya role petugas yang boleh akses
if ($_SESSION['role'] != 'petugas') {
    header("location:../../admin/dashboard/index.php");
}

// Data user dan tanggal
$username = $_SESSION['username'];
date_default_timezone_set('Asia/Jakarta');
$tgl_hari_ini = date('Y-m-d');

// ===== QUERY DATA STATISTIK =====

// Hitung total transaksi hari ini
$query_trx = mysqli_query($conn, "SELECT COUNT(*) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
$data_trx = mysqli_fetch_assoc($query_trx);

// Hitung total penjualan hari ini
$query_harian = mysqli_query($conn, "SELECT SUM(TotalHarga) as total_hari FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
$data_harian = mysqli_fetch_assoc($query_harian);
$total_hari = $data_harian['total_hari'] ?? 0;

// Hitung produk dengan stok rendah (< 10)
$stok_low = mysqli_query($conn, "SELECT COUNT(*) as limit_stok FROM produk WHERE Stok < 10");
$d_stok = mysqli_fetch_assoc($stok_low);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Kasir SUSPUK</title>
    
    <!-- Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ===== RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ===== LAYOUT DASAR ===== */
        body {
            background: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #2d3748;
        }

        /* ===== WELCOME CARD ===== */
        .welcome-card {
            background: linear-gradient(135deg, #a78847 0%, #e2b45f 100%);
            padding: 2rem;
            border-radius: 16px;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Badge status sistem */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Dot hijau untuk status online */
        .status-dot {
            width: 8px;
            height: 8px;
            background: #48bb78;
            border-radius: 50%;
        }

        .welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            font-size: 1rem;
            opacity: 0.95;
        }

        /* Tombol mulai transaksi */
        .btn-start {
            background: white;
            color: #667eea;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
            color: #667eea;
        }

        /* ===== STATISTIK CARDS ===== */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid;
        }

        /* Border warna untuk tiap card */
        .stat-card.primary { border-left-color: #667eea; }
        .stat-card.success { border-left-color: #48bb78; }
        .stat-card.warning { border-left-color: #f59e0b; }

        /* Icon dalam stat card */
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stat-card.primary .stat-icon { background: #667eea; }
        .stat-card.success .stat-icon { background: #48bb78; }
        .stat-card.warning .stat-icon { background: #f59e0b; }

        .stat-label {
            font-size: 0.875rem;
            color: #718096;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a202c;
        }

        .stat-value small {
            font-size: 0.875rem;
            font-weight: 500;
            color: #a0aec0;
            margin-left: 0.25rem;
        }

        /* ===== TABEL TRANSAKSI ===== */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: between;
            align-items: center;
            background: #f7fafc;
        }

        .table-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1a202c;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Badge tanggal */
        .date-badge {
            background: #eff6ff;
            color: #1e40af;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .transaction-table {
            width: 100%;
            margin: 0;
        }

        /* Header tabel */
        .transaction-table thead {
            background: #f7fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .transaction-table th {
            padding: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        /* Baris tabel */
        .transaction-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s;
        }

        .transaction-table tbody tr:hover {
            background: #f7fafc;
        }

        .transaction-table td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }

        /* Badge waktu */
        .time-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            color: #1e40af;
        }

        .customer-name {
            font-weight: 600;
            color: #1a202c;
            font-size: 0.95rem;
        }

        .price-text {
            font-weight: 700;
            color: #059669;
            font-size: 1rem;
        }

        /* Tombol detail */
        .btn-detail {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            font-weight: 600;
            font-size: 0.875rem;
            color: #4a5568;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-detail:hover {
            background: #667eea;
            border-color: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* ===== AKSES CEPAT ===== */
        .quick-actions-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-action-item {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.2s;
            text-decoration: none;
            color: #2d3748;
            display: block;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .quick-action-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            color: #2d3748;
        }

        .quick-action-item i {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            display: block;
        }

        .quick-action-title {
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* ===== TIPS CARD ===== */
        .tips-card {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 1.5rem;
            border-radius: 12px;
            margin-top: 1.5rem;
        }

        .tips-header {
            font-weight: 700;
            color: #92400e;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tips-content {
            color: #78350f;
            font-size: 0.9375rem;
            line-height: 1.6;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #a0aec0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .welcome-card {
                padding: 1.5rem;
            }

            .welcome-title {
                font-size: 1.5rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .transaction-table th,
            .transaction-table td {
                padding: 0.875rem 0.75rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>

        <div class="container-fluid p-4">
            
            <!-- ===== WELCOME SECTION ===== -->
            <div class="welcome-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <!-- Status badge -->
                        <div class="status-badge">
                            <span class="status-dot"></span>
                            <span>SISTEM ONLINE</span>
                        </div>
                        
                        <!-- Welcome message -->
                        <h1 class="welcome-title">Selamat Datang, <?= htmlspecialchars($username); ?>!</h1>
                        <p class="welcome-subtitle">
                            <i class="fas fa-chart-line me-2"></i>
                            Dashboard siap membantu mengelola transaksi dengan efisien
                        </p>
                    </div>
                    
                    <!-- Tombol mulai transaksi -->
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="../penjualan/index.php" class="btn-start">
                            <i class="fas fa-shopping-cart"></i>
                            Mulai Transaksi
                        </a>
                    </div>
                </div>
            </div>

            <!-- ===== STATISTIK CARDS ===== -->
            <div class="stats-container">
                
                <!-- Card Transaksi Hari Ini -->
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="stat-label">Transaksi Hari Ini</div>
                    <div class="stat-value">
                        <?= $data_trx['total']; ?>
                        <small>Transaksi</small>
                    </div>
                </div>

                <!-- Card Total Penjualan -->
                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-label">Total Penjualan</div>
                    <div class="stat-value" style="font-size: 1.5rem;">
                        Rp <?= number_format($total_hari, 0, ',', '.'); ?>
                    </div>
                </div>

                <!-- Card Produk Hampir Habis -->
                <div class="stat-card warning">
                    <div class="stat-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="stat-label">Produk Hampir Habis</div>
                    <div class="stat-value">
                        <?= $d_stok['limit_stok']; ?>
                        <small>Item</small>
                    </div>
                </div>
            </div>

            <!-- ===== KONTEN UTAMA ===== -->
            <div class="row">
                
                <!-- RIWAYAT TRANSAKSI -->
                <div class="col-lg-8">
                    <div class="table-card">
                        <!-- Header tabel -->
                        <div class="table-header">
                            <h5 class="table-title">
                                <i class="fas fa-history"></i>
                                Riwayat Transaksi
                            </h5>
                            <span class="date-badge">
                                <i class="far fa-calendar-alt me-2"></i>
                                <?= date('d M Y'); ?>
                            </span>
                        </div>
                        
                        <!-- Tabel -->
                        <div class="p-3">
                            <div class="table-responsive">
                                <table class="transaction-table">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Pelanggan</th>
                                            <th>Total</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query 5 transaksi terakhir hari ini
                                        $log = mysqli_query($conn, "SELECT * FROM penjualan 
                                               JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                               WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'
                                               ORDER BY PenjualanID DESC LIMIT 5");

                                        // Cek jika tidak ada data
                                        if (mysqli_num_rows($log) == 0): ?>
                                            <tr>
                                                <td colspan="4">
                                                    <div class="empty-state">
                                                        <i class="fas fa-shopping-bag"></i>
                                                        <h3>Belum Ada Transaksi</h3>
                                                        <p>Belum ada transaksi hari ini</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif;
                                        
                                        // Loop data transaksi
                                        while ($l = mysqli_fetch_array($log)): ?>
                                            <tr>
                                                <!-- Waktu -->
                                                <td>
                                                    <span class="time-badge">
                                                        <i class="far fa-clock"></i>
                                                        <?= date('H:i', strtotime($l['TanggalPenjualan'])); ?>
                                                    </span>
                                                </td>
                                                
                                                <!-- Nama Pelanggan -->
                                                <td>
                                                    <span class="customer-name">
                                                        <?= htmlspecialchars($l['NamaPelanggan']); ?>
                                                    </span>
                                                </td>
                                                
                                                <!-- Total Harga -->
                                                <td>
                                                    <span class="price-text">
                                                        Rp <?= number_format($l['TotalHarga'], 0, ',', '.'); ?>
                                                    </span>
                                                </td>
                                                
                                                <!-- Tombol Cetak -->
                                                <td class="text-center">
                                                    <a href="../penjualan/detail.php?id=<?= $l['PenjualanID']; ?>" class="btn-detail">
                                                        <i class="fas fa-print"></i>
                                                        Cetak
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AKSES CEPAT & TIPS -->
                <div class="col-lg-4">
                    
                    <!-- Akses Cepat -->
                    <h5 class="quick-actions-title">
                        <i class="fas fa-bolt"></i>
                        Akses Cepat
                    </h5>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <a href="../penjualan/index.php" class="quick-action-item">
                                <i class="fas fa-cash-register" style="color: #667eea;"></i>
                                <div class="quick-action-title">Kasir</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="../produk/index.php" class="quick-action-item">
                                <i class="fas fa-boxes" style="color: #48bb78;"></i>
                                <div class="quick-action-title">Produk</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="../laporan/index.php" class="quick-action-item">
                                <i class="fas fa-chart-bar" style="color: #a855f7;"></i>
                                <div class="quick-action-title">Laporan</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0)" onclick="confirmLogout()" class="quick-action-item">
                                <i class="fas fa-sign-out-alt" style="color: #ef4444;"></i>
                                <div class="quick-action-title">Keluar</div>
                            </a>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="tips-card">
                        <div class="tips-header">
                            <i class="fas fa-lightbulb"></i>
                            Tips Hari Ini
                        </div>
                        <p class="tips-content mb-0">
                            Pastikan untuk selalu mengecek stok produk di awal shift. Lakukan restock jika diperlukan untuk menghindari kekosongan barang.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        // Konfirmasi logout dengan SweetAlert
        function confirmLogout() {
            Swal.fire({
                title: 'Akhiri Sesi?',
                text: 'Pastikan semua transaksi sudah tersimpan dengan baik',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../../auth/logout.php";
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>