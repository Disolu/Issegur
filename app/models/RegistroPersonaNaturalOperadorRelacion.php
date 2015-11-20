<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class RegistroPersonaNaturalOperadorRelacion extends Eloquent
{
    protected $table = 'RegistroPersonaNaturalOperadorRelacion';
    public $timestamps = false;

    public function registrarNaturalOperadorRelaction($operadores, $savedRegistroNaturalId){

        foreach($operadores as $operador){
            $newRegNaturalOp =  new RegistroPersonaNaturalOperadorRelacion();
            $newRegNaturalOp->regnat_id = $savedRegistroNaturalId;
            $newRegNaturalOp->op_id = $operador;
            $newRegNaturalOp->save();
        }
    }

    public function actualizarOperador($regnat_id, $op_id){
        $detalleNaturalOperador =  RegistroPersonaNaturalOperadorRelacion::where('regnat_id','=', $regnat_id)->first();
        $detalleNaturalOperador->op_id = $op_id;
        $detalleNaturalOperador->save();
    }

}