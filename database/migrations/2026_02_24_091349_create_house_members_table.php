<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('house_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_holder_id')->constrained('house_holders')->onDelete('cascade');
            $table->string('full_name');
            $table->foreignId('relationship_id')->nullable()->constrained('relationships');
            $table->string('dob_bs');
            $table->date('dob_ad')->nullable();
            $table->integer('age')->nullable();
            $table->foreignId('gender_id')->nullable()->constrained('genders');
            $table->foreignId('religion_id')->nullable()->constrained('religions');
            $table->string('contact_number')->nullable();
            $table->foreignId('marital_status_id')->nullable()->constrained('marital_statuses');
            $table->foreignId('institution_type_id')->nullable()->constrained('institution_types');
            $table->foreignId('education_level_id')->nullable()->constrained('education_levels');
            $table->foreignId('special_skill_id')->nullable()->constrained('special_skills');
            $table->foreignId('government_support_type_id')->nullable()->constrained('government_support_types');
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->string('occupation')->nullable();
            $table->foreignId('health_status_id')->nullable()->constrained('health_statuses');
            $table->foreignId('blood_group_id')->nullable()->constrained('blood_groups');
            $table->string('citizenship_number')->nullable();
            $table->foreignId('citizenship_district_id')->nullable()->constrained('districts');
            $table->string('permanent_account_no')->nullable();
            $table->string('nid_no')->nullable();
            $table->foreignId('disability_id')->nullable()->constrained('disabilities');
            $table->boolean('has_voterId')->default(false);
            $table->foreignId('pooling_place_id')->nullable()->constrained('pooling_places');
            $table->foreignId('native_speaking_level')->nullable()->constrained('mother_tongue_proficiencies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_members');
    }
};
