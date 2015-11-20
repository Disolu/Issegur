<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroParticipanteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('RegistroParticipante', function(Blueprint $table)
		{
            $table->integer('reg_id')->unsigned();
            $table->integer('turno_id')->unsigned();
            $table->integer('pa_id')->unsigned();
            $table->integer('emp_id')->unsigned()->nullable();
//            $table->integer('detop_id')->unsigned();
            $table->date('fecha_programacion');

            $keys = array('reg_id', 'pa_id');
            $table->primary($keys);
            $table->foreign('reg_id')->references('reg_id')->on('Registro');
            $table->foreign('turno_id')->references('turno_id')->on('Turno');
            $table->foreign('pa_id')->references('pa_id')->on('Participante');
            $table->foreign('emp_id')->references('emp_id')->on('Empresa');
//            $table->foreign('detop_id')->references('detop_id')->on('DetalleNroOperacion');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('RegistroParticipante');
	}

}
