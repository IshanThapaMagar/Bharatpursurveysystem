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
        Schema::create('government_support_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('government_support_type_id')
                ->constrained('government_support_types', 'id')
                ->onDelete('cascade')
                ->name('gov_trans_fk');
            $table->string('locale');
            $table->string('name');
            $table->timestamps();

            $table->unique(['government_support_type_id','locale'], 'gov_support_type_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('government_support_type_translations');
    }
};