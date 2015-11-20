<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipanteOperadorRelacionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ParticipanteOperadorRelacion', function(Blueprint $table)
		{
            $table->integer('pa_id')->unsigned();
            $table->integer('op_id')->unsigned();
            
            $keys = array('pa_id', 'op_id');
            $table->primary($keys);
            $table->foreign('pa_id')->references('pa_id')->on('Participante');
            $table->foreign('op_id')->references('op_id')->on('Operador');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ParticipanteOperadorRelacion');
	}

}
