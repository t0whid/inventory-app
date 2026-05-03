<?php

namespace App\Http\Middleware;

use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('staff_id')) {
            return redirect()
                ->route('staff.login')
                ->with('error', 'Please login first.');
        }

        $staff = Staff::where('id', session('staff_id'))
            ->where('is_active', true)
            ->first();

        if (!$staff) {
            $request->session()->forget([
                'staff_id',
                'staff_name',
                'staff_phone',
            ]);

            return redirect()
                ->route('staff.login')
                ->with('error', 'Your staff account is inactive or not found.');
        }

        view()->share('loggedStaff', $staff);

        return $next($request);
    }
}