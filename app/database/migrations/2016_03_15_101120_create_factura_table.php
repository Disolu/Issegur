<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturaTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('Factura', function(Blueprint $table)
    {
        $table->increments('id');
        $table->string('number',11);
        $table->string('ruc',11);
        $table->string('empresa',255);
        $table->text('direccion');
        $table->text('data');
        $table->date('date');
        $table->integer('userid')->unsigned();
        $table->text('letras');
        $table->foreign('userid')->references('id')->on('Usuario');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('Factura');
  }

}
