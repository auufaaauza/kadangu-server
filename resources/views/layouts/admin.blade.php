<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-global.css') }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, #1E293B 0%, #0F172A 100%);
            border-right: 1px solid rgba(13, 148, 136, 0.2);
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #2d9d91;
            border-radius: 10px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #0f766e;
        }
        
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.1);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }
        
        .logo-text {
            color: white;
            font-size: 20px;
            font-weight: 700;
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .sidebar-menu {
            padding: 20px 12px;
        }
        
        .menu-section {
            margin-bottom: 24px;
        }
        
        .menu-label {
            color: #94A3B8;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 16px;
            margin-bottom: 8px;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #CBD5E1;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 4px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: #2d9d91; /* Tosca */
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .menu-item:hover::before,
        .menu-item.active::before {
            opacity: 0.1;
        }
        
        .menu-item:hover {
            color: white;
            transform: translateX(4px);
        }
        
        .menu-item.active {
            color: white;
            background: rgba(45, 157, 145, 0.1); /* Tosca with opacity */
        }
        
        .menu-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }
        
        .menu-item.active .menu-icon {
            color: #5fb3a9; /* Light Tosca */
        }
        
        .menu-text {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(139, 92, 246, 0.1);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #94A3B8;
            font-size: 14px;
        }
        
        .breadcrumb-item {
            color: #CBD5E1;
        }
        
        .breadcrumb-item.active {
            color: #EC4899;
            font-weight: 600;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: rgba(139, 92, 246, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .user-menu:hover {
            background: rgba(139, 92, 246, 0.2);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            color: white;
            font-size: 14px;
            font-weight: 600;
        }
        
        .user-role {
            color: #94A3B8;
            font-size: 12px;
        }
        
        /* Page Content */
        .page-content {
            padding: 32px;
        }
        
        .page-header {
            margin-bottom: 32px;
        }
        
        .page-title {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .page-subtitle {
            color: #94A3B8;
            font-size: 14px;
        }
        
        /* Hamburger Button */
        .hamburger {
            display: none;
            width: 40px;
            height: 40px;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 10px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            color: #8B5CF6;
            font-size: 20px;
            transition: all 0.3s ease;
        }
        
        .hamburger:hover {
            background: rgba(139, 92, 246, 0.2);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .hamburger {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-masks-theater"></i>
                </div>
                <span class="logo-text">Kadangu Admin</span>
            </a>
        </div>
        
        <nav class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-label">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-chart-line"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </div>
            
            <div class="menu-section">
                <div class="menu-label">Content Management</div>
                <a href="{{ route('admin.pertunjukan.index') }}" class="menu-item {{ request()->routeIs('admin.pertunjukan.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-ticket"></i></span>
                    <span class="menu-text">Pertunjukan</span>
                </a>
                <a href="{{ route('admin.berita.index') }}" class="menu-item {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-newspaper"></i></span>
                    <span class="menu-text">Berita</span>
                </a>
                <a href="{{ route('admin.banner.index') }}" class="menu-item {{ request()->routeIs('admin.banner.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-image"></i></span>
                    <span class="menu-text">Banner</span>
                </a>
            </div>
            
            <div class="menu-section">
                <div class="menu-label">Kategori Seni</div>
                <a href="{{ route('admin.kategori.index', 'musik') }}" class="menu-item {{ request()->is('admin/kategori/musik*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-music"></i></span>
                    <span class="menu-text">Musik</span>
                </a>
                <a href="{{ route('admin.kategori.index', 'tari') }}" class="menu-item {{ request()->is('admin/kategori/tari*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-user-ninja"></i></span>
                    <span class="menu-text">Tari</span>
                </a>
                <a href="{{ route('admin.kategori.index', 'teater') }}" class="menu-item {{ request()->is('admin/kategori/teater*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-theater-masks"></i></span>
                    <span class="menu-text">Teater</span>
                </a>
                <a href="{{ route('admin.kategori.index', 'seni-rupa') }}" class="menu-item {{ request()->is('admin/kategori/seni-rupa*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-palette"></i></span>
                    <span class="menu-text">Seni Rupa</span>
                </a>
                <a href="{{ route('admin.kategori.index', 'sastra') }}" class="menu-item {{ request()->is('admin/kategori/sastra*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-book-open"></i></span>
                    <span class="menu-text">Sastra</span>
                </a>
                <a href="{{ route('admin.kategori.index', 'film') }}" class="menu-item {{ request()->is('admin/kategori/film*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-film"></i></span>
                    <span class="menu-text">Film</span>
                </a>
                <a href="{{ route('admin.kategori.index', 'budaya') }}" class="menu-item {{ request()->is('admin/kategori/budaya*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-landmark"></i></span>
                    <span class="menu-text">Budaya</span>
                </a>
                <a href="{{ route('admin.kategori.index', 'workshop') }}" class="menu-item {{ request()->is('admin/kategori/workshop*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-handshake"></i></span>
                    <span class="menu-text">Workshop</span>
                </a>
            </div>
            
            <div class="menu-section">
                <div class="menu-label">Orders</div>
                <a href="{{ route('admin.booking.index') }}" class="menu-item {{ request()->routeIs('admin.booking.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-receipt"></i></span>
                    <span class="menu-text">Bookings</span>
                </a>
                <a href="{{ route('admin.transaction.index') }}" class="menu-item {{ request()->routeIs('admin.transaction.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <span class="menu-text">Transactions</span>
                </a>
                <a href="{{ route('admin.payment-settings.index') }}" class="menu-item {{ request()->routeIs('admin.payment-settings.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-credit-card"></i></span>
                    <span class="menu-text">Payment Settings</span>
                </a>
            </div>
            
            <div class="menu-section">
                <div class="menu-label">Talent Management</div>
                <a href="{{ route('admin.talent.index') }}" class="menu-item {{ request()->routeIs('admin.talent.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-star"></i></span>
                    <span class="menu-text">Talents</span>
                </a>
                <a href="{{ route('admin.talent-booking.index') }}" class="menu-item {{ request()->routeIs('admin.talent-booking.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="fas fa-calendar-check"></i></span>
                    <span class="menu-text">Talent Bookings</span>
                </a>
            </div>
            
            <div class="menu-section">
                <div class="menu-label">Account</div>
                <a href="{{ route('profile.edit') }}" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-user-circle"></i></span>
                    <span class="menu-text">Profile</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="menu-item" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                        <span class="menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="menu-text">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="hamburger" id="hamburger">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="breadcrumb">
                    <span class="breadcrumb-item">Admin</span>
                    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                    <span class="breadcrumb-item active">{{ $pageTitle ?? 'Dashboard' }}</span>
                </div>
            </div>
            
            <div class="navbar-right">
                <div class="user-menu">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="page-content">
            @yield('content')
        </div>
    </main>
    
    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
        <i class="fas fa-moon" id="darkModeIcon"></i>
    </button>
    
    <script>
        // Mobile sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger');
        
        hamburger.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnHamburger = hamburger.contains(event.target);
            
            if (!isClickInsideSidebar && !isClickOnHamburger && window.innerWidth <= 768) {
                sidebar.classList.remove('active');
            }
        });
        
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeIcon = document.getElementById('darkModeIcon');
        const body = document.body;
        
        // Check for saved theme preference or default to dark mode
        const currentTheme = localStorage.getItem('theme') || 'dark';
        
        // Apply saved theme
        if (currentTheme === 'light') {
            body.classList.add('light-mode');
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
        }
        
        // Toggle dark mode
        darkModeToggle.addEventListener('click', function() {
            body.classList.toggle('light-mode');
            
            if (body.classList.contains('light-mode')) {
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
                localStorage.setItem('theme', 'light');
            } else {
                darkModeIcon.classList.remove('fa-sun');
                darkModeIcon.classList.add('fa-moon');
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>

