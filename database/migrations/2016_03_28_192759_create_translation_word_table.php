<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationWordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translation_word', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('translation_id')->unsigned();
            $table->integer('word_id')->unsigned();
            $table->timestamps();

            $table->foreign('translation_id')->references('id')->on('translations')->onDelete('cascade');
            $table->foreign('word_id')->references('id')->on('words')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('translation_word');
    }
}
