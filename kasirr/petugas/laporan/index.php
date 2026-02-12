<?php 
// ===== AUTENTIKASI & SETUP =====
session_start();
include '../../main/connect.php';

// Redirect jika belum login
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// ===== FILTER TANGGAL =====
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';

// ===== QUERY DATA =====
// Buat kondisi WHERE berdasarkan filter
$where = "";
if($tgl_mulai != '' && $tgl_selesai != '') {
    $where = " WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'";
}

// Hitung total omset dan jumlah transaksi
$summary = mysqli_query($conn, "SELECT SUM(TotalHarga) as total, COUNT(*) as jml FROM penjualan $where");
$ds = mysqli_fetch_assoc($summary);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Kasir SUSPUK</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* ===== RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ===== LAYOUT DASAR ===== */
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f7fa;
            color: #2d3748;
        }

        .container-fluid {
            padding: 1.5rem;
            max-width: 1400px;
        }

        /* ===== HEADER ===== */
        .header {
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #718096;
            font-size: 0.95rem;
        }

        /* Tombol cetak */
        .btn-print {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .btn-print:hover {
            background: #5568d3;
            transform: translateY(-1px);
            color: white;
        }

        /* ===== CARD FILTER ===== */
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        /* Focus state input */
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        /* Tombol filter */
        .btn-filter {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-filter:hover {
            background: #5568d3;
        }

        /* Tombol reset */
        .btn-reset {
            background: white;
            border: 1px solid #e2e8f0;
            color: #718096;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        /* ===== STATISTIK CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #718096;
            font-weight: 600;
        }

        /* Icon dalam stat card */
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-icon.revenue {
            background: #f0fdf4;
            color: #48bb78;
        }

        .stat-icon.transaction {
            background: #eef2ff;
            color: #667eea;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a202c;
        }

        .stat-subtext {
            font-size: 0.85rem;
            color: #a0aec0;
            margin-top: 0.25rem;
        }

        /* ===== TABEL CARD ===== */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Wrapper tabel dengan scroll */
        .table-responsive {
            max-height: 600px;
            overflow: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Header tabel dengan sticky position */
        thead th {
            background: #f7fafc;
            padding: 1rem;
            text-align: left;
            font-size: 0.8rem;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 2px solid #e2e8f0;
        }

        /* Cell tabel */
        tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f7fafc;
        }

        /* Hover effect baris */
        tbody tr:hover {
            background: #f7fafc;
        }

        /* Nomor urut */
        .row-number {
            font-weight: 600;
            color: #a0aec0;
        }

        /* Info tanggal */
        .date-info {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.25rem;
        }

        .time-info {
            font-size: 0.85rem;
            color: #718096;
        }

        .customer-name {
            font-weight: 600;
            color: #1a202c;
        }

        .price {
            font-weight: 700;
            font-size: 1.1rem;
            color: #48bb78;
        }

        /* ===== TOMBOL DETAIL ===== */
        .btn-detail {
            background: #eef2ff;
            color: #667eea;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-detail:hover {
            background: #667eea;
            color: white;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: #e2e8f0;
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            color: #718096;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #a0aec0;
        }

        /* ===== PRINT STYLES ===== */
        .print-header,
        .print-footer {
            display: none;
        }

        @media print {
            /* Sembunyikan elemen no-print */
            .no-print { 
                display: none !important; 
            }
            
            body { 
                background: white !important; 
            }
            
            /* Hilangkan shadow saat print */
            .table-card,
            .stat-card,
            .filter-card {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
            }
            
            /* Tampilkan header print */
            .print-header {
                display: block;
                text-align: center;
                padding: 2rem 0;
                border-bottom: 2px solid #667eea;
                margin-bottom: 2rem;
            }

            .print-header h2 {
                font-size: 1.75rem;
                color: #1a202c;
                margin-bottom: 0.5rem;
            }

            .print-header p {
                color: #718096;
            }

            /* Tampilkan footer print */
            .print-footer {
                display: block;
                margin-top: 3rem;
                text-align: right;
                padding: 2rem;
            }

            .print-footer p {
                margin-bottom: 0.5rem;
            }

            /* Space untuk tanda tangan */
            .print-signature {
                margin-top: 3rem;
                font-weight: 700;
                color: #1a202c;
                border-top: 2px solid #1a202c;
                display: inline-block;
                padding-top: 0.5rem;
            }
        }

        /* ===== SCROLLBAR ===== */
        .table-responsive::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f7fafc;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .header h1 { 
                font-size: 1.5rem; 
            }
            
            .stats-grid { 
                grid-template-columns: 1fr; 
            }
            
            .stat-value { 
                font-size: 1.5rem; 
            }
            
            thead th, tbody td { 
                padding: 0.75rem; 
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
        
        <div class="flex-fill">
            <div class="container-fluid">
                
                <!-- ===== HEADER ===== -->
                <div class="header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h1><i class="fas fa-chart-line me-2"></i>Laporan Penjualan</h1>
                            <p>Analisis performa penjualan toko Anda</p>
                        </div>
                        <div class="col-md-6 text-end mt-3 mt-md-0">
                            <button class="btn-print no-print" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Cetak Laporan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ===== FILTER CARD ===== -->
                <div class="filter-card no-print">
                    <form method="GET">
                        <div class="row g-3">
                            <!-- Input tanggal mulai -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-calendar-day me-2"></i>Dari Tanggal
                                </label>
                                <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
                            </div>
                            
                            <!-- Input tanggal selesai -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-calendar-check me-2"></i>Sampai Tanggal
                                </label>
                                <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
                            </div>
                            
                            <!-- Tombol filter dan reset -->
                            <div class="col-md-4">
                                <label class="form-label d-block">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn-filter">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                    <a href="index.php" class="btn-reset">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- ===== STATISTIK RINGKASAN ===== -->
                <div class="stats-grid">
                    <!-- Card Total Pendapatan -->
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Total Pendapatan</span>
                            <div class="stat-icon revenue">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="stat-value">Rp <?= number_format($ds['total'] ?? 0, 0, ',', '.') ?></div>
                        <div class="stat-subtext">Omset periode ini</div>
                    </div>

                    <!-- Card Total Transaksi -->
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Total Transaksi</span>
                            <div class="stat-icon transaction">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="stat-value"><?= $ds['jml'] ?></div>
                        <div class="stat-subtext">Pesanan terlayani</div>
                    </div>
                </div>

                <!-- ===== TABEL LAPORAN ===== -->
                <div class="table-card">
                    
                    <!-- Header khusus untuk print -->
                    <div class="print-header">
                        <h2><i class="fas fa-store me-2"></i>LAPORAN PENJUALAN KASIR SUSPUK</h2>
                        <p>Periode: <?= ($tgl_mulai ?: 'Semua Data') ?> s/d <?= ($tgl_selesai ?: 'Sekarang') ?></p>
                    </div>

                    <!-- Wrapper tabel responsive -->
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th width="180">Waktu Transaksi</th>
                                    <th>Pelanggan</th>
                                    <th width="180" class="text-end">Total Bayar</th>
                                    <th width="120" class="text-center no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                
                                // Query data penjualan dengan JOIN pelanggan
                                $query = mysqli_query($conn, "SELECT * FROM penjualan JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID $where ORDER BY TanggalPenjualan DESC");
                                
                                // Cek jika ada data
                                if(mysqli_num_rows($query) > 0) {
                                    // Loop data transaksi
                                    while($d = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <!-- Nomor urut -->
                                    <td class="row-number"><?= str_pad($no++, 2, "0", STR_PAD_LEFT) ?></td>
                                    
                                    <!-- Waktu transaksi -->
                                    <td>
                                        <div class="date-info"><?= date('d M Y', strtotime($d['TanggalPenjualan'])) ?></div>
                                        <div class="time-info">
                                            <i class="fas fa-clock me-1"></i><?= date('H:i', strtotime($d['TanggalPenjualan'])) ?> WIB
                                        </div>
                                    </td>
                                    
                                    <!-- Nama pelanggan -->
                                    <td>
                                        <div class="customer-name"><?= htmlspecialchars($d['NamaPelanggan']) ?></div>
                                    </td>
                                    
                                    <!-- Total bayar -->
                                    <td class="text-end">
                                        <div class="price">Rp <?= number_format($d['TotalHarga'], 0, ',', '.') ?></div>
                                    </td>
                                    
                                    <!-- Tombol detail (disembunyikan saat print) -->
                                    <td class="text-center no-print">
                                        <a href="detail.php?id=<?= $d['PenjualanID'] ?>" class="btn-detail">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    // Tampilkan empty state jika tidak ada data
                                ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <h4>Tidak Ada Data</h4>
                                            <p>Tidak ada transaksi pada periode yang dipilih</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer khusus untuk print -->
                    <div class="print-footer">
                        <p style="color: #718096;">Dicetak pada: <strong><?= date('d/m/Y H:i') ?> WIB</strong></p>
                        <br><br><br>
                        <!-- Space untuk tanda tangan -->
                        <p class="print-signature">( ____________________ )</p>
                        <p style="color: #718096; font-weight: 600;">Admin Kasir SUSPUK</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>