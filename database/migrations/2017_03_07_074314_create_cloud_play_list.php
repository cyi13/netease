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
                $table->increments('id');
                $table->integer('listId')->index()->comment('歌单Id');
                $table->string('listTitle',50)->nullable()->comment('歌单标题');
                $table->string('listImg')->nullable()->comment('歌单头像');
                $table->string('link')->comment('歌单的链接地址');
                $table->integer('listenNum')->nullable()->comment('歌单收听数');
                $table->string('by')->nullable()->comment('创建人');
                $table->string('spaceLink')->nullable()->comment('创建人的空间链接');
                $table->integer('parentCateId')->default(0)->comment('所属分类');
                $table->integer('collectNum')->nullable()->default(0)->comment('被收藏数');
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
