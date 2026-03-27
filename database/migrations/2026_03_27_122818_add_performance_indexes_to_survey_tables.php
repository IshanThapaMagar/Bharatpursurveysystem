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
        $this->addIndexIfNotExists('answers', 'question_id');
        $this->addIndexIfNotExists('answers', 'question_option_id');
        $this->addIndexIfNotExists('responses', 'ward_id');
        $this->addIndexIfNotExists('responses', 'householder_id');
        $this->addIndexIfNotExists('question_options', 'question_id');
        $this->addIndexIfNotExists('questions', 'survey_section_id');
        $this->addIndexIfNotExists('survey_sections', 'ward_id');
        $this->addIndexIfNotExists('house_members', 'house_holder_id');
    }

    private function addIndexIfNotExists($table, $column)
    {
        $indexes = Schema::getIndexes($table);
        $hasIndex = false;
        foreach ($indexes as $index) {
            // Check if there is an index that perfectly matches this single column
            if (count($index['columns']) === 1 && $index['columns'][0] === $column) {
                $hasIndex = true;
                break;
            }
        }

        if (!$hasIndex) {
            Schema::table($table, function (Blueprint $t) use ($column) {
                $t->index($column);
            });
        }
    }

    public function down(): void
    {
        // No explicit down needed since they may have pre-existed.
    }
};
