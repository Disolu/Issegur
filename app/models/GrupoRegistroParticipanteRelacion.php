<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class GrupoRegistroParticipanteRelacion extends Eloquent
{
    protected $table = 'GrupoRegistroParticipanteRelacion';
    //protected $primaryKey = 'op_id';
    public $timestamps = false;

    public function registrarGrupoParticipanteRelaction($participantes, $savedGrupoRegistroId, $savedEmpresaId){

        foreach($participantes as $participante){
            $newRegParticipante =  new GrupoRegistroParticipanteRelacion();

            $savedParticipante = with(new Participante)->guardar($participante);
            $newRegParticipante->emp_id = $savedEmpresaId;
            $newRegParticipante->greg_id = $savedGrupoRegistroId;
            $newRegParticipante->pa_id = $savedParticipante->pa_id;
            $newRegParticipante->save();
        }
    }



}