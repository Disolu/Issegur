<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class Turno extends Eloquent
{
    protected $table = 'Turno';
    protected $primaryKey = 'turno_id';

    public function consultarTurnosPorDia($dia){
        $turnos = DB::table('Turno')
        ->where('turno_dia', $dia)
        ->where('turno_activo', 1)
        ->get();

        return $turnos;
    }

    public function obtenerTurnoPorId($turnoId){
        $turno = DB::table('Turno')->where('turno_id', $turnoId)->where('turno_fecha_unica', null)->first();

        $horaInicio = date("g:i A", strtotime($turno->turno_hora_inicio));
        $horaFin = date("g:i A", strtotime($turno->turno_hora_fin));
        $horario = $horaInicio.' - '.$horaFin;

        return $horario;
    }

    public function obtenerTurnoPorIdTodos($turnoId){
        $turno = DB::table('Turno')->where('turno_id', $turnoId)->first();

        $horaInicio = date("g:i A", strtotime($turno->turno_hora_inicio));
        $horaFin = date("g:i A", strtotime($turno->turno_hora_fin));
        $horario = $horaInicio.' - '.$horaFin;

        return $horario;
    }

    public function obtenerNombreDiaPorId($turnoId){
        $turno = DB::table('Turno')->where('turno_id', $turnoId)->first();

        return $turno->turno_dia;
    }

    public function guardarTurno($dia, $hora_ini,$hora_fin,$fecha_unica){
        $nuevoTurno =  new Turno();
        $nuevoTurno->turno_dia = $dia;
        $nuevoTurno->turno_hora_inicio = $hora_ini;
        $nuevoTurno->turno_hora_fin = $hora_fin;
        $nuevoTurno->turno_fecha_unica =  DateTime::createFromFormat('d/m/Y', $fecha_unica)->format('Y-m-d');
        $nuevoTurno->save();
    }

}