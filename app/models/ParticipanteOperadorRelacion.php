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

    public function registrarParticipanteOperadorRelaction($operadores, $participanteId){
        if(is_array($operadores)){
            foreach($operadores as $operador){
            $newRegParOp =  new ParticipanteOperadorRelacion();
            $newRegParOp->pa_id = $participanteId;
            $newRegParOp->op_id = $operador;
            $newRegParOp->save();
            }
        }
        else{
            $newRegParOp =  new ParticipanteOperadorRelacion();
            $newRegParOp->pa_id = $participanteId;
            $newRegParOp->op_id = $operadores;
            $newRegParOp->save();
        }

    }

    public function actualizarOperador($regnat_id, $op_id){
        $detalleNaturalOperador =  RegistroPersonaNaturalOperadorRelacion::where('regnat_id','=', $regnat_id)->first();
        $detalleNaturalOperador->op_id = $op_id;
        $detalleNaturalOperador->save();
    }

}