<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteRightAnswerIdFieldForArticleQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_questions', function (Blueprint $table) {
            $table->dropColumn('right_answer_id');
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
            $table
                ->integer('right_answer_id')
                ->nullable()
                ->references('id')
                ->on('article_answer_variants')
                ->onDelete('CASCADE');
        });
    }
}
