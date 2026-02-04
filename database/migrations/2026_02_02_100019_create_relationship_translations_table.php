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
        Schema::create('relationship_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relationship_id')->constrained('relationships', 'id', 'relationship_trans_fk')->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->timestamps();

            $table->unique(['relationship_id','locale'], 'relationship_trans_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relationship_translations');
    }
};
