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
        Schema::create('house_holders', function (Blueprint $table) {
            $table->id();
            $table->string('profile_photo')->nullable();
            $table->string('householder_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->foreignId('mother_tongue_id')->constrained('mother_tongues')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('caste_id')->constrained('castes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('tole_id')->constrained('toles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('ward_no');
            $table->string('lot_number');
            $table->string('house_number');
            $table->string('phone_number', 10);
            $table->string('citizenship_permanent_address');
            $table->timestamps();


            $table->index('mother_tongue_id');
            $table->index('caste_id');
            $table->index('tole_id');
            $table->index('ward_no');


            $table->index(['mother_tongue_id', 'caste_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_holders');
    }
};