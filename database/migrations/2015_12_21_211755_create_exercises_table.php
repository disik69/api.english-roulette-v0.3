<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('word_id')->unsigned();
            $table->integer('translation_id')->unsigned();
            $table->enum('status', ['new', 'old'])->default('new');
            $table->integer('reading')->unsigned();
            $table->integer('memory')->unsigned();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('word_id')->references('id')->on('words');
            $table->foreign('translation_id')->references('id')->on('translations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exercises');
    }
}
