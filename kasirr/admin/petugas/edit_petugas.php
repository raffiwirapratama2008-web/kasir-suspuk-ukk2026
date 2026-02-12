<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM user WHERE UserID='$id'");
$d = mysqli_fetch_array($data);

if (!$d) {
    header("location:index.php");
    exit;
}

// Proses Update
if(isset($_POST['update'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    if(empty($password)){
        mysqli_query($conn, "UPDATE user SET Username='$username', Role='$role' WHERE UserID='$id'");
    } else {
        // Gunakan password_hash jika sistem loginmu mendukungnya, atau sesuaikan kembali
        mysqli_query($conn, "UPDATE user SET Username='$username', Role='$role', Password='$password' WHERE UserID='$id'");
    }
    header("location:index.php?pesan=sukses");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Kasir Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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

        .btn-update {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            transition: all 0.3s;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3);
        }
        /* Efek Transisi Halus */
.transition-all {
    transition: all 0.3s ease-in-out;
}

/* Efek Hover: Mengembalikan opacity jadi terang dan geser sedikit ke kiri */
.opacity-100-hover:hover {
    opacity: 1 !important;
    color: #1e293b !important; /* Warna teks jadi lebih gelap/tegas saat hover */
    transform: translateX(-3px); /* Efek gerak sedikit ke kiri agar terasa seperti 'kembali' */
}

/* Memastikan ikon juga ikut berubah warna */
.opacity-100-hover:hover i {
    color: #ef4444; /* Ikon silang jadi merah saat hover */
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
                            <h4 class="fw-extrabold text-dark mb-1">Edit Petugas</h4>
                            <p class="text-muted small">Update informasi akun atau hak akses user</p>
                        </div>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="username" class="form-control form-control-custom border-start-0" 
                                           value="<?= $d['Username']; ?>" required style="border-radius: 0 12px 12px 0;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role / Hak Akses</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-user-shield text-muted"></i></span>
                                    <select name="role" class="form-select form-control-custom border-start-0" style="border-radius: 0 12px 12px 0;">
                                        <option value="admin" <?= $d['Role'] == 'admin' ? 'selected' : ''; ?>>Admin (Akses Penuh)</option>
                                        <option value="petugas" <?= $d['Role'] == 'petugas' ? 'selected' : ''; ?>>Petugas (Kasir)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Ganti Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-key text-muted"></i></span>
                                    <input type="password" name="password" class="form-control form-control-custom border-start-0" 
                                           placeholder="Isi hanya jika ingin ganti..." style="border-radius: 0 12px 12px 0;">
                                </div>
                                <div class="form-text text-danger" style="font-size: 0.7rem;">* Kosongkan jika password tidak ingin diubah.</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="update" class="btn btn-primary text-white fw-bold btn-update">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                                <a href="index.php" class="btn btn-link text-muted text-decoration-none small fw-bold mt-2 opacity-75 opacity-100-hover transition-all">
                                    <i class="fas fa-times me-1"></i> Batal & Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>