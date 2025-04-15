<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the provinces.
     */
    public function index(Request $request)
    {
        $query = Province::query();

        if ($request->filled('search')) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->search) . '%']);
        }

        $provinces = $query->withCount(['users', 'posts'])
            ->paginate(10)
            ->withQueryString();

        return view('admin.provinces.index', compact('provinces'));
    }

    /**
     * Show the form for creating a new province.
     */
    public function create()
    {
        return view('admin.provinces.create');
    }

    /**
     * Store a newly created province in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:provinces,name',
        ]);

        Province::create($validated);

        return redirect()->route('admin.provinces.index')
            ->with('success', 'Province created successfully.');
    }

    /**
     * Display the specified province.
     */
    public function show(Province $province)
    {
        $province->load(['users', 'posts']);

        return view('admin.provinces.show', compact('province'));
    }

    /**
     * Show the form for editing the specified province.
     */
    public function edit(Province $province)
    {
        $province->load('users');

        // Get users not already assigned to this province
        $availableUsers = User::where('role', '!=', 'admin')
                              ->where(function ($query) use ($province) {
                                  $query->where('role', '!=', 'staff')
                                        ->orWhere('province_id', '!=', $province->id)
                                        ->orWhereNull('province_id');
                              })
                              ->get();

        return view('admin.provinces.edit', compact('province', 'availableUsers'));
    }

    /**
     * Update the specified province in storage.
     */
    public function update(Request $request, Province $province)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:provinces,name,' . $province->id,
        ]);

        $province->update($validated);

        return redirect()->route('admin.provinces.show', $province)
            ->with('success', 'Province updated successfully.');
    }

    /**
     * Remove the specified province from storage.
     */
    public function destroy(Province $province)
    {
        // Check if province has associated posts or users
        if ($province->posts()->count() > 0 || $province->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete province with associated posts or users.');
        }

        $province->delete();

        return redirect()->route('admin.provinces.index')
            ->with('success', 'Province deleted successfully.');
    }

    /**
     * Assign a user as staff to this province.
     */
    public function assignStaff(Request $request, Province $province)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->role = 'staff';
        $user->province_id = $province->id;
        $user->save();

        return redirect()->route('admin.provinces.edit', $province)
            ->with('success', 'Staff member assigned successfully.');
    }

    /**
     * Remove a staff member from this province.
     */
    public function removeStaff(Province $province, User $user)
    {
        if ($user->province_id !== $province->id || $user->role !== 'staff') {
            return redirect()->back()
                ->with('error', 'This user is not a staff member of this province.');
        }

        $user->role = 'user';
        $user->province_id = null;
        $user->save();

        return redirect()->route('admin.provinces.edit', $province)
            ->with('success', 'Staff member removed successfully.');
    }
}
