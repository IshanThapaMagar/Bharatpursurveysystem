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
        Schema::create('resource_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->foreignId('tole_id')->constrained('toles')->cascadeOnDelete();
            $table->string('electricity_pole_number')->nullable();
            $table->foreignId('tole_dev_office_type_id')->nullable()->constrained('tole_development_office_types')->nullOnDelete();
            $table->boolean('nala_nikash')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_mappings');
    }
};
