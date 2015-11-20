<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Turno', function(Blueprint $table)
		{
			$table->increments('turno_id');
            $table->string('turno_dia','20');
            $table->time('turno_hora_inicio');
            $table->time('turno_hora_fin');
            $table->date('turno_fecha_unica')->nullable();
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
		Schema::drop('Turno');
	}

}
