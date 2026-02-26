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
        Schema::create('citizenship_permanent_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Insert default lookup strings straight away
        DB::table('citizenship_permanent_addresses')->insert([
            ['name' => 'स्थायी जन्म', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'बसाइसराइ', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'अस्थायी', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'बसाइसराइ नभएको', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citizenship_permanent_addresses');
    }
};
