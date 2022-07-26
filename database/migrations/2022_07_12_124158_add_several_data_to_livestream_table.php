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
        Schema::create('livestreams', function (Blueprint $table) {
            $table->id();
            $table->string('sports_type');
            $table->string('league_name');
            $table->string('home_team');
            $table->string('home_mark');
            $table->string('away_team');
            $table->string('away_mark');
            $table->date('start_date');
            $table->time('time');
            $table->string('status');
            $table->string('uid',2048);
            $table->timestamps();
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
