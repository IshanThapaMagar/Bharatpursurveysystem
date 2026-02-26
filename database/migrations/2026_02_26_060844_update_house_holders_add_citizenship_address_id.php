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
        Schema::table('house_holders', function (Blueprint $table) {
            $table->dropColumn('citizenship_permanent_address');
            $table->foreignId('citizenship_permanent_address_id')
                  ->nullable()
                  ->after('phone_number')
                  ->constrained('citizenship_permanent_addresses')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('house_holders', function (Blueprint $table) {
            $table->dropForeign(['citizenship_permanent_address_id']);
            $table->dropColumn('citizenship_permanent_address_id');
            $table->string('citizenship_permanent_address')->nullable();
        });
    }
};
