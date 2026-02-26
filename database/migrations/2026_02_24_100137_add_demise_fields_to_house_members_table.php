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
            $table->boolean('is_demised')->default(false)->after('native_speaking_level');
            $table->string('demise_date')->nullable()->after('is_demised');
        });
    }

    public function down(): void
    {
        Schema::table('house_members', function (Blueprint $table) {
            $table->dropColumn(['is_demised', 'demise_date']);
        });
    }
};
