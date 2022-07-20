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
            $table->timestamps();
            $table->integer('uid');
            $table->string('url_1');
            $table->string('url_2');
            $table->string('url_3');
            $table->string('url_4');
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
