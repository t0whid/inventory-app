<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Staff Panel') - Inventory App</title>
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

        .staff-wrapper {
            min-height: 100vh;
        }

        .staff-topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 18px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .staff-content {
            padding: 18px;
        }

        .page-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        }

        .bottom-nav {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-around;
            padding: 8px 4px;
            z-index: 200;
        }

        .bottom-nav a {
            color: #6b7280;
            text-decoration: none;
            text-align: center;
            font-size: 12px;
            padding: 4px 8px;
        }

        .bottom-nav a i {
            display: block;
            font-size: 18px;
            margin-bottom: 2px;
        }

        .bottom-nav a.active {
            color: #16a34a;
            font-weight: 700;
        }

        .content-with-bottom-nav {
            padding-bottom: 76px;
        }

        #toast-container>.toast {
            opacity: 1;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="staff-wrapper">
        <div class="staff-topbar">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="mb-0">@yield('page_title', 'Staff Panel')</h5>
                    <small class="text-muted">
                        {{ $loggedStaff->name ?? session('staff_name') }}
                    </small>
                </div>

                <form action="{{ route('staff.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="staff-content content-with-bottom-nav">
            @yield('content')
        </div>

        <div class="bottom-nav">
            <a href="{{ route('staff.dashboard') }}"
                class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                Home
            </a>

            <a href="{{ route('staff.stock-entry.index') }}"
                class="{{ request()->routeIs('staff.stock-entry.*') ? 'active' : '' }}">
                <i class="fa-solid fa-boxes-stacked"></i>
                Stock
            </a>

            <a href="{{ route('staff.wastage.index') }}"
                class="{{ request()->routeIs('staff.wastage.*') ? 'active' : '' }}">
                <i class="fa-solid fa-trash"></i>
                Wastage
            </a>

            <a href="{{ route('staff.oos.index') }}" class="{{ request()->routeIs('staff.oos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-xmark"></i>
                OOS
            </a>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- jQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- Toastr JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "4000",
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
