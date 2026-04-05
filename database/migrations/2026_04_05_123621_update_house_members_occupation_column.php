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
        Schema::table('house_members', function (Blueprint $table) {
            $table->dropColumn('occupation');
            $table->foreignId('occupation_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('house_members', function (Blueprint $table) {
            $table->dropForeign(['occupation_id']);
            $table->dropColumn('occupation_id');
            $table->string('occupation')->nullable();
        });
    }
};
