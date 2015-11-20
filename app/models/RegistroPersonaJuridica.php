<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class RegistroPersonaJuridica extends Eloquent
{
    protected $table = 'RegistroPersonaJuridica';
    //protected $primaryKey = 'op_id';
    public $timestamps = false;

    public function registrarPersonaJuridica($savedRegistroId, $savedGrupoRegistroId){
        $newRegJuridica =  new RegistroPersonaJuridica();

        $newRegJuridica->greg_id = $savedGrupoRegistroId;
        $newRegJuridica->reg_id = $savedRegistroId;
        $newRegJuridica->save();
    }

}