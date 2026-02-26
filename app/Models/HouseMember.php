<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseMember extends Model
{
    protected $fillable = [
        'house_holder_id',
        'full_name',
        'relationship_id',
        'dob_bs',
        'dob_ad',
        'age',
        'gender_id',
        'religion_id',
        'contact_number',
        'marital_status_id',
        'institution_type_id',
        'education_level_id',
        'special_skill_id',
        'government_support_type_id',
        'district_id',
        'occupation',
        'health_status_id',
        'blood_group_id',
        'citizenship_number',
        'citizenship_district_id',
        'permanent_account_no',
        'nid_no',
        'disability_id',
        'has_voterId',
        'pooling_place_id',
        'native_speaking_level',
    ];
    public function houseHolder()
    {
        return $this->belongsTo(HouseHolder::class, 'house_holder_id');
    }

    public function relationship()
    {
        return $this->belongsTo(Relationship::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function institutionType()
    {
        return $this->belongsTo(InstitutionType::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function specialSkill()
    {
        return $this->belongsTo(SpecialSkill::class);
    }

    public function governmentSupportType()
    {
        return $this->belongsTo(GovernmentSupportType::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function citizenshipDistrict()
    {
        return $this->belongsTo(District::class, 'citizenship_district_id');
    }

    public function healthStatus()
    {
        return $this->belongsTo(HealthStatus::class);
    }

    public function bloodGroup()
    {
        return $this->belongsTo(BloodGroup::class);
    }

    public function disability()
    {
        return $this->belongsTo(Disability::class);
    }

    public function nativeSpeakingLevel()
    {
        return $this->belongsTo(MotherTongueProficiency::class, 'native_speaking_level');
    }
}
