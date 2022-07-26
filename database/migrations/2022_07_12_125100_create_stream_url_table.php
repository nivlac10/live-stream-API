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
        Schema::create('stream_url', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->string('sports_type');
            $table->string('home_team');
            $table->string('home_mark');
            $table->string('away_team');
            $table->string('away_mark');
            $table->date('start_date');
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
        Schema::dropIfExists('stream_url');
    }
};
