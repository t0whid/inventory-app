<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'unique:users,phone'],
            'email' => ['nullable', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['super_admin', 'admin'])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin user created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $loggedUser = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in(['super_admin', 'admin'])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (
            $user->role === 'super_admin'
            && $validated['role'] !== 'super_admin'
            && !$loggedUser->isRootSuperAdmin()
        ) {
            return back()
                ->withInput()
                ->with('error', 'Only Towhid can change a super admin role.');
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin user updated successfully.');
    }

    public function destroy(User $user)
    {
        $loggedUser = Auth::user();

        if ($loggedUser->id === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'super_admin' && !$loggedUser->isRootSuperAdmin()) {
            return back()->with('error', 'Only Towhid can delete a super admin.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin user deleted successfully.');
    }
}