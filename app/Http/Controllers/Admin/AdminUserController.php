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
        $loggedUser = Auth::user();

        $query = User::latest();

        /*
         * Root super admin is hidden from everyone except root super admin.
         * No UI will show that root super admin exists.
         */
        if (!$loggedUser->isRootSuperAdmin()) {
            $rootUserId = $this->rootSuperAdminId();

            if ($rootUserId) {
                $query->where('id', '!=', $rootUserId);
            }
        }

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users', 'loggedUser'));
    }

    public function create()
    {
        $loggedUser = Auth::user();

        if (!$this->canCreateUser($loggedUser)) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You are not allowed to create admin users.');
        }

        return view('admin.users.create', compact('loggedUser'));
    }

    public function store(Request $request)
    {
        $loggedUser = Auth::user();

        if (!$this->canCreateUser($loggedUser)) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You are not allowed to create admin users.');
        }

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
        $loggedUser = Auth::user();

        if (!$this->canEditUser($loggedUser, $user)) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You are not allowed to edit this user.');
        }

        $canManageRoleStatus = $this->canManageRoleStatus($loggedUser, $user);

        return view('admin.users.edit', compact(
            'user',
            'loggedUser',
            'canManageRoleStatus'
        ));
    }

    public function update(Request $request, User $user)
    {
        $loggedUser = Auth::user();

        if (!$this->canEditUser($loggedUser, $user)) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You are not allowed to update this user.');
        }

        $rules = [
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
        ];

        if ($this->canManageRoleStatus($loggedUser, $user)) {
            $rules['role'] = ['required', Rule::in(['super_admin', 'admin'])];
            $rules['is_active'] = ['nullable', 'boolean'];
        }

        $validated = $request->validate($rules);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        if ($this->canManageRoleStatus($loggedUser, $user)) {
            $validated['is_active'] = $request->boolean('is_active');
        } else {
            unset($validated['role'], $validated['is_active']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin user updated successfully.');
    }

    public function destroy(User $user)
    {
        $loggedUser = Auth::user();

        if (!$this->canDeleteUser($loggedUser, $user)) {
            return back()->with('error', 'You are not allowed to delete this user.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Admin user deleted successfully.');
    }

    private function canCreateUser(User $loggedUser): bool
    {
        return $loggedUser->role === 'super_admin';
    }

    private function canEditUser(User $loggedUser, User $targetUser): bool
    {
        if ($loggedUser->isRootSuperAdmin()) {
            return true;
        }

        if ($targetUser->isRootSuperAdmin()) {
            return false;
        }

        if ($loggedUser->role === 'super_admin') {
            if ($loggedUser->id === $targetUser->id) {
                return true;
            }

            return $targetUser->role === 'admin';
        }

        if ($loggedUser->role === 'admin') {
            return $loggedUser->id === $targetUser->id;
        }

        return false;
    }

    private function canDeleteUser(User $loggedUser, User $targetUser): bool
    {
        if ($loggedUser->id === $targetUser->id) {
            return false;
        }

        if ($loggedUser->isRootSuperAdmin()) {
            return true;
        }

        if ($targetUser->isRootSuperAdmin()) {
            return false;
        }

        if ($loggedUser->role === 'super_admin') {
            return $targetUser->role === 'admin';
        }

        return false;
    }

    private function canManageRoleStatus(User $loggedUser, User $targetUser): bool
    {
        if ($loggedUser->isRootSuperAdmin()) {
            return true;
        }

        /*
         * Other super admin can manage admin users only.
         * Other super admin can edit own profile,
         * but cannot change own role/status.
         */
        if ($loggedUser->role === 'super_admin' && $targetUser->role === 'admin') {
            return true;
        }

        return false;
    }

    private function rootSuperAdminId(): ?int
    {
        $rootPhone = config('app.root_super_admin_phone');

        if (!$rootPhone) {
            return null;
        }

        return User::query()
            ->where('role', 'super_admin')
            ->where('phone', $rootPhone)
            ->value('id');
    }
}