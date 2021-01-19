<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('article_id')->nullable()->references('id')->on('articles')->onDelete('CASCADE');
            $table
                ->integer('right_answer_id')
                ->nullable()
                ->references('id')
                ->on('article_answer_variants')
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
        Schema::dropIfExists('article_questions');
    }
}
