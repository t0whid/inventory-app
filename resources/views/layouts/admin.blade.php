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

    {{-- Toastr CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

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
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
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

        .topbar-user-btn {
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 10px;
        }

        .topbar-user-btn:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .user-dropdown {
            width: 300px;
            border-radius: 14px;
            overflow: hidden;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            background: #eef2ff;
            color: #2563eb;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            font-size: 18px;
        }

        .dropdown-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .dropdown-info-label {
            color: #6b7280;
            font-size: 14px;
            white-space: nowrap;
        }

        .dropdown-info-value {
            color: #111827;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
            word-break: break-word;
        }

        .min-w-0 {
            min-width: 0;
        }

        #toast-container>.toast {
            opacity: 1;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
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
                background: rgba(0, 0, 0, 0.45);
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

            .topbar-user-name {
                display: none;
            }

            .user-dropdown {
                width: 270px;
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
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge"></i>
                    Dashboard
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield"></i>
                    Admin Users
                </a>

                <a href="{{ route('admin.products.index') }}"
                    class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box"></i>
                    Products
                </a>

                <a href="{{ route('admin.staffs.index') }}"
                    class="{{ request()->routeIs('admin.staffs.*') ? 'active' : '' }}">
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

        {{-- Main Content --}}
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
                    <button class="btn topbar-user-btn dropdown-toggle d-flex align-items-center gap-2 px-3"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-circle-user fs-5"></i>
                        <span class="topbar-user-name fw-semibold">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end user-dropdown shadow border-0">
                        <li class="px-3 py-3 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar">
                                    <i class="fa-solid fa-user"></i>
                                </div>

                                <div class="min-w-0">
                                    <div class="fw-semibold text-dark text-truncate">
                                        {{ auth()->user()->name ?? 'Admin' }}
                                    </div>
                                    <div class="small text-muted text-truncate">
                                        {{ auth()->user()->phone ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="px-3 py-2">
                            <div class="dropdown-info-row">
                                <span class="dropdown-info-label">
                                    <i class="fa-solid fa-phone me-1"></i>
                                    Phone
                                </span>

                                <span class="dropdown-info-value">
                                    {{ auth()->user()->phone ?? '-' }}
                                </span>
                            </div>
                        </li>

                        <li class="px-3 py-2">
                            <div class="dropdown-info-row">
                                <span class="dropdown-info-label">
                                    <i class="fa-solid fa-user-shield me-1"></i>
                                    Role
                                </span>

                                <span class="dropdown-info-value text-capitalize">
                                    {{ str_replace('_', ' ', auth()->user()->role ?? '-') }}
                                </span>
                            </div>
                        </li>

                        <li>
                            <hr class="dropdown-divider my-2">
                        </li>

                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf

                                <button type="submit" class="dropdown-item text-danger py-2">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- jQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- Toastr JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle?.addEventListener('click', function() {
            sidebar.classList.add('show');
            sidebarOverlay.classList.add('show');
        });

        sidebarOverlay?.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    </script>

    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "4000",
            extendedTimeOut: "1000",
            showDuration: "300",
            hideDuration: "300",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            newestOnTop: true,
            preventDuplicates: true
        };

        @if (session('success'))
            toastr.success(@json(session('success')));
        @endif

        @if (session('error'))
            toastr.error(@json(session('error')));
        @endif

        @if (session('warning'))
            toastr.warning(@json(session('warning')));
        @endif

        @if (session('info'))
            toastr.info(@json(session('info')));
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error(@json($error));
            @endforeach
        @endif
    </script>

    @stack('scripts')

</body>

</html>
