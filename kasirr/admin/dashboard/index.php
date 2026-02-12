<?php 
// ===== AUTENTIKASI & SETUP =====
session_start();

// Cek apakah user sudah login
if($_SESSION['status'] != "login"){
    header("location:../../auth/login.php?pesan=belum_login");
}

// Koneksi database
include '../../main/connect.php';

// Tanggal hari ini
$tgl_hari_ini = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kasir SUSPUK</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ===== RESET ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ===== VARIABEL WARNA & STYLE ===== */
        :root {
            /* Warna Utama */
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #ec4899;
            --accent: #8b5cf6;
            
            /* Warna Status */
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            
            /* Background */
            --bg-main: #f8fafc;
            --bg-alt: #f1f5f9;
            --surface: #ffffff;
            
            /* Teks */
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #94a3b8;
            
            /* Border */
            --border-color: #e2e8f0;
            
            /* Shadow */
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            
            /* Border Radius */
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.25rem;
            --radius-2xl: 1.5rem;
        }

        /* ===== LAYOUT DASAR ===== */
        body {
            background: var(--bg-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-primary);
        }

        .main-wrapper {
            max-width: 1440px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ===== HEADER ===== */
        .premium-header {
            background: var(--surface);
            border-radius: var(--radius-2xl);
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            position: relative;
        }

        /* Garis accent di atas */
        .premium-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg,#a78847, #e2b45f 100%);
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
            margin-bottom: 0.5rem;
        }

        .header-subtitle {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .header-subtitle i {
            color: var(--primary);
        }

        /* ===== USER CARD ===== */
        .user-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            padding: 0.875rem 1.5rem;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
        }

        .user-avatar {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .user-details h4 {
            font-weight: 700;
            color: white;
            margin: 0;
        }

        .user-details p {
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            text-transform: capitalize;
        }

        /* ===== STATISTIK CARDS ===== */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-box {
            background: var(--surface);
            border-radius: var(--radius-xl);
            padding: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi lingkaran background */
        .stat-box::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: var(--stat-gradient);
            opacity: 0.1;
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        /* Gradient untuk tiap tipe card */
        .stat-box.box-success { --stat-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .stat-box.box-primary { --stat-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
        .stat-box.box-warning { --stat-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }

        .stat-top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        /* Icon dengan gradient */
        .stat-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-lg);
            background: var(--icon-bg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .box-success .stat-icon-wrapper { --icon-bg: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .box-primary .stat-icon-wrapper { --icon-bg: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
        .box-warning .stat-icon-wrapper { --icon-bg: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }

        .stat-icon-wrapper i {
            color: white;
            font-size: 1.5rem;
        }

        /* Badge status (LIVE, TODAY, dll) */
        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: var(--badge-bg);
            color: var(--badge-color);
            border-radius: var(--radius-md);
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid var(--badge-border);
        }

        .box-success .stat-badge { 
            --badge-bg: #f0fdf4; 
            --badge-color: #059669; 
            --badge-border: #bbf7d0;
        }
        .box-primary .stat-badge { 
            --badge-bg: #eef2ff; 
            --badge-color: #4f46e5; 
            --badge-border: #c7d2fe;
        }
        .box-warning .stat-badge { 
            --badge-bg: #fffbeb; 
            --badge-color: #d97706; 
            --badge-border: #fde68a;
        }

        .stat-label {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 0.625rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .stat-number small {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-left: 0.25rem;
        }

        /* ===== LAYOUT 2 KOLOM ===== */
        .content-layout {
            display: grid;
            grid-template-columns: 1.75fr 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        /* ===== TABEL CARD ===== */
        .premium-table-card {
            background: var(--surface);
            border-radius: var(--radius-2xl);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .table-card-header {
            padding: 2rem;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-bottom: 1px solid var(--border-color);
        }

        .table-header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .table-title-section h2 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .table-description {
            font-size: 0.9375rem;
            color: var(--text-secondary);
            font-weight: 600;
            margin: 0;
        }

        /* Tombol detail */
        .btn-view-detail {
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(79 70 229 / 0.3);
            transition: all 0.2s ease;
        }

        .btn-view-detail:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            color: white;
        }

        /* ===== TABEL ===== */
        .premium-table {
            width: 100%;
            border-collapse: collapse;
        }

        .premium-table thead th {
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

        .premium-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.15s ease;
        }

        .premium-table tbody tr:hover {
            background: var(--bg-main);
        }

        .premium-table tbody td {
            padding: 1.5rem 2rem;
            vertical-align: middle;
        }

        /* Badge ranking (1, 2, 3, dll) */
        .rank-number {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            margin-right: 1rem;
        }

        .rank-number.gold {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgb(251 191 36 / 0.4);
        }

        .rank-number.silver {
            background: linear-gradient(135deg, #e5e7eb 0%, #9ca3af 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgb(156 163 175 / 0.4);
        }

        .rank-number.bronze {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgb(217 119 6 / 0.4);
        }

        .rank-number.regular {
            background: var(--bg-alt);
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
        }

        .product-title {
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Badge jumlah terjual */
        .qty-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            color: var(--primary);
            border-radius: var(--radius-md);
            font-weight: 700;
            border: 1px solid #c7d2fe;
        }

        .revenue-value {
            font-weight: 800;
            color: var(--success);
        }

        /* ===== QUICK ACTIONS ===== */
        .actions-section {
            position: sticky;
            top: 2rem;
        }

        .section-header h3 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 1.25rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .action-item {
            background: var(--surface);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-xl);
            padding: 2rem 1.25rem;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .action-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--action-accent);
        }

        /* Warna accent tiap action */
        .action-item.action-blue { --action-accent: var(--primary); }
        .action-item.action-green { --action-accent: var(--success); }
        .action-item.action-orange { --action-accent: var(--warning); }
        .action-item.action-purple { --action-accent: var(--accent); }

        .action-icon-box {
            width: 64px;
            height: 64px;
            border-radius: var(--radius-lg);
            background: var(--action-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .action-item.action-blue .action-icon-box { 
            --action-bg: linear-gradient(135deg, #6366f1 0%, #7646e5 100%); 
        }
        .action-item.action-green .action-icon-box { 
            --action-bg: linear-gradient(135deg, #10b981 0%, #059669 100%); 
        }
        .action-item.action-orange .action-icon-box { 
            --action-bg: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
        }
        .action-item.action-purple .action-icon-box { 
            --action-bg: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); 
        }

        .action-icon-box i {
            color: white;
            font-size: 1.75rem;
        }

        .action-title {
            font-weight: 700;
            color: var(--text-primary);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .content-layout {
                grid-template-columns: 1fr;
            }

            .actions-section {
                position: relative;
                top: 0;
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

            .stats-container {
                grid-template-columns: 1fr;
            }

            .stat-number {
                font-size: 2rem;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .premium-table thead th,
            .premium-table tbody td {
                padding: 1rem;
            }
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-alt);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: var(--radius-md);
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
        
        <!-- HEADER -->
        <div class="premium-header">
            <div class="header-grid">
                <div class="header-main">
                    <h1>Dashboard Overview</h1>
                    <div class="header-subtitle">
                        <i class="fas fa-calendar-day"></i>
                        <span><?= strftime('%A, %d %B %Y', strtotime($tgl_hari_ini)); ?></span>
                    </div>
                </div>
                
                <!-- Info User Login -->
                <div class="user-card">
                    <div class="user-avatar">
                        <?= strtoupper(substr($_SESSION['username'], 0, 2)); ?>
                    </div>
                    <div class="user-details">
                        <h4><?= $_SESSION['username']; ?></h4>
                        <p><?= $_SESSION['role']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATISTIK -->
        <div class="stats-container">
            
            <!-- Card Omzet -->
            <div class="stat-box box-success">
                <div class="stat-top">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-badge">
                        <i class="fas fa-arrow-trend-up"></i>
                        <span>LIVE</span>
                    </div>
                </div>
                <div class="stat-label">penjualan hari ini</div>
                <?php 
                    // Hitung total omzet hari ini
                    $q_omset = mysqli_query($conn, "SELECT SUM(TotalHarga) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
                    $d_omset = mysqli_fetch_assoc($q_omset);
                    $omset = $d_omset['total'] ?? 0;
                ?>
                <div class="stat-number">Rp <?= number_format($omset); ?></div>
            </div>

            <!-- Card Produk Terjual -->
            <div class="stat-box box-primary">
                <div class="stat-top">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="stat-badge">
                        <i class="fas fa-chart-line"></i>
                        <span>TODAY</span>
                    </div>
                </div>
                <div class="stat-label">Produk Terjual</div>
                <?php 
                    // Hitung total produk terjual hari ini
                    $q_pdk = mysqli_query($conn, "SELECT SUM(JumlahProduk) as terjual FROM detailpenjualan 
                                                  JOIN penjualan ON detailpenjualan.PenjualanID = penjualan.PenjualanID 
                                                  WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
                    $d_pdk = mysqli_fetch_assoc($q_pdk);
                    $terjual = $d_pdk['terjual'] ?? 0;
                ?>
                <div class="stat-number"><?= number_format($terjual); ?><small>Unit</small></div>
            </div>

            <!-- Card Member -->
            <div class="stat-box box-warning">
                <div class="stat-top">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-badge">
                        <i class="fas fa-check-circle"></i>
                        <span>ACTIVE</span>
                    </div>
                </div>
                <div class="stat-label">Total Pelanggan</div>
                <?php 
                    // Hitung total member
                    $q_plg = mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggan");
                    $d_plg = mysqli_fetch_assoc($q_plg);
                ?>
                <div class="stat-number"><?= number_format($d_plg['total']); ?><small>Pelanggan</small></div>
            </div>
        </div>

        <!-- KONTEN UTAMA: Tabel & Quick Actions -->
        <div class="content-layout">
            
            <!-- TABEL PRODUK TERLARIS -->
            <div class="premium-table-card">
                <div class="table-card-header">
                    <div class="table-header-content">
                        <div class="table-title-section">
                            <h2>
                                <i class="fas fa-fire text-warning"></i>
                                Produk Terlaris
                            </h2>
                            <p class="table-description">Top 5 produk dengan penjualan terbanyak</p>
                        </div>
                        <a href="../laporan/index.php" class="btn-view-detail">
                            Lihat Detail
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Terjual</th>
                                <th class="text-end">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Ambil 5 produk terlaris
                            $best = mysqli_query($conn, "SELECT NamaProduk, SUM(JumlahProduk) as total, SUM(Subtotal) as uang 
                                                        FROM detailpenjualan JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                                        GROUP BY detailpenjualan.ProdukID ORDER BY total DESC LIMIT 5");
                            
                            $rank = 1;
                            while($b = mysqli_fetch_assoc($best)): 
                                // Tentukan warna badge berdasarkan ranking
                                $badge = 'regular';
                                if($rank == 1) $badge = 'gold';
                                elseif($rank == 2) $badge = 'silver';
                                elseif($rank == 3) $badge = 'bronze';
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="rank-number <?= $badge; ?>"><?= $rank; ?></span>
                                        <span class="product-title"><?= $b['NamaProduk']; ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="qty-badge">
                                        <i class="fas fa-cube"></i>
                                        <?= number_format($b['total']); ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="revenue-value">Rp <?= number_format($b['uang']); ?></span>
                                </td>
                            </tr>
                            <?php 
                                $rank++;
                            endwhile; 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="actions-section">
                <div class="section-header">
                    <h3>Aksi Cepat</h3>
                </div>
                <div class="actions-grid">
                    <a href="../penjualan/index.php" class="action-item action-blue">
                        <div class="action-icon-box">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <div class="action-title">Kasir</div>
                    </a>
                    <a href="../produk/index.php" class="action-item action-green">
                        <div class="action-icon-box">
                            <i class="fas fa-boxes-stacked"></i>
                        </div>
                        <div class="action-title">Kelola Stok</div>
                    </a>
                    <a href="../laporan/index.php" class="action-item action-orange">
                        <div class="action-icon-box">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="action-title">Laporan</div>
                    </a>
                    <a href="../petugas/index.php" class="action-item action-purple">
                        <div class="action-icon-box">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="action-title">Kelola User</div>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ===== ANIMASI COUNTER =====
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.stat-number');
        
        counters.forEach(counter => {
            const text = counter.textContent;
            const numberMatch = text.match(/[\d,]+/);
            
            if (numberMatch) {
                const target = parseInt(numberMatch[0].replace(/,/g, ''));
                const duration = 1000;
                let current = 0;
                const increment = target / (duration / 16);
                
                const updateCounter = () => {
                    current += increment;
                    
                    if (current < target) {
                        const formatted = Math.floor(current).toLocaleString('id-ID');
                        
                        // Update sesuai format
                        if (text.includes('Rp')) {
                            counter.textContent = 'Rp ' + formatted;
                        } else if (text.includes('Unit')) {
                            counter.innerHTML = formatted + '<small>Unit</small>';
                        } else if (text.includes('Member')) {
                            counter.innerHTML = formatted + '<small>Member</small>';
                        }
                        
                        requestAnimationFrame(updateCounter);
                    } else {
                        // Set nilai final
                        const formatted = target.toLocaleString('id-ID');
                        
                        if (text.includes('Rp')) {
                            counter.textContent = 'Rp ' + formatted;
                        } else if (text.includes('Unit')) {
                            counter.innerHTML = formatted + '<small>Unit</small>';
                        } else if (text.includes('Member')) {
                            counter.innerHTML = formatted + '<small>Member</small>';
                        }
                    }
                };
                
                updateCounter();
            }
        });
    });
</script>
</body>
</html>