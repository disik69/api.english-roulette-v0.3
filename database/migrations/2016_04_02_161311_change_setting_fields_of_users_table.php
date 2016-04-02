<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSettingFieldsOfUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('lesson_size')->unsigned()->after('password');

            $table->dropColumn(['reading_count', 'memory_count', 'repeat_term']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('reading_count')->unsigned()->after('lesson_size');
            $table->integer('memory_count')->unsigned()->after('reading_count');
            $table->integer('repeat_term')->unsigned()->after('memory_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lesson_size', 'reading_count', 'memory_count', 'repeat_term']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('reading_count')->unsigned();
            $table->integer('memory_count')->unsigned();
            $table->integer('repeat_term')->unsigned();
        });
    }
}
