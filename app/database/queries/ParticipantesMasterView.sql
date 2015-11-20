create view ParticipantesMasterView
as
select r.reg_id 'RegistroId' , r.reg_modalidad 'Modalidad',DATE_FORMAT(pa.detop_fecha, '%d/%m/%Y') 'NroOperacionFecha', pa.detop_monto 'NroOperacionMonto', pa.detop_numero 'NroOperacion',e.emp_ruc 'RUC',
e.emp_razon_social 'RazonSocial', rpa.fecha_programacion 'FechaProgramacion',
t.turno_id 'TurnoId', t.turno_dia 'Dia',t.turno_hora_inicio 'HoraInicio', CONCAT(DATE_FORMAT(t.turno_hora_inicio, '%l:%i %p') ,' - ' , DATE_FORMAT(t.turno_hora_fin, '%l:%i %p')) 'Turno',
(case when e.emp_razon_social is null then 'N' else 'J' end) 'TipoRegistro', rs.regsoli_Nombre 'SolicitanteNombre', rs.regsoli_Apellidos 'SolicitanteApellidos', rs.regsoli_Telefono 'SolicitanteTelefono', rs.regsoli_Email 'SolicitanteEmail',
o.op_id 'OperadorId' ,o.op_nombre 'Operador',
pa.* from Participante pa
left join RegistroParticipante rpa on pa.pa_id = rpa.pa_id
left join ParticipanteOperadorRelacion paop on pa.pa_id = paop.pa_id
left join Empresa e on rpa.emp_id  = e.emp_id
join Operador o on paop.op_id = o.op_id
join Turno t on rpa.turno_id = t.turno_id
join Registro r on rpa.reg_id = r.reg_id
left join RegistroSolicitante rs on e.emp_id = rs.emp_id
group by pa.pa_id
order by r.reg_id, pa.pa_id




