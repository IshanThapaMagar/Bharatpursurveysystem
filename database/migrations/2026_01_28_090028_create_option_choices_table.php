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
        Schema::create('option_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_group_id')->constrained('option_groups')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('choice_text')->nullable();
            $table->string('custom_input_type')->nullable();
            $table->string('custom_input_placeholder')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_choices');
    }
};