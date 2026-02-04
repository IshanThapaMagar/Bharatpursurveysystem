<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::table('survey_sections', function (Blueprint $table) {

    $table->foreignId('ward_id')
    ->after('order_index')
    ->constrained('wards')
    ->onUpdate('cascade')
    ->onDelete('restrict');


    $table->index('ward_id');
    $table->index('order_index');
    $table->index(['ward_id', 'order_index']);
    });
    }

    public function down(): void
    {
    Schema::table('survey_sections', function (Blueprint $table) {
    $table->dropForeign(['ward_id']);
    $table->dropIndex(['ward_id']);
    $table->dropIndex(['order_index']);
    $table->dropIndex(['ward_id', 'order_index']);
    $table->dropColumn('ward_id');
    });
    }
};