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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('responses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('question_option_id')->nullable()->constrained('question_options')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('answer_numeric', 12,2)->nullable();
            $table->string('answer_text')->nullable();
            $table->text('custom_input_value')->nullable();
            $table->foreignId('unit_of_measure_id')->nullable()->constrained('unit_of_measures')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};