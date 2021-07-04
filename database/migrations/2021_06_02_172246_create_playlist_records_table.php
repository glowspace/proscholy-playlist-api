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
            $table->string('type');
            $table->unsignedInteger('song_lyric_id');
            $table->unsignedInteger('title_tag_id')->nullable();
            $table->string('title_custom')->nullable();
            $table->string('name');
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
