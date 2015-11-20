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

    <script>
        var ReprogramacionDialog = new (function () {
            var me = this;

            me.dniParticipante = ko.observable(null);
            me.nombreParticipante = ko.observable(null);
            me.nuevaFechaProgramacion = ko.observable(null);
            me.IsFechaProgramacionSupplied = ko.computed(function(){ return $.trim(me.nuevaFechaProgramacion()).length > 0}, me)
            me.nuevoTurno = ko.observable(null);
            me.isNuevoTurnoSupplied = ko.computed(function(){ return (me.nuevoTurno() > 0)}, me)
            me.hasBeenInitialized = false;
            me.supressValidationMessages = ko.observable(true);
            me.validationRegistros = ko.observableArray([]);

            me.initialize = function () {
                if (!me.hasBeenInitialized) {
                    ko.applyBindings(ReprogramacionDialog, $("#reprogramacionDialog")[0]);

                    $("#nuevaFechaProgramacion").datepicker({
                         dateFormat: 'dd/mm/yy',
                         minDate: new Date()
                    });
                    me.hasBeenInitialized = true;
                }
                $(document.body).on("change","#nuevaFechaProgramacion", me.onFechaProgramacionChange);

                me.datePickerSettings();
                $("#reprogramacionDialog").modal("show");
            };

            me.setParticipante = function (dni, nombre) {
                me.dniParticipante(dni);
                me.nombreParticipante(nombre);
            };

            me.onGuardarReprogramacion = function (data, event) {
                me.validate({
                    valid: function () {
                        $.ajax({
                            type: "GET",
                            url: path + "/api/v1/reprogramarParticipante",
                            data: {dni: me.dniParticipante(),fechaProgramacion: me.nuevaFechaProgramacion(), turnoId: me.nuevoTurno()},
                            dataType: "json",
                            contentType: "application/json; charset=utf-8",
                            success: function (data) {
                                var validationArray = data.validation;
                                me.validationRegistros.removeAll();

                                if(validationArray.length > 0) {
                                    for (var i = 0; i < validationArray.length; i++) {
                                        me.validationRegistros.push(validationArray[i]);
                                    }
                                    $("#registroValidations").show();
                                }
                                else{
                                    $("#reprogramacionDialog").modal("hide");
                                    toastr.success('El participante ha sido reprogramado con éxito','Participante Reprogramado');
                                    ReprogramacionViewModel.loadParticipantesAReprogramar();
                                }

                            },
                            error: function (data) {
                                console.log(data);
                                console.log("error ;(");
                            }
                        });
                    },
                    invalid: function () {

                    }
                });
            };

            me.validate = function (options) {
                me.supressValidationMessages(false);
                if (me.IsFechaProgramacionSupplied() && me.isNuevoTurnoSupplied()) {
                    options.valid();
                }
                else{
                    options.invalid();
                }
            };

            me.onFechaProgramacionChange = function () {
                var currentDate = $("#nuevaFechaProgramacion").datepicker("getDate");
                if (currentDate != null || $.trim(currentDate) != "") {
                    var dia = $.datepicker.formatDate('DD', currentDate);
                    var optionsAsString = "<option value=''>Elija su horario</option>";

                    $.ajax({
                        type: "GET",
                        url: path + "/api/v1/consultarTurnosPorDia",
                        async: false,
                        data: {nombreDia: dia },
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (data) {
                            for (i = 0;i < data.turnos.length; i++){
                                optionsAsString += "<option value='" + data.turnos[i].turnoId+ "'>" + data.turnos[i].turnoHorario + "</option>";
                            }
                            $("#horarios").html(optionsAsString);
                            $("#horarios").prop('disabled', false);
                        },
                        error: function (data) {
                            console.log(data);
                            console.log("error ;(");
                        }
                    });
                }
                else{
                    //limpiamos el combobox de horarios y los deshabilitamos
                    $("#horarios").html(optionsAsString);
                    $("#horarios").prop('disabled', true);
                }
            };

            me.datePickerSettings = function(){
                $.datepicker.regional['es'] = {
                    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                    weekHeader: 'Sm',
                    dateFormat: 'dd/mm/yy',
                    firstDay: 1,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: ''
                };
                $.datepicker.setDefaults($.datepicker.regional['es']);
            };

            return {
                initialize: me.initialize,
                setParticipante: me.setParticipante,
                dniParticipante: me.dniParticipante,
                nombreParticipante: me.nombreParticipante,
                nuevaFechaProgramacion: me.nuevaFechaProgramacion,
                nuevoTurno: me.nuevoTurno,
                onGuardarReprogramacion: me.onGuardarReprogramacion,
                onFechaProgramacionChange: me.onFechaProgramacionChange,
                supressValidationMessages: me.supressValidationMessages,
                IsFechaProgramacionSupplied: me.IsFechaProgramacionSupplied,
                isNuevoTurnoSupplied: me.isNuevoTurnoSupplied,
                supressValidationMessages: me.supressValidationMessages,
                validationRegistros: me.validationRegistros
            };
        });

        var ReprogramacionViewModel = function (){
            var me = this;

            me.participantes = ko.observableArray([]);
            me.loadingParticipantes = ko.observable(false);

            me.initialize = function () {
                $(document.body).on('keydown', '#filtroPersonal' , me.search);
                me.loadParticipantesAReprogramar();

                ko.applyBindings(ReprogramacionViewModel, $("#reprogramacion")[0]);
            };

            me.search = function (e) {
                var inputValue = e.keyCode;
                //si se presiona enter
                if(inputValue == 13) {
                    me.loadParticipantesAReprogramar();
                }
            };

            me.loadParticipantesAReprogramar = function () {
                me.loadingParticipantes(true);
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/obtenerParticipantesAReprogramar",
                    data: {searchText: $("#filtroPersonal").val()},
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (data) {
                        var participantesRaw = data.result;
                        me.loadingParticipantes(false);
                        me.participantes.removeAll();
                        for (var i = 0; i < participantesRaw.length; i++) {
                            me.participantes.push(participantesRaw[i]);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        console.log("error :(");
                    }
                });
            };

            me.onReprogramarClick = function (data, event) {
                var nombreParticipante = data.pa_nombres + ' ' + data.pa_apellido_paterno + ' ' + data.pa_apellido_materno;
                ReprogramacionDialog.setParticipante(data.pa_dni, nombreParticipante);
                ReprogramacionDialog.initialize();
            };

            return {
                initialize: me.initialize,
                participantes : me.participantes,
                loadParticipantesAReprogramar: me.loadParticipantesAReprogramar,
                onReprogramarClick: me.onReprogramarClick
            }
        }();

        $(function () {
            ReprogramacionViewModel.initialize();
        });
    </script>
@stop