<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Inventory App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    {{-- Toastr CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #2563eb, #111827);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 16px;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        }

        .login-icon {
            width: 64px;
            height: 64px;
            background: #2563eb;
            color: #fff;
            border-radius: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            margin: 0 auto 18px;
        }

        #toast-container > .toast {
            opacity: 1;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border-radius: 8px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 24px 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-icon">
        <i class="fa-solid fa-boxes-stacked"></i>
    </div>

    <h4 class="text-center mb-1">Inventory Admin</h4>
    <p class="text-center text-muted mb-4">Login with phone and password</p>

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-phone"></i>
                </span>
                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="form-control"
                    placeholder="9876543210"
                    autocomplete="tel"
                >
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter password"
                    autocomplete="current-password"
                >
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 mt-3">
            <i class="fa-solid fa-right-to-bracket me-1"></i>
            Login
        </button>
    </form>
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
        extendedTimeOut: "1000",
        showDuration: "300",
        hideDuration: "300",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        newestOnTop: true,
        preventDuplicates: true
    };

    @if(session('success'))
        toastr.success(@json(session('success')));
    @endif

    @if(session('error'))
        toastr.error(@json(session('error')));
    @endif

    @if(session('warning'))
        toastr.warning(@json(session('warning')));
    @endif

    @if(session('info'))
        toastr.info(@json(session('info')));
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error(@json($error));
        @endforeach
    @endif
</script>

</body>
</html>