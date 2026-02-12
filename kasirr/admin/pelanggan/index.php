<?php 
session_start();
include '../../main/connect.php';

// Proteksi halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Variabel untuk menandai menu aktif di sidebar
$current_dir = 'pelanggan'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Kasir SUSPUK </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #2d3748;
        }

        /* Header */
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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

        /* Stats Cards */
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

        .stat-card.primary { border-left-color: #667eea; }
        .stat-card.success { border-left-color: #48bb78; }
        .stat-card.info { border-left-color: #4299e1; }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .stat-card.primary .stat-icon { background: #667eea; }
        .stat-card.success .stat-icon { background: #48bb78; }
        .stat-card.info .stat-icon { background: #4299e1; }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #718096;
            font-weight: 500;
        }

        /* Main Card */
        .main-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* Search Bar */
        .search-section {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            background: #fafafa;
        }

        .search-wrapper {
            position: relative;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #eab566;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .search-count {
            color: #718096;
            font-size: 0.9rem;
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
        }

        .customer-table {
            width: 100%;
            margin: 0;
        }

        .customer-table thead {
            background: #f7fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .customer-table th {
            padding: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .customer-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s;
        }

        .customer-table tbody tr:hover {
            background: #f7fafc;
        }

        .customer-table td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }

        /* Customer Info */
        .customer-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .customer-avatar {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #e4a24c;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .customer-details .name {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.15rem;
        }

        .customer-details .id {
            font-size: 0.8rem;
            color: #a0aec0;
        }

        /* Contact & Address */
        .contact-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 6px;
            font-size: 0.9rem;
            color: #166534;
        }

        .contact-badge i {
            color: #22c55e;
        }

        .address-badge {
            display: inline-flex;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #78350f;
            max-width: 250px;
        }

        .address-badge i {
            color: #f59e0b;
            margin-top: 0.15rem;
        }

        /* Buttons */
        .btn-view-history {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #4299e1;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-view-history:hover {
            background: #3182ce;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-edit {
            background: #fbbf24;
            color: white;
        }

        .btn-edit:hover {
            background: #f59e0b;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        /* Empty State */
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

        /* Responsive */
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

            .search-section {
                padding: 1rem;
            }

            .search-wrapper {
                max-width: 100%;
            }

            .customer-table th,
            .customer-table td {
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
            <!-- Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-users me-2"></i>Data Pelanggan
                </h1>
                <p class="page-subtitle">Kelola dan pantau data pelanggan Anda</p>
            </div>

            <!-- Statistics -->
            <?php 
            $total_pelanggan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pelanggan"));
            $pelanggan_aktif = mysqli_num_rows(mysqli_query($conn, "
                SELECT DISTINCT p.PelangganID 
                FROM pelanggan p 
                INNER JOIN penjualan pj ON p.PelangganID = pj.PelangganID 
                WHERE MONTH(pj.TanggalPenjualan) = MONTH(CURRENT_DATE())
            "));
            $total_transaksi = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM penjualan WHERE PelangganID IS NOT NULL"));
            ?>

            <div class="stats-container">
                <div class="stat-card primary">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $total_pelanggan; ?></div>
                    <div class="stat-label">Total Pelanggan</div>
                </div>

                <div class="stat-card success">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $pelanggan_aktif; ?></div>
                    <div class="stat-label">Pelanggan Aktif Bulan Ini</div>
                </div>

                <div class="stat-card info">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $total_transaksi; ?></div>
                    <div class="stat-label">Total Transaksi</div>
                </div>
            </div>

            <!-- Main Table Card -->
            <div class="main-card">
                <!-- Search Section -->
                <div class="search-section">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="searchInput" class="search-input" placeholder="Cari nama, telepon, atau alamat...">
                        </div>
                        <div class="search-count">
                            <i class="fas fa-database me-1"></i>
                            <strong><?= $total_pelanggan ?></strong> pelanggan
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-wrapper">
                    <table class="customer-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th style="width: 25%;">Pelanggan</th>
                                <th style="width: 18%;">Kontak</th>
                                <th style="width: 25%;">Alamat</th>
                                <th class="text-center" style="width: 15%;">Histori</th>
                                <th class="text-center" style="width: 12%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="customerTableBody">
                            <?php 
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY NamaPelanggan ASC");
                            
                            if(mysqli_num_rows($query) > 0) {
                                while($row = mysqli_fetch_array($query)){
                                    // Ambil inisial untuk avatar
                                    $nama_parts = explode(' ', $row['NamaPelanggan']);
                                    $initials = '';
                                    foreach($nama_parts as $part) {
                                        $initials .= strtoupper(substr($part, 0, 1));
                                        if(strlen($initials) >= 2) break;
                                    }
                            ?>
                            <tr>
                                <td>
                                    <span style="color: #a0aec0; font-weight: 600;"><?= str_pad($no++, 2, "0", STR_PAD_LEFT); ?></span>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-avatar"><?= $initials; ?></div>
                                        <div class="customer-details">
                                            <div class="name"><?= htmlspecialchars($row['NamaPelanggan']); ?></div>
                                            <div class="id">ID: #<?= $row['PelangganID']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-badge">
                                        <i class="fas fa-phone-alt"></i>
                                        <span><?= htmlspecialchars($row['NomorTelepon']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="address-badge">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?= htmlspecialchars($row['Alamat']); ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="histori_belanja.php?id=<?= $row['PelangganID']; ?>" class="btn-view-history">
                                        <i class="fas fa-receipt"></i>
                                        Lihat Histori
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?= $row['PelangganID']; ?>" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <button onclick="konfirmasiHapus(<?= $row['PelangganID']; ?>)" class="btn-action btn-delete" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                } 
                            } else {
                            ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-users-slash"></i>
                                        <h3>Belum Ada Pelanggan</h3>
                                        <p>Mulai tambahkan pelanggan pertama Anda</p>
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

    <script>
    // Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#customerTableBody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if(text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Delete Confirmation
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Pelanggan?',
            text: 'Data pelanggan dan histori belanja akan terhapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "hapus.php?id=" + id;
            }
        });
    }
    </script>

    <?php if(isset($_GET['pesan'])): ?>
    <script>
        <?php if($_GET['pesan'] == 'hapus_sukses'): ?>
        Swal.fire({
            title: 'Terhapus!',
            text: 'Data pelanggan berhasil dihapus.',
            icon: 'success',
            confirmButtonColor: '#48bb78',
            timer: 2000
        });
        <?php elseif($_GET['pesan'] == 'sukses'): ?>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data pelanggan berhasil diperbarui.',
            icon: 'success',
            confirmButtonColor: '#48bb78',
            timer: 2000
        });
        <?php endif; ?>
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>