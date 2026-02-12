<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Hitung statistik stok
$query_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
$total_produk = mysqli_fetch_assoc($query_total)['total'];

$query_habis = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk WHERE Stok = 0");
$total_habis = mysqli_fetch_assoc($query_habis)['total'];

$query_hampir_habis = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk WHERE Stok > 0 AND Stok <= 5");
$total_hampir_habis = mysqli_fetch_assoc($query_hampir_habis)['total'];

$query_tersedia = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk WHERE Stok > 5");
$total_tersedia = mysqli_fetch_assoc($query_tersedia)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Produk - Kasir SUSPUK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f7fa;
            color: #2d3748;
        }

        .container-fluid {
            padding: 1.5rem;
            max-width: 1400px;
        }

        /* Header */
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

        /* Search */
        .search-box {
            position: relative;
            max-width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            background: white;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-left: 3px solid;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card.total { border-color: #667eea; }
        .stat-card.available { border-color: #48bb78; }
        .stat-card.low { border-color: #ed8936; }
        .stat-card.out { border-color: #f56565; }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #718096;
            font-weight: 500;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .stat-card.total .stat-icon { background: #eef2ff; color: #667eea; }
        .stat-card.available .stat-icon { background: #f0fdf4; color: #48bb78; }
        .stat-card.low .stat-icon { background: #fffaf0; color: #ed8936; }
        .stat-card.out .stat-icon { background: #fff5f5; color: #f56565; }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-responsive {
            max-height: 600px;
            overflow: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

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

        tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f7fafc;
        }

        tbody tr:hover {
            background: #f7fafc;
        }

        tbody tr.low-stock {
            background: #fffaf0;
        }

        tbody tr.out-stock {
            background: #fff5f5;
        }

        /* Badge */
        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-success {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge-warning {
            background: #feebc8;
            color: #7c2d12;
        }

        .badge-danger {
            background: #fed7d7;
            color: #742a2a;
        }

        /* Numbers */
        .price {
            font-weight: 600;
            color: #667eea;
        }

        .stock {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .stock.low { color: #f56565; }
        .stock.ok { color: #48bb78; }

        /* Scrollbar */
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

        /* Alert */
        .alert-info {
            background: #ebf8ff;
            border-left: 3px solid #4299e1;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            color: #2c5282;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header h1 { font-size: 1.5rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .stat-value { font-size: 1.5rem; }
            thead th, tbody td { padding: 0.75rem; }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="flex-fill">
            <div class="container-fluid">
                <!-- Header -->
                <div class="header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h1><i class="fas fa-boxes me-2"></i>Stok Produk</h1>
                            <p>Pantau ketersediaan produk Anda</p>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <div class="search-box ms-auto">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchInput" placeholder="Cari produk...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card total">
                        <div class="stat-header">
                            <span class="stat-label">Total Produk</span>
                            <div class="stat-icon">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="stat-value"><?= $total_produk ?></div>
                    </div>

                    <div class="stat-card available">
                        <div class="stat-header">
                            <span class="stat-label">Tersedia</span>
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="stat-value"><?= $total_tersedia ?></div>
                    </div>

                    <div class="stat-card low">
                        <div class="stat-header">
                            <span class="stat-label">Hampir Habis</span>
                            <div class="stat-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="stat-value"><?= $total_hampir_habis ?></div>
                    </div>

                    <div class="stat-card out">
                        <div class="stat-header">
                            <span class="stat-label">Stok Habis</span>
                            <div class="stat-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                        <div class="stat-value"><?= $total_habis ?></div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table id="productTable">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th>Nama Produk</th>
                                    <th width="150">Harga</th>
                                    <th width="100">Stok</th>
                                    <th width="150">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $sql = mysqli_query($conn, "SELECT * FROM produk ORDER BY NamaProduk ASC");
                                while($d = mysqli_fetch_array($sql)){
                                    $row_class = "";
                                    $stock_class = "ok";
                                    $status_badge = "success";
                                    $status_icon = "check-circle";
                                    $status_text = "Tersedia";
                                    
                                    if($d['Stok'] <= 0) {
                                        $row_class = "out-stock";
                                        $stock_class = "low";
                                        $status_badge = "danger";
                                        $status_icon = "times-circle";
                                        $status_text = "Habis";
                                    } elseif($d['Stok'] <= 5) {
                                        $row_class = "low-stock";
                                        $stock_class = "low";
                                        $status_badge = "warning";
                                        $status_icon = "exclamation-triangle";
                                        $status_text = "Hampir Habis";
                                    }
                                ?>
                                <tr class="<?= $row_class ?>">
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= $d['NamaProduk'] ?></strong></td>
                                    <td><span class="price">Rp <?= number_format($d['Harga'], 0, ',', '.') ?></span></td>
                                    <td><span class="stock <?= $stock_class ?>"><?= $d['Stok'] ?></span></td>
                                    <td>
                                        <span class="badge badge-<?= $status_badge ?>">
                                            <i class="fas fa-<?= $status_icon ?>"></i>
                                            <?= $status_text ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Info -->
                <div class="alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Produk dengan stok ≤ 5 ditandai sebagai "Hampir Habis". Segera lakukan restocking.
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#productTable tbody tr');
            
            rows.forEach(row => {
                const productName = row.cells[1].textContent.toLowerCase();
                row.style.display = productName.includes(searchValue) ? '' : 'none';
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>