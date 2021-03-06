<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Rutas públicas
Route::get('registro','RegistroController@start');
Route::get('registro/juridica','RegistroController@RegistroJuridica');
Route::get('registro/natural','RegistroController@RegistroNatural');
Route::get('registro/confirmacion','RegistroController@Confirmacion');

//Intranet
Route::get('intranet/login','LoginController@Initialize');
Route::get('intranet/cerrarSesion','LoginController@Logout');
Route::get('intranet/calendario','IntranetController@InitializeCalendar');
Route::get('intranet/participantes','IntranetController@InitializeParticipantes');
Route::get('intranet/reportes','IntranetController@InitializeReportes');
Route::get('intranet/reportes/participantesPorOperador','IntranetController@InitializeRepParticipantesPorOperador');
Route::get('intranet/reportes/participantesPorEmpresa','IntranetController@InitializeRepParticipantesPorEmpresa');
Route::get('intranet/reprogramacion','IntranetController@ReprogramarParticipantes');
Route::get('intranet/facturas','IntranetController@IntranetFacturas');
Route::get('intranet/generarFichaExcel/{turno}/{fecha}','IntranetController@GenerarFichaExcel');

Route::get('intranet/facturas/generar','FacturaController@generar');
Route::get('intranet/facturas/ver/{id}','FacturaController@show');
Route::get('intranet/facturas/consultar','FacturaController@consultar');
Route::get('intranet/facturas/reporte','FacturaController@reporte');
Route::get('intranet/facturas/excel','FacturaController@excel');
Route::get('intranet/facturas/configurar','FacturaController@configurar');


//Consulta de Personal
Route::get('consulta','ConsultaController@InitializeConsulta');
Route::get('consulta/volvo','ConsultaController@InitializeConsultaVolvo');


//upload voucher
Route::post("uploadFile", function(){
    $file = Input::file("voucher");
    $originalName = Input::file("voucher")->getClientOriginalName();//nombre original de la foto

    //guardamos la imagen en public/imgs con el nombre original
    $file->move("upload",$file->getClientOriginalName());

    return Response::json(array(
        'resultado' =>  true
    ), 200
    )->setCallback(Input::get('callback'));
});
//send Confirmation email
Route::post("sendConfirmationMail" , function(){
    $emailPostData = Input::all();
    $emailData =  array();
    $user = array();

    //verificamos si es personal natural o juridica

    if($emailPostData["tipoPeronsa"] == "J"){
        $emailData["persona"] = "J";
        $user["email"] = $emailPostData["soliEmail"];
        $emailData["razonSocial"] = $emailPostData["razonSocial"];
        $emailData["grupos"] = [];
        $grupos = $emailPostData["grupos"];
        foreach($grupos as $grupo){
            $grupoEmailData = new StdClass;
            $turno = new Turno();
            $grupoEmailData->fecha = $grupo["fechaProgramacion"];
            $grupoEmailData->turno = $turno->obtenerTurnoPorId($grupo["turnoId"]);
            $grupoEmailData->operadores = join('-', $grupo["selectedOperadoresText"]);
            $grupoEmailData->participantes = count($grupo["participantes"]);
            array_push($emailData["grupos"] , $grupoEmailData);
        }
    }
    else{
        $turno = new Turno();
        $emailData["persona"] = "N";
        $emailData["fecha"] = $emailPostData["fechaProgramacion"];
        $emailData["turno"] = $turno->obtenerTurnoPorId($emailPostData["turnoId"]);
        $user["email"] = $emailPostData["email"];
        $emailData["dni"] = $emailPostData["dni"];
        $emailData["nombres"] = $emailPostData["nombres"];
        $emailData["apellidos"] = $emailPostData["ape_paterno"]." ".$emailPostData["ape_materno"];
    }

    Mail::send('emails.confirmacion.mail', $emailData, function($message) use ($user){
        $message->subject("Confirmación de Registro");
        $message->from('cursos@institutodeseguridad.edu.pe', 'Issegur');
        $message->to($user['email'])->cc('cursos@institutodeseguridad.edu.pe');
    });

    return Response::json(array(
        'resultado' =>  true
    ), 200
    )->setCallback(Input::get('callback'));
});
//upload participante photo
Route::post("uploadParticipanteFoto", function(){
    $file = Input::all();
    if ($file != null) {
        //guardamos la imagen en public/imgs con el nombre original
        $file['photoImg']->move("fotos",$file['photoImg']->getClientOriginalName());
    }

    return Response::json(array(
        'resultado' =>  true
    ), 200
    )->setCallback(Input::get('callback'));
});
//upload ficha asistencia
Route::post("uploadFichaAsistencia", function(){
    $file = Input::all();
    if ($file != null) {
        //guardamos la imagen en public/imgs con el nombre original
        $file["fichaAsistenciaData"]->move("fichas",$file["fichaAsistenciaData"]->getClientOriginalName());
    }

    return Response::json(array(
        'resultado' =>  true
    ), 200
    )->setCallback(Input::get('callback'));
});
//upload examen participante
Route::post("uploadExamenParticipante", function(){
    $file = Input::all();
    if ($file["examenData"] != null) {
        //guardamos la imagen en public/imgs con el nombre original
        $file["examenData"]->move("examenes",$file["examenData"]->getClientOriginalName());
    }

    return Response::json(array(
        'resultado' =>  true
    ), 200
    )->setCallback(Input::get('callback'));
});

