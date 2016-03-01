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
    public $timestamps = false;
    protected $primaryKey = 'pa_id';

    public function registrarParticipanteOperadorRelacion($operadores, $participanteId, $registroId){
        if(is_array($operadores)){
            foreach($operadores as $operador){
            $newRegParOp =  new ParticipanteOperadorRelacion();
            $newRegParOp->pa_id = $participanteId;
            $newRegParOp->op_id = $operador;
            $newRegParOp->reg_id = $registroId;
            $newRegParOp->save();
            }
        }
        else{
            $newRegParOp =  new ParticipanteOperadorRelacion();
            $newRegParOp->pa_id = $participanteId;
            $newRegParOp->op_id = $operadores;
            $newRegParOp->reg_id = $registroId;
            $newRegParOp->save();
        }

    }

}