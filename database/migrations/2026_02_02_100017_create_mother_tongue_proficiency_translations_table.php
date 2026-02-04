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
        Schema::create('mother_tongue_proficiency_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_tongue_proficiency_id')->constrained('mother_tongue_proficiencies', 'id', 'mother_tongue_trans_fk')->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->timestamps();

            $table->unique(['mother_tongue_proficiency_id','locale'], 'mother_tongue_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mother_tongue_proficiency_translations');
    }
};