<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class DetalleNroOperacion extends Eloquent
{
    protected $table = 'DetalleNroOperacion';
    protected $primaryKey = 'detop_id';
    public $timestamps = false;

    public function guardarDetalleNroOperacion($nroOperacionId, $nroOperacion, $montoPago, $fechaOp,  $tipoPagoOp){ //$voucherArchivoUrl $horaOp, $minutosOp, $apmOp,
        $detalleOperacion = null;

        if($nroOperacionId){
            $detalleOperacion = DetalleNroOperacion::where('detop_id','=', $nroOperacionId)->first();

            $detalleOperacion->detop_monto = $montoPago;
            $detalleOperacion->detop_fecha = $fechaOp;
//            $detalleOperacion->detop_horas = $horaOp;
//            $detalleOperacion->detop_minutos = $minutosOp;
//            $detalleOperacion->detop_apm = $apmOp;
            $detalleOperacion->detop_tipoPago = $tipoPagoOp;
            $detalleOperacion->save();
        }


        return $detalleOperacion;
    }

    public function inicializarDetalleNroOperacion($nroOperacion, $montoPago , $fechaOperacion){
        $detalleOperacion = DetalleNroOperacion::where('detop_numero','=', $nroOperacion)->first();
        if(!$detalleOperacion){
            $detalleOperacion =  new DetalleNroOperacion();
            $detalleOperacion->detop_numero = $nroOperacion;
            $detalleOperacion->detop_monto = $montoPago;
            $detalleOperacion->detop_fecha = $fechaOperacion;
            $detalleOperacion->save();
        }

        return $detalleOperacion;
    }


    public function obtenerDetalleOperacionPorId($detalleOperacionId){
        $existingDetalleOperacion = DetalleNroOperacion::where('detop_id','=', $detalleOperacionId)->first();

        return $existingDetalleOperacion;
    }

    public function actualizarNroOperacion($detalleOperacionId, $nroOperacion)
    {
        $detalleOperacion = DetalleNroOperacion::where('detop_id','=', $detalleOperacionId)->first();

        $detalleOperacion->detop_numero = $nroOperacion;
        $detalleOperacion->save();
    }

    public function obtenerDetalleOperacionPorNroOperacion($nroOperacion){
        $existingDetalleOperacion = DetalleNroOperacion::where('detop_numero','=', $nroOperacion)->first();

        return $existingDetalleOperacion;
    }

}