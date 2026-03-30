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
        Schema::create('resource_mapping_pole_types', function (Blueprint $table) {
            $table->foreignId('resource_mapping_id')->constrained()->onDelete('cascade');
            $table->foreignId('pole_type_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
        });

        Schema::create('resource_mapping_road_types', function (Blueprint $table) {
            $table->foreignId('resource_mapping_id')->constrained()->onDelete('cascade');
            $table->foreignId('road_type_id')->constrained()->onDelete('cascade');
            $table->decimal('length', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_mapping_pivot_tables');
    }
};
