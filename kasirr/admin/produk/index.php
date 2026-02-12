<?php 
session_start();
include '../../main/connect.php';
// Cek login
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Kasir</title>
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
            --primary-light: #f1e363;
            --primary-dark: #ca9038;
            --secondary: #ecb248;
            --accent: #f6df5c;
            
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
            --gradient-primary: linear-gradient(135deg, #f0c05a 0%, #a2954b 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --gradient-info: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            
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

        .header-content {
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

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Search Box */
        .search-container {
            position: relative;
            min-width: 320px;
        }

        .search-input {
            background: var(--bg-alt);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem 0.75rem 3rem;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--text-primary);
            transition: all 0.2s ease;
            width: 100%;
        }

        .search-input:focus {
            background: var(--surface);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 1rem;
        }

        /* Buttons */
        .btn-add-product {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 0.75rem 1.5rem;
            font-size: 0.9375rem;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgb(79 70 229 / 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add-product:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px -1px rgb(79 70 229 / 0.4);
            color: white;
        }

        /* Product Table Card */
        .product-table-card {
            background: var(--surface);
            border-radius: var(--radius-2xl);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
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

        /* Product Number */
        .product-number {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.9375rem;
            border: 1px solid #c7d2fe;
        }

        /* Product Info */
        .product-info-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-icon-box {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-lg);
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .product-sku {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .product-sku i {
            font-size: 0.75rem;
        }

        /* Price Display */
        .price-display {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
            border-radius: var(--radius-md);
            font-weight: 800;
            color: var(--info);
            font-size: 1rem;
        }

        /* Stock Badge */
        .stock-badge {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-lg);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.125rem;
        }

        .stock-badge.stock-good {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgb(16 185 129 / 0.3);
        }

        .stock-badge.stock-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgb(245 158 11 / 0.3);
        }

        .stock-badge.stock-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgb(239 68 68 / 0.3);
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

        .action-button-edit {
            border-color: #fde68a;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        }

        .action-button-edit:hover {
            border-color: var(--warning);
            background: var(--warning);
        }

        .action-button-edit:hover i {
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

        /* Table Footer */
        .table-footer {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .total-inventory {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .total-label-section h4 {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .total-label-section p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .total-amount {
            font-size: 2rem;
            font-weight: 800;
            color: var(--success);
        }

        /* Stock Legend */
        .stock-legend {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            font-weight: 600;
            font-size: 0.9375rem;
            box-shadow: var(--shadow-xs);
        }

        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
        }

        .legend-dot.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .legend-dot.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .legend-dot.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        }

        /* Responsive */
        @media (max-width: 992px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
            }

            .search-container {
                width: 100%;
                min-width: auto;
            }
        }

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

            .modern-table thead th,
            .modern-table tbody td {
                padding: 1rem;
            }

            .product-icon-box {
                width: 44px;
                height: 44px;
                font-size: 1rem;
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
    <?php include '../../template/sidebar.php'; ?>

    <div class="main-wrapper w-100">
        
        <!-- Premium Header -->
        <div class="premium-header">
            <div class="header-content">
                <div class="header-main">
                    <h1>
                        <span class="header-icon">📦</span>
                        Manajemen Produk
                    </h1>
                    <p class="header-subtitle">Atur katalog barang dan pantau ketersediaan stok</p>
                </div>
                <div class="header-actions">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" 
                               id="searchInput" 
                               class="search-input" 
                               placeholder="Cari produk atau SKU...">
                    </div>
                    <a href="tambah.php" class="btn-add-product">
                        <i class="fas fa-plus"></i>
                        Tambah Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Table -->
        <div class="product-table-card">
            <div class="table-responsive">
                <table class="modern-table" id="productTable">
                    <thead>
                        <tr>
                            <th width="8%">No</th>
                            <th>Informasi Produk</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $total_aset = 0;
                        $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY NamaProduk ASC");
                        
                        if(mysqli_num_rows($query) == 0){
                            echo "<tr><td colspan='5' class='p-0'>
                                    <div class='empty-state'>
                                        <div class='empty-icon'>
                                            <i class='fas fa-box-open'></i>
                                        </div>
                                        <div class='empty-title'>Belum ada produk terdaftar</div>
                                        <p class='empty-text'>
                                            Mulai tambahkan produk untuk membangun katalog toko Anda
                                        </p>
                                    </div>
                                  </td></tr>";
                        }

                        while($d = mysqli_fetch_array($query)){
                            $subtotal_produk = $d['Harga'] * $d['Stok'];
                            $total_aset += $subtotal_produk;
                            
                            $stok_class = 'stock-good';
                            if($d['Stok'] < 10) $stok_class = 'stock-warning';
                            if($d['Stok'] < 5) $stok_class = 'stock-danger';
                        ?>
                        <tr class="product-row">
                            <td>
                                <span class="product-number"><?= str_pad($no++, 2, "0", STR_PAD_LEFT); ?></span>
                            </td>
                            <td>
                                <div class="product-info-wrapper">
                                    <div class="product-icon-box">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                    <div class="product-info">
                                        <div class="product-name"><?= $d['NamaProduk']; ?></div>
                                        <div class="product-sku">
                                            <i class="fas fa-barcode"></i>
                                            SKU: PRD-<?= str_pad($d['ProdukID'], 5, '0', STR_PAD_LEFT); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="price-display">
                                    <i class="fas fa-tag"></i>
                                    Rp <?= number_format($d['Harga'], 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="stock-badge <?= $stok_class; ?>">
                                    <?= $d['Stok']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="edit.php?id=<?= $d['ProdukID']; ?>" 
                                       class="action-button action-button-edit"
                                       title="Edit Produk">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <a href="hapus.php?id=<?= $d['ProdukID']; ?>" 
                                       class="action-button action-button-delete"
                                       onclick="return confirm('⚠️ Menghapus produk akan berpengaruh pada data transaksi terkait.\n\nApakah Anda yakin ingin melanjutkan?')"
                                       title="Hapus Produk">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr id="noResults" style="display: none;">
                            <td colspan="5" class="p-0">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <div class="empty-title">Produk tidak ditemukan</div>
                                    <p class="empty-text">
                                        Coba gunakan kata kunci yang berbeda
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <?php if(mysqli_num_rows($query) > 0): ?>
            <div class="table-footer" id="totalFooter">
                <div class="total-inventory">
                    <div class="total-label-section">
                        <h4>Total Nilai Inventaris</h4>
                        <p>Nilai aset produk dalam sistem</p>
                    </div>
                    <div class="total-amount">
                        Rp <?= number_format($total_aset, 0, ',', '.'); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Stock Legend -->
        <div class="stock-legend">
            <div class="legend-item">
                <span class="legend-dot danger"></span>
                <span>Stok Kritis (< 5)</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot warning"></span>
                <span>Stok Rendah (< 10)</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot success"></span>
                <span>Stok Aman</span>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search Functionality
    $(document).ready(function(){
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            var visibleCount = 0;

            $("#productTable tbody .product-row").filter(function() {
                var match = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(match);
                if(match) visibleCount++;
            });

            if(visibleCount === 0 && value !== "") {
                $("#noResults").show();
                $("#totalFooter").hide();
            } else {
                $("#noResults").hide();
                if($("#productTable tbody .product-row").length > 0) {
                    $("#totalFooter").show();
                }
            }
        });
    });
</script>
</body>
</html>