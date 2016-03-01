<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class RegistroParticipante extends Eloquent
{
    protected $table = 'RegistroParticipante';
    protected $primaryKey = 'pa_id';
    public $timestamps = false;

    public function guardarRegistroParticipante($savedRegistroId,$fechaProgramacion, $turnoId, $participanteId, $savedEmpresaId = null){

        $newRegistroParticipante = new RegistroParticipante();

        $newRegistroParticipante->reg_id = $savedRegistroId;
        $newRegistroParticipante->turno_id = $turnoId;
        $newRegistroParticipante->pa_id = $participanteId;
        $newRegistroParticipante->emp_id = $savedEmpresaId;
        $newRegistroParticipante->fecha_programacion = $fechaProgramacion;

        $newRegistroParticipante->save();

        return $newRegistroParticipante;
    }

    public function guardarRegistroParticipantes($savedRegistroId,$fechaProgramacion, $turnoId, $participantes, $operadores, $savedEmpresaId ,$nroOperacion,$montoPago, $fechaOperacionFormatted){
        if($participantes){
            foreach($participantes as $par){
                $participante = array (
                    "dni" =>   $par['dni'],
                    "nombres" =>  $par['nombres'],
                    "ape_paterno" => $par['ape_paterno'],
                    "ape_materno" => $par['ape_materno'],
                    "nroOperacion" => $nroOperacion,
                    "fechaOperacion" => $fechaOperacionFormatted,
                    "montoPago" => $montoPago,
                    "email" =>  null
                );
                $savedParticipante = with(new Participante)->guardar($participante);

                $newRegistroParticipante = new RegistroParticipante();
                $newRegistroParticipante->reg_id = $savedRegistroId;
                $newRegistroParticipante->turno_id = $turnoId;
                $newRegistroParticipante->pa_id = $savedParticipante->pa_id;
                $newRegistroParticipante->emp_id = $savedEmpresaId;
                $newRegistroParticipante->fecha_programacion = $fechaProgramacion;

                $newRegistroParticipante->save();

                with(new ParticipanteOperadorRelacion)->registrarParticipanteOperadorRelaction($operadores,$savedParticipante->pa_id, $savedRegistroId);
            }
        }
    }

    public function reprogramarParticipante($participanteId, $fechaProgramacion,$turnoId){
        $registroParticipante = RegistroParticipante::where('pa_id','=', $participanteId)->first();
        $registroParticipante->turno_id = $turnoId;
        $registroParticipante->fecha_programacion = $fechaProgramacion;
        $registroParticipante->save();

        $participante = Participante::where('pa_id','=', $participanteId)->first();
        $participante->pa_asistencia = null;
        $participante->save();
    }

}