//upload examen participante
Route::post("uploadSctrParticipante", function(){
    $file = Input::all();
    if ($file["sctrData"] != null) {
        //guardamos la imagen en public/imgs con el nombre original
        $file["sctrData"]->move("sctr",$file["sctrData"]->getClientOriginalName());
    }

    return Response::json(array(
        'resultado' =>  true
    ), 200
    )->setCallback(Input::get('callback'));
});

//api
//publico, sin indentificacion
Route::group(array('prefix' => 'api/v1'), function(){
    //Registro Público
    Route::get('consultarTurnosPorDia', 'RegistroController@MostrarHorariosPorDia');
    Route::get('consultarTurnosPorDiaSinReestriccion','RegistroController@MostrarHorariosPorDiaSinReestriccion');
    Route::get('getOperadores', 'RegistroController@ObtenerOperadores');
    Route::get('consultarDNI', 'RegistroController@ConsultarDNI');
    Route::get('consultarRUC', 'RegistroController@ConsultarRUC');
    Route::get('consultarNroOperacion','RegistroController@ConsultarNroOperacion');
    Route::post('guardarRegistroJuridica', 'RegistroController@GuardarRegistroJuridica');
    Route::post('guardarRegistroNatural', 'RegistroController@GuardarRegistroNatural');

    //ConsultaPersonal
    Route::get('buscarPersonal', 'ConsultaController@BuscarPersonal');
    Route::get('buscarPersonal/{operadorId}', 'ConsultaController@BuscarPersonalPorOperador');
    Route::get('getParticipanteInfoByDNI', 'ConsultaController@GetParticipanteInfoByDNI');


    //Intranet Login
    Route::get('login', 'LoginController@ValidateUser');
    Route::get('ransaLogin','LoginController@ValidateRansaUser');
    Route::get('ransaLogout','LoginController@LogoutRansaUser');

    //


});

//privado, requiere autentificacion
Route::group(array('before' => 'auth','prefix' => 'api/v1'),function() {
    //Intranet
    //Calendario
    Route::get('obtenerParticipantesPorFechaYDia','IntranetController@ObtenerParticipantesPorFechaYDia');
    Route::get('agregarTurnoManual','IntranetController@AgregarTurnoManual');
    //Participantes
    Route::get('obtenerParticipantesPorFechaYTurno', 'IntranetController@ObtenerParticipantesPorFechaYTurno');
    Route::get('obtenerDetalleOperacionPorRegistro', 'IntranetController@ObtenerDetalleOperacionPorRegistro');
    Route::get('guardarDetalleOperacionPorRegistro', 'IntranetController@GuardarDetalleOperacionPorRegistro');
    Route::post('actualizarParticipantes', 'IntranetController@ActualizarParticipantes');
    Route::get('registarParticipanteManual','IntranetController@RegistarParticipanteManual');
    //Reportes
    Route::get('reporteParticipantesByOperador','ReporteController@ReporteParticipantesByOperador');
    Route::get('reporteParticipantesByOperadorDetalle','ReporteController@ReporteParticipantesByOperadorDetalle');
    Route::get('getReportParticipantesPorOperadorPagerDetails', 'ReporteController@GetReportParticipantesPorOperadorPagerDetails');

    Route::get('obtenerEmpresasNombresAutocomplete','ReporteController@ObtenerEmpresasNombresAutocomplete');
    Route::get('obtenerEmpresaPorRazonSocial','ReporteController@ObtenerEmpresaPorRazonSocial');
    Route::get('obtenerEmpresaPorRuc','ReporteController@ObtenerEmpresaPorRuc');
    //
    //Reprogramacion
    Route::get('obtenerParticipantesAReprogramar','IntranetController@ObtenerParticipantesAReprogramar');
    Route::get('reprogramarParticipante','IntranetController@ReprogramarParticipante');

    Route::get('facturas/loadbyruc','FacturaController@loadbyruc');
    Route::get('facturas/createedit','FacturaController@createedit');
    Route::post('facturas/new','FacturaController@store');
    Route::get('facturas/search','FacturaController@search');
    Route::get('facturas/report','FacturaController@report');
    Route::get('facturas/config','FacturaController@updateConfig');
    Route::get('facturas/cancelar/{id}','FacturaController@cancelar');

});
