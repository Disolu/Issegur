<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class RegistroSolicitante extends Eloquent
{
    protected $table = 'RegistroSolicitante';
    protected $primaryKey = 'regsoli_id';
    public $timestamps = false;

    public function guardarRegistroSolicitante($savedEmpresaId, $soliNombre, $soliApellidos, $soliTelefono, $soliEmail){

        $newRegistroSolicitante = new RegistroSolicitante;

        $newRegistroSolicitante->emp_id = $savedEmpresaId;
        $newRegistroSolicitante->regsoli_Nombre = $soliNombre;
        $newRegistroSolicitante->regsoli_Apellidos = $soliApellidos;
        $newRegistroSolicitante->regsoli_Telefono = $soliTelefono;
        $newRegistroSolicitante->regsoli_Email = $soliEmail;

        $newRegistroSolicitante->save();

        return $newRegistroSolicitante;
    }

}