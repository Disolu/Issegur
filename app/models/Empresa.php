<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class Empresa extends Eloquent
{
    protected $table = 'Empresa';
    protected $primaryKey = 'emp_id';
    public $timestamps = false;

    public function getEmpresaByRuc($ruc){
        $empresa = DB::table('Empresa')->where('emp_ruc', $ruc)->first();

        return $empresa;
    }

    public function getEmpresabyRazonSocial($razonSocial){
        $empresa = DB::table('Empresa')->where('emp_razon_social', $razonSocial)->first();

        return $empresa;
    }

    public function registrarEmpresa($ruc, $razonSocial){
        $existingEmpresa = Empresa::where('emp_ruc','=', $ruc)->first();

        if(!$existingEmpresa){
            $existingEmpresa =  new Empresa;
        }
        $existingEmpresa->emp_ruc = $ruc;
        $existingEmpresa->emp_razon_social = $razonSocial;
        $existingEmpresa->save();

        return $existingEmpresa;
    }

    public function obtenerNombresParaAutocomplete(){
        $empresas = DB::table('Empresa')->select('emp_id as id', 'emp_razon_social as name')->get();

        return $empresas;
    }

}