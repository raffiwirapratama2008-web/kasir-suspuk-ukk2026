<?php
// ===== AUTENTIKASI & SETUP =====
session_start();
include '../../main/connect.php';

// Redirect jika belum login
if ($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Ambil parameter filter tanggal dari URL
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Kasir SUSPUK</title>

    <!-- CSS Libraries -->
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

        /* ===== HEADER HALAMAN ===== */
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #718096;
            font-size: 0.95rem;
        }

        /* ===== TOMBOL CETAK ===== */
        .btn-print {
            background: #1a202c;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-print:hover {
            background: #2d3748;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 32, 44, 0.3);
        }

        /* ===== CARD FILTER ===== */
        .filter-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .filter-title {
            font-weight: 600;
            font-size: 1rem;
            color: #1a202c;
            margin-bottom: 0.25rem;
        }

        .filter-subtitle {
            font-size: 0.875rem;
            color: #718096;
            margin-bottom: 1rem;
        }

        /* ===== FORM INPUT ===== */
        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        /* Focus state untuk input */
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* ===== TOMBOL FILTER ===== */
        .btn-filter {
            background: #48bb78;
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .btn-filter:hover {
            background: #38a169;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
        }

        /* Tombol reset */
        .btn-reset {
            background: white;
            color: #4a5568;
            border: 1px solid #e2e8f0;
            padding: 0.625rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
            transform: translateY(-2px);
        }

        /* ===== STATISTIK CARDS ===== */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
        }

        /* Border warna untuk tiap card */
        .stat-card.revenue {
            border-left-color: #48bb78;
        }

        .stat-card.transaction {
            border-left-color: #4299e1;
        }

        .stat-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }

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

        .stat-card.revenue .stat-icon {
            background: #48bb78;
        }

        .stat-card.transaction .stat-icon {
            background: #4299e1;
        }

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
            margin-bottom: 0.5rem;
        }

        /* Badge dalam stat card */
        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .badge-success {
            background: #f0fdf4;
            color: #166534;
        }

        .badge-info {
            background: #eff6ff;
            color: #1e40af;
        }

        /* ===== CARD UTAMA ===== */
        .main-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* ===== TABEL ===== */
        .table-wrapper {
            overflow-x: auto;
        }

        .report-table {
            width: 100%;
            margin: 0;
        }

        /* Header tabel */
        .report-table thead {
            background: #f7fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .report-table th {
            padding: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        /* Baris tabel */
        .report-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s;
        }

        .report-table tbody tr:hover {
            background: #f7fafc;
        }

        .report-table td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }

        /* ===== ELEMEN DALAM TABEL ===== */

        /* Nomor urut */
        .row-number {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #f7fafc;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: #667eea;
        }

        /* Info pelanggan */
        .customer-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Avatar pelanggan dengan initial */
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #eac066;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .customer-name {
            font-weight: 600;
            color: #1a202c;
            font-size: 0.95rem;
        }

        .customer-label {
            font-size: 0.8125rem;
            color: #a0aec0;
        }

        /* Info tanggal */
        .date-info {
            font-weight: 600;
            color: #1a202c;
            font-size: 0.9375rem;
            margin-bottom: 0.25rem;
        }

        .time-info {
            font-size: 0.8125rem;
            color: #718096;
        }

        /* Badge harga */
        .price-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 8px;
            font-weight: 600;
            color: #166534;
            font-size: 0.9375rem;
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

        /* ===== EMPTY STATE (Tidak ada data) ===== */
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

        /* ===== PRINT STYLES ===== */
        .print-header {
            display: none;
        }

        /* Aturan saat mencetak */
        @media print {

            /* Sembunyikan elemen dengan class no-print */
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .container-fluid {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .main-card {
                box-shadow: none !important;
                border: none !important;
            }

            /* Tampilkan header khusus print */
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 3rem;
                padding-bottom: 2rem;
                border-bottom: 3px solid #48bb78;
            }

            .print-header h1 {
                font-size: 2rem;
                font-weight: 700;
                color: #1a202c;
                margin-bottom: 0.5rem;
            }

            .print-header h4 {
                font-size: 1.25rem;
                color: #667eea;
                font-weight: 400;
            }

            /* Hilangkan hover effect saat print */
            .report-table tbody tr:hover {
                background: transparent;
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .filter-card {
                padding: 1.25rem;
            }

            .report-table th,
            .report-table td {
                padding: 0.875rem 0.75rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar (disembunyikan saat print) -->
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>

        <div class="container-fluid p-4">

            <!-- ===== HEADER HALAMAN ===== -->
            <div class="page-header no-print">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-chart-line me-2"></i>Laporan Penjualan
                        </h1>
                        <p class="page-subtitle mb-0">Pantau performa dan analisis penjualan</p>
                    </div>
                    <!-- Tombol cetak -->
                    <button class="btn-print" onclick="window.print()">
                        <i class="fas fa-print"></i>
                        Cetak Laporan
                    </button>
                </div>
            </div>

            <!-- ===== FILTER TANGGAL ===== -->
            <div class="filter-card no-print">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon" style="background: #eacb66; margin-bottom: 0;">
                        <i class="fas fa-filter"></i>
                    </div>
                    <div>
                        <div class="filter-title">Filter Laporan</div>
                        <div class="filter-subtitle">Pilih periode waktu untuk analisis data</div>
                    </div>
                </div>

                <!-- Form filter -->
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn-filter flex-grow-1">
                            <i class="fas fa-search me-2"></i>Terapkan Filter
                        </button>
                        <a href="index.php" class="btn-reset">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- ===== STATISTIK RINGKASAN ===== -->
            <?php
            // Buat kondisi WHERE berdasarkan filter tanggal
            $where = "";
            if ($tgl_mulai != '' && $tgl_selesai != '') {
                $where = " WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'";
            }

            // Query untuk ambil total omset dan jumlah transaksi
            $summary = mysqli_query($conn, "SELECT SUM(TotalHarga) as total, COUNT(*) as jml FROM penjualan $where");
            $ds = mysqli_fetch_assoc($summary);
            ?>

            <div class="stats-container no-print">
                <!-- Card Total Omset -->
                <div class="stat-card revenue">
                    <div class="stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-label">Total penjualan</div>
                    <div class="stat-value">Rp <?= number_format($ds['total'] ?? 0, 0, ',', '.'); ?></div>
                    <span class="stat-badge badge-success">
                        <i class="fas fa-arrow-trend-up"></i>
                        Penjualan
                    </span>
                </div>

                <!-- Card Volume Transaksi -->
                <div class="stat-card transaction">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-label">Total Transaksi</div>
                    <div class="stat-value"><?= number_format($ds['jml']); ?></div>
                    <span class="stat-badge badge-info">
                        <i class="fas fa-check-circle"></i>
                        Transaksi
                    </span>
                </div>
            </div>

            <!-- ===== TABEL LAPORAN ===== -->
            <div class="main-card">
                <div class="card-body p-4">

                    <!-- Header khusus untuk print -->
                    <div class="print-header">
                        <h1>LAPORAN PENJUALAN</h1>
                        <h4>KASIR SUSPUK</h4>
                        <div class="mt-3 mx-auto" style="width: 120px; height: 4px; background: #eac066; border-radius: 4px;"></div>

                        <!-- Info periode -->
                        <?php if ($tgl_mulai != ''): ?>
                            <p class="mt-4 mb-0 fw-bold" style="font-size: 1rem;">
                                Periode: <?= date('d M Y', strtotime($tgl_mulai)) ?> — <?= date('d M Y', strtotime($tgl_selesai)) ?>
                            </p>
                        <?php else: ?>
                            <p class="mt-4 mb-0 fw-bold" style="font-size: 1rem;">Periode: Semua Waktu</p>
                        <?php endif; ?>

                        <small class="text-muted d-block mt-2">Tanggal Cetak: <?= date('d/m/Y H:i'); ?> WIB</small>
                    </div>

                    <!-- Wrapper tabel responsive -->
                    <div class="table-wrapper">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th style="width: 20%;">Tanggal & Waktu</th>
                                    <th style="width: 30%;">Nama Pelanggan</th>
                                    <th class="text-end" style="width: 25%;">Total Pembayaran</th>
                                    <th class="no-print text-center" style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;

                                // Query data penjualan dengan JOIN pelanggan
                                $query = mysqli_query($conn, "SELECT * FROM penjualan 
                                         JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                         $where ORDER BY TanggalPenjualan DESC");

                                // Cek jika tidak ada data
                                if (mysqli_num_rows($query) == 0) {
                                    echo "<tr><td colspan='5'>
                                            <div class='empty-state'>
                                                <i class='fas fa-inbox'></i>
                                                <h3>Tidak Ada Data</h3>
                                                <p>Tidak ada transaksi ditemukan pada periode ini</p>
                                            </div>
                                          </td></tr>";
                                }

                                // Loop data transaksi
                                while ($d = mysqli_fetch_array($query)) {
                                    // Ambil 2 huruf pertama nama untuk avatar
                                    $initials = strtoupper(substr($d['NamaPelanggan'], 0, 2));
                                ?>
                                    <tr>
                                        <!-- Nomor urut -->
                                        <td>
                                            <span class="row-number"><?= str_pad($no++, 2, "0", STR_PAD_LEFT); ?></span>
                                        </td>

                                        <!-- Tanggal & Waktu -->
                                        <td>
                                            <div class="date-info">
                                                <i class="far fa-calendar-alt me-2 text-primary"></i>
                                                <?= date('d M Y', strtotime($d['TanggalPenjualan'])); ?>
                                            </div>
                                            <div class="time-info">
                                                <i class="far fa-clock me-1"></i>
                                                <?= date('H:i', strtotime($d['TanggalPenjualan'])); ?> WIB
                                            </div>
                                        </td>

                                        <!-- Info Pelanggan -->
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-avatar"><?= $initials; ?></div>
                                                <div>
                                                    <div class="customer-name"><?= htmlspecialchars($d['NamaPelanggan']); ?></div>
                                                    <div class="customer-label">Pelanggan</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Total Pembayaran -->
                                        <td class="text-end">
                                            <span class="price-badge">
                                                <i class="fas fa-money-bill-wave"></i>
                                                Rp <?= number_format($d['TotalHarga'], 0, ',', '.'); ?>
                                            </span>
                                        </td>

                                        <!-- Tombol Detail (disembunyikan saat print) -->
                                        <td class="no-print text-center">
                                            <a href="detail.php?id=<?= $d['PenjualanID']; ?>" class="btn-detail">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer untuk print (tanda tangan) -->
                    <div class="d-none d-print-block mt-5 pt-4 border-top">
                        <div class="row">
                            <div class="col-8">
                                <small class="text-muted d-block mb-1">Catatan:</small>
                                <small class="text-muted">Dokumen ini dicetak secara otomatis dari sistem Kasir SUSPUK</small>
                            </div>
                            <div class="col-4 text-center">
                                <p class="mb-0 fw-bold">Dicetak Oleh,</p>
                                <!-- Space untuk tanda tangan -->
                                <div style="margin-top: 60px;">
                                    <div style="border-top: 2px solid #000; display: inline-block; padding-top: 10px; min-width: 180px;">
                                        <strong><?= $_SESSION['username'] ?? 'Administrator'; ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>