<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCloudMusicCategory extends Migration
{
    /**
     * 创建网易云音乐分类表
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('cloud_music_category')){
            Schema::create('cloud_music_category', function (Blueprint $table) {
                $table->engine = 'myisam';
                $table->increments('cateId');
                $table->string('cateName',20);
                $table->integer('parentCateId')->default(0);
                $table->string('parentCateName',20)->default(0);
                $table->string('link')->default(0);                                                                                 
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cloud_music_category');
    }
}
