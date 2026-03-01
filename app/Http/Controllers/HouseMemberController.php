<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gender;
use App\Models\Religion;
use App\Models\BloodGroup;
use App\Models\Disability;
use App\Models\District;
use App\Models\EducationLevel;
use App\Models\GovernmentSupportType;
use App\Models\HealthStatus;
use App\Models\InstitutionType;
use App\Models\MaritalStatus;
use App\Models\MotherTongueProficiency;
use App\Models\Relationship;
use App\Models\SpecialSkill;
use App\Models\PoolingPlace;
use App\Models\HouseMember;
use App\Models\HouseHolder;
use App\Models\RelationshipTranslation;
use App\Models\Response;
use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;

class HouseMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $householder_id = $request->household_id;
        $householder = HouseHolder::with('tole')->findOrFail($householder_id);

        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $householder->tole->ward_id) {
            abort(403, 'Unauthorized access to this householder.');
        }

        // Find the "Self" relationship ID
        $self_relationship_id = Relationship::whereHas('translations', function ($query) {
            $query->where('locale', 'en')->where('name', 'Self');
        })->first()?->id;

        $householderExists = HouseMember::where('house_holder_id', $householder_id)
            ->where('relationship_id', $self_relationship_id)
            ->exists();
        $locale = session('locale', app()->getLocale());

        $genders = Gender::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'gender_id', 'name');
        }])->get(['id']);

        $religions = Religion::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'religion_id', 'name');
        }])->get(['id']);

        $bloodGroups = BloodGroup::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'blood_group_id', 'name');
        }])->get(['id']);

        $disabilities = Disability::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'disability_id', 'name');
        }])->get(['id']);

        $districts = District::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'district_id', 'name');
        }])->get(['id']);

        $educationLevels = EducationLevel::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'education_level_id', 'name');
        }])->get(['id']);

        $governmentSupportTypes = GovernmentSupportType::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'government_support_type_id', 'name');
        }])->get(['id']);

        $healthStatuses = HealthStatus::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'health_status_id', 'name');
        }])->get(['id']);

        $institutionTypes = InstitutionType::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'institution_type_id', 'name');
        }])->get(['id']);

        $maritalStatuses = MaritalStatus::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'marital_status_id', 'name');
        }])->get(['id']);

        $motherTongueProficiencies = MotherTongueProficiency::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'mother_tongue_proficiency_id', 'name');
        }])->get(['id']);

        $relationships = Relationship::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'relationship_id', 'name');
        }])->get(['id']);

        $specialSkills = SpecialSkill::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'special_skill_id', 'name');
        }])->get(['id']);

        $poolingPlaces = PoolingPlace::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'pooling_place_id', 'name');
        }])->get(['id']);

        return view('housedescription.createhousemember', compact(
            'genders',
            'religions',
            'bloodGroups',
            'disabilities',
            'districts',
            'educationLevels',
            'governmentSupportTypes',
            'healthStatuses',
            'institutionTypes',
            'maritalStatuses',
            'motherTongueProficiencies',
            'relationships',
            'specialSkills',
            'poolingPlaces',
            'householder_id',
            'householder',
            'self_relationship_id',
            'householderExists'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'house_holder_id' => 'required|exists:house_holders,id',
            'full_name' => 'required|string|max:255',
            'relationship_id' => 'nullable|exists:relationships,id',
            'dob' => 'required|string',
            'gender_id' => 'nullable|exists:genders,id',
            'religion_id' => 'nullable|exists:religions,id',
            'contact_number' => 'nullable|string',
            'marital_status_id' => 'nullable|exists:marital_statuses,id',
            'institution_type_id' => 'nullable|exists:institution_types,id',
            'education_level_id' => 'nullable|exists:education_levels,id',
            'special_skill_id' => 'nullable|exists:special_skills,id',
            'government_support_type_id' => 'nullable|exists:government_support_types,id',
            'district_id' => 'nullable|exists:districts,id',
            'occupation' => 'nullable|string',
            'health_status_id' => 'nullable|exists:health_statuses,id',
            'blood_group_id' => 'nullable|exists:blood_groups,id',
            'citizenship_number' => 'nullable|string',
            'citizenship_district_id' => 'nullable|exists:districts,id',
            'permanent_account_no' => 'nullable|string',
            'nid_no' => 'nullable|string',
            'disability_id' => 'nullable|exists:disabilities,id',
            'has_voterId' => 'nullable|boolean',
            'pooling_place_id' => 'nullable|exists:pooling_places,id',
            'native_speaking_level' => 'nullable|exists:mother_tongue_proficiencies,id',
        ]);

        $householder = HouseHolder::with('tole')->findOrFail($validated['house_holder_id']);
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $householder->tole->ward_id) {
            abort(403, 'Unauthorized access to this householder.');
        }

        $self_relationship_id = Relationship::whereHas('translations', function ($query) {
            $query->where('locale', 'en')->where('name', 'Self');
        })->first()?->id;

        if (isset($validated['relationship_id']) && $validated['relationship_id'] == $self_relationship_id) {
            $exists = HouseMember::where('house_holder_id', $validated['house_holder_id'])
                ->where('relationship_id', $self_relationship_id)
                ->exists();
            if ($exists) {
                return back()->withInput()->with('error', __('Householder information already exists for this house.'));
            }
        }

        $dob_bs = $request->dob;
        
        try {
            $date = LaravelNepaliDate::from($dob_bs);
            $dob_ad_str = $date->toEnglishDate(); 
            $dob_ad = \Carbon\Carbon::parse($dob_ad_str);
            
           
            $age = $dob_ad->age;

            $houseMember = new \App\Models\HouseMember();
            $houseMember->fill($validated);
            $houseMember->dob_bs = $dob_bs;
            $houseMember->dob_ad = $dob_ad_str;
            $houseMember->age = $age;
            $houseMember->save();

            return redirect()->back()
                ->with('success', __('Family member added successfully'));

        } catch (\Exception $e) {
            return back()->withInput()->with('error', __('Error processing date: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = HouseMember::with('houseHolder.tole')->findOrFail($id);
        $authUser = auth()->user();

        // Superadmin can edit anything; Others can only edit members in their assigned ward
        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $member->houseHolder->tole->ward_id) {
            abort(403, 'You do not have permission to edit this member.');
        }

        $locale = session('locale', app()->getLocale());

        $genders = Gender::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'gender_id', 'name');
        }])->get(['id']);

        $religions = Religion::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'religion_id', 'name');
        }])->get(['id']);

        $bloodGroups = BloodGroup::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'blood_group_id', 'name');
        }])->get(['id']);

        $disabilities = Disability::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'disability_id', 'name');
        }])->get(['id']);

        $districts = District::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'district_id', 'name');
        }])->get(['id']);

        $educationLevels = EducationLevel::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'education_level_id', 'name');
        }])->get(['id']);

        $governmentSupportTypes = GovernmentSupportType::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'government_support_type_id', 'name');
        }])->get(['id']);

        $healthStatuses = HealthStatus::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'health_status_id', 'name');
        }])->get(['id']);

        $institutionTypes = InstitutionType::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'institution_type_id', 'name');
        }])->get(['id']);

        $maritalStatuses = MaritalStatus::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'marital_status_id', 'name');
        }])->get(['id']);

        $motherTongueProficiencies = MotherTongueProficiency::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'mother_tongue_proficiency_id', 'name');
        }])->get(['id']);

        $relationships = Relationship::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'relationship_id', 'name');
        }])->get(['id']);

        $self_relationship_id = Relationship::whereHas('translations', function ($query) {
            $query->where('locale', 'en')->where('name', 'Self');
        })->first()?->id;

        $householderExists = HouseMember::where('house_holder_id', $member->house_holder_id)
            ->where('relationship_id', $self_relationship_id)
            ->where('id', '!=', $member->id)
            ->exists();

        $specialSkills = SpecialSkill::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'special_skill_id', 'name');
        }])->get(['id']);

        $poolingPlaces = PoolingPlace::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale)->select('id', 'pooling_place_id', 'name');
        }])->get(['id']);

        return view('housedescription.edithousemember', compact(
            'member',
            'genders',
            'religions',
            'bloodGroups',
            'disabilities',
            'districts',
            'educationLevels',
            'governmentSupportTypes',
            'healthStatuses',
            'institutionTypes',
            'maritalStatuses',
            'motherTongueProficiencies',
            'relationships',
            'specialSkills',
            'poolingPlaces',
            'self_relationship_id',
            'householderExists'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = HouseMember::with('houseHolder.tole')->findOrFail($id);
        $authUser = auth()->user();

        // Superadmin can update anything; Others can only update members in their assigned ward
        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $member->houseHolder->tole->ward_id) {
            abort(403, 'You do not have permission to update this member.');
        }

        $validated = $request->validate([
            'full_name'                  => 'required|string|max:255',
            'relationship_id'            => 'nullable|exists:relationships,id',
            'dob'                        => 'required|string',
            'gender_id'                  => 'nullable|exists:genders,id',
            'religion_id'                => 'nullable|exists:religions,id',
            'contact_number'             => 'nullable|string',
            'marital_status_id'          => 'nullable|exists:marital_statuses,id',
            'institution_type_id'        => 'nullable|exists:institution_types,id',
            'education_level_id'         => 'nullable|exists:education_levels,id',
            'special_skill_id'           => 'nullable|exists:special_skills,id',
            'government_support_type_id' => 'nullable|exists:government_support_types,id',
            'district_id'                => 'nullable|exists:districts,id',
            'occupation'                 => 'nullable|string',
            'health_status_id'           => 'nullable|exists:health_statuses,id',
            'blood_group_id'             => 'nullable|exists:blood_groups,id',
            'citizenship_number'         => 'nullable|string',
            'citizenship_district_id'    => 'nullable|exists:districts,id',
            'permanent_account_no'       => 'nullable|string',
            'nid_no'                     => 'nullable|string',
            'disability_id'              => 'nullable|exists:disabilities,id',
            'has_voterId'                => 'nullable|boolean',
            'pooling_place_id'           => 'nullable|exists:pooling_places,id',
            'native_speaking_level'      => 'nullable|exists:mother_tongue_proficiencies,id',
        ]);
        $self_relationship_id = Relationship::whereHas('translations', function ($query) {
            $query->where('locale', 'en')->where('name', 'Self');
        })->first()?->id;

        if (isset($validated['relationship_id']) && $validated['relationship_id'] == $self_relationship_id) {
            $exists = HouseMember::where('house_holder_id', $member->house_holder_id)
                ->where('relationship_id', $self_relationship_id)
                ->where('id', '!=', $member->id)
                ->exists();
            if ($exists) {
                return back()->withInput()->with('error', __('Householder information already exists for this house.'));
            }
        }

        $dob_bs = $request->dob;

        try {
            $date       = LaravelNepaliDate::from($dob_bs);
            $dob_ad_str = $date->toEnglishDate();
            $dob_ad     = \Carbon\Carbon::parse($dob_ad_str);
            $age        = $dob_ad->age;

            $member->fill($validated);
            $member->dob_bs = $dob_bs;
            $member->dob_ad = $dob_ad_str;
            $member->age    = $age;
            $member->save();

            // Redirect back to the survey response show page
            $response = Response::where('householder_id', $member->house_holder_id)->first();
            if ($response) {
                return redirect()->route('survey-responses.show', $response->id)
                    ->with('success', __('Family member updated successfully'));
            }

            return redirect()->back()->with('success', __('Family member updated successfully'));

        } catch (\Exception $e) {
            return back()->withInput()->with('error', __('Error processing date: ') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = HouseMember::with('houseHolder.tole')->findOrFail($id);
        $authUser = auth()->user();

        // Only Superadmin or Ward Admin of the specific ward can delete
        if (!$authUser->isSuperAdmin() && !($authUser->isWardAdmin() && $authUser->ward_id == $member->houseHolder->tole->ward_id)) {
            abort(403, 'You do not have permission to delete this member.');
        }

        $member->delete();

        return redirect()->back()->with('success', __('Family member deleted successfully'));
    }

    public function markDemise(Request $request, string $id)
    {
        $member = HouseMember::with('houseHolder.tole')->findOrFail($id);
        $authUser = auth()->user();

        // Only Superadmin or Ward Admin of the specific ward can mark as demise
        if (!$authUser->isSuperAdmin() && !($authUser->isWardAdmin() && $authUser->ward_id == $member->houseHolder->tole->ward_id)) {
            abort(403, 'You do not have permission to mark this member as demised.');
        }

        $member->is_demised = true;
        $member->demise_date = $request->demise_date ?? now()->toDateString(); 
        $member->save();

        return redirect()->back()->with('success', __('Member marked as demised'));
    }
}