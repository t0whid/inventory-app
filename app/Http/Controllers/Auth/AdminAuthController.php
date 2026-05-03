<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt([
            'phone' => $credentials['phone'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $remember)) {
            return back()
                ->withInput($request->only('phone'))
                ->with('error', 'Invalid phone or password.');
        }

        $request->session()->regenerate();

        return redirect()->route('admin.users.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}