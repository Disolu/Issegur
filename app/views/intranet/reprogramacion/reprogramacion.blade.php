@extends('intranet.layout.site')
@section('stylesSection')
    {{HTML::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')}}
    <style>
        div.ui-datepicker{
            font-size:12px;
        }
    </style>
@stop
@section('main')
    <div id="reprogramacion" class="container">
            <div class="row">
                <div class="col-md-12">
                    <div id="reprogramacionHeader" class="pull-left">
                        <h1>Reprogramación</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="inner-addon left-addon center">
                        <label for="filtroPersonal" class="icon-label"><i class="fa fa-search fa-1x"></i></label>
                        <input id="filtroPersonal" class="form-control input-lg" type="text" placeholder="Ingrese un DNI, nombre o apellido">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div id="participantesBody" class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <th class="pa-table-header center" style="width: 70px;">N°</th>
                            <th class="pa-table-header center" style="width: 120px">DNI</th>
                            <th class="pa-table-header">Participante</th>
                            <th class="pa-table-header center" style="width: 120px">Estado</th>
                            <th class="pa-table-header center"></th>
                            </thead>
                            <tbody>
                            <!-- ko if: !loadingParticipantes() && participantes().length < 1 -->
                            <tr>
                                <td colspan="5">
                                    No existen participantes a reprogramar.
                                </td>
                            </tr>
                            <!-- /ko -->
                            <!-- ko if: loadingParticipantes() -->
                            <tr>
                                <td colspan="5" style="text-align: center">
                                    <img src="{{asset("assets/img/ajax-loader.gif")}}" alt=""/>
                                </td>
                            </tr>
                            <!-- /ko -->
                            <!--ko foreach: { data: participantes, as: 'paInfo' } -->
                            <tr class="participante" data-bind="attr:{'data-id': paInfo.pa_id}">
                                <td data-bind="text: $index() + 1" class="center"></td>
                                <td data-bind="text: paInfo.pa_dni" class="center"></td>
                                <td data-bind="text: paInfo.pa_nombres + ' ' + paInfo.pa_apellido_paterno + ' ' + paInfo.pa_apellido_materno"></td>
                                <td  class="center">
                                    <!-- ko if: paInfo.pa_asistencia == 0-->
                                    <label class="label label-warning">Ausente</label>
                                    <!--/ko-->
                                    <!-- ko ifnot: paInfo.pa_asistencia == 0-->
                                    <label class="label label-danger">Desaprobado</label>
                                    <!--/ko-->
                                </td>
                                <td class="center">
                                    <button class="btn btn-primary btn-sm" data-bind="click: onReprogramarClick"> Reprogramar</button>
                                </td>
                            </tr>
                            <!--/ko-->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>

    <div class="modal fade" id="reprogramacionDialog" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title center">Reprogramar Participante</h4>
                </div>
                <div class="modal-body">
                    <div class="row center">
                        <span style="font-size: large;color:#808080" data-bind="text: dniParticipante"></span>&nbsp;
                        <span style="font-size: large;" data-bind="text: nombreParticipante"></span>
                    </div>
                    <br/><br/>
                    <div class="form-inline center"  style="padding-bottom: 20px;">
                        <div class="form-group">
                            <label for="nuevaFechaProgramacion"> Mover a </label>&nbsp;
                            <input  type="text" id="nuevaFechaProgramacion" data-bind="value: nuevaFechaProgramacion"  class="form-control" style="width:150px;"/>
                            <br/>
                            <label class="label label-danger" data-bind="visible: !IsFechaProgramacionSupplied() && !supressValidationMessages()">Ingrese una fecha</label>
                        </div>
                        &nbsp;&nbsp;
                        <div class="form-group">
                            <label for="horarios"> Turno </label>&nbsp;
                            <select id="horarios" class="form-control" data-bind="value: nuevoTurno" disabled>
                                <option value="0">Elija su horario</option>
                            </select>
                            <br/>
                            <label class="label label-danger" data-bind="visible: !isNuevoTurnoSupplied() && !supressValidationMessages()">Seleccione un turno</label>
                        </div>
                    </div>

                    <fieldset id="registroValidations" style="display:none;">
                        <div class="pull-left">
                            <div class="validation-error" >Lo sentimos, no se pudo completar su registro, no hay cupos disponibles para : <br/>
                                <!-- ko foreach: { data: validationRegistros, as: 'val' } -->
                                <!--ko text: val.fecha--><!--/ko-->&nbsp; <!--ko text: val.turno--><!--/ko-->
                                <!--/ko-->
                                <br/>
                                <br/>
                                Por favor, elija una nueva fecha o turno e inténtelo nuevamente.
                            </div>
                        </div>
                    </fieldset>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bind="click: onGuardarReprogramacion" id="confirm">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

@stop
@section('scriptsSection')
    @include('intranet.reprogramacion.reprogramacionJS')
@stop