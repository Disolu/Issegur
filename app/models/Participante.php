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

    public function guardar($participante){
        $existingParticipante = Participante::where('pa_dni','=', $participante["dni"])->first();

        if(!$existingParticipante){
            $existingParticipante =  new Participante();
            $existingParticipante->pa_dni = $participante["dni"];
            $existingParticipante->pa_nombres = $participante["nombres"];
            $existingParticipante->pa_apellido_paterno = $participante["ape_paterno"];
            $existingParticipante->pa_apellido_materno = $participante["ape_materno"];
            $existingParticipante->pa_email = in_array("email", $participante)? $participante["email"] : null;
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
                            (select count(pa_dni) from ParticipantesMasterView pmv where pmv.FechaProgramacion = '" . $fecha . "' and pmv.TurnoId = turno_Id) 'Participantes'"))
            ->orderBy('turno_hora_inicio', 'asc')
            ->get();

        return $participantesView;
    }

    public function obtenerCantidadPorFechaYTurno($fecha, $turnoId){
        $participantesView = DB::table('ParticipantesMasterView')
            ->where('FechaProgramacion', $fecha)
            ->where('TurnoId', $turnoId)
            ->count();

        return $participantesView;
    }

    public function obtenerParticipantesPorFechayTurno($fecha ,$turno){
        $participantesView = DB::table('ParticipantesMasterView')
            ->where('FechaProgramacion', $fecha)
            ->where('Turno', $turno)
            ->select('pa_id','pa_dni','pa_nombres','pa_apellido_paterno','pa_apellido_materno','RUC','RazonSocial','OperadorId','Operador','RegistroId','NroOperacionFecha','NroOperacionMonto','NroOperacion' ,'pa_foto','pa_ficha_asistencia','pa_examen','pa_asistencia','pa_nota','pa_aprobado')
            ->orderBy('created_at','asc')
            ->get();

        return $participantesView;
    }

    public function obtenerParticipantesPorFechayTurnoYSearchText($fecha ,$turno, $searchText){
        $participantesView = DB::table('ParticipantesMasterView')
            ->where('FechaProgramacion', $fecha)
            ->where('Turno', $turno)
            ->Where(function ($query) use($searchText){
                $query->where('pa_dni','LIKE', '%'.$searchText.'%')
                    ->OrWhere('pa_nombres','LIKE', '%'.$searchText.'%')
                    ->OrWhere('pa_apellido_paterno','LIKE', '%'.$searchText.'%')
                    ->OrWhere('pa_apellido_materno','LIKE', '%'.$searchText.'%');
            })
            ->select('pa_id','pa_dni','pa_nombres','pa_apellido_paterno','pa_apellido_materno','RUC','RazonSocial','OperadorId','Operador','RegistroId','NroOperacionFecha','NroOperacionMonto','NroOperacion' ,'pa_foto','pa_ficha_asistencia','pa_examen','pa_asistencia','pa_nota','pa_aprobado')
            ->orderBy('created_at','asc')
            ->get();

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

    public function obtenerNumeroDeParticipantesPorRegistroId($registroId){
        $matchingParticipantes = DB::table('ParticipantesMasterView')
            ->where('RegistroId', $registroId)
            ->count();

        return $matchingParticipantes;
    }

    public function obtenerNumeroDeParticipantesPorDetalleOperacionId($detOperacionId){
        $matchingParticipantes = DB::table('ParticipantesMasterView')
            ->where('DetOperacionId', $detOperacionId)
            ->count();

        return $matchingParticipantes;
    }

    public function obtenerInfoPorDNI($dni){
        $matchingParticipanteInfo = DB::table('ParticipantesMasterView')
            ->where('pa_dni',$dni)
            ->get();

        return $matchingParticipanteInfo;
    }

    public function puedeParticipanteRegistrarse($fecha, $turnoId, $nroParticipantes, $modalidad){
        $puedeRegistrarse = true;
        $matchingParticipanteCount = DB::table('ParticipantesMasterView')
            ->where('FechaProgramacion', $fecha)
            ->where('TurnoId', $turnoId)
            ->where('Modalidad', $modalidad)
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

            $registroParticipante = RegistroParticipante::where('pa_id','=', $participante["id"])->orderBy('reg_id', 'desc')->first();
            $registroParticipante->emp_id = $existingEmpresa->emp_id;
            $registroParticipante->save();
        }

        if($participante["operador"]){
            $paId = $participante["id"];
            $existingParticipanteOperador = ParticipanteOperadorRelacion::where('pa_id','=', $paId)->first();
            $existingParticipanteOperador->op_id = $participante["operador"];
            $existingParticipanteOperador->save();
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
            $existingParticipante->pa_examen = $participante["examen"];
        }
        $existingParticipante->save();

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

}