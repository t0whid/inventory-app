<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: white;
            padding: 30px;
            width: 380px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h2 {
            margin-top: 0;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 11px;
            margin-top: 6px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .error {
            color: #dc2626;
            margin-bottom: 12px;
            text-align: center;
        }

        .field-error {
            color: #dc2626;
            font-size: 14px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
        }

        .remember input {
            width: auto;
            margin: 0;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Admin Login</h2>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        <label>Phone</label>
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="01700000000">
        @error('phone') <div class="field-error">{{ $message }}</div> @enderror

        <label>Password</label>
        <input type="password" name="password" placeholder="Password">
        @error('password') <div class="field-error">{{ $message }}</div> @enderror

        <label class="remember">
            <input type="checkbox" name="remember" value="1">
            Remember me
        </label>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>