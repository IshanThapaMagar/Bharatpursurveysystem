<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\WardDesignation;
use App\Models\WardMember;
use App\Services\SurveyDuplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class WardController extends Controller
{
    public function create()
    {
        $wardDesignations = WardDesignation::with('translations')->get();
        return view('palika.wardForm', compact('wardDesignations'));
    }

    public function edit(Ward $ward)
    {
        $ward->load('members');
        $wardDesignations = WardDesignation::with('translations')->get();
        return view('palika.wardForm', compact('ward', 'wardDesignations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ward_no' => 'required|integer|unique:wards,ward_no',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'building_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'members' => 'required|array',
            'members.*.name' => 'required|string|max:255',
            'members.*.email' => 'nullable|email|max:255',
            'members.*.phone_number' => 'nullable|string|max:20',
            'members.*.ward_designation_id' => 'required|exists:ward_designations,id',
            'members.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->validateMemberCounts($request->members);

        $newWardId = null;

        DB::transaction(function () use ($request, &$newWardId) {
            $buildingPhotoPath = $request->file('building_photo')->store('ward_buildings', 'public');

            $ward = Ward::create([
                'ward_no' => $request->ward_no,
                'name' => $request->name,
                'location' => $request->location,
                'description' => $request->description,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'building_photo' => $buildingPhotoPath,
            ]);

            $newWardId = $ward->id;

            $designationIds = collect($request->members)->pluck('ward_designation_id')->unique();
            $designations = WardDesignation::with('translations')->whereIn('id', $designationIds)->get()->keyBy('id');

            foreach ($request->members as $index => $memberData) {
                $photoPath = null;
                if ($request->hasFile("members.{$index}.photo")) {
                    $designation = $designations->get($memberData['ward_designation_id']);
                    $enTitle = $designation->translations->where('locale', 'en')->first()?->name ?? 'member';
                    $filename = \Illuminate\Support\Str::slug($enTitle) . '_' . time() . '_' . $index . '.' . $request->file("members.{$index}.photo")->getClientOriginalExtension();
                    $photoPath = $request->file("members.{$index}.photo")->storeAs('ward/members/' . $ward->id, $filename, 'public');
                }

                WardMember::create([
                    'ward_id' => $ward->id,
                    'ward_designation_id' => $memberData['ward_designation_id'],
                    'name' => $memberData['name'],
                    'email' => $memberData['email'] ?? null,
                    'phone_number' => $memberData['phone_number'] ?? null,
                    'photo' => $photoPath,
                ]);
            }
        });

        // Automatically assign existing surveys to the newly created ward
        $surveyService = new SurveyDuplicationService();
        $surveyService->assignExistingSurveysToNewWard($newWardId);

        return redirect()->route('wards.show', $newWardId)->with('success', 'Ward added successfully.');
    }

    public function update(Request $request, Ward $ward)
    {
        $request->validate([
            'ward_no' => 'required|integer|unique:wards,ward_no,' . $ward->id,
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'building_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'members' => 'required|array',
            'members.*.name' => 'required|string|max:255',
            'members.*.email' => 'nullable|email|max:255',
            'members.*.phone_number' => 'nullable|string|max:20',
            'members.*.ward_designation_id' => 'required|exists:ward_designations,id',
            'members.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->validateMemberCounts($request->members);

        DB::transaction(function () use ($request, $ward) {
            $data = $request->only(['ward_no', 'name', 'location', 'description', 'contact_number', 'email', 'latitude', 'longitude']);

            if ($request->hasFile('building_photo')) {
                if ($ward->building_photo) {
                    Storage::disk('public')->delete($ward->building_photo);
                }
                $data['building_photo'] = $request->file('building_photo')->store('ward_buildings', 'public');
            }

            $ward->update($data);


            $wardMembers = $ward->members()->get()->keyBy('id');
            $existingMemberIds = $wardMembers->keys()->toArray();
            $processedIds = [];


            $designationIds = collect($request->members)->pluck('ward_designation_id')->unique();
            $designations = WardDesignation::with('translations')->whereIn('id', $designationIds)->get()->keyBy('id');

            foreach ($request->members as $index => $memberData) {
                $memberId = $memberData['id'] ?? null;
                $photoPath = $memberData['existing_photo'] ?? null;

                if ($request->hasFile("members.{$index}.photo")) {

                    if ($photoPath) {
                        Storage::disk('public')->delete($photoPath);
                    }

                    $designation = $designations->get($memberData['ward_designation_id']);
                    $enTitle = $designation->translations->where('locale', 'en')->first()?->name ?? 'member';
                    $filename = \Illuminate\Support\Str::slug($enTitle) . '_' . time() . '_' . $index . '.' . $request->file("members.{$index}.photo")->getClientOriginalExtension();
                    $photoPath = $request->file("members.{$index}.photo")->storeAs('ward/members/' . $ward->id, $filename, 'public');
                }

                if ($memberId && $wardMembers->has($memberId)) {
                    $member = $wardMembers->get($memberId);
                    $member->update([
                        'ward_designation_id' => $memberData['ward_designation_id'],
                        'name' => $memberData['name'],
                        'email' => $memberData['email'] ?? null,
                        'phone_number' => $memberData['phone_number'] ?? null,
                        'photo' => $photoPath,
                    ]);
                    $processedIds[] = $memberId;
                } else {
                    $newMember = WardMember::create([
                        'ward_id' => $ward->id,
                        'ward_designation_id' => $memberData['ward_designation_id'],
                        'name' => $memberData['name'],
                        'email' => $memberData['email'] ?? null,
                        'phone_number' => $memberData['phone_number'] ?? null,
                        'photo' => $photoPath,
                    ]);
                    $processedIds[] = $newMember->id;
                }
            }


            $toDelete = array_diff($existingMemberIds, $processedIds);
            foreach ($toDelete as $id) {
                $member = $wardMembers->get($id);
                if ($member && $member->photo) {
                    Storage::disk('public')->delete($member->photo);
                }
                $member?->delete();
            }
        });

        return redirect()->route('wards.show', $ward)->with('success', 'Ward updated successfully');
    }

    public function show(Ward $ward)
    {
        $ward->load(['members.designation.translations', 'surveySections']);
        return view('palika.wardShow', compact('ward'));
    }

    public function destroy(Ward $ward)
    {
        if ($ward->building_photo) {
            Storage::disk('public')->delete($ward->building_photo);
        }

        foreach ($ward->members as $member) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
        }

        $ward->delete();

        return redirect()->route('palika.index')->with('success', 'Ward deleted successfully.');
    }

    private function validateMemberCounts($members)
    {
        $chairpersonCount = 0;
        $memberCount = 0;

        // Fetch designation IDs for comparison
        $chairpersonDesignation = WardDesignation::whereHas('translations', function ($q) {
            $q->where('name', 'Ward Chairperson')->orWhere('name', 'वडा अध्यक्ष');
        })->first();

        $memberDesignation = WardDesignation::whereHas('translations', function ($q) {
            $q->where('name', 'Ward Member')->orWhere('name', 'वडा सदस्य');
        })->first();

        foreach ($members as $member) {
            if ($chairpersonDesignation && $member['ward_designation_id'] == $chairpersonDesignation->id) {
                $chairpersonCount++;
            }
            if ($memberDesignation && $member['ward_designation_id'] == $memberDesignation->id) {
                $memberCount++;
            }
        }

        if ($chairpersonCount !== 1) {
            abort(422, 'There must be exactly one Ward Chairperson.');
        }

        if ($memberCount !== 4) {
            abort(422, 'There must be exactly four Ward Members.');
        }
    }
}
