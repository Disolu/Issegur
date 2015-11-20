<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class GrupoRegistro extends Eloquent
{
    protected $table = 'GrupoRegistro';
    protected $primaryKey = 'greg_id';
    public $timestamps = false;

    public function registrarGrupo($fechaProgramacion, $turnoId){
        $newGrupo =  new GrupoRegistro();

        $newGrupo->greg_fecha_programacion = Carbon\Carbon::createFromFormat('d/m/Y', $fechaProgramacion);
        $newGrupo->turno_id = $turnoId;
        $newGrupo->save();

        return $newGrupo;
    }

}