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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_section_id')->constrained('survey_sections')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('question_text');
            $table->string('question_subtext')->nullable();
            $table->boolean('answer_required')->default(false);
            $table->foreignId('input_type_id')->constrained('input_types')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('option_group_id')->nullable()->constrained('option_groups')->nullOnDelete()->cascadeOnUpdate();
            $table->integer('scale_from')->nullable();
            $table->integer('scale_to')->nullable();
            $table->string('scale_label_low')->nullable();
            $table->string('scale_label_high')->nullable();
            $table->boolean('allow_multiple_option_answers')->default(false);
            $table->integer('order_index');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};