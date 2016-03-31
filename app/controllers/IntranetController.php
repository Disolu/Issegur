<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19/07/2015
 * Time: 10:59 AM
 */

class IntranetController extends BaseController{

    public function InitializeCalendar(){
        if (Auth::check())
        {
            return View::make('intranet.calendar.calendar');
        }
        else{
            return View::make('intranet.auth.login');
        }
    }

    public function InitializeParticipantes(){
        if (Auth::check())
        {
            return View::make('intranet.participantes.participantes');
        }
        else{
            return View::make('intranet.auth.login');
        }
    }

    public function InitializeReportes()
    {
        if (Auth::check())
        {
            return View::make('intranet.reportes.reportesLista');
        }
        else{
            return View::make('intranet.auth.login');
        }
    }

    public function InitializeRepParticipantesPorOperador(){
        if (Auth::check())
        {
            return View::make('intranet.reportes.reporteParticipantesOperador');
        }
        else{
            return View::make('intranet.auth.login');
        }
    }

    public function InitializeRepParticipantesPorEmpresa(){
        if (Auth::check())
        {
            return View::make('intranet.reportes.reporteParticipantesEmpresa');
        }
        else{
            return View::make('intranet.auth.login');
        }
    }

    public function ReprogramarParticipantes(){
        if (Auth::check())
        {
            return View::make('intranet.reprogramacion.reprogramacion');
        }
        else{
            return View::make('intranet.auth.login');
        }
    }

    public function IntranetFacturas(){
        if (Auth::check())
        {
            $user = Auth::user();
            return View::make('intranet.facturas.facturas',compact('user'));
        }
        else{
            return View::make('intranet.auth.login');
        }
    }

