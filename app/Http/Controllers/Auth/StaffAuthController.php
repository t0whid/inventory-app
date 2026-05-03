<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffAuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('staff_id')) {
            return redirect()->route('staff.dashboard');
        }

        return view('auth.staff-login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'pin' => ['required', 'regex:/^[0-9]{4,8}$/'],
        ], [
            'pin.regex' => 'PIN must be 4 to 8 digits only.',
        ]);

        $staff = Staff::where('phone', $validated['phone'])
            ->where('is_active', true)
            ->first();

        if (!$staff || !Hash::check($validated['pin'], $staff->pin)) {
            return back()
                ->withInput($request->only('phone'))
                ->with('error', 'Invalid phone or PIN.');
        }

        session([
            'staff_id' => $staff->id,
            'staff_name' => $staff->name,
            'staff_phone' => $staff->phone,
        ]);

        $request->session()->regenerate();

        return redirect()
            ->route('staff.dashboard')
            ->with('success', 'Staff login successful.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'staff_id',
            'staff_name',
            'staff_phone',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('staff.login')
            ->with('success', 'Logged out successfully.');
    }
}