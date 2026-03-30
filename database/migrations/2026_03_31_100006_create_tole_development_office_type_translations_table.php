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
        Schema::create('tole_development_office_type_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tole_dev_off_type_id');
            $table->foreign('tole_dev_off_type_id', 'tdot_type_id_fk')
                  ->references('id')
                  ->on('tole_development_office_types')
                  ->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tole_development_office_type_translations');
    }
};
