<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserArticleAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_article_answers', function (Blueprint $table) {
            $table
                ->integer('user_id')
                ->references('id')
                ->on('app_users')
                ->onDelete('CASCADE');
            $table
                ->integer('answer_id')
                ->references('id')
                ->on('article_answer_variants')
                ->onDelete('CASCADE');
            $table
                ->integer('question_id')
                ->references('id')
                ->on('article_questions')
                ->onDelete('CASCADE');
            $table->primary(['user_id', 'answer_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_article_answers');
    }
}
