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
        Schema::create('dashboard_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('ward_id')->nullable()->unique();
            $table->json('age_groups')->nullable();
            $table->json('gender_groups')->nullable();
            $table->json('citizenship_groups')->nullable();
            $table->json('mother_tongue_stats')->nullable();
            $table->json('caste_stats')->nullable();
            $table->json('education_stats')->nullable();
            $table->json('religion_stats')->nullable();
            $table->integer('total_householders')->default(0);
            $table->integer('total_members')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_statistics');
    }
};
