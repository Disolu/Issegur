<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class Participante extends Eloquent
{
    protected $table = 'Participante';
    protected $primaryKey = 'pa_id';

    public function consultarDNI($dni){
        $participante = DB::table('Participante')->where('pa_dni',$dni)->get();

        return $participante;
    }

    public function obtenerParticipantesIdsPorEmpresa($empresaId){    
        $participantesIds = DB::table('RegistroParticipante')->where('emp_id', $empresaId)
                    ->select('pa_id')->distinct()->get();

        return $participantesIds;
    }

    public function obtenerParticipantesPorIds($participantesIds){
        $Ids = array();
        foreach ($participantesIds as $parId) {
            array_push($Ids, $parId->pa_id);
        }
        $participantes = DB::table('Participante')
                            ->whereIn('pa_id', $Ids)
                            ->whereNotNull('pa_asistencia')
                            ->orderBy('pa_apellido_paterno')
                            ->get();

        return $participantes;
    }

    public function guardar($participante){
        $existingParticipante = Participante::where('pa_dni','=', $participante["dni"])->first();

        if(!$existingParticipante){
            $existingParticipante =  new Participante();
            $existingParticipante->pa_dni = $participante["dni"];
            $existingParticipante->pa_nombres = $participante["nombres"];
            $existingParticipante->pa_apellido_paterno = $participante["ape_paterno"];
            $existingParticipante->pa_apellido_materno = $participante["ape_materno"];
            $existingParticipante->pa_email = isset($participante["email"])? $participante["email"] : null;
            $existingParticipante->detop_numero = $participante["nroOperacion"];
            $existingParticipante->detop_fecha = $participante["fechaOperacion"] != "" ? $participante["fechaOperacion"]: null;
            $existingParticipante->detop_monto = $participante["montoPago"];
            $existingParticipante->save();
        }
        return $existingParticipante;
    }

    public function obtenerPorFechaAgrupadosEnTurnos($fecha ,$dia)
    {
        $participantesView = DB::table('Turno')
            ->where('turno_dia', $dia)
            ->select(DB::raw("turno_id 'TurnoId', turno_fecha_unica 'FechaUnica', CONCAT(DATE_FORMAT(turno_hora_inicio, '%l:%i %p') ,' - ' , DATE_FORMAT(turno_hora_fin, '%l:%i %p')) 'Turno',
                            (select count(distinct pa_dni) from ParticipantesMasterView pmv where pmv.FechaProgramacion = '" . $fecha . "' and pmv.TurnoId = turno_Id) 'Participantes'"))
            ->orderBy('turno_hora_inicio', 'asc')
            ->get();


        return $participantesView;
    }

    public function obtenerCantidadPorFechaYTurno($fecha, $turnoId){
        $participantesView = DB::table('ParticipantesMasterView')
            ->where('FechaProgramacion', $fecha)
            ->where('TurnoId', $turnoId)
            ->groupBy('pa_id')
            ->count();

        return $participantesView;
    }

    public function obtenerParticipantesPorFechayTurno($fecha ,$turno){
        $participantesView = DB::table('ParticipantesMasterView')
            /*->leftjoin('ParticipanteOperadorRelacion', function($join){
                $join->on('ParticipantesMasterView.pa_id','=','ParticipanteOperadorRelacion.pa_id')->orOn('ParticipantesMasterView.RegistroId','=','ParticipanteOperadorRelacion.reg_id');
            })
            ->leftjoin('Operador','ParticipanteOperadorRelacion.op_id','=','Operador.op_id')*/
            ->where('FechaProgramacion', $fecha)
            ->where('Turno', $turno)
            /*->select('pa_id','pa_dni','pa_nombres','pa_apellido_paterno','pa_apellido_materno','RUC','RazonSocial','OperadorId','Operador','RegistroId','NroOperacionFecha','NroOperacionMonto','NroOperacion' ,'pa_foto','pa_ficha_asistencia','pa_examen','pa_asistencia','pa_nota','pa_aprobado')*/
            //->select(DB::raw("ParticipantesMasterView.*"))
            //, Operador.op_id 'OperadorId' , Operador.op_nombre 'Operador'
            ->groupBy('pa_id')
            ->orderBy('created_at','asc')
            ->get();

            $participantesView = $this->agregarOperadoresParticipantesView($participantesView);

        return  $participantesView;
    }

    public function obtenerParticipantesParaFichaExcel($fecha ,$turno){
        $participantesView = DB::table('ParticipantesMasterView')           
            ->where('FechaProgramacion', $fecha)
            ->where('Turno', $turno) 
            ->where('pa_asistencia', 1)
            ->groupBy('pa_id')
            ->orderBy('created_at','asc')
            ->get();

            $participantesView = $this->agregarOperadoresParticipantesView($participantesView);

        return  $participantesView;
    }

    public function obtenerParticipantesPorFechayTurnoYSearchText($fecha ,$turno, $searchText){
        $participantesView = DB::table('ParticipantesMasterView')
            /*->leftjoin('ParticipanteOperadorRelacion','ParticipantesMasterView.RegistroId','=','ParticipanteOperadorRelacion.reg_id')
            ->leftjoin('Operador','ParticipanteOperadorRelacion.op_id','=','Operador.op_id')*/
            ->where('ParticipantesMasterView.FechaProgramacion', $fecha)
            ->where('ParticipantesMasterView.Turno', $turno)
            ->Where(function ($query) use($searchText){
                $query->where('ParticipantesMasterView.pa_dni','LIKE', '%'.$searchText.'%')
                    ->OrWhere('ParticipantesMasterView.pa_nombres','LIKE', '%'.$searchText.'%')
                    ->OrWhere('ParticipantesMasterView.pa_apellido_paterno','LIKE', '%'.$searchText.'%')
                    ->OrWhere('ParticipantesMasterView.pa_apellido_materno','LIKE', '%'.$searchText.'%')
                    ->OrWhere('ParticipantesMasterView.RUC','LIKE', '%'.$searchText.'%');
            })
            /*->select('pa_id','pa_dni','pa_nombres','pa_apellido_paterno','pa_apellido_materno','RUC','RazonSocial','OperadorId','Operador','RegistroId','NroOperacionFecha','NroOperacionMonto','NroOperacion' ,'pa_foto','pa_ficha_asistencia','pa_examen','pa_asistencia','pa_nota','pa_aprobado')*/
            //->select(DB::raw("ParticipantesMasterView.*"))
            ->groupBy('ParticipantesMasterView.pa_id')
            ->orderBy('ParticipantesMasterView.created_at','asc')
            ->get();

            $participantesView = $this->agregarOperadoresParticipantesView($participantesView);

        return $participantesView;
    }

    public function agregarOperadoresParticipantesView($participantesView){

            $operadorObj = new Operador();

            foreach ($participantesView as $par) {
                $existingParticipanteOperador = ParticipanteOperadorRelacion::where('pa_id','=', $par->pa_id)
                                                                        ->where('reg_id','=', $par->RegistroId)
                                                                        ->first();

                if ($existingParticipanteOperador) {
                    $par->OperadorId = $existingParticipanteOperador->op_id;
                    $par->Operador = $operadorObj->obtenerNombrePorId($par->OperadorId);
                }
                else{
                    $existingParticipanteOperador = ParticipanteOperadorRelacion::where('pa_id','=', $par->pa_id)
                                                                        ->where('reg_id','=', 0)
                                                                        ->orderBy('created_at','desc')
                                                                        ->first();

                   
                    $par->OperadorId = $existingParticipanteOperador->op_id;
                    $par->Operador = $operadorObj->obtenerNombrePorId($par->OperadorId);  
                                                                                  
                                                                                                                   
                }                
               
            }

            return $participantesView;
    }

    public function obtenerPorDniNombreOApellido($searchText){
        $matchingParticipantes = DB::table('Participante')
            ->where('pa_asistencia', 1)
            ->where('pa_nota', '>', 11)
            ->Where(function ($query) use($searchText){
                $query->where('pa_dni','LIKE', '%'.$searchText.'%')
                    ->OrWhere('pa_nombres','LIKE', '%'.$searchText.'%')
                    ->OrWhere('pa_apellido_paterno','LIKE', '%'.$searchText.'%')
                    ->OrWhere('pa_apellido_materno','LIKE', '%'.$searchText.'%');
            })
            ->select('pa_dni','pa_nombres','pa_apellido_paterno','pa_apellido_materno')
            ->get();

        return $matchingParticipantes;
    }

    public function obtenerInfoPorDNI($dni){
        $matchingParticipanteInfo = DB::table('ParticipantesMasterView')
            ->where('pa_dni',$dni)
            ->get();

        $matchingParticipanteInfo = $this->agregarOperadoresParticipantesView($matchingParticipanteInfo);

        return $matchingParticipanteInfo;
    }
    

    public function puedeParticipanteRegistrarse($fecha, $turnoId, $nroParticipantes, $modalidad){
        $puedeRegistrarse = true;
        $matchingParticipanteCount = DB::table('ParticipantesMasterView')
            ->where('FechaProgramacion', $fecha)
            ->where('TurnoId', $turnoId)
            ->where('Modalidad', $modalidad)
            ->Where(function ($query) {
                $query->where('pa_asistencia',0)
                    ->Orwhere('pa_asistencia', null);
            })
            ->count();

        if($modalidad == "W"){
            if ($matchingParticipanteCount + $nroParticipantes > 35) {
                $puedeRegistrarse = false;
            }
        }
        else{
            if ($matchingParticipanteCount + $nroParticipantes > 50){
                $puedeRegistrarse = false;
            }
        }

        return $puedeRegistrarse;
    }

    public function actualizarParticipante($participante){

        $existingParticipante = Participante::where('pa_id','=', $participante["id"])->first();

        if($participante["ruc"]){
            $existingEmpresa = Empresa::where('emp_ruc','=', $participante["ruc"])->first();
            if($existingEmpresa){
                $existingEmpresa->emp_razon_social = $participante["razonSocial"];
            }
            else{
                $existingEmpresa =  new Empresa();
                $existingEmpresa->emp_ruc = $participante["ruc"];
                $existingEmpresa->emp_razon_social = $participante["razonSocial"];
            }
            $existingEmpresa->save();

            $registroId = $participante["registroId"];
            $registroParticipante = RegistroParticipante::where('pa_id','=', $participante["id"])
                                                ->where('reg_id','=',$registroId)
                                                ->orderBy('reg_id', 'desc')->first();
            $registroParticipante->emp_id = $existingEmpresa->emp_id;
            $registroParticipante->save();
        }

        if($participante["operador"]){
            $paId = $participante["id"];
            $registroId = $participante["registroId"];
            $existingParticipanteOperador = ParticipanteOperadorRelacion::where('pa_id','=', $paId)
                                                                        ->where('reg_id','=', $registroId)
                                                                        ->first();
            //si no existe ninguno, quiere decir q tiene reg_id = 0
            if($existingParticipanteOperador){
                $existingParticipanteOperador->op_id = $participante["operador"];
                $existingParticipanteOperador->save();
            } 
            else{
                $existingParticipanteOperador = ParticipanteOperadorRelacion::where('pa_id','=', $paId)
                                                                        ->where('reg_id','=', 0)
                                                                        ->first(); 

                $existingParticipanteOperador->op_id = $participante["operador"];
                $existingParticipanteOperador->save();
            }                                                                       
            
        }

        $existingParticipante->pa_dni = $participante["dni"];
        $existingParticipante->pa_nombres = $participante["nombres"];
        $existingParticipante->pa_apellido_paterno = $participante["apePaterno"];
        $existingParticipante->pa_apellido_materno = $participante["apeMaterno"];
        $existingParticipante->pa_asistencia = $participante["asistencia"] == ""? null : $participante["asistencia"];
        
        if($participante["nota"]){
            $existingParticipante->pa_nota = $participante["nota"]?(float) $participante["nota"] : null;
        }
        $existingParticipante->pa_aprobado = $existingParticipante->pa_nota? ($existingParticipante->pa_nota >= 11? 1 : 0 ): null;
        $existingParticipante->detop_numero = $participante["nroOperacion"];
        if($participante["fechaOperacion"]){
            $existingParticipante->detop_fecha = DateTime::createFromFormat('d/m/Y', $participante["fechaOperacion"])->format('Y-m-d');
        }
        if($participante["montoOperacion"]){
            $existingParticipante->detop_monto = $participante["montoOperacion"];
        }
        if($participante["foto"]){
            $existingParticipante->pa_foto = $participante["foto"];
        }
        if($participante["ficha"]){
            $existingParticipante->pa_ficha_asistencia = $participante["ficha"];
        }
        if($participante["examen"]){
            if ((strlen($existingParticipante->pa_examen) == 0) || ((strlen($existingParticipante->pa_examen) > 0) && ($participante["examen"] != null))) {
                $existingParticipante->pa_examen = $participante["examen"];
            }            
        }
        $existingParticipante->save();

    }

    function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public function reporteParticipantesPorOperador($operadorId, $fechaDesde, $fechaHasta, $skip, $take, $sortField, $sortDirection)
    {
        //we set the fields
        if ($this->startsWith($sortField,'pa')) {
            $sortField = 'Participante.'.$sortField;
        }else{
            $sortField = 'Empresa.'.$sortField;
        }

        $matchingParticipantes = DB::table('Participante')
            ->leftJoin('ParticipanteOperadorRelacion','Participante.pa_id','=','ParticipanteOperadorRelacion.pa_id')
            ->leftjoin('Operador','ParticipanteOperadorRelacion.op_id','=','Operador.op_id')
            ->join('RegistroParticipante','Participante.pa_id','=','RegistroParticipante.pa_id')
            ->join('Empresa','RegistroParticipante.emp_id','=','Empresa.emp_id')
            ->where('Operador.op_id', $operadorId)
            ->whereBetween('Participante.created_at', array($fechaDesde, $fechaHasta))
            ->select(DB::raw("distinct Participante.pa_dni , Participante.pa_nombres , Participante.pa_apellido_paterno ,Participante.pa_apellido_materno , Empresa.emp_razon_social 'RazonSocial'"))
            ->skip($skip)->take($take)
            ->orderBy($sortField, $sortDirection)
            ->get();

            //log::info($sortField.' '.$sortDirection);

        return $matchingParticipantes;
    }

    public function reporteParticipantesPorOperadorCount($operadorId, $fechaDesde, $fechaHasta)
    {
        $matchingParticipantes = DB::table('Participante')
            ->leftJoin('ParticipanteOperadorRelacion','Participante.pa_id','=','ParticipanteOperadorRelacion.pa_id')
            ->leftjoin('Operador','ParticipanteOperadorRelacion.op_id','=','Operador.op_id')
            ->join('RegistroParticipante','Participante.pa_id','=','RegistroParticipante.pa_id')
            ->join('Empresa','RegistroParticipante.emp_id','=','Empresa.emp_id')
            ->where('Operador.op_id', $operadorId)
            ->whereBetween('Participante.created_at', array($fechaDesde, $fechaHasta))
            ->select(DB::raw('distinct Participante.pa_dni , Participante.pa_nombres , Participante.pa_apellido_paterno ,Participante.pa_apellido_materno'))
            ->get();


        return count($matchingParticipantes);
    }

    public function obtenerParticipantesAReprogramar()
    {
        $matchingParticipantes = DB::table('Participante')
            ->where('pa_asistencia',0)
            ->Orwhere('pa_nota','<', 11)
            ->select('pa_dni','pa_nombres','pa_apellido_paterno','pa_apellido_materno','pa_asistencia')
            ->get();

        return $matchingParticipantes;
    }

    public function obtenerParticipantesAReprogramarBySearchtext($searchText)
    {
        $matchingParticipantes = DB::table('Participante')
            ->Where(function ($query) {
                $query->where('pa_asistencia',0)
                    ->Orwhere('pa_nota','<', 11);
            })
            ->Where(function ($query) use($searchText){
                $query->where('pa_dni','LIKE', '%'.$searchText.'%')
                    ->OrWhere('pa_nombres','LIKE', '%'.$searchText.'%')
                    ->OrWhere('pa_apellido_paterno','LIKE', '%'.$searchText.'%')
                    ->OrWhere('pa_apellido_materno','LIKE', '%'.$searchText.'%');
            })
            ->select('pa_dni','pa_nombres','pa_apellido_paterno','pa_apellido_materno','pa_asistencia')
            ->get();

        return $matchingParticipantes;
    }

    public function puedeRegistrarseNuevoTurno($dni){
        $participante = Participante::where('pa_dni', '=', $dni)
                                      ->first();
        //obtenemos primero el id del participante
        if ($participante) {
            $participanteId = $participante->pa_id;

            $participanteRaw = DB::table('RegistroParticipante')
                    ->select('fecha_programacion')
                    ->where('pa_id','=',$participanteId)
                    ->orderBy('reg_id','desc')
                    ->first();

            if ($participanteRaw) {
                if (strtotime($participanteRaw->fecha_programacion) < strtotime(date("Y-m-d"))) {
                    return true;  
                }  
                else{
                    return false;
                }
            }
            else{
                return true;
            }

        }
        else{
            return true;
        }

    }

}



