<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class RegistroPersonaNatural extends Eloquent
{
    protected $table = 'RegistroPersonaNatural';
    protected $primaryKey = 'regnat_id';
    public $timestamps = false;

    public function registrarPersonaNatural($savedRegistroId, $savedParticipanteId, $fechaProgramacion,$turnoId, $email){
        $newRegNatural =  new RegistroPersonaNatural();

        $newRegNatural->reg_id = $savedRegistroId;
        $newRegNatural->pa_id = $savedParticipanteId;
        $newRegNatural->regnat_fechaProgramacion = Carbon\Carbon::createFromFormat('d/m/Y', $fechaProgramacion);
        $newRegNatural->turno_id = $turnoId;
        $newRegNatural->regnat_email = $email;
        $newRegNatural->save();

        return $newRegNatural;
    }

}