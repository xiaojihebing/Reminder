<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLantouzisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lantouzis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lid');
            $table->string('title')->nullable();
            $table->string('rate')->nullable();
            $table->string('days')->nullable();
            $table->string('remain_money')->nullable();
            $table->string('buy_url')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('lantouzis');
    }
}
