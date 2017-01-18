<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmzdmtasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smzdmtasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('rurl');
            $table->integer('type')->default(1);
            $table->string('rate')->nullable();
            $table->string('times')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('smzdmtasks');
    }
}
