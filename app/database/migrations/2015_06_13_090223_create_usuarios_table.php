<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Usuario', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('rol_id')->unsigned();
            $table->string('username');
            $table->string('password');
            $table->string('remember_token',100);
			$table->timestamps();
            $table->foreign('rol_id')->references('rol_id')->on('Rol');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Usuario');
	}

}
