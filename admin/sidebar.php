<?php
// admin/sidebar.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php'; 

$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['admin_id']) && $current_page !== 'login.php') {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['admin_id']) && $current_page === 'login.php') {
    header("Location: dashboard.php");
    exit();
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --sidebar-bg: #0d021b;
        --accent: #7c3aed;
        --text-dim: #a1a1aa;
        --sidebar-width: 280px;
    }

    /* 1. HIDE SCROLLBARS GLOBALLY FOR SIDEBAR */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    /* Sidebar Base */
    .admin-sidebar {
        width: var(--sidebar-width);
        background: var(--sidebar-bg);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        padding: 2rem 1.2rem;
        z-index: 1000;
        transition: transform 0.5s cubic-bezier(0.77, 0.2, 0.05, 1);
        display: flex;
        flex-direction: column;
    }

    /* Main Content */
    .admin-main-content {
        margin-left: var(--sidebar-width);
        padding: 40px;
        background: #05010a;
        min-height: 100vh;
        transition: all 0.5s ease;
    }

    /* Navigation Links */
    .nav-link {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 16px 20px;
        color: var(--text-dim);
        text-decoration: none;
        border-radius: 16px;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-link i { font-size: 1.2rem; }

    .nav-link:hover {
        background: rgba(124, 58, 237, 0.1);
        color: white;
        transform: translateX(5px);
    }

    .nav-link.active {
        background: var(--accent);
        color: white;
        box-shadow: 0 15px 30px -10px rgba(124, 58, 237, 0.5);
    }

    /* Mobile Bottom Toggle Bar */
    .mobile-bottom-nav {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 70px;
        background: rgba(13, 2, 27, 0.8);
        backdrop-filter: blur(20px);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        z-index: 1100;
        justify-content: space-around;
        align-items: center;
        padding: 0 20px;
    }

    .toggle-trigger {
        background: var(--accent);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: 1.2rem;
        box-shadow: 0 8px 20px rgba(124, 58, 237, 0.3);
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        z-index: 999;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    /* Responsive Logic */
    @media (max-width: 1024px) {
        .admin-sidebar {
            transform: translateX(-100%);
            border-radius: 0 40px 40px 0; /* Fancy curved edge on mobile */
        }

        .admin-sidebar.active {
            transform: translateX(0);
        }

        .admin-main-content {
            margin-left: 0;
            width: 100%;
            padding: 20px;
            padding-bottom: 100px; /* Space for bottom nav */
        }

        .mobile-bottom-nav {
            display: flex;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
    }
</style>

<div class="mobile-bottom-nav">
    <a href="dashboard.php" class="text-zinc-500 text-xl"><i class="fa-solid fa-house"></i></a>
    <button class="toggle-trigger" id="menuBtn">
        <i class="fa-solid fa-bars-staggered" id="menuIcon"></i>
    </button>
    <a href="logout.php" class="text-red-500 text-xl"><i class="fa-solid fa-power-off"></i></a>
</div>

<div class="sidebar-overlay" id="overlay"></div>

<aside class="admin-sidebar no-scrollbar" id="sidebar">
    <div class="mb-12 px-4">
        <h2 class="text-white font-black text-3xl tracking-tighter">SEYI<span class="text-purple-500">DEV</span></h2>
        <p class="text-[10px] text-zinc-500 uppercase font-bold tracking-[0.3em]">Administrator</p>
    </div>

    <nav class="flex-1 overflow-y-auto no-scrollbar">
        <span class="menu-label" style="color: var(--text-dim); font-size: 10px; text-transform: uppercase; letter-spacing: 2px; font-weight: 800; margin: 20px 0 15px 10px; display: block;">Core</span>
        
        <a href="dashboard.php" class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-grip"></i>
            <span>Overview</span>
        </a>

        <a href="user_register.php" class="nav-link <?= $current_page == 'user_register.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Admins</span>
        </a>

        <a href="team_members.php" class="nav-link <?= $current_page == 'team_members.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-user-group"></i>
            <span>Team</span>
        </a>

        <span class="menu-label" style="color: var(--text-dim); font-size: 10px; text-transform: uppercase; letter-spacing: 2px; font-weight: 800; margin: 30px 0 15px 10px; display: block;">Inventory</span>

        <a href="product_upload.php" class="nav-link <?= $current_page == 'product_upload.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-layer-group"></i>
            <span>Products</span>
        </a>

        <a href="enquiry.php" class="nav-link <?= $current_page == 'enquiry.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-comment-dots"></i>
            <span>Enquiries</span>
        </a>

        <div class="mt-auto pt-10">
            <a href="logout.php" class="nav-link text-red-500 border border-red-500/10 hover:bg-red-500 hover:text-white">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</aside>

<script>
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const menuIcon = document.getElementById('menuIcon');

    function toggleMenu() {
        const isOpen = sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        // Morph the icon
        if(isOpen) {
            menuIcon.classList.replace('fa-bars-staggered', 'fa-chevron-down');
            menuBtn.style.transform = "rotate(180deg)";
        } else {
            menuIcon.classList.replace('fa-chevron-down', 'fa-bars-staggered');
            menuBtn.style.transform = "rotate(0deg)";
        }
    }

    menuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMenu();
    });

    overlay.addEventListener('click', toggleMenu);

    // Auto-close on resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
    });
</script>