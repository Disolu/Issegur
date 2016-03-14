<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRansaUsuariosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('RansaUsuario', function(Blueprint $table)
		{
            $table->increments('ruser_id');
            $table->string('ruser_username');
            $table->string('ruser_password');
            $table->boolean('ruser_activo')->default(true);
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
		Schema::drop('RansaUsuario');
	}

}
