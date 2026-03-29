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
        Schema::create('palika_designation_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('palika_designation_id')->constrained('palika_designations', 'id', 'palika_desig_trans_fk')->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->timestamps();

            $table->unique(['palika_designation_id', 'locale'], 'palika_desig_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('palika_designation_translations');
    }
};
