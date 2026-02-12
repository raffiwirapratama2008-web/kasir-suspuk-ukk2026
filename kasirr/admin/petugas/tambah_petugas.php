<?php 
session_start();
include '../../main/connect.php';
// Proteksi halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - Kasir SUSPUK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
            --primary-gradient: linear-gradient(45deg, #4facfe, #00f2fe);
        }

        body { 
            background: radial-gradient(circle at top right, #e2e8f0, #cbd5e1), 
                        linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: #1e293b; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            position: relative;
        }

        /* Dot Pattern Background */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(#64748b 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            opacity: 0.1;
            z-index: -1;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        }

        .form-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            font-weight: 700;
        }

        .form-control-custom, .form-select-custom {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .form-control-custom:focus {
            background: #fff;
            box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.15);
            border-color: #4facfe;
        }

        .btn-register {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            transition: all 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3);
            filter: brightness(1.05);
        }

        /* CSS Kustom untuk Efek Batal */
        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        .btn-cancel-custom:hover {
            opacity: 1 !important;
            color: #1e293b !important;
            transform: translateX(-3px);
        }

        .btn-cancel-custom:hover i {
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="container-fluid p-4">
            <div class="col-md-5 mx-auto mt-4">
                
                <a href="index.php" class="text-muted text-decoration-none small fw-bold mb-3 d-inline-block opacity-75 opacity-100-hover transition-all">
                    <i class="fas fa-chevron-left me-1"></i> KEMBALI KE MANAJEMEN USER
                </a>

                <div class="glass-card overflow-hidden">
                    <div style="height: 6px; background: var(--primary-gradient);"></div>
                    
                    <div class="card-body p-4 p-md-5">
                        <div class="mb-4 text-center">
                            <div class="bg-primary bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                                <i class="fas fa-user-plus fa-2x text-primary"></i>
                            </div>
                            <h4 class="fw-extrabold text-dark mb-1">Registrasi User</h4>
                            <p class="text-muted small">Buat akun baru untuk akses sistem Kasir Pro</p>
                        </div>

                        <form action="proses_tambah.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="Username" class="form-control form-control-custom border-start-0" 
                                           placeholder="Masukkan username..." required style="border-radius: 0 12px 12px 0;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="Password" class="form-control form-control-custom border-start-0" 
                                           placeholder="Buat password aman..." required style="border-radius: 0 12px 12px 0;">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Role / Level Akses</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-id-badge text-muted"></i></span>
                                    <select name="Role" class="form-select form-control-custom border-start-0" required style="border-radius: 0 12px 12px 0;">
                                        <option value="" disabled selected>Pilih hak akses...</option>
                                        <option value="admin">Administrator</option>
                                        <option value="petugas">Petugas Kasir</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary text-white fw-bold btn-register">
                                    <i class="fas fa-check-circle me-2"></i>Daftarkan Akun
                                </button>
                                
                                <a href="index.php" class="btn btn-link text-muted text-decoration-none small fw-bold mt-2 opacity-75 transition-all btn-cancel-custom">
                                    <i class="fas fa-times me-1"></i> Batal & Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted small">
                        <i class="fas fa-shield-alt me-1"></i> Data login disimpan secara terenkripsi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>