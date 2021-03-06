<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class ParticipanteOperadorRelacion extends Eloquent
{
    protected $table = 'ParticipanteOperadorRelacion';
    public $timestamps = true;
    protected $primaryKey = 'pa_id';

    public function registrarParticipanteOperadorRelacion($operadores, $participanteId, $registroId){
        foreach($operadores as $operador){
            $newRegParOp =  new ParticipanteOperadorRelacion();
            $newRegParOp->pa_id = $participanteId;
            $newRegParOp->op_id = $operador;
            $newRegParOp->reg_id = $registroId;
            $newRegParOp->save();
        }

    }

}
