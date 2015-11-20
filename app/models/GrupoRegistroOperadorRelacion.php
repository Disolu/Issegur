<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class GrupoRegistroOperadorRelacion extends Eloquent
{
    protected $table = 'GrupoRegistroOperadorRelacion';
    //protected $primaryKey = 'op_id';
    public $timestamps = false;

    public function registrarGrupoOperadorRelaction($operadores, $savedGrupoRegistroId){

        foreach($operadores as $operador){
            $newGrupoOp =  new GrupoRegistroOperadorRelacion();
            $newGrupoOp->greg_id = $savedGrupoRegistroId;
            $newGrupoOp->op_id = $operador;
            $newGrupoOp->save();
        }
    }

}