<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJingdongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jingdongs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('skuid');
            $table->string('current_price')->nullable();
            $table->string('target_price')->nullable();
            $table->string('rate')->nullable();
            $table->string('times')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
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
        Schema::dropIfExists('jingdongs');
    }
}
