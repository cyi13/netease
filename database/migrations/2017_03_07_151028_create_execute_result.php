<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExecuteResult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   if(!Schema::hasTable('execute_result')){
            Schema::create('execute_result', function (Blueprint $table) {
                $table->engine = 'myisam';
                $table->increments('id');
                $table->integer('cateId');
                $table->integer('offset');
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
        Schema::dropIfExists('execute_result');
    }
}
