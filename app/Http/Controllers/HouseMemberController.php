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
    public function create()
    {
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
            'poolingPlaces'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}