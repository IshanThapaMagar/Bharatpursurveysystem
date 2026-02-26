<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Ward;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::user();
        $query = User::with(['role.translations', 'ward']);

        if (!$authUser->isSuperAdmin()) {
            if ($authUser->isWardAdmin()) {
                $query->where('ward_id', $authUser->ward_id);
            } else {
                // If somehow a data collector or someone else accesses it
                $query->where('id', $authUser->id);
            }
        }

        $users = $query->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authUser = Auth::user();
        
        // Roles that can be assigned
        if ($authUser->isSuperAdmin()) {
            $roles = Role::where('slug', '!=', 'superadmin')->with('translations')->get();
            $wards = Ward::all();
        } elseif ($authUser->isWardAdmin()) {
            $roles = Role::whereIn('slug', ['ward_admin', 'data_collector'])->with('translations')->get();
            $wards = Ward::where('id', $authUser->ward_id)->get();
        } else {
            abort(403, 'Unauthorized action.');
        }

        return view('users.create', compact('roles', 'wards'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authUser = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $role = Role::find($request->role_id);

        // Security check for role assignment
        if (!$authUser->isSuperAdmin()) {
            if (!$authUser->isWardAdmin() || !in_array($role->slug, ['ward_admin', 'data_collector'])) {
                abort(403, 'Cannot assign this role.');
            }
            // Ward Admins can only assign to their own ward
            if ($request->ward_id != $authUser->ward_id) {
                abort(403, 'Cannot assign to a different ward.');
            }
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'role_id' => $request->role_id,
            'ward_id' => $request->ward_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // To be implemented if needed
        return redirect()->route('users.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // To be implemented if needed
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting itself
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
