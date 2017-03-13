<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCloudMusicMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cloud_music_message', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('musicId')->comment('音乐id');
            $table->string('musicTitle')->nullable()->comment('音乐标题');
            $table->string('link')->nullable()->comment('链接地址');
            $table->string('musicAlbumTitle')->nullable()->comment('专辑名称');
            $table->string('musicAlbumLink')->nullable()->comment('专辑链接');
            $table->string('singerMessage')->nullable()->comment('歌手信息');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cloud_music_message');
    }
}
