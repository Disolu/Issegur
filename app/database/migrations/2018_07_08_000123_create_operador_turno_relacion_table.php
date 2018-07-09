<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperadorTurnoRelacionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('OperadorTurnoRelacion', function(Blueprint $table)
		{
            $table->integer('op_id')->unsigned();
            $table->integer('turno_id')->unsigned();
            
            $keys = array('op_id','turno_id');
            $table->primary($keys);
            $table->foreign('op_id')->references('op_id')->on('Operador');
            $table->foreign('turno_id')->references('turno_id')->on('Turno');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('OperadorTurnoRelacion');
	}

}
