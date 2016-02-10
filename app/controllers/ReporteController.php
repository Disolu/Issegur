<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25/11/2015
 * Time: 11:16 PM
 */

class ReporteController extends BaseController{

    public function ReporteParticipantesByOperador(){
        $operadorId = $_GET['operadorId'];
        $fechaInicio = $_GET['fechaInicio'];
        $fechaFin = $_GET['fechaFin'];

        //format dates
        $fechaInicioFormatted = DateTime::createFromFormat('d/m/Y', $fechaInicio)->format('Y-m-d');
        $fechaFinFormatted = DateTime::createFromFormat('d/m/Y', $fechaFin)->format('Y-m-d');

        $participante = new Participante();
        $reportData = $participante->reporteParticipantesPorOperador($operadorId, $fechaInicioFormatted, $fechaFinFormatted);

        return Response::json(array(
            'participantes' =>  $reportData,
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function ReporteParticipantesByOperadorDetalle(){
        
    }

}