<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 03/08/2015
 * Time: 07:29 PM
 */

class ConsultaController extends BaseController{

    public function InitializeConsulta(){
        return View::make('consultaPersonal.index');
    }

    public function InitializeConsultaVolvo(){
        return View::make('consultaPersonal.volvo');
    }

    public function BuscarPersonal(){
        $searchText = $_GET['searchText'];

        $participante = new Participante();
        $matchingParticipantes = $participante->obtenerPorDniNombreOApellido($searchText);

        return Response::json(array(
            'participantes' =>  $matchingParticipantes
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function BuscarPersonalPorOperador($operadorId){
        $searchText = $_GET['searchText'];

        $participante = new Participante();
        $matchingParticipantes = $participante->obtenerPorDniNombreOApellidoYOperador($searchText, $operadorId);

        return Response::json(array(
            'participantes' =>  $matchingParticipantes
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function GetParticipanteInfoByDNI(){
        $dni = $_GET['dni'];
        $participante = new Participante();
        $participanteInfo = $participante->obtenerInfoPorDNI($dni);

        return Response::json(array(
            'participanteInfo' =>  $participanteInfo
        ), 200
        )->setCallback(Input::get('callback'));
    }
}