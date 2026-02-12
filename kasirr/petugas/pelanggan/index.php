<?php 
// ===== AUTENTIKASI & SETUP =====
session_start();
include '../../main/connect.php';

// Redirect jika belum login
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Variabel untuk menu aktif sidebar
$current_dir = 'pelanggan'; 

// ===== HITUNG STATISTIK =====
// Total semua pelanggan
$total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pelanggan"));

// Pelanggan VIP (10 pertama)
$vip = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pelanggan LIMIT 10"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Kasir SUSPUK</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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

        /* Tombol tambah pelanggan */
        .btn-add {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .btn-add:hover {
            background: #5568d3;
            transform: translateY(-1px);
            color: white;
        }

        /* ===== STATISTIK CARDS ===== */
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
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

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

        /* Icon dalam stat card */
        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .stat-icon.purple { background: #eef2ff; color: #667eea; }
        .stat-icon.blue { background: #f0f9ff; color: #3b82f6; }
        .stat-icon.green { background: #f0fdf4; color: #48bb78; }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
        }

        /* ===== PENCARIAN & FILTER ===== */
        .search-filter-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        /* Search box dengan icon */
        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
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

        /* Filter pills (Semua, VIP, Regular) */
        .filter-pills {
            display: flex;
            gap: 0.5rem;
        }

        .filter-pill {
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
            font-weight: 500;
            color: #4a5568;
        }

        .filter-pill:hover,
        .filter-pill.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
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

        tbody tr:hover {
            background: #f7fafc;
        }

        /* ===== INFO PELANGGAN ===== */
        .customer-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Avatar dengan initial */
        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }

        .customer-name {
            font-weight: 600;
            color: #1a202c;
        }

        .customer-id {
            font-size: 0.8rem;
            color: #a0aec0;
        }

        /* ===== INFO KONTAK ===== */
        .contact-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .contact-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .contact-icon.phone {
            background: #f0fdf4;
            color: #48bb78;
        }

        .contact-icon.location {
            background: #fff5f0;
            color: #ed8936;
        }

        /* ===== BADGE STATUS ===== */
        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-vip {
            background: #fef5e7;
            color: #f59e0b;
        }

        .badge-regular {
            background: #eff6ff;
            color: #3b82f6;
        }

        /* ===== TOMBOL AKSI ===== */
        .btn-action {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            border: none;
            font-size: 0.85rem;
            transition: all 0.2s;
            cursor: pointer;
            margin: 0 0.15rem;
        }

        /* Tombol histori */
        .btn-history {
            background: #eef2ff;
            color: #667eea;
        }

        .btn-history:hover {
            background: #667eea;
            color: white;
        }

        /* Tombol edit */
        .btn-edit {
            background: #fef3c7;
            color: #d97706;
        }

        .btn-edit:hover {
            background: #f59e0b;
            color: white;
        }

        /* Tombol hapus */
        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-delete:hover {
            background: #ef4444;
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
            margin-bottom: 1.5rem;
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
                grid-template-columns: repeat(2, 1fr); 
            }
            
            .stat-value { 
                font-size: 1.5rem; 
            }
            
            .search-filter-row { 
                flex-direction: column; 
            }
            
            .filter-pills { 
                flex-wrap: wrap; 
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
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="flex-fill">
            <div class="container-fluid">
                
                <!-- ===== HEADER ===== -->
                <div class="header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h1><i class="fas fa-users me-2"></i>Data Pelanggan</h1>
                            <p>Kelola informasi pelanggan Anda</p>
                        </div>
                

                <!-- ===== STATISTIK ===== -->
                <div class="stats-grid">
                    <!-- Card Total Pelanggan -->
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Total Pelanggan</span>
                            <div class="stat-icon purple">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stat-value"><?= $total ?></div>
                    </div>

                    <!-- Card Pelanggan VIP -->
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Pelanggan VIP</span>
                            <div class="stat-icon blue">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="stat-value"><?= $vip ?></div>
                    </div>

                </div>

                <!-- ===== PENCARIAN & FILTER ===== -->
                <div class="search-filter-row">
                    <!-- Search box -->
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari pelanggan...">
                    </div>
                    
                    <!-- Filter pills -->
                    <div class="filter-pills">
                        <div class="filter-pill active" onclick="filterCustomers('all')">Semua</div>
                    
                  
                    </div>
                </div>

                <!-- ===== TABEL PELANGGAN ===== -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table id="customerTable">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th width="250">Pelanggan</th>
                                    <th width="150">Kontak</th>
                                    <th>Alamat</th>
                                    <th width="100">Status</th>
                                    <th width="120">Histori</th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                
                                // Query semua pelanggan, urut berdasarkan nama
                                $query = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY NamaPelanggan ASC");
                                
                                // Cek jika ada data
                                if(mysqli_num_rows($query) > 0) {
                                    // Loop data pelanggan
                                    while($row = mysqli_fetch_array($query)){
                                        // Ambil huruf pertama untuk avatar
                                        $initial = strtoupper(substr($row['NamaPelanggan'], 0, 1));
                                        
                                        // Tentukan status VIP (10 pertama)
                                        $isVip = ($no <= 10);
                                ?>
                                <tr class="customer-row" data-type="<?= $isVip ? 'vip' : 'regular' ?>">
                                    <!-- Nomor urut -->
                                    <td><?= $no++ ?></td>
                                    
                                    <!-- Info Pelanggan -->
                                    <td>
                                        <div class="customer-info">
                                            <div class="customer-avatar"><?= $initial ?></div>
                                            <div>
                                                <div class="customer-name"><?= $row['NamaPelanggan'] ?></div>
                                                <div class="customer-id">#PLG-<?= str_pad($row['PelangganID'], 4, '0', STR_PAD_LEFT) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Nomor Telepon -->
                                    <td>
                                        <div class="contact-info">
                                            <div class="contact-icon phone">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <span><?= $row['NomorTelepon'] ?></span>
                                        </div>
                                    </td>
                                    
                                    <!-- Alamat (dipotong jika terlalu panjang) -->
                                    <td>
                                        <div class="contact-info">
                                            <div class="contact-icon location">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <span><?= substr($row['Alamat'], 0, 35) . (strlen($row['Alamat']) > 35 ? '...' : '') ?></span>
                                        </div>
                                    </td>
                                    
                                    <!-- Badge Status (VIP/Regular) -->
                                    <td>
                                        <span class="badge badge-<?= $isVip ? 'vip' : 'regular' ?>">
                                            <i class="fas fa-<?= $isVip ? 'star' : 'user' ?>"></i>
                                            <?= $isVip ? 'VIP' : 'Regular' ?>
                                        </span>
                                    </td>
                                    
                                    <!-- Tombol Histori Belanja -->
                                    <td>
                                        <a href="histori_belanja.php?id=<?= $row['PelangganID'] ?>" class="btn-action btn-history">
                                            <i class="fas fa-history me-1"></i> Lihat
                                        </a>
                                    </td>
                                    
                                    <!-- Tombol Aksi (Edit & Hapus) -->
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $row['PelangganID'] ?>" class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Tombol hapus hanya untuk admin -->
                                        <?php if($_SESSION['role'] == 'admin'): ?>
                                        <a href="javascript:void(0)" onclick="konfirmasiHapus(<?= $row['PelangganID'] ?>)" class="btn-action btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    // Tampilkan empty state jika tidak ada data
                                ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="fas fa-users-slash"></i>
                                            <h4>Belum Ada Pelanggan</h4>
                                        
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
    // Fungsi pencarian pelanggan
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#customerTable tbody tr.customer-row');
        
        // Loop setiap baris tabel
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            // Tampilkan jika cocok dengan pencarian
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    // Fungsi filter pelanggan (Semua/VIP/Regular)
    function filterCustomers(type) {
        const rows = document.querySelectorAll('#customerTable tbody tr.customer-row');
        const pills = document.querySelectorAll('.filter-pill');
        
        // Update active pill
        pills.forEach(pill => pill.classList.remove('active'));
        event.target.classList.add('active');
        
        // Filter baris berdasarkan tipe
        rows.forEach(row => {
            if(type === 'all') {
                row.style.display = '';
            } else {
                row.style.display = row.dataset.type === type ? '' : 'none';
            }
        });
    }

    // Konfirmasi hapus dengan SweetAlert
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Pelanggan?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "hapus.php?id=" + id;
            }
        })
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>