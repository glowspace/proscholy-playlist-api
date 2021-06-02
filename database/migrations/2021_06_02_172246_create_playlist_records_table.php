<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_records', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedInteger('playlist_id');
            $table->unsignedInteger('song_lyric_id')->nullable();
            $table->string('title')->nullable();
            $table->unsignedInteger('order');
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
        Schema::dropIfExists('playlist_records');
    }
}