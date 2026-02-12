<?php 
session_start();
include '../../main/connect.php';

// Proteksi Halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penjualan - Kasir Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Premium Color Palette */
            --primary: #e5bb46;
            --primary-light: #f1bf63;
            --primary-dark: #caa838;
            --secondary: #ecb848;
            --accent: #f6d25c;
            
            --success: #10b981;
            --success-light: #34d399;
            --warning: #f59e0b;
            --warning-light: #fbbf24;
            --danger: #ef4444;
            --info: #3b82f6;
            
            /* Background & Surface */
            --bg-main: #f8fafc;
            --bg-alt: #f1f5f9;
            --surface: #ffffff;
            
            /* Text Colors */
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #94a3b8;
            
            /* Border & Divider */
            --border-color: #e2e8f0;
            --divider: #cbd5e1;
            
            /* Shadows */
            --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, #ead666 0%, #a28e4b 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            
            /* Radius */
            --radius-sm: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.25rem;
            --radius-2xl: 1.5rem;
        }

        body {
            background: var(--bg-main);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, system-ui, sans-serif;
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .main-wrapper {
            max-width: 1600px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Premium Header */
        .premium-header {
            background: var(--surface);
            border-radius: var(--radius-2xl);
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .premium-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .header-grid {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .header-main h1 {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.03em;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-icon {
            font-size: 2.25rem;
        }

        .header-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 600;
        }

        /* Filter Card */
        .filter-section {
            background: var(--surface);
            border-radius: var(--radius-2xl);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .filter-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .filter-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-input {
            background: var(--bg-alt);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .form-input:focus {
            background: var(--surface);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        /* Buttons */
        .btn-primary-custom {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 0.75rem 1.5rem;
            font-size: 0.9375rem;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgb(79 70 229 / 0.3);
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgb(79 70 229 / 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: var(--bg-alt);
            color: var(--text-primary);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .btn-secondary-custom:hover {
            background: var(--surface);
            border-color: var(--text-tertiary);
            transform: translateY(-2px);
        }

        .btn-print {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 0.75rem 1.5rem;
            font-size: 0.9375rem;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgb(44 62 80 / 0.3);
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgb(44 62 80 / 0.4);
            color: white;
        }

        /* Alert Banner */
        .alert-custom {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            border: 1px solid #c7d2fe;
            border-radius: var(--radius-lg);
            padding: 1rem 1.5rem;
            color: var(--primary);
            font-size: 0.9375rem;
            font-weight: 600;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(79, 70, 229, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Table Card */
        .table-card {
            background: var(--surface);
            border-radius: var(--radius-2xl);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .table-header {
            padding: 2rem;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-bottom: 1px solid var(--border-color);
        }

        .table-header h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .table-header p {
            font-size: 0.9375rem;
            color: var(--text-secondary);
            font-weight: 600;
            margin: 0;
        }

        /* Modern Table */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modern-table thead th {
            padding: 1.25rem 2rem;
            background: var(--bg-alt);
            border-bottom: 2px solid var(--border-color);
            font-size: 0.8125rem;
            font-weight: 800;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.075em;
            text-align: left;
        }

        .modern-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.15s ease;
        }

        .modern-table tbody tr:hover {
            background: var(--bg-main);
        }

        .modern-table tbody tr:last-child {
            border-bottom: none;
        }

        .modern-table tbody td {
            padding: 1.5rem 2rem;
            vertical-align: middle;
        }

        /* Invoice Badge */
        .invoice-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            color: var(--primary);
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.9375rem;
            border: 1px solid #c7d2fe;
        }

        /* Date & Time Display */
        .transaction-date {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .transaction-date i {
            color: var(--primary);
            font-size: 0.875rem;
        }

        .transaction-time {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .transaction-time i {
            font-size: 0.75rem;
        }

        /* Customer Display */
        .customer-display {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .customer-avatar {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1rem;
        }

        .customer-details {
            flex: 1;
        }

        .customer-name {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .customer-code {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .customer-code i {
            font-size: 0.75rem;
        }

        /* Total Amount */
        .amount-display {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            border-radius: var(--radius-md);
            font-weight: 800;
            color: var(--success);
            font-size: 1.0625rem;
        }

        /* Action Buttons */
        .action-button {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            border: 2px solid var(--border-color);
            background: var(--surface);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .action-button-view {
            border-color: #c7d2fe;
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        }

        .action-button-view:hover {
            border-color: var(--primary);
            background: var(--primary);
        }

        .action-button-view:hover i {
            color: white;
        }

        .action-button-delete {
            border-color: #fecaca;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        }

        .action-button-delete:hover {
            border-color: var(--danger);
            background: var(--danger);
        }

        .action-button-delete:hover i {
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
        }

        .empty-icon {
            font-size: 5rem;
            color: var(--text-tertiary);
            margin-bottom: 2rem;
            opacity: 0.5;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }

        .empty-text {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 500;
            line-height: 1.6;
        }

        /* Print Styles */
        @media print {
            .no-print { display: none !important; }
            body { 
                background: white !important; 
            }
            .premium-header,
            .filter-section,
            .table-card {
                border: 1px solid #dee2e6;
                box-shadow: none;
                background: white;
            }
            .table-card {
                border-radius: 0;
            }
            .modern-table tbody tr:hover {
                background: transparent;
            }
        }

        .print-info {
            display: none;
        }

        @media print {
            .print-info {
                display: block;
                margin-top: 3rem;
                padding-top: 1.5rem;
                border-top: 2px solid #dee2e6;
                text-align: right;
                font-size: 0.875rem;
                color: var(--text-secondary);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-wrapper {
                padding: 1rem;
            }

            .premium-header {
                padding: 1.5rem;
            }

            .header-main h1 {
                font-size: 1.75rem;
            }

            .filter-section {
                padding: 1.5rem;
            }

            .modern-table thead th,
            .modern-table tbody td {
                padding: 1rem;
            }

            .customer-avatar {
                width: 40px;
                height: 40px;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-alt);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: var(--radius-sm);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-tertiary);
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="no-print">
        <?php include '../../template/sidebar.php'; ?>
    </div>

    <div class="main-wrapper w-100">
        
        <!-- Premium Header -->
        <div class="premium-header no-print">
            <div class="header-grid">
                <div class="header-main">
                    <h1>
                        <span class="header-icon">💳</span>
                        Data Penjualan
                    </h1>
                    <p class="header-subtitle">Kelola dan pantau riwayat transaksi toko Anda</p>
                </div>
                <?php if(isset($_GET['tgl_mulai'])): ?>
                <button onclick="window.print()" class="btn-print">
                    <i class="fas fa-print me-2"></i>Cetak Laporan
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section no-print">
            <div class="filter-title">
                <div class="filter-icon">
                    <i class="fas fa-filter"></i>
                </div>
                Filter Periode Penjualan
            </div>
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Mulai Tanggal</label>
                    <input type="date" 
                           name="tgl_mulai" 
                           class="form-control form-input" 
                           value="<?= $_GET['tgl_mulai'] ?? ''; ?>" 
                           required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" 
                           name="tgl_selesai" 
                           class="form-control form-input" 
                           value="<?= $_GET['tgl_selesai'] ?? ''; ?>" 
                           required>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn-primary-custom flex-grow-1">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                    <a href="index.php" class="btn-secondary-custom">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="table-card">
            <?php 
            $tgl_mulai = $_GET['tgl_mulai'] ?? '';
            $tgl_selesai = $_GET['tgl_selesai'] ?? '';

            if ($tgl_mulai != '' && $tgl_selesai != '') {
                $query_str = "SELECT * FROM penjualan 
                              JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                              WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'
                              ORDER BY PenjualanID DESC";
                echo "<div class='p-4 pb-0 no-print'>
                        <div class='alert-custom'>
                            <div class='alert-icon'>
                                <i class='far fa-calendar-check'></i>
                            </div>
                            <span>Menampilkan laporan penjualan: <strong>" . date('d M Y', strtotime($tgl_mulai)) . "</strong> s/d <strong>" . date('d M Y', strtotime($tgl_selesai)) . "</strong></span>
                        </div>
                      </div>";
            } else {
                $query_str = "SELECT * FROM penjualan 
                              JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                              ORDER BY PenjualanID DESC";
            }
            $sql = mysqli_query($conn, $query_str);
            ?>

            <div class="table-header">
                <h2>
                    <i class="fas fa-receipt text-warning"></i>
                    Riwayat Transaksi
                </h2>
                <p>Daftar lengkap transaksi penjualan dengan detail pelanggan</p>
            </div>

            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>No. Nota</th>
                            <th>Tanggal & Waktu</th>
                            <th>Pelanggan</th>
                            <th class="text-end">Total Pembayaran</th>
                            <th class="text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($sql) == 0){
                            echo "<tr>
                                    <td colspan='5' class='p-0'>
                                        <div class='empty-state'>
                                            <div class='empty-icon'>
                                                <i class='fas fa-inbox'></i>
                                            </div>
                                            <div class='empty-title'>Tidak ada data transaksi</div>
                                            <p class='empty-text'>
                                                Belum ada transaksi untuk periode yang dipilih.<br>
                                                Silakan sesuaikan filter tanggal atau hapus filter untuk melihat semua transaksi.
                                            </p>
                                        </div>
                                    </td>
                                  </tr>";
                        }
                        while($d = mysqli_fetch_array($sql)){
                        ?>
                        <tr>
                            <td>
                                <span class="invoice-badge">
                                    <i class="fas fa-hashtag"></i>
                                    <?= str_pad($d['PenjualanID'], 5, '0', STR_PAD_LEFT); ?>
                                </span>
                            </td>
                            <td>
                                <div class="transaction-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?= date('d M Y', strtotime($d['TanggalPenjualan'])); ?>
                                </div>
                                <div class="transaction-time">
                                    <i class="far fa-clock"></i>
                                    <?= date('H:i', strtotime($d['TanggalPenjualan'])); ?> WIB
                                </div>
                            </td>
                            <td>
                                <div class="customer-display">
                                    <div class="customer-avatar">
                                        <?= strtoupper(substr($d['NamaPelanggan'], 0, 2)); ?>
                                    </div>
                                    <div class="customer-details">
                                        <div class="customer-name"><?= $d['NamaPelanggan']; ?></div>
                                        <div class="customer-code">
                                            <i class="fas fa-id-card"></i>
                                            CL-<?= str_pad($d['PelangganID'], 4, '0', STR_PAD_LEFT); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <span class="amount-display">
                                    <i class="fas fa-money-bill-wave"></i>
                                    Rp <?= number_format($d['TotalHarga'], 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td class="text-center no-print">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="detail.php?id=<?= $d['PenjualanID']; ?>" 
                                       class="action-button action-button-view"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                    <a href="hapus.php?id=<?= $d['PenjualanID']; ?>" 
                                       class="action-button action-button-delete"
                                       onclick="return confirm('⚠️ Apakah Anda yakin ingin menghapus transaksi ini?\n\nTransaksi yang dihapus tidak dapat dikembalikan.')"
                                       title="Hapus">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Print Info -->
        <div class="print-info">
            <p class="mb-0"><strong>Dicetak pada:</strong> <?= date('d/m/Y H:i'); ?> WIB</p>
            <p class="mb-0"><strong>Dicetak oleh:</strong> <?= $_SESSION['username']; ?></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>