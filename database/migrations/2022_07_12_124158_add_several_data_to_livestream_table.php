<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('livestreams', function (Blueprint $table) {
            $table->integer('uid');
            $table->time('time');
            $table->string('home_mark');
            $table->string('home_score');
            $table->string('away_mark');
            $table->string('away_score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('livestreams', function (Blueprint $table) {
            //
        });
    }
};
