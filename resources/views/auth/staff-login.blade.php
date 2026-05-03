<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Login - Inventory App</title>
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
            background: linear-gradient(135deg, #16a34a, #111827);
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
            background: #16a34a;
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
        <i class="fa-solid fa-user"></i>
    </div>

    <h4 class="text-center mb-1">Staff Login</h4>
    <p class="text-center text-muted mb-4">Login with phone and PIN</p>

    <form action="{{ route('staff.login.submit') }}" method="POST">
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
                    placeholder="+91 9876543210"
                    autocomplete="tel"
                >
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">PIN</label>

            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-key"></i>
                </span>

                <input
                    type="password"
                    name="pin"
                    id="pinInput"
                    class="form-control"
                    placeholder="Enter PIN"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="8"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                >

                <button class="btn btn-outline-secondary" type="button" id="togglePin">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-success w-100 py-2 mt-3">
            <i class="fa-solid fa-right-to-bracket me-1"></i>
            Login
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('admin.login') }}" class="small text-decoration-none">
            Admin login
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
    const pinInput = document.getElementById('pinInput');
    const togglePin = document.getElementById('togglePin');

    togglePin?.addEventListener('click', function () {
        const icon = this.querySelector('i');

        if (pinInput.type === 'password') {
            pinInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            pinInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "4000",
        newestOnTop: true,
        preventDuplicates: true
    };

    @if(session('success'))
        toastr.success(@json(session('success')));
    @endif

    @if(session('error'))
        toastr.error(@json(session('error')));
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error(@json($error));
        @endforeach
    @endif
</script>

</body>
</html>