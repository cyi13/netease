<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('fun_function_list',function(Blueprint $table){
                //存储引擎
                $table->engine = "InnoDB";
                //自增长Id
                $table->increments('id');
                //创建列 string为数据库的varchar类型
                $table->string('functionName');
                $table->string('functionAddress');
                //默认添加create_at和update_at 列
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
        Schema::dropIfExists('fun_function_list');
    }
}
