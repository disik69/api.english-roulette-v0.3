<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropRememberToken();
            $table->integer('reading_count')->unsigned();
            $table->integer('memory_count')->unsigned();
            $table->integer('repeat_term')->unsigned();
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
            $table->rememberToken();
            $table->dropColumn(['reading_count', 'memory_count', 'repeat_term']);
        });
    }
}
