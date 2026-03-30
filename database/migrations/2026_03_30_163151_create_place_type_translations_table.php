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
        Schema::create('place_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('place_type_id')->constrained('place_types')->cascadeOnDelete();
            $table->string('locale');
            $table->string('name');
            $table->timestamps();

            $table->unique(['place_type_id', 'locale'], 'place_type_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_type_translations');
    }
};
