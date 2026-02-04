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
        Schema::create('health_status_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('health_status_id')->constrained('health_statuses', 'id', 'health_status_trans_fk')->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->timestamps();

            $table->unique(['health_status_id','locale'], 'health_status_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_status_translations');
    }
};
