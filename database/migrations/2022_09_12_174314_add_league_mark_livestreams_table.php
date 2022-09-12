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
            $table->string('league_mark', 2048)->after('away_mark');
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
            $table->dropColumn('league_mark');
        });
    }
};
