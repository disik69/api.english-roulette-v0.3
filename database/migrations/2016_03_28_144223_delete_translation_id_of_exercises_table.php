<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTranslationIdOfExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropForeign(['translation_id']);
            $table->dropColumn('translation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->integer('translation_id')->after('word_id')->unsigned();

            $table->foreign('translation_id')->references('id')->on('translations');
        });
    }
}
