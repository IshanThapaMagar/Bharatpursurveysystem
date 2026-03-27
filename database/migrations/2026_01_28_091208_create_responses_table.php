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
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('householder_id')->constrained('house_holders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->index('user_id');
            $table->index('ward_id');
            $table->index('householder_id');
            $table->index(['ward_id', 'householder_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};