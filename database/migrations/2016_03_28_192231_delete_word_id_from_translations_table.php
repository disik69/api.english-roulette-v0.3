<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteWordIdFromTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->dropForeign(['word_id']);
            $table->dropColumn('word_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->integer('word_id')->unsigned()->after('id');

            $table->foreign('word_id')->references('id')->on('words')->onDelete('cascade');
        });
    }
}
