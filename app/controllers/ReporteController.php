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
        $skip = $_GET['skip'];
        $take = $_GET['take'];
        $sortField = $_GET['sortField'];
        $sortDirection = $_GET['sortDirection'];

        //format dates
        $fechaInicioFormatted = DateTime::createFromFormat('d/m/Y', $fechaInicio)->format('Y-m-d');
        $fechaFinFormatted = DateTime::createFromFormat('d/m/Y', $fechaFin)->format('Y-m-d');

        $participante = new Participante();
        $reportData = $participante->reporteParticipantesPorOperador($operadorId, 
                                                                     $fechaInicioFormatted, 
                                                                     $fechaFinFormatted, 
                                                                     $skip,$take,$sortField,$sortDirection);        

        return Response::json(array(
            'participantes' =>  $reportData

        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function GetReportParticipantesPorOperadorPagerDetails()
    {       
        $operadorId = $_GET['operadorId'];
        $fechaInicio = $_GET['fechaInicio'];
        $fechaFin = $_GET['fechaFin'];
        $pageSize = $_GET['pageSize'];

        //format dates
        $fechaInicioFormatted = DateTime::createFromFormat('d/m/Y', $fechaInicio)->format('Y-m-d');
        $fechaFinFormatted = DateTime::createFromFormat('d/m/Y', $fechaFin)->format('Y-m-d');

        $participante = new Participante();
        $totalRows = $participante->reporteParticipantesPorOperadorCount($operadorId, $fechaInicioFormatted, $fechaFinFormatted);
        $numberOfPages = floor($totalRows/ $pageSize) + 1;

        return Response::json(array(
            'numberOfPages' => $numberOfPages,
            'totalRows' => $totalRows
        ), 200
        )->setCallback(Input::get('callback'));   
    }

    public function ObtenerEmpresasNombresAutocomplete(){
        $empresa =  new Empresa();
        $empresas = $empresa->obtenerNombresParaAutocomplete();

        return Response::json(array(
            'empresasAutocomplete' => $empresas
        ), 200
        )->setCallback(Input::get('callback'));  
    }

    public function ObtenerEmoresaPorRazonSocial(){
        //obtenemos la razon social
        $razonSocial = $_GET['razonSocial'];

        $empresaObj = new Empresa();
        $solicitanteObj =  new RegistroSolicitante();
        $matchingEmpresa = $empresaObj->getEmpresabyRazonSocial($razonSocial);
        $solicitantes = $solicitanteObj->obtenerSolicitantesPorEmpresa($matchingEmpresa->emp_id);

        return Response::json(array(
            'matchingEmpresa' => $matchingEmpresa,
            'solicitantes' => $solicitantes
        ), 200
        )->setCallback(Input::get('callback'));  

    }
}

