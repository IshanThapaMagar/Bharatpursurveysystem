<?php

namespace App\Http\Controllers;

use App\Models\ResourceMapping;
use App\Models\Ward;
use App\Models\PoleType;
use App\Models\RoadType;
use App\Models\ToleDevelopmentOfficeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceMappingController extends Controller
{
    public function index()
    {
        $resourceMappings = ResourceMapping::with(['ward', 'tole', 'toleDevelopmentOfficeType'])->paginate(15);
        return view('resource_mapping.index', compact('resourceMappings'));
    }

    public function create()
    {
        $wards = Ward::all();
        $toleDevOfficeTypes = ToleDevelopmentOfficeType::with('translations')->get();
        $poleTypes = PoleType::with('translations')->get();
        $roadTypes = RoadType::with('translations')->get();
        return view('resource_mapping.create', compact('wards', 'toleDevOfficeTypes', 'poleTypes', 'roadTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'tole_id' => 'required|exists:toles,id',
            'electricity_pole_number' => 'nullable|string|max:255',
            'tole_dev_office_type_id' => 'nullable|exists:tole_development_office_types,id',
            'nala_nikash' => 'nullable|boolean',
            'pole_types' => 'nullable|array',
            'pole_types.*.id' => 'exists:pole_types,id',
            'pole_types.*.quantity' => 'nullable|integer|min:0',
            'road_types' => 'nullable|array',
            'road_types.*.id' => 'exists:road_types,id',
            'road_types.*.length' => 'nullable|numeric|min:0',
        ]);

        // Convert checkbox 'on' to boolean if necessary, though validate boolean usually handles 1/0
        $validated['nala_nikash'] = $request->has('nala_nikash');

        DB::transaction(function () use ($request, $validated) {
            $resourceMapping = ResourceMapping::create($validated);

            if ($request->has('pole_types')) {
                $poles = collect($request->pole_types)
                    ->filter(fn($p) => isset($p['quantity']) && $p['quantity'] > 0)
                    ->mapWithKeys(fn($p) => [$p['id'] => ['quantity' => $p['quantity']]]);
                if ($poles->isNotEmpty()) {
                    $resourceMapping->poleTypes()->sync($poles);
                }
            }

            if ($request->has('road_types')) {
                $roads = collect($request->road_types)
                    ->filter(fn($r) => isset($r['length']) && $r['length'] > 0)
                    ->mapWithKeys(fn($r) => [$r['id'] => ['length' => $r['length']]]);
                if ($roads->isNotEmpty()) {
                    $resourceMapping->roadTypes()->sync($roads);
                }
            }
        });

        return redirect()->route('resource-mapping.index')->with('success', 'Resource mapping created successfully.');
    }

    public function edit(ResourceMapping $resourceMapping)
    {
        $resourceMapping->load(['poleTypes', 'roadTypes']);
        $wards = Ward::all();
        $toleDevOfficeTypes = ToleDevelopmentOfficeType::with('translations')->get();
        $poleTypes = PoleType::with('translations')->get();
        $roadTypes = RoadType::with('translations')->get();
        return view('resource_mapping.create', compact('resourceMapping', 'wards', 'toleDevOfficeTypes', 'poleTypes', 'roadTypes'));
    }

    public function update(Request $request, ResourceMapping $resourceMapping)
    {
        $validated = $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'tole_id' => 'required|exists:toles,id',
            'electricity_pole_number' => 'nullable|string|max:255',
            'tole_dev_office_type_id' => 'nullable|exists:tole_development_office_types,id',
            'nala_nikash' => 'nullable|boolean',
            'pole_types' => 'nullable|array',
            'pole_types.*.id' => 'exists:pole_types,id',
            'pole_types.*.quantity' => 'nullable|integer|min:0',
            'road_types' => 'nullable|array',
            'road_types.*.id' => 'exists:road_types,id',
            'road_types.*.length' => 'nullable|numeric|min:0',
        ]);

        $validated['nala_nikash'] = $request->has('nala_nikash');

        DB::transaction(function () use ($request, $validated, $resourceMapping) {
            $resourceMapping->update($validated);

            $poles = collect($request->pole_types ?? [])
                ->filter(fn($p) => isset($p['quantity']) && $p['quantity'] > 0)
                ->mapWithKeys(fn($p) => [$p['id'] => ['quantity' => $p['quantity']]]);
            $resourceMapping->poleTypes()->sync($poles);

            $roads = collect($request->road_types ?? [])
                ->filter(fn($r) => isset($r['length']) && $r['length'] > 0)
                ->mapWithKeys(fn($r) => [$r['id'] => ['length' => $r['length']]]);
            $resourceMapping->roadTypes()->sync($roads);
        });

        return redirect()->route('resource-mapping.index')->with('success', 'Resource mapping updated successfully.');
    }

    public function destroy(ResourceMapping $resourceMapping)
    {
        $resourceMapping->delete();
        return redirect()->route('resource-mapping.index')->with('success', 'Resource mapping deleted successfully.');
    }
}
