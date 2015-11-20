<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroSolicitanteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('RegistroSolicitante', function(Blueprint $table)
		{
            $table->increments('regsoli_id');
            $table->integer('emp_id')->unsigned();
            $table->string('regsoli_Nombre','100');
            $table->string('regsoli_Apellidos','100');
            $table->string('regsoli_Telefono','100');
            $table->string('regsoli_Email','100');
            $table->foreign('emp_id')->references('emp_id')->on('Empresa');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('RegistroSolicitante');
	}

}
