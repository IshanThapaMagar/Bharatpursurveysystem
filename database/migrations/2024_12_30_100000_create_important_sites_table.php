<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('important_sites', function (Blueprint $table) {
            $table->id();
            $table->string('place_name');
            $table->unsignedBigInteger('ward_id');
            $table->unsignedBigInteger('place_type_id')->nullable();
            $table->text('place_description')->nullable();
            $table->string('photo')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index('ward_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('important_sites');
    }
};