    public function ObtenerParticipantesPorFechaYDia(){
        $participante = new Participante;
        //obtenemos los parametros
        $fecha = $_GET['fecha'];
        $dia = $_GET['dia'];
        //llamamos a la funcion
        $participantesPorTurno = $participante->obtenerPorFechaAgrupadosEnTurnos($fecha, $dia);

        return Response::json(array(
            'result' =>  $participantesPorTurno
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function ObtenerParticipantesPorFechaYTurno(){
        $participante = new Participante;
        //obtenemos los parametros
        $fecha = $_GET['fecha'];
        $turno = $_GET['turno'];
        $searchText = $_GET['searchText'];

        $participantesPorTurno = null;
        //si existen los valores
        if($fecha && $turno && (strlen($searchText) == 0)){
            $participantesPorTurno = $participante->obtenerParticipantesPorFechayTurno($fecha, $turno);
        }
        else if ($fecha && $turno && (strlen($searchText) > 0)){
            $participantesPorTurno = $participante->obtenerParticipantesPorFechayTurnoYSearchText($fecha, $turno, $searchText);
        }

        return Response::json(array(
            'result' =>  $participantesPorTurno
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function ObtenerDetalleOperacionPorRegistro(){

        $detOperacionId  = $_GET['detOperacionId'];

        $detalleOperacionObj = new DetalleNroOperacion;
        $detalleNroOperacionInfo = $detalleOperacionObj->obtenerDetalleOperacionPorId($detOperacionId);

        $participante = new Participante;
        $nroParticipantes =  $participante->obtenerNumeroDeParticipantesPorDetalleOperacionId($detOperacionId);

        $detalleOperacion =  new stdClass;
        $detalleOperacion->fecha = $detalleNroOperacionInfo->detop_fecha;
        $detalleOperacion->horas = $detalleNroOperacionInfo->detop_horas;
        $detalleOperacion->minutos = $detalleNroOperacionInfo->detop_minutos;
        $detalleOperacion->apm = $detalleNroOperacionInfo->detop_apm;
        $detalleOperacion->monto = $detalleNroOperacionInfo->detop_monto;
        $detalleOperacion->cantidadParticipantes = $nroParticipantes;
        $detalleOperacion->tipoPago = $detalleNroOperacionInfo->detop_tipoPago;

        return Response::json(array(
            'result' =>  $detalleOperacion
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function GuardarDetalleOperacionPorRegistro(){
        $detalle  = $_GET['detalle'];
        $newDetalleOperacion = new DetalleNroOperacion();

        $newDetalleOperacion->guardarDetalleNroOperacion($detalle["detOperacionId"],$detalle['nroOperacion'],$detalle['monto'],$detalle['fecha'],
            $detalle['tipoPago']);
        // $detalle['horas'],$detalle['minutos'],$detalle['apm'],

        return Response::json(array(
            'result' =>  true
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function ActualizarParticipantes(){

        $participante = new Participante;
        $registro = new Registro;

        if(Request::ajax()){
            //get the raw data
            $data = Input::all();

            //getting the list of participants
            $participantesArray = $data["participantes"];

            foreach($participantesArray as $par){
                $participante->actualizarParticipante($par);
                //$registro->actualizarNroOperacion($par["registroId"],$par["nroOperacion"]);
            }
        }

        return Response::json(array(
            'result' =>  true
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function AgregarTurnoManual(){
        $nuevoTurno = $_GET['turno'];

        $turnoObj =  new Turno();
        $turnoObj->guardarTurno($nuevoTurno["dia"],$nuevoTurno["hora_inicio"],$nuevoTurno["hora_fin"],$nuevoTurno["fecha_unica"]);

        return Response::json(array(
            'result' =>  true
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function RegistarParticipanteManual(){
        $participanteObj = new Participante;
        $participante = $_GET['participante'];

        $fecha = $participante["fecha"];
        $turnoId = $participante["turno"];

        //variable para almacenar resultado
        $validation = array();
        $resultado = true;


        if($participanteObj->puedeParticipanteRegistrarse($fecha, $turnoId, 1 , "M")){
            //inicializamos los objetos del modelo
            $participanteObj = new Participante;
            $registroObj =  new Registro;
            $empresaObj =  new Empresa;
            $detalleOperacionObj = new DetalleNroOperacion;
            $registroParticipanteObj =  new RegistroParticipante;
            $participanteOperadorObj =  new ParticipanteOperadorRelacion;
            //formatiamos la fecha

            if($participante["tipoRegistro"] == "J"){
                /*registro de empresa*/
                $ruc = $participante['ruc'];
                $razonSocial = $participante['razonSocial'];

                $savedEmpresa = $empresaObj->registrarEmpresa($ruc, $razonSocial);

                /*registro de pagos*/
                $nroOperacion = $participante['nroOperacion'];
                $montoPago = $participante['monto'];

                $savedRegistro = $registroObj->inicializarRegistro("M"); //$archivo

                //$savedDetalleOperacion = $detalleOperacionObj->inicializarDetalleNroOperacion($nroOperacion,$montoPago, null);

                //creamos al participante
                $participanteRaw = array (
                    "dni" =>   $participante['dni'],
                    "nombres" =>  $participante['nombres'],
                    "ape_paterno" => $participante['ape_paterno'],
                    "ape_materno" => $participante['ape_materno'],
                    "nroOperacion" => $nroOperacion,
                    "fechaOperacion" => "",
                    "montoPago" => $montoPago
                );
                //lo guardamos
                $savedParticipante = $participanteObj->guardar($participanteRaw);

                $registroParticipanteObj->guardarRegistroParticipante($savedRegistro->reg_id, $fecha,$turnoId,$savedParticipante->pa_id,$savedEmpresa->emp_id);

                /*registro de los operadores*/
                $operador = $participante['almacen'];

                $participanteOperadorObj->registrarParticipanteOperadorRelaction($operador,$savedParticipante->pa_id, $savedRegistro->reg_id);
            }
            else{
                /*registro de pagos*/
                $nroOperacion = $participante['nroOperacion'];
                $montoPago = $participante['monto'];

                $savedRegistro = $registroObj->inicializarRegistro("M");//$archivo

                //$savedDetalleOperacion = $detalleOperacionObj->inicializarDetalleNroOperacion(, null);
                /*registor del participante*/
                //creamos al participante
                $participanteRaw = array (
                    "dni" =>   $participante['dni'],
                    "nombres" =>  $participante['nombres'],
                    "ape_paterno" => $participante['ape_paterno'],
                    "ape_materno" => $participante['ape_materno'],
                    "nroOperacion" => $nroOperacion,
                    "fechaOperacion" => "",
                    "montoPago" => $montoPago
                );
                //lo guardamos
                $savedParticipante = $participanteObj->guardar($participanteRaw);

                $registroParticipanteObj->guardarRegistroParticipante($savedRegistro->reg_id, $fecha,$turnoId,$savedParticipante->pa_id);

                /*registro de los operadores*/
                $operador = $participante['almacen'];

                $participanteOperadorObj->registrarParticipanteOperadorRelaction($operador,$savedParticipante->pa_id, $savedRegistro->reg_id);
            }
        }
        else{
            $turno = with(new Turno)->obtenerTurnoPorIdTodos($turnoId);

            $resultado = false;
            $limite =  new stdClass();
            $limite->fecha = DateTime::createFromFormat('Y-m-d', $fecha)->format('d/m/Y');
            $limite->turno = $turno;
            array_push($validation,$limite);
        }

        return Response::json(array(
            'resultado' =>  $resultado,
            'validation' => $validation
        ), 200
        )->setCallback(Input::get('callback'));

    }

    public function GenerarFichaExcel($turno, $fecha){
        $participante = new Participante;
        $participantesPorTurno = $participante->obtenerParticipantesPorFechayTurno($fecha, $turno);
        $fechaOriginal = DateTime::createFromFormat('Y-m-d', $fecha);
        $fechaFormat = $fechaOriginal->format('d/m/Y');

        Excel::create("RegistroAsistencia", function($excel) use($fechaFormat, $participantesPorTurno){
            $excel->sheet('Sheetname', function($sheet) use($fechaFormat, $participantesPorTurno){
                $sheet->loadView('excel.registroAsistencia')->with('fecha', $fechaFormat)->with('participantes', $participantesPorTurno);
                $sheet->cells('A1:I1', function($cells) {
                    // manipulate the range of cells
                    // Set font size
                    $cells->setFontSize(18);
                    $cells->setFontWeight('bold');
                });
            });
        })->export('xlsx');
    }

    public function ObtenerParticipantesAReprogramar(){
        $participante = new Participante;
        $participantesAReprogramar = null;

        $searchText = $_GET['searchText'];
        //llamamos a la funcion
        if($searchText && strlen($searchText) > 0){
            $participantesAReprogramar = $participante->obtenerParticipantesAReprogramarBySearchtext($searchText);
        }
        else{
            $participantesAReprogramar = $participante->obtenerParticipantesAReprogramar();
        }


        return Response::json(array(
            'result' =>  $participantesAReprogramar
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function ReprogramarParticipante(){
        $participanteObj = new Participante;
        $registroParticipanteObj =  new RegistroParticipante();

        $dni = $_GET['dni'];
        $fechaProgramacion = $_GET['fechaProgramacion'];
        $fechaFormatted = DateTime::createFromFormat('d/m/Y', $fechaProgramacion)->format('Y-m-d');
        $turnoId = $_GET['turnoId'];

        //variable para almacenar resultado
        $validation = array();
        $resultado = true;


        if($participanteObj->puedeParticipanteRegistrarse($fechaFormatted, $turnoId, 1 , "W")) {
            $matchingPar = $participanteObj->consultarDNI($dni);
            $registroParticipanteObj->reprogramarParticipante($matchingPar[0]->pa_id, $fechaFormatted, $turnoId);
        }
        else{
            $turno = with(new Turno)->obtenerTurnoPorIdTodos($turnoId);

            $resultado = false;
            $limite =  new stdClass();
            $limite->fecha = $fechaFormatted;
            $limite->turno = $turno;
            array_push($validation,$limite);
        }

        return Response::json(array(
            'resultado' =>  $resultado,
            'validation' => $validation
        ), 200
        )->setCallback(Input::get('callback'));
    }
}
