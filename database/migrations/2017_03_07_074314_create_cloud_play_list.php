<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCloudPlayList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('cloud_play_list')){
            Schema::create('cloud_play_list', function (Blueprint $table) {
                $table->engine = 'Innodb';
                $table->integer('listId')->comment('歌单Id')->primary();
                $table->string('listTitle',50)->comment('歌单标题');
                $table->string('listImg')->comment('歌单头像');
                $table->string('link')->comment('歌单的链接地址');
                $table->integer('parentCateId')->comment('所属分类');
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
        Schema::dropIfExists('cloud_play_list');
    }
}
