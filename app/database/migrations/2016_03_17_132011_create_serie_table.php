<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerieTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('Serie', function(Blueprint $table)
    {
        $table->increments('id');
        $table->integer('serie')->unsigned();
        $table->integer('number')->unsigned();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('Serie');
  }

}
