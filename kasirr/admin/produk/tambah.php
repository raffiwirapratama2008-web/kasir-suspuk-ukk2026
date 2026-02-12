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
    <title>Tambah Produk - Kasir SUSPUK</title>
    
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

        .breadcrumb-item a {
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
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
            box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.15);
            border-color: #4facfe;
        }

        .input-group-text-custom {
            background: rgba(255,255,255,0.5);
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 12px 0 0 12px;
            color: #4facfe;
            font-weight: bold;
        }

        .rounded-end-custom {
            border-radius: 0 12px 12px 0 !important;
        }

        .btn-simpan { 
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            transition: all 0.3s; 
        }

        .btn-simpan:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3);
            filter: brightness(1.05);
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include '../../template/sidebar.php'; ?>
    
    <div class="container-fluid p-4">
        <div class="col-md-6 mx-auto mt-4">
            
            <nav aria-label="breadcrumb" class="mb-3">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none"><i class="fas fa-box me-1"></i> Data Produk</a></li>
                <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Tambah Baru</li>
              </ol>
            </nav>

            <div class="glass-card overflow-hidden">
                <div style="height: 6px; background: var(--primary-gradient);"></div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                            <i class="fas fa-plus-circle fa-2x text-primary"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark mb-1">Tambah Produk Baru</h4>
                        <p class="text-muted small">Lengkapi detail produk untuk inventaris toko</p>
                    </div>

                    <form id="formTambah" action="proses_tambah.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-tag text-muted"></i></span>
                                <input type="text" name="NamaProduk" class="form-control form-control-custom border-start-0 rounded-end-custom" 
                                       placeholder="Masukkan nama produk..." required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7 mb-4">
                                <label class="form-label fw-bold">Harga Jual</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-custom">Rp</span>
                                    <input type="number" name="Harga" class="form-control form-control-custom rounded-end-custom" 
                                           placeholder="0" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-5 mb-4">
                                <label class="form-label fw-bold">Stok Awal</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-cubes text-muted"></i></span>
                                    <input type="number" name="Stok" class="form-control form-control-custom border-start-0 rounded-end-custom" 
                                           placeholder="0" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="button" onclick="confirmAdd()" class="btn btn-primary text-white fw-bold btn-simpan">
                                <i class="fas fa-check-circle me-2"></i>Simpan Ke Database
                            </button>
<a href="index.php" class="btn btn-link text-muted text-decoration-none small fw-bold mt-1 btn-back-link">
    <i class="fas fa-arrow-left me-1"></i> Batal & Kembali
</a>                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmAdd() {
    const form = document.getElementById('formTambah');
    const nama = form.NamaProduk.value;
    const harga = form.Harga.value;
    const stok = form.Stok.value;

    if(!nama || !harga || !stok) {
        Swal.fire({
            icon: 'warning',
            title: 'Ups!',
            text: 'Semua kolom wajib diisi.',
            confirmButtonColor: '#4facfe',
            borderRadius: '15px'
        });
        return;
    }

    if(harga < 0 || stok < 0) {
        Swal.fire({
            icon: 'error',
            title: 'Input Salah',
            text: 'Harga/Stok tidak boleh minus.',
            confirmButtonColor: '#4facfe',
            borderRadius: '15px'
        });
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Data',
        text: "Tambahkan '" + nama + "' ke dalam produk?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4facfe',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'Ya, Tambahkan!',
        cancelButtonText: 'Cek Lagi',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
}
</script>
</body>
</html>