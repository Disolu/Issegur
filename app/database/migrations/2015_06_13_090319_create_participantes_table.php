<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Participante', function(Blueprint $table)
		{
			$table->increments('pa_id');
            $table->string('pa_dni','8');
            $table->string('pa_nombres','200');
            $table->string('pa_apellido_paterno','250');
            $table->string('pa_apellido_materno','250');
            $table->string('pa_email','100')->nullable();
            $table->string('pa_foto','500');
            $table->string('pa_ficha_asistencia','500');
            $table->string('pa_examen','500');
            $table->boolean('pa_asistencia')->nullable();
            $table->decimal('pa_nota',5,2)->nullable();
            $table->boolean('pa_aprobado')->nullable();
            $table->string('detop_numero','20');
            $table->date('detop_fecha')->nullable();
            $table->double('detop_monto',10,2);
            $table->boolean('pa_eliminado')->default(false);
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
		Schema::drop('Participante');
	}

}
