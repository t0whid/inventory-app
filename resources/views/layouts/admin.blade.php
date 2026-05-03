<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel') - Inventory App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #111827;
            color: #fff;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-menu {
            padding: 15px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #d1d5db;
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 6px;
            font-size: 15px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #2563eb;
            color: #fff;
        }

        .main-content {
            flex-grow: 1;
            min-width: 0;
        }

        .topbar {
            background: #fff;
            padding: 14px 22px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-area {
            padding: 24px;
        }

        .page-card {
            background: #fff;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.04);
        }

        .mobile-toggle {
            display: none;
            border: 0;
            background: #2563eb;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 991px) {
            .sidebar {
                position: fixed;
                left: -270px;
                top: 0;
                height: 100vh;
                z-index: 1050;
            }

            .sidebar.show {
                left: 0;
            }

            .mobile-toggle {
                display: inline-block;
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.45);
                z-index: 1040;
            }

            .content-area {
                padding: 16px;
            }

            .topbar {
                padding: 12px 16px;
            }
        }

        @media (max-width: 575px) {
            .page-card {
                padding: 16px;
            }

            .table-responsive {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="admin-wrapper">

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-boxes-stacked me-2"></i>
            Inventory App
        </div>

        <div class="sidebar-menu">
            <a href="{{ url('/admin/dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-shield"></i>
                Admin Users
            </a>

            <a href="#" class="{{ request()->is('admin/products*') ? 'active' : '' }}">
                <i class="fa-solid fa-box"></i>
                Products
            </a>

            <a href="#" class="{{ request()->is('admin/staffs*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                Staffs
            </a>

            <a href="#" class="{{ request()->is('admin/reports*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i>
                Reports
            </a>

            <a href="#" class="{{ request()->is('admin/alerts*') ? 'active' : '' }}">
                <i class="fa-solid fa-bell"></i>
                Alerts
            </a>

            <a href="#" class="{{ request()->is('admin/settings*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i>
                Settings
            </a>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Main --}}
    <main class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="mobile-toggle" id="sidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div>
                    <h5 class="mb-0">@yield('page_title', 'Admin Panel')</h5>
                    <small class="text-muted">@yield('page_subtitle', 'Manage your inventory system')</small>
                </div>
            </div>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-user-circle me-1"></i>
                    {{ auth()->user()->name ?? 'Admin' }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <span class="dropdown-item-text small text-muted">
                            {{ auth()->user()->phone ?? '' }}
                        </span>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    sidebarToggle?.addEventListener('click', function () {
        sidebar.classList.add('show');
        sidebarOverlay.classList.add('show');
    });

    sidebarOverlay?.addEventListener('click', function () {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    });
</script>

@stack('scripts')

</body>
</html>