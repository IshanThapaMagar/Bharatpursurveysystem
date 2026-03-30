<?php

namespace App\Http\Controllers;

use App\Models\ImportantSite;
use App\Models\Ward;
use App\Models\PlaceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportantSiteController extends Controller
{
    public function index()
    {
        $sites = ImportantSite::with(['ward', 'placeType'])->latest()->get();
        return view('important-sites.index', compact('sites'));
    }

    public function create()
    {
        $wards = Ward::orderBy('ward_no')->get();
        $placeTypes = PlaceType::all();
        return view('important-sites.create', compact('wards', 'placeTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'place_name' => 'required|string|max:255',
            'ward_id' => 'required|exists:wards,id',
            'place_type_id' => 'required|exists:place_types,id',
            'place_description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $ward = Ward::find($request->ward_id);
        
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = $photo->store("important_site/{$ward->id}", 'public');
            $validated['photo'] = $path;
        }

        ImportantSite::create($validated);

        return redirect()->route('important-site.index')
            ->with('success', __('Important site created successfully'));
    }

    public function edit(ImportantSite $importantSite)
    {
        $wards = Ward::orderBy('ward_no')->get();
        $placeTypes = PlaceType::all();
        return view('important-sites.edit', compact('importantSite', 'wards', 'placeTypes'));
    }

    public function update(Request $request, ImportantSite $importantSite)
    {
        $validated = $request->validate([
            'place_name' => 'required|string|max:255',
            'ward_id' => 'required|exists:wards,id',
            'place_type_id' => 'required|exists:place_types,id',
            'place_description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $ward = Ward::find($request->ward_id);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($importantSite->photo && Storage::disk('public')->exists($importantSite->photo)) {
                Storage::disk('public')->delete($importantSite->photo);
            }
            // Store new photo
            $photo = $request->file('photo');
            $path = $photo->store("important_site/{$ward->id}", 'public');
            $validated['photo'] = $path;
        }

        $importantSite->update($validated);

        return redirect()->route('important-site.index')
            ->with('success', __('Important site updated successfully'));
    }

    public function show(ImportantSite $importantSite)
    {
        $importantSite->load(['ward', 'placeType']);
        return view('important-sites.show', compact('importantSite'));
    }

    public function destroy(ImportantSite $importantSite)
    {
        $importantSite->deletePhoto();
        $importantSite->delete();

        return redirect()->route('important-site.index')
            ->with('success', __('Important site deleted successfully'));
    }

    public function deletePhoto(ImportantSite $importantSite)
    {
        $importantSite->deletePhoto();

        return redirect()->route('important-site.edit', $importantSite)
            ->with('success', __('Photo deleted successfully'));
    }
}