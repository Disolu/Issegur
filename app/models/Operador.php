<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class Operador extends Eloquent
{
    protected $table = 'Operador';
    protected $primaryKey = 'op_id';
    public $timestamps = false;

    public function obtenerOperadores(){
        $operadores = DB::table('Operador')->get();

        return $operadores;
    }

}