<?php
/**
 * Created by PhpStorm.
 * User: disolu
 * Date: 14/06/15
 * Time: 01:00 PM
 */

class RegistroController extends BaseController {

    public function start()
    {
        return View::make('registro.start');
    }

    public function RegistroJuridica()
    {
        return View::make('registro.registroJuridica');
    }

    public function RegistroNatural()
    {
        return View::make('registro.registroNatural');
    }

    public function Confirmacion()
    {
        return View::make('registro.registroConfirmacion');
    }

    public function MostrarHorariosPorDia(){
        $dia = $_GET['nombreDia'];
        $turnos = with(new Turno)->consultarTurnosPorDia($dia);
        //creamos el array para guardar los resultados
        $array = array();

        foreach($turnos as $turno){
            $horaInicio = date("g A", strtotime($turno->turno_hora_inicio));
            $horaFin = date("g A", strtotime($turno->turno_hora_fin));
            $horario = $horaInicio.' a '.$horaFin;
            $obj = new stdClass();
            $obj->turnoId = $turno->turno_id;
            $obj->turnoHorario = $horario;
            array_push($array, $obj);
        }

        return Response::json(array(
            'turnos' =>  $array
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function ObtenerOperadores()
    {
        $operadores = with(new Operador)->obtenerOperadores();

        return Response::json(array(
            'operadores' =>  $operadores
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function ConsultarDNI(){
        $paDNI = $_GET['dni'];

        $matchingParticipante = with(new Participante)->consultarDNI($paDNI);

        return Response::json(array(
            'participante' =>  $matchingParticipante
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function ConsultarRUC(){
        $paRUC = $_GET['ruc'];

        $matchingEmpresa = with(new Empresa)->getEmpresaByRUC($paRUC);

        return Response::json(array(
            'empresa' =>  $matchingEmpresa
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function ConsultarNroOperacion(){
        $nroOperacion = $_GET['nroOperacion'];

        $detalle = new DetalleNroOperacion();
        $existingDetalleOperacion = $detalle->obtenerDetalleOperacionPorNroOperacion($nroOperacion);

        return Response::json(array(
            'pago' =>  $existingDetalleOperacion
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function GuardarRegistroJuridica()
    {
        //creamos los objetos del modelo para acceder a los datos
        $registroObj =  new Registro();
        $participanteObj = new Participante();
        $empresaObj =  new Empresa();
        $registroSolicitanteObj =  new RegistroSolicitante();
        $registroParticipanteObj =  new RegistroParticipante();

        $data = Input::all();
        $registro = $data['registro'];
        /*registro de grupos*/
        $grupos = $registro['grupos'];

        //variables para procesar resultado de validacion de vacante
        $validation = array();
        //array para almacenar la validacion del participante
    	$vParticipante = array();
        $resultado = true;

        //si existen grupos
        if ($grupos) {
            foreach ($grupos as $grupo) {
                //obtenemos los datos que necesitamos para validar si hay vacante para realizar el registro
                $fechaProgramacion = $grupo['fechaProgramacion'];
                $turnoId = $grupo['turnoId'];
                //formatemos la fecha
                $fechaFormatted = DateTime::createFromFormat('d/m/Y', $fechaProgramacion)->format('Y-m-d');
                $participantesARegistrar = $grupo['participantes'];
                
                foreach($participantesARegistrar as $par){
                	$existingParticipante = Participante::where('pa_dni','=',$par['dni'])->first();
                	if($existingParticipante != null){
                	    $resultado = false;
                	    $limite =  new stdClass();
			    $limite->pa_dni = $par['dni'];
				
			    array_push($vParticipante ,$limite);  
			}			              	
                }

                if (!$participanteObj->puedeParticipanteRegistrarse($fechaFormatted, $turnoId, count($participantesARegistrar), "W")) {
                    $turno = with(new Turno)->obtenerTurnoPorIdTodos($turnoId);
                    $resultado = false;
                    $limite =  new stdClass();
                    $limite->fecha = $fechaProgramacion;
                    $limite->turno = $turno;
                    array_push($validation,$limite);
                }
            }
        }

        if($resultado){
            /*registro de empresa*/
            $ruc = $registro['ruc'];
            $razonSocial = $registro['razonSocial'];

            $savedEmpresa = $empresaObj->registrarEmpresa($ruc, $razonSocial);

            /*registro de pagos*/
            $nroOperacion = $registro['nroOperacion'];
            $fechaOperacion = $registro['fechaOperacion'];
            //formatemos la fechade operacion
            $fechaOperacionFormatted = DateTime::createFromFormat('d/m/Y', $fechaOperacion)->format('Y-m-d');
            //$archivo = $registro['archivo'];
            $montoPago = $registro['montoPago'];

            $savedRegistro = $registroObj->inicializarRegistro(); //$archivo

            /*registor de solicitante , en caso de persona Juridica*/
            $soliNombre = $registro['soliNombre'];
            $soliApellidos = $registro['soliApellido'];
            $soliTelefono = $registro['soliTelefono'];
            $soliEmail = $registro['soliEmail'];

            $registroSolicitanteObj->guardarRegistroSolicitante($savedEmpresa->emp_id, $soliNombre, $soliApellidos, $soliTelefono, $soliEmail);

            //Registro del detalle de operacion
            //$savedDetalleOperacion = $detalleOperacionObj->inicializarDetalleNroOperacion();

            /*registro de grupos*/
            $grupos = $registro['grupos'];
            //si existen grupos
            if($grupos){
                foreach ($grupos as $grupo){
                    //obtenemos los datos de fecha y turno
                    $fechaProgramacion = $grupo['fechaProgramacion'];
                    $turnoId = $grupo['turnoId'];

                    //formateamos la fecha
                    $fechaFormatted = DateTime::createFromFormat('d/m/Y', $fechaProgramacion)->format('Y-m-d');

                    //Obtenemos a los participantes del grupo
                    $participantes = $grupo['participantes'];

                    //Obtenemos a los operadores del grupo
                    $operadores = $grupo['selectedOperadoresIds'];

                    //guardamos el registro del participante y sus operadores
                    $registroParticipanteObj->guardarRegistroParticipantes($savedRegistro->reg_id,$fechaFormatted,$turnoId,$participantes,$operadores,$savedEmpresa->emp_id,$nroOperacion,$montoPago, $fechaOperacionFormatted);
                    
                }
            }
        }

        return Response::json(array(
            'resultado' =>  $resultado,
            'validation' => $validation,
            'validationParticipante' => $vParticipante
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function GuardarRegistroNatural()
    {
        //creamos los objetos del modelo para acceder a los datos
        $participanteObj = new Participante;
        $registroObj = new Registro;
        $registroParticipanteObj =  new RegistroParticipante;
        $participanteOperadorObj =  new ParticipanteOperadorRelacion;

        $data = Input::all();
        $registro  = $data['registro'];

        //obtenemos los datos que necesitamos para validar si hay vacante para realizar el registro
        $fechaProgramacion = $registro['fechaProgramacion'];
        $turnoId = $registro['turnoId'];
        //formatemos la fecha
        $fechaFormatted = DateTime::createFromFormat('d/m/Y', $fechaProgramacion)->format('Y-m-d');

        //variables para procesar resultado de validacion de vacante
        $validation = array();
        $vParticipante = array();
        $resultado = true;

        $existingParticipante = Participante::where('pa_dni', '=', $registro['dni'])->first();

        if ($existingParticipante != null) {
            $resultado = false;
            $limite = new stdClass();
            $limite->pa_dni = $registro['dni'];

            array_push($vParticipante, $limite);
        }

        //preguntamos si hay vacante
        if ($participanteObj->puedeParticipanteRegistrarse($fechaFormatted, $turnoId, 1, "W")) {
            if ($resultado) {

                /*registro de pagos*/
                $nroOperacion = $registro['nroOperacion'];
                $fechaOperacion = $registro['fechaOperacion'];
                //formatemos la fechade operacion
                $fechaOperacionFormatted = DateTime::createFromFormat('d/m/Y', $fechaOperacion)->format('Y-m-d');
                //$archivo = $registro['archivo'];
                $montoPago = $registro['montoPago'];

                $savedRegistro = $registroObj->inicializarRegistro();
                //          $savedDetalleOperacion = $detalleOperacionObj->inicializarDetalleNroOperacion();

                /*registor del participante*/
                //creamos al participante
                $participante = array(
                    "dni" => $registro['dni'],
                    "nombres" => $registro['nombres'],
                    "ape_paterno" => $registro['ape_paterno'],
                    "ape_materno" => $registro['ape_materno'],
                    "email" => $registro['email'],
                    "nroOperacion" => $nroOperacion,
                    "fechaOperacion" => $fechaOperacionFormatted,
                    "montoPago" => $montoPago
                );
                //lo guardamos
                $savedParticipante = $participanteObj->guardar($participante);

                /*registro de la persona natural*/

                $registroParticipanteObj->guardarRegistroParticipante($savedRegistro->reg_id, $fechaFormatted, $turnoId, $savedParticipante->pa_id);

                /*registro de los operadores*/
                $operadores = $registro['selectedOperadoresIds'];

                $participanteOperadorObj->registrarParticipanteOperadorRelaction($operadores, $savedParticipante->pa_id);
            }
        } else {
            $turno = with(new Turno)->obtenerTurnoPorIdTodos($turnoId);

            $resultado = false;
            $limite = new stdClass();
            $limite->fecha = $fechaProgramacion;
            $limite->turno = $turno;
            array_push($validation, $limite);
        }

        return Response::json(array(
            'resultado' =>  $resultado,
            'validation' => $validation,
            'validationParticipante' => $vParticipante
        ), 200
        )->setCallback(Input::get('callback'));
    }
}