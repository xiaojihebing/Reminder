<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRfqtasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfqtasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('taskname');
            $table->string('keyword');
            $table->string('category')->nullable();
            $table->string('email')->nullable();
            $table->string('rate')->nullable();
            $table->string('times')->nullable();
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
        Schema::dropIfExists('rfqtasks');
    }
}
