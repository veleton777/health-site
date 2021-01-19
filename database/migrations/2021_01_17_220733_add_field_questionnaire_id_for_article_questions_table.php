<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldQuestionnaireIdForArticleQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_questions', function (Blueprint $table) {
            $table->integer('questionnaire_id')
                ->nullable()
                ->references('id')
                ->on('article_questionnaires')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_questions', function (Blueprint $table) {
            $table->dropColumn('questionnaire_id');
        });
    }
}
