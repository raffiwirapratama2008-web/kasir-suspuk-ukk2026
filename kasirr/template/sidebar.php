<?php
// Mengambil nama folder aktif untuk menentukan menu mana yang 'active'
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * {
        font-family: 'Poppins', sans-serif;
    }

    /* Disable Animation on First Load */
    .no-transition {
        transition: none !important;
    }

    /* Simple 3D Sidebar */
    .sidebar-3d {
        background: linear-gradient(145deg, #2d3748, #1a202c);
        width: 270px;
        min-height: 100vh;
        position: fixed;
        box-shadow: 
            10px 0 30px rgba(0, 0, 0, 0.5),
            inset -5px 0 15px rgba(0, 0, 0, 0.3);
        transition: all 0.4s ease;
        z-index: 1000;
        transform-style: preserve-3d;
        perspective: 1000px;
    }

    .sidebar-3d.collapsed {
        width: 85px;
    }

    /* Header with 3D Effect */
    .header-3d {
        padding: 25px 20px;
        background: linear-gradient(145deg, #3d4757, #2d3748);
        box-shadow: 
            0 5px 15px rgba(0, 0, 0, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transform: translateZ(20px);
    }

    .logo-3d {
        display: flex;
        align-items: center;
        gap: 12px;
        color: white;
    }

    .logo-icon-3d {
        width: 45px;
        height: 45px;
        background: linear-gradient(145deg,#a78847, #e2b45f);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        box-shadow: 
            5px 5px 15px rgba(0, 0, 0, 0.4),
            -2px -2px 8px rgba(255, 255, 255, 0.1);
        transform: translateZ(10px);
    }

    .logo-text-3d {
        font-weight: 800;
        font-size: 1.3rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .sidebar-3d.collapsed .logo-text-3d {
        display: none;
    }

    /* Toggle Button 3D */
    .toggle-3d {
        width: 35px;
        height: 35px;
        background: linear-gradient(145deg, #3d4757, #2d3748);
        border: none;
        border-radius: 8px;
        color: #667eea;
        cursor: pointer;
        box-shadow: 
            3px 3px 8px rgba(0, 0, 0, 0.4),
            -2px -2px 6px rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }

    .toggle-3d:hover {
        box-shadow: 
            5px 5px 12px rgba(0, 0, 0, 0.5),
            -3px -3px 8px rgba(255, 255, 255, 0.08);
        transform: scale(1.05);
    }

    .toggle-3d:active {
        box-shadow: 
            inset 3px 3px 8px rgba(0, 0, 0, 0.5),
            inset -2px -2px 6px rgba(255, 255, 255, 0.05);
    }

    /* Navigation 3D */
    .nav-3d {
        padding: 20px 15px;
        list-style: none;
        margin: 0;
    }

    .nav-item-3d {
        margin-bottom: 10px;
    }

    .nav-link-3d {
        display: flex;
        align-items: center;
        padding: 14px 16px;
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        font-weight: 600;
        background: linear-gradient(145deg, #2d3748, #1a202c);
        box-shadow: 
            3px 3px 8px rgba(0, 0, 0, 0.3),
            -2px -2px 6px rgba(255, 255, 255, 0.03);
        transform: translateZ(5px);
    }

    /* Hover Effect - Raised 3D */
    .nav-link-3d:hover:not(.active) {
        color: white;
        transform: translateZ(15px) translateX(5px);
        box-shadow: 
            5px 5px 15px rgba(0, 0, 0, 0.4),
            -3px -3px 10px rgba(255, 255, 255, 0.05),
            inset 1px 1px 2px rgba(102, 126, 234, 0.2);
    }

    /* Active Menu - Pressed 3D */
    .nav-link-3d.active {
        background: linear-gradient(145deg, #a78847, #e2b45f);
        color: white !important;
        box-shadow: 
            inset 3px 3px 8px rgba(0, 0, 0, 0.3),
            inset -2px -2px 6px rgba(255, 255, 255, 0.2),
            0 0 20px rgba(102, 126, 234, 0.4);
        transform: translateZ(2px);
    }

    /* Icon 3D */
    .icon-3d {
        min-width: 22px;
        font-size: 1.1rem;
        margin-right: 12px;
        text-align: center;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .sidebar-3d.collapsed .icon-3d {
        margin-right: 0;
    }

    .sidebar-3d.collapsed .nav-text-3d {
        display: none;
    }

    .sidebar-3d.collapsed .nav-link-3d {
        justify-content: center;
        padding: 14px 0;
    }

    /* User Card 3D */
    .user-card-3d {
        margin: 20px 15px;
        padding: 15px;
        background: linear-gradient(145deg, #3d4757, #2d3748);
        border-radius: 12px;
        box-shadow: 
            5px 5px 15px rgba(0, 0, 0, 0.4),
            -3px -3px 10px rgba(255, 255, 255, 0.05);
        display: flex;
        align-items: center;
        gap: 12px;
        transform: translateZ(10px);
    }

    .user-avatar-3d {
        width: 42px;
        height: 42px;
        background: linear-gradient(145deg, #a78847, #e2b45f);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.2rem;
        color: white;
        box-shadow: 
            3px 3px 8px rgba(0, 0, 0, 0.4),
            -2px -2px 6px rgba(255, 255, 255, 0.1);
    }

    .user-info-3d {
        flex: 1;
    }

    .user-name-3d {
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .user-role-3d {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .sidebar-3d.collapsed .user-card-3d {
        padding: 12px;
        justify-content: center;
    }

    .sidebar-3d.collapsed .user-info-3d {
        display: none;
    }

    /* Logout Button 3D */
    .logout-3d {
        position: absolute;
        bottom: 20px;
        left: 15px;
        right: 15px;
    }

    .btn-logout-3d {
        width: 100%;
        padding: 14px;
        background: linear-gradient(145deg, #e53e3e, #c53030);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 
            4px 4px 12px rgba(0, 0, 0, 0.4),
            -2px -2px 8px rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        transform: translateZ(5px);
    }

    .btn-logout-3d:hover {
        box-shadow: 
            6px 6px 18px rgba(0, 0, 0, 0.5),
            -3px -3px 10px rgba(255, 255, 255, 0.15);
        transform: translateZ(15px) scale(1.02);
        color: white;
    }

    .btn-logout-3d:active {
        box-shadow: 
            inset 3px 3px 8px rgba(0, 0, 0, 0.5),
            inset -2px -2px 6px rgba(255, 255, 255, 0.1);
        transform: translateZ(2px);
    }

    .sidebar-3d.collapsed .logout-text-3d {
        display: none;
    }

    /* Spacer */
    .sidebar-spacer-3d {
        width: 270px;
        flex-shrink: 0;
        transition: all 0.4s ease;
    }

    .sidebar-spacer-3d.collapsed {
        width: 85px;
    }

    /* Divider 3D */
    .divider-3d {
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
        margin: 15px 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    /* Tooltip for collapsed state */
    .sidebar-3d.collapsed .nav-link-3d {
        position: relative;
    }

    .sidebar-3d.collapsed .nav-link-3d:hover::after {
        content: attr(data-title);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(145deg, #a78847, #e2b45f);
        color: white;
        padding: 8px 15px;
        border-radius: 8px;
        white-space: nowrap;
        margin-left: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.4);
        z-index: 1000;
        animation: tooltipFade 0.3s ease;
    }

    @keyframes tooltipFade {
        from {
            opacity: 0;
            margin-left: 10px;
        }
        to {
            opacity: 1;
            margin-left: 15px;
        }
    }

    /* Scrollbar 3D */
    .sidebar-3d::-webkit-scrollbar {
        width: 8px;
    }

    .sidebar-3d::-webkit-scrollbar-track {
        background: #1a202c;
        box-shadow: inset 2px 0 5px rgba(0, 0, 0, 0.5);
    }

    .sidebar-3d::-webkit-scrollbar-thumb {
        background: linear-gradient(145deg, #a78847, #e2b45f);
        border-radius: 10px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
    }

    /* Badge 3D */
    .badge-3d {
        background: linear-gradient(145deg, #a78847, #e2b45f);
        color: white;
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-left: auto;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.3);
    }

    .sidebar-3d.collapsed .badge-3d {
        display: none;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .sidebar-3d {
            width: 85px;
        }
        
        .sidebar-spacer-3d {
            width: 85px;
        }
        
        .sidebar-3d .logo-text-3d,
        .sidebar-3d .nav-text-3d,
        .sidebar-3d .logout-text-3d,
        .sidebar-3d .user-info-3d,
        .sidebar-3d .badge-3d {
            display: none;
        }
        
        .sidebar-3d .nav-link-3d {
            justify-content: center;
        }

        .sidebar-3d .icon-3d {
            margin-right: 0;
        }
    }
</style>

<div class="sidebar-3d no-transition" id="mainSidebar">
    <!-- Header -->
    <div class="header-3d">
        <div class="logo-3d">
            <div class="logo-icon-3d">
                <i class="fas fa-cash-register"></i>
            </div>
            <div class="logo-text-3d">SUSPUK</div>
        </div>
        <button class="toggle-3d" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="divider-3d"></div>

    

    <!-- Navigation -->
    <ul class="nav-3d">
        <li class="nav-item-3d">
            <a href="../dashboard/index.php" 
               class="nav-link-3d <?= ($current_dir == 'dashboard') ? 'active' : ''; ?>"
               data-title="Dashboard">
                <i class="icon-3d fas fa-chart-pie"></i>
                <span class="nav-text-3d">Dashboard</span>
            </a>
        </li>
        <li class="nav-item-3d">
            <a href="../penjualan/index.php" 
               class="nav-link-3d <?= ($current_dir == 'penjualan') ? 'active' : ''; ?>"
               data-title="Penjualan">
                <i class="icon-3d fas fa-shopping-cart"></i>
                <span class="nav-text-3d">Penjualan</span>
            </a>
        </li>
        <li class="nav-item-3d">
            <a href="../produk/index.php" 
               class="nav-link-3d <?= ($current_dir == 'produk') ? 'active' : ''; ?>"
               data-title="Data Produk">
                <i class="icon-3d fas fa-boxes"></i>
                <span class="nav-text-3d">Data Produk</span>
            </a>
        </li>
        <li class="nav-item-3d">
            <a href="../pelanggan/index.php" 
               class="nav-link-3d <?= ($current_dir == 'pelanggan') ? 'active' : ''; ?>"
               data-title="Data Pelanggan">
                <i class="icon-3d fas fa-user-tag"></i>
                <span class="nav-text-3d">Data Pelanggan</span>
            </a>
        </li>
        <?php if($_SESSION['role'] == 'admin'): ?>
        <li class="nav-item-3d">
            <a href="../petugas/index.php" 
               class="nav-link-3d <?= ($current_dir == 'petugas') ? 'active' : ''; ?>"
               data-title="Registrasi">
                <i class="icon-3d fas fa-users-cog"></i>
                <span class="nav-text-3d">Registrasi</span>
                <span class="badge-3d">Admin</span>
            </a>
        </li>
        <?php endif; ?>
        <li class="nav-item-3d">
            <a href="../laporan/index.php" 
               class="nav-link-3d <?= ($current_dir == 'laporan') ? 'active' : ''; ?>"
               data-title="Laporan">
                <i class="icon-3d fas fa-file-invoice-dollar"></i>
                <span class="nav-text-3d">Laporan</span>
            </a>
        </li>
    </ul>

    <!-- Logout -->
    <div class="logout-3d">
        <a href="../../auth/logout.php" class="btn-logout-3d" 
           onclick="return confirm('Yakin ingin keluar?')">
            <i class="fas fa-power-off"></i>
            <span class="logout-text-3d">Keluar</span>
        </a>
    </div>
</div>

<!-- Spacer -->
<div class="sidebar-spacer-3d d-none d-md-block no-transition" id="mainSpacer"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const sidebar = $("#mainSidebar");
    const spacer = $("#mainSpacer");
    const btn = $("#toggleSidebar");

    // Load saved state
    if (localStorage.getItem("sidebar_collapsed") === "true") {
        sidebar.addClass("collapsed");
        spacer.addClass("collapsed");
    }

    // Enable transitions after load
    setTimeout(function() {
        sidebar.removeClass("no-transition");
        spacer.removeClass("no-transition");
    }, 100);

    // Toggle sidebar
    btn.click(function() {
        sidebar.toggleClass("collapsed");
        spacer.toggleClass("collapsed");
        
        const isCollapsed = sidebar.hasClass("collapsed");
        localStorage.setItem("sidebar_collapsed", isCollapsed);
    });
});
</script>