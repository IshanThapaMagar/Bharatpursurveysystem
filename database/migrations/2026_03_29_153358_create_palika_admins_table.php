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
        Schema::create('palika_admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('designation_id')->unique()->constrained('palika_designations')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('palika_admins');
    }
};
