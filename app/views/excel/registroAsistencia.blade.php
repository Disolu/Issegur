<html>
<!-- Headings -->
<tr>
    <td colspan="9" style="text-align: center">REGISTRO DE ASISTENCIA INDUCCION DE SST - ISSEGUR</td>
</tr>
<tr>
    <td colspan="9" style="text-align: center;margin-top: -20px">SERVICIOS COMUNES DE GESTION DEL SISTEMA DE SEGURIDAD Y SALUD EN EL TRABAJO</td>
</tr>
<tr>
    <td colspan="9" style="text-align: center">RANSA (R) - TRAMARSA (T)</td>
</tr>
<tr>
    <td   colspan="9" style="text-align: center"><h3>FECHA: {{ $fecha }}</h3></td>
</tr>
<tr>

    <td><b>N&#176;</b></td>
    <td><b>DNI</b></td>
    <td><b>NOMBRES</b></td>
    <td><b>APELLIDOS</b></td>
    <td><b>EMPRESA</b></td>
    <td><b>FIRMA</b></td>
    <td><b>ALMACEN</b></td>
    <td><b>CODIGO</b></td>
    <td><b>NOTA</b></td>
    <td><b>OPERACION</b></td>
    <td><b>FECHA</b></td>
    <td><b>MONTO</b></td>
    <td><b>RUC</b></td>
</tr>

@for ($i = 0; $i < count($participantes); $i++)
    <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $participantes[$i]->pa_dni }}</td>
        <td>{{ HTML::entities(strtoupper($participantes[$i]->pa_nombres)) }}</td>
        <td>{{ HTML::entities(strtoupper($participantes[$i]->pa_apellido_paterno)).' '. HTML::entities(strtoupper($participantes[$i]->pa_apellido_materno)) }}</td>
        <td>{{ $participantes[$i]->RazonSocial? HTML::entities(strtoupper($participantes[$i]->RazonSocial)): HTML::entities(strtoupper($participantes[$i]->pa_nombres)).' '. HTML::entities(strtoupper($participantes[$i]->pa_apellido_paterno)).' '. HTML::entities(strtoupper($participantes[$i]->pa_apellido_materno))  }}</td>
        <td>@if($participantes[$i]->pa_asistencia === null)
                PENDIENTE
            @elseif ($participantes[$i]->pa_asistencia === 0)
                NO ASISTI&#211;
            @else

            @endif
        </td>
        <td>{{ strtoupper($participantes[$i]->Operador) }}</td>
        <td></td>
        <td>{{ $participantes[$i]->pa_nota }}</td>
        <td>{{ $participantes[$i]->NroOperacion }}</td>
        <td>{{ $participantes[$i]->NroOperacionFecha? $participantes[$i]->NroOperacionFecha : '' }}</td>
        <td>S/. {{ $participantes[$i]->NroOperacionMonto }}</td>
        <td>{{ $participantes[$i]->RUC?  $participantes[$i]->RUC : '' }}</td>
    </tr>

@endfor



</html>