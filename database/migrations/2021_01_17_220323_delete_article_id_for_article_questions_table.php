<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteArticleIdForArticleQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_questions', function (Blueprint $table) {
            $table->dropColumn('article_id');
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
            $table->integer('article_id')
                ->nullable()
                ->references('id')
                ->on('articles')
                ->onDelete('CASCADE');
        });
    }
}
