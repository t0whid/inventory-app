<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::latest()->paginate(20);

        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staffs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'unique:staffs,phone'],
            'pin' => ['required', 'regex:/^[0-9]{4,8}$/'],
            'role' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'pin.regex' => 'PIN must be 4 to 8 digits only.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Staff::create($validated);

        return redirect()
            ->route('admin.staffs.index')
            ->with('success', 'Staff created successfully.');
    }

    public function edit(Staff $staff)
    {
        return view('admin.staffs.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => [
                'required',
                'string',
                'max:30',
                Rule::unique('staffs', 'phone')->ignore($staff->id),
            ],
            'pin' => ['nullable', 'regex:/^[0-9]{4,8}$/'],
            'role' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'pin.regex' => 'PIN must be 4 to 8 digits only.',
        ]);

        if (empty($validated['pin'])) {
            unset($validated['pin']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $staff->update($validated);

        return redirect()
            ->route('admin.staffs.index')
            ->with('success', 'Staff updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()
            ->route('admin.staffs.index')
            ->with('success', 'Staff deleted successfully.');
    }
}
