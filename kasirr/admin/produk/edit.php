<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM produk WHERE ProdukID='$id'");
$d = mysqli_fetch_array($data);

// Jika ID tidak ditemukan
if (!$d) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Kasir Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
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
            margin-bottom: 0.5rem;
        }

        .form-control-custom {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .form-control-custom:focus {
            background: #fff;
            box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.15);
            border-color: #ffc107;
        }

        .btn-update { 
            background: linear-gradient(45deg, #ffc107, #ff9800);
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            transition: all 0.3s; 
        }

        .btn-update:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 8px 20px rgba(255,152,0,0.3);
            filter: brightness(1.1);
        }

        .input-group-text-custom {
            background: rgba(255,255,255,0.5);
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 12px 0 0 12px;
            color: #64748b;
            font-weight: bold;
        }

        .rounded-end-custom {
            border-radius: 0 12px 12px 0 !important;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include '../../template/sidebar.php'; ?>

    <div class="container-fluid p-4">
        <div class="col-md-6 mx-auto mt-4">
            
            <a href="index.php" class="text-decoration-none text-muted small fw-bold mb-3 d-inline-block">
                <i class="fas fa-chevron-left me-1"></i> KEMBALI KE DAFTAR PRODUK
            </a>

            <div class="glass-card overflow-hidden">
                <div style="height: 6px; background: linear-gradient(to right, #ffc107, #ff9800);"></div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <h4 class="fw-extrabold text-dark mb-1">Edit Produk</h4>
                        <p class="text-muted small">ID Produk: <span class="badge bg-light text-dark">PRD-<?= $d['ProdukID']; ?></span></p>
                    </div>

                    <form id="formEdit" action="proses_edit.php" method="POST">
                        <input type="hidden" name="ProdukID" value="<?= $d['ProdukID']; ?>">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-tag text-muted"></i></span>
                                <input type="text" name="NamaProduk" class="form-control form-control-custom border-start-0 rounded-end-custom" 
                                       placeholder="Contoh: Kopi Susu Gula Aren" value="<?= $d['NamaProduk']; ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7 mb-4">
                                <label class="form-label fw-bold">Harga Jual</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-custom">Rp</span>
                                    <input type="number" name="Harga" class="form-control form-control-custom rounded-end-custom" 
                                           value="<?= $d['Harga']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-5 mb-4">
                                <label class="form-label fw-bold">Stok Barang</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-cubes text-muted"></i></span>
                                    <input type="number" name="Stok" class="form-control form-control-custom border-start-0 rounded-end-custom" 
                                           value="<?= $d['Stok']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-2">
                            <button type="button" onclick="confirmEdit()" class="btn btn-warning text-white fw-bold btn-update py-3">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="index.php" class="btn btn-link text-muted text-decoration-none small fw-bold mt-2">Batalkan Perubahan</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted" style="font-size: 0.75rem;">
                    Pastikan data harga dan stok sudah sesuai sebelum menekan tombol update.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function confirmEdit() {
    Swal.fire({
        title: 'Konfirmasi Update',
        text: "Perubahan data produk akan langsung diterapkan di sistem",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ff9800',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEdit').submit();
        }
    })
}
</script>
</body>
</html>