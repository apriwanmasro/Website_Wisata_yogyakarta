<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Wisata Yogyakarta') | VisitJogja</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --jogja-maroon: #8B1A1A;
            --jogja-gold: #C8923A;
            --jogja-cream: #FDF6EC;
            --jogja-dark: #1A0A0A;
            --jogja-brown: #5C3317;
            --jogja-light: #FFF9F0;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--jogja-cream);
            color: var(--jogja-dark);
            min-height: 100vh;
        }

        /* ========= SIDEBAR ========= */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--jogja-dark) 0%, var(--jogja-maroon) 60%, var(--jogja-brown) 100%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23C8923A' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(200, 146, 58, 0.3);
            position: relative;
        }

        .sidebar-logo .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--jogja-gold), #e8a84a);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 10px;
            box-shadow: 0 4px 15px rgba(200, 146, 58, 0.4);
        }

        .sidebar-logo .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            line-height: 1.1;
        }

        .sidebar-logo .brand-sub {
            font-size: 11px;
            color: var(--jogja-gold);
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .sidebar-nav .nav-label {
            font-size: 10px;
            font-weight: 700;
            color: rgba(200, 146, 58, 0.7);
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 12px 10px 6px;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
            position: relative;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(200, 146, 58, 0.15);
            color: var(--jogja-gold);
        }

        .sidebar-nav .nav-link.active {
            background: linear-gradient(135deg, var(--jogja-gold), #e8a84a);
            color: var(--jogja-dark);
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(200, 146, 58, 0.4);
        }

        .sidebar-nav .nav-link i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(200, 146, 58, 0.2);
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            text-align: center;
        }

        /* ========= MAIN CONTENT ========= */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ========= TOPBAR ========= */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: rgba(253, 246, 236, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(139, 26, 26, 0.1);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: between;
            gap: 16px;
        }

        .topbar .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--jogja-maroon);
            flex: 1;
        }

        .topbar .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar .btn-jogja {
            background: linear-gradient(135deg, var(--jogja-maroon), #a52020);
            color: white;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s;
            box-shadow: 0 3px 10px rgba(139, 26, 26, 0.3);
        }

        .btn-jogja {
            background: linear-gradient(135deg, var(--jogja-maroon), #6B1414);
            color: white;
            padding: 8px 16px;
            /* ← Harusnya kecil, bukan 20px */
            border-radius: 10px;
            font-size: 13px;
            /* ← Harusnya kecil */
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .topbar .btn-jogja:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(139, 26, 26, 0.4);
            color: white;
        }

        /* ========= PAGE CONTENT ========= */
        .page-content {
            padding: 28px 32px;
            flex: 1;
        }

        /* ========= CARDS ========= */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(139, 26, 26, 0.08);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(200, 146, 58, 0.1), transparent);
            transform: translate(20px, -20px);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .stat-card .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--jogja-dark);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }

        /* ========= TABLE ========= */
        .table-card {
            background: white;
            border-radius: 16px;
            border: 1px solid rgba(139, 26, 26, 0.08);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .table-card .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(139, 26, 26, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card .table-header h5 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--jogja-maroon);
            margin: 0;
        }

        .wisata-table {
            width: 100%;
            margin: 0;
        }

        .wisata-table thead th {
            background: linear-gradient(135deg, var(--jogja-maroon), #a52020);
            color: white;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 14px 16px;
            border: none;
        }

        .wisata-table tbody tr {
            transition: background 0.15s;
        }

        .wisata-table tbody tr:hover {
            background: var(--jogja-light);
        }

        .wisata-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            vertical-align: middle;
            font-size: 14px;
        }

        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .kategori-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            color: white;
        }

        /* ========= BUTTONS ========= */
        .btn-action {
            background: linear-gradient(135deg, var(--jogja-maroon), #6B1414);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #6B1414, var(--jogja-maroon));
        }

        .btn-view {
            background: var(--jogja-gold);
            color: var(--jogja-dark);
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background: #e8a84a;
            color: white;
            box-shadow: 0 4px 10px rgba(200, 146, 58, 0.4);
        }

        .btn-edit {
            background: var(--jogja-maroon);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background: #a52020;
            box-shadow: 0 4px 10px rgba(139, 26, 26, 0.4);
        }

        .btn-delete {
            background: var(--jogja-dark);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background: #5C3317;
            box-shadow: 0 4px 10px rgba(92, 51, 23, 0.4);
        }

        /* ========= FORM ========= */
        .form-control,
        .form-select {
            border: 1px solid rgba(139, 26, 26, 0.3);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--jogja-maroon);
            box-shadow: 0 0 5px rgba(139, 26, 26, 0.5);
            outline: none;
        }

        .btn-submit {
            background: var(--jogja-gold);
            color: var(--jogja-dark);
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(200, 146, 58, 0.3);
        }

        .btn-submit:hover {
            background: #e8a84a;
            box-shadow: 0 6px 15px rgba(200, 146, 58, 0.4);
            transform: translateY(-2px);
        }

        /* ========= ALERTS ========= */
        .alert-jogja {
            border-radius: 12px;
            border: none;
            padding: 14px 18px;
            font-size: 14px;
            font-weight: 500;
        }

        /* ========= SECTION DIVIDER ========= */
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--jogja-maroon);
            padding-bottom: 8px;
            border-bottom: 2px solid var(--jogja-gold);
            margin-bottom: 20px;
            display: inline-block;
        }

        /* ========= SEARCH BAR ========= */
        .search-bar {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            border: 1px solid rgba(139, 26, 26, 0.08);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        /* ========= SCROLLBAR ========= */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(139, 26, 26, 0.3);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--jogja-maroon);
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">🏛️</div>
            <div class="brand-name">VisitJogja</div>
            <div class="brand-sub">Tourism Management</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>

            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('wisata.index') }}" class="nav-link {{ request()->routeIs('wisata.index') ? 'active' : '' }}">
                <i class="bi bi-collection"></i>
                <span>Data Wisata</span>
            </a>

            <a href="{{ route('wisata.create') }}" class="nav-link {{ request()->routeIs('wisata.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Wisata</span>
            </a>

            <div class="nav-label" style="margin-top:8px">Visualisasi</div>

            <a href="{{ route('wisata.map') }}" class="nav-link {{ request()->routeIs('wisata.map') ? 'active' : '' }}">
                <i class="bi bi-map"></i>
                <span>Peta Wisata</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div>🌿 Daerah Istimewa Yogyakarta</div>
            <div style="margin-top:4px">15 Destinasi Terpopuler</div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            <div class="topbar-actions">
                @yield('topbar-actions')
                <a href="{{ route('wisata.create') }}" class="btn-jogja">
                    <i class="bi bi-plus-lg"></i> Tambah Wisata
                </a>
            </div>
        </div>

        <!-- ALERTS -->
        <div style="padding: 0 32px; margin-top: 16px;">
            @if(session('success'))
            <div class="alert alert-success alert-jogja alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-jogja alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>

        <!-- PAGE CONTENT -->
        <div class="page-content">
            @yield('content')
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>

</html>