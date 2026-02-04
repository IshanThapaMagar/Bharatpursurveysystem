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
        Schema::create('district_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('districts', 'id', 'district_trans_fk')->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->timestamps();

            $table->unique(['district_id','locale'], 'district_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('district_translations');
    }
};
