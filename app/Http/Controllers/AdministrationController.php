<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PalikaAdmin;
use App\Models\PalikaDesignation;
use Illuminate\Support\Facades\Storage;

class AdministrationController extends Controller
{
    public function index()
    {
        $admins = PalikaAdmin::with('designation.translations')
            ->select('id', 'name', 'designation_id', 'email', 'phone', 'photo')
            ->get();
        return view("palika.index", compact('admins'));
    }

    public function createAdmin()
    {
        $designations = PalikaDesignation::with('translations')->get();
        $usedDesignations = PalikaAdmin::pluck('designation_id')->toArray();
        return view("palika.addAdmin", compact('designations', 'usedDesignations'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation_id' => 'required|exists:palika_designations,id|unique:palika_admins,designation_id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('palika_admins', 'public');
        }

        PalikaAdmin::create([
            'name' => $request->name,
            'designation_id' => $request->designation_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => $photoPath,
        ]);

        return redirect()->route('palika.index')->with('success', 'Admin added successfully.');
    }
}
