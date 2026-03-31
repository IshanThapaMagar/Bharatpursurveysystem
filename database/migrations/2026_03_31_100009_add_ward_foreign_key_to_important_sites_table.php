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
        Schema::table('important_sites', function (Blueprint $table) {
            $table->foreign('ward_id')
                ->references('id')
                ->on('wards')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('important_sites', function (Blueprint $table) {
            $table->dropForeign(['ward_id']);
        });
    }
};
