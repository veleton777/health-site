<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialistForArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_need_consult')->default(false);
            $table->string('specialist_name')->nullable();
            $table->string('specialist_phone')->nullable();
            $table->tinyInteger('percent_match')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('is_need_consult');
            $table->dropColumn('specialist_name');
            $table->dropColumn('specialist_phone');
            $table->dropColumn('percent_match');
        });
    }
}
