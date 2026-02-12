<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Kasir SUSPUK</title>
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

        /* Add User Button */
        .btn-add {
            background: #eac766;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add:hover {
            background: #d3b155;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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

        .stat-card.total { border-left-color: #eabc66; }
        .stat-card.admin { border-left-color:  #f5d365; }
        .stat-card.petugas { border-left-color: #c58530; }

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

        .stat-card.total .stat-icon { background: #eabc66; }
        .stat-card.admin .stat-icon { background: #f5d365; }
        .stat-card.petugas .stat-icon { background: #c58530; }

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

        /* Table */
        .table-wrapper {
            overflow-x: auto;
        }

        .user-table {
            width: 100%;
            margin: 0;
        }

        .user-table thead {
            background: #f7fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .user-table th {
            padding: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .user-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s;
        }

        .user-table tbody tr:hover {
            background: #f7fafc;
        }

        .user-table td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #ceb64d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .user-name {
            font-weight: 600;
            color: #1a202c;
            font-size: 1rem;
        }

        /* Role Badges */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
        }

        .badge-admin {
            background: #d6a750;
        }

        .badge-petugas {
            background: #b97e25;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .btn-edit {
            background: #66baea;
            color: white;
        }

        .btn-edit:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #f56565;
            color: white;
        }

        .btn-delete:hover {
            background: #e53e3e;
            transform: translateY(-2px);
        }

        /* Row Number */
        .row-number {
            font-weight: 600;
            color: #a0aec0;
            font-size: 0.95rem;
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

            .user-table th,
            .user-table td {
                padding: 0.875rem 0.75rem;
                font-size: 0.85rem;
            }

            .btn-add {
                width: 100%;
                justify-content: center;
                margin-top: 1rem;
            }
        }

        /* Loading State */
        .loading {
            text-align: center;
            padding: 3rem;
            color: #a0aec0;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>

        <div class="container-fluid p-4">
            <!-- Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-users-cog me-2"></i>Manajemen User
                        </h1>
                        <p class="page-subtitle">Kelola pengguna dan hak akses sistem</p>
                    </div>
                    <a href="tambah_petugas.php" class="btn-add">
                        <i class="fas fa-user-plus"></i>
                        Tambah User
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <?php 
            $total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user"));
            $admin = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE Role='admin'"));
            $petugas = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE Role='petugas'"));
            ?>

            <div class="stats-container">
                <div class="stat-card total">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $total; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>

                <div class="stat-card admin">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $admin; ?></div>
                    <div class="stat-label">Administrator</div>
                </div>

                <div class="stat-card petugas">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $petugas; ?></div>
                    <div class="stat-label">Petugas</div>
                </div>
            </div>

            <!-- Main Table Card -->
            <div class="main-card">
                <div class="table-wrapper">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th style="width: 45%;">Username</th>
                                <th style="width: 35%;">Hak Akses</th>
                                <th class="text-center" style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM user ORDER BY UserID DESC");
                            
                            if(mysqli_num_rows($query) > 0) {
                                while($d = mysqli_fetch_array($query)){
                                    // Ambil inisial untuk avatar
                                    $initial = strtoupper(substr($d['Username'], 0, 1));
                            ?>
                            <tr>
                                <td>
                                    <span class="row-number"><?= str_pad($no++, 2, "0", STR_PAD_LEFT); ?></span>
                                </td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar"><?= $initial; ?></div>
                                        <span class="user-name"><?= htmlspecialchars($d['Username']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if($d['Role'] == 'admin'): ?>
                                        <span class="role-badge badge-admin">
                                            <i class="fas fa-crown"></i>
                                            Administrator
                                        </span>
                                    <?php else: ?>
                                        <span class="role-badge badge-petugas">
                                            <i class="fas fa-id-badge"></i>
                                            Petugas
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit_petugas.php?id=<?= $d['UserID']; ?>" 
                                           class="btn-action btn-edit" 
                                           title="Edit User">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?= $d['UserID']; ?>)" 
                                                class="btn-action btn-delete" 
                                                title="Hapus User">
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
                                <td colspan="4">
                                    <div class="loading">
                                        <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                        <p>Belum ada data user</p>
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
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus User?',
            text: 'User ini akan kehilangan akses ke sistem secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f5c365',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "hapus.php?id=" + id;
            }
        })
    }
    </script>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'sukses'): ?>
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data user telah diperbarui dengan sukses.',
            icon: 'success',
            confirmButtonColor: '#48bb78',
            timer: 2000
        });
    </script>
    <?php endif; ?>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'hapus_sukses'): ?>
    <script>
        Swal.fire({
            title: 'Terhapus!',
            text: 'User telah dihapus dari sistem.',
            icon: 'success',
            confirmButtonColor: '#48bb78',
            timer: 2000
        });
    </script>
    <?php endif; ?>
</body>
</html>