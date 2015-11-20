<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class Registro extends Eloquent
{
    protected $table = 'Registro';
    protected $primaryKey = 'reg_id';

    public function inicializarRegistro($modalidad = "W"){ //$voucherArchivoUrl

        $newRegistro = new Registro;

        $newRegistro->reg_voucher = "";
        $newRegistro->reg_modalidad = $modalidad;
        $newRegistro->save();

        return $newRegistro;
    }

    public function guardarDetalleOperacionPorRegistro($detalle){
        $existingRegistro = Registro::where('reg_id','=', $detalle['registroId'])->first();
        $newDetalleOperacion = new DetalleNroOperacion();

        $newDetalleOperacion->guardarDetalleNroOperacion($existingRegistro->detop_id,$detalle['nroOperacion'],$detalle['monto'],$detalle['fecha'],
                                                                                            $detalle['horas'],$detalle['minutos'],$detalle['apm'],
                                                                                            $detalle['tipoPago']);

        $existingRegistro->save();
    }

    public function actualizarNroOperacion($detalleOpId, $nroOperacion){
        $detalleOperacion =  new DetalleNroOperacion();

        $detalleOperacion->actualizarNroOperacion($detalleOpId, $nroOperacion);
    }

}