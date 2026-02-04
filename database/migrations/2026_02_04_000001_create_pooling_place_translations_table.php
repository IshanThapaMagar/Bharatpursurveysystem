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
        Schema::create('pooling_place_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pooling_place_id')->constrained('pooling_places', 'id', 'pooling_place_trans_fk')->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->timestamps();

            $table->unique(['pooling_place_id','locale'], 'pooling_place_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pooling_place_translations');
    }
};
