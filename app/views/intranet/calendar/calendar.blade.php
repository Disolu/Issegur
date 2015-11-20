@extends('intranet.layout.site')
@section('stylesSection')
    {{ HTML::style('assets/bootstrap-datepicker/css/datepicker.css') }}
@stop
@section('main')
    <div id="calendar" class="container calendarSection">
        <div class="row">
            <div class="col-md-1 col-md-offset-2">
                <button id="btnAgregarTurno" class="btn btn-success button-agregar-turno"><i class="fa fa-plus fa-3x"></i></button>
                <span class="label-nuevo-turno">Nuevo Turno</span>
            </div>
            <div class="col-md-7">
                <div class="form-group calendar-utilities">
                    <div class="pull-left">
                        <h2 class="mes" data-bind="text: currentdayNameLong() + ' '"></h2>
                        <span class="dia-label" data-bind="text: currentdayNumber() + ' ' + currentMonthName()"></span>
                    </div>
                    <div class="pull-right">
                        <div class="btn-group" role="group">
                            <button class="btn btn-default" id="btnPrevious">
                                <i class="fa fa-angle-left"> </i>
                            </button>
                            <button class="btn btn-default" id="btnToday">
                                 Hoy
                            </button>
                            <button class="btn btn-default" id="btnNext">
                                <i class="fa fa-angle-right"> </i>
                            </button>
                        </div>
                        &nbsp;
                        <button class="btn btn-default default-date-picker"><i class="fa fa-calendar"></i></button>
                        <input type="hidden" id="datepickerValue" class="default-date-picker"/>
                    </div>
                </div>

                <div class="btn-group calendar-day-buttons-array" data-toggle="buttons">
                    <!-- ko foreach: { data: matchingDates, as: 'date' } -->
                    <label class="btn btn-default calendario-dia-block">
                        <input type="radio" name="calendarDayOptions" data-bind="attr: { 'id': date.dayNumber ,'data-id': date.dayWeekNumber, 'data-dayname': date.dayName, 'data-monthname': date.monthName, 'data-dateraw': date.dateRaw }" autocomplete="off">
                        <span class="calendario-dia" data-bind="text: date.shortDayName"></span>
                    </label>
                    <!--/ko-->
                    <label class="btn btn-default calendario-resumen-block actualizarParticipantes">
                        <span class="span-resumen"><i class="fa fa-refresh"></i> Actualizar</span>
                    </label>
                </div>
                <div class="panel panel-default">
                    <!-- ko if: loadingTurnos() -->
                    <div class="panel-body">
                        <div style="text-align: center">
                            <img src="{{asset("assets/img/ajax-loader.gif")}}" alt=""/>
                        </div>
                    </div>
                    <!-- /ko -->
                    <!-- ko foreach: { data: currentTurnosArray, as: 'turno' } -->
                        <!-- ko if: turno.FechaUnica != null && turno.FechaUnica == $parent.currentSelectedDate() -->
                        <div data-bind="attr:{title: turno.Turno}" class="panel-footer panel-hora-turno">
                            Participantes <a href="javascript:void(0);" class="participantesLink"><span style="font-size: 18px" data-bind="text: turno.Participantes"></span></a>
                            <div class="pull-right">
                                <small>Última actualización: <span style="font-weight: bold" data-bind="text: $parent.lastUpdateDate"></span></small>&nbsp;
                            </div>
                        </div>
                        <!--/ko-->
                        <!-- ko if: turno.FechaUnica == null -->
                        <div data-bind="attr:{title: turno.Turno , class: 'panel-footer panel-hora-turno'}">
                            Participantes <a href="javascript:void(0);" class="participantesLink"><span style="font-size: 18px" data-bind="text: turno.Participantes"></span></a>
                            <div class="pull-right">
                                <small>Última actualización: <span style="font-weight: bold" data-bind="text: $parent.lastUpdateDate"></span></small>&nbsp;
                            </div>
                        </div>
                        <!--/ko-->
                    <!--/ko-->
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarTurnoDialog" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title center">Agregar Turno</h4>
                </div>
                <div class="modal-body">
                    <p style="font-size: 20px" class="center" data-bind="text: currentFecha"></p>
                    <table style="width: 100%">
                        <tr>
                            <td>
                                <div class="form-group center">
                                    <label>Hora Inicio <small>(formato 24 horas)</small></label> <br/>
                                    <input type="text" id="iniHoras" class="form-control input-sm soloNumeros" style="width: 70px;display: inline" placeholder="Horas" maxlength="2"/> :
                                    <input type="text" id="iniMinutos" class="form-control input-sm soloNumeros" style="width: 70px;display: inline" placeholder="Minutos" maxlength="2"/>
                                </div>
                            </td>
                            <td>
                                <div class="form-group center" >
                                    <label>Hora Fin <small>(formato 24 horas)</small></label> <br/>
                                    <input type="text" id="finHoras" class="form-control input-sm soloNumeros" style="width: 70px;display: inline" placeholder="Horas" maxlength="2"/> :
                                    <input type="text" id="finMinutos" class="form-control input-sm soloNumeros" style="width: 70px;display: inline" placeholder="Minutos" maxlength="2"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div id="errores" style="display:none;">
                        <label class="label label-danger">Ingrese todos los campos</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirm" data-bind="click: agregarTurno">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scriptsSection')
    {{HTML::script('assets/bootstrap-datepicker/js/bootstrap-datepicker.js')}}
    @include('intranet.calendar.calendarJS')
@stop