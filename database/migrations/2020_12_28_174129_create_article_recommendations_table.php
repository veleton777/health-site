<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_recommendations', function (Blueprint $table) {
            $table->integer('user_id')->references('id')->on('app_users')->onDelete('CASCADE');
            $table->integer('article_id')->references('id')->on('articles')->onDelete('CASCADE');
            $table->primary(['user_id', 'article_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_recommendations');
    }
}
