@extends('master.siteConsulta')
@section('main')
    <div id="consultaPersonal" class="consultaPersonalSection">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 center">
                <i class="fa fa-users fa-3x"></i><h1 class="consultaHeader">Consulta de Personal</h1>
            </div>
        </div>
        <div class="row" id="filtroSection">
            <div class="col-md-8 col-md-offset-2 marginSection">
                <div class="inner-addon left-addon">
                    <label for="filtroPersonal" class="icon-label"><i class="fa fa-search fa-1x"></i></label>
                    <input id="filtroPersonal" class="form-control input-lg" type="text" placeholder="Ingrese un DNI, nombre o apellido">
                </div>
            </div>
            <div class="col-md-8 col-md-offset-2 marginSection">
                <div class="list-group">
                    <!-- ko foreach: { data: searchResults, as: 'pa' } -->
                    <a role="button" class="list-group-item participanteItem">
                        <h4 class="list-group-item-heading" data-bind="text: pa.pa_dni"></h4>
                        <p class="list-group-item-text" data-bind="text: pa.pa_nombres + ' ' + pa.pa_apellido_paterno + ' ' + pa.pa_apellido_materno"></p>
                    </a>
                    <!--/ko-->
                </div>
            </div>
        </div>
        <div class="row" id="detallesSection" style="display:none;">
            <div class="col-md-6 col-md-offset-3 marginSection ">
                <div class="detallesContainer">
                    <div class="row detallesHeader">
                        <div class="col-md-6">
                            <div class="pull-right">
                                <img id="paPhoto" data-bind="attr: {'src': fotoUrl()}" height="130px" width="125px"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pull-left">
                                <p class="name-header" data-bind="text: nombre() + ' ' + apellidoPaterno() + ' ' + apellidoMaterno()"></p>
                                <h2 class="dni-header"><small data-bind="text: dni()"></small></h2>
                            </div>
                        </div>
                    </div>
                    <div class="row detallesBody">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <th>Curso</th>
                                        <th>Operador</th>
                                        <th>Empresa</th>
                                        <th colspan="2" class="center">Vigencia</th>
                                        <th class="center">Estado</th>
                                    </thead>
                                    <tbody>
                                    <!--ko foreach: { data: aditionalInfoArray, as: 'paInfo' } -->
                                    <tr>
                                        <td>
                                            Inducción SST
                                        </td>
                                        <td data-bind="text: paInfo.operador"></td>
                                        <td data-bind="text: paInfo.empresa"></td>
                                        <td class="center" data-bind="text: paInfo.fechaCurso"></td>
                                        <td class="center" data-bind="text: paInfo.fechaVigencia"></td>
                                        <td class="center">
                                            <!-- ko if: paInfo.activo -->
                                            <span class='label label-success'>Activo</span>
                                            <!-- /ko -->
                                            <!-- ko ifnot: paInfo.activo -->
                                            <span class='label label-danger'>Inactivo</span>
                                            <!-- /ko -->
                                        </td>
                                    </tr>
                                    <!--/ko-->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row detallesFooter center">
                    <button id="btnRegresar" class="btn btn-primary btn-lg"><i class="fa fa-arrow-left"></i> Volver</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scriptsSection')
    <script>
        var DetalleParticipanteViewModel = new (function () {
           var me = this;

            me.nombre = ko.observable(null);
            me.apellidoPaterno = ko.observable(null);
            me.apellidoMaterno = ko.observable(null);
            me.dni = ko.observable(null);
            me.fotoUrl = ko.observable(null);
            me.aditionalInfoArray = ko.observableArray([]);
            me.hasBeenInitialized = false;

            me.initialize = function () {
                if (!me.hasBeenInitialized) {
                    ko.applyBindings(DetalleParticipanteViewModel, $("#detallesSection")[0]);
                    me.hasBeenInitialized = true;
                }
            };

            me.setSelectedParticipanteInfo = function (participante) {
                me.nombre(participante.nombre);
                me.apellidoPaterno(participante.apellidoPaterno);
                me.apellidoMaterno(participante.apellidoMaterno);
                me.dni(participante.dni);
                me.fotoUrl(participante.fotoUrl);
                me.aditionalInfoArray(participante.aditionalInfoDetails.slice(0));
            };

            return{
                nombre: me.nombre,
                apellidoPaterno: me.apellidoPaterno,
                apellidoMaterno: me.apellidoMaterno,
                dni: me.dni,
                fotoUrl: me.fotoUrl,
                aditionalInfoArray: me.aditionalInfoArray,
                initialize: me.initialize,
                setSelectedParticipanteInfo: me.setSelectedParticipanteInfo
            }
        });
        var ConsultaParticipantesViewModel = function () {
            var me = this;

            me.searchText = ko.observable(null);
            me.searchResults = ko.observableArray([]);

            me.initialize = function () {
                $(document.body).on('keydown', '#filtroPersonal' , me.search);
                $(document.body).on('click', '.participanteItem' , me.onParticipanteItemClick);
                $(document.body).on('click', '#btnRegresar' , me.onRegresarButtonClick);

                ko.applyBindings(ConsultaParticipantesViewModel, $("#filtroSection")[0]);
            };

            me.search = function (e) {
                var inputValue = e.keyCode;
                //si se presiona enter
                if(inputValue == 13){
                    $.ajax({
                        type: "GET",
                        url: path + "/api/v1/buscarPersonal",
                        data: { searchText: $("#filtroPersonal").val()},
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (data) {
                            var participantesRaw = data.participantes;
                            me.searchResults.removeAll();
                            for (var i = 0; i < participantesRaw.length; i++) {
                                me.searchResults.push(participantesRaw[i]);
                            }
                        },
                        error: function (data) {
                            console.log('error');
                            console.log(data);
                        }
                    });
                }
            };

            me.onParticipanteItemClick = function (e) {
                $item = $(e.target);
                var dni = ko.dataFor($item[0]).pa_dni;
                if (dni) {
                    me.getParticipanteInfobyDni(dni);
                }
            };

            me.onRegresarButtonClick = function () {
                $("#filtroPersonal").val("");
                me.searchResults.removeAll();

                $("#filtroSection").show();
                $("#detallesSection").hide();
                $("#filtroPersonal")[0].focus();
            };

            me.getParticipanteInfobyDni = function (dni) {
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/getParticipanteInfoByDNI",
                    data: { dni: dni},
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        me.mostrarDetalleParticipante(data.participanteInfo);
                    },
                    error: function (data) {
                        console.log('error');
                        console.log(data);
                    }
                });
            };

            me.mostrarDetalleParticipante = function (participanteRaw) {
                var firstParticipante = participanteRaw[0];
                //creamos el objeto incial
                var mainParticipante = {
                    nombre: firstParticipante.pa_nombres,
                    apellidoPaterno: firstParticipante.pa_apellido_paterno,
                    apellidoMaterno: firstParticipante.pa_apellido_materno,
                    dni: firstParticipante.pa_dni,
                    fotoUrl: $.trim(firstParticipante.pa_foto) == "" ? "fotos/no-avatar.png": firstParticipante.pa_foto,
                    aditionalInfoDetails:[]
                };

                for (var i = 0; i < participanteRaw.length; i++) {
                    var current = participanteRaw[i];
                    var fechaCurrent = new Date(current.FechaProgramacion);
                    fechaCurrent.setDate(fechaCurrent.getDate() + 1);

                    var currentParticipante = {
                        operador: current.Operador,
                        empresa: $.trim(current.RazonSocial) == "" ? "N/A" : current.RazonSocial,
                        fechaCurso: fechaCurrent.getDate() + '/' + (fechaCurrent.getMonth() + 1) + '/' + (fechaCurrent.getFullYear()),
                        fechaVigencia: fechaCurrent.getDate() + '/' + (fechaCurrent.getMonth() + 1) + '/' + (fechaCurrent.getFullYear() + 1),
                        activo: true
                    };

                    //verificamos dependiendo de las fechas si esta activo o inactivo
                    fechaCurrent.setMonth(fechaCurrent.getMonth() + 12);
                    currentParticipante.activo = new Date() < fechaCurrent;
                    //agregamos la informacion adicional al array aditionalInfo
                    mainParticipante.aditionalInfoDetails.push(currentParticipante);
                }

                $("#filtroSection").hide();
                $("#detallesSection").show();
                DetalleParticipanteViewModel.setSelectedParticipanteInfo(mainParticipante);
                DetalleParticipanteViewModel.initialize();

            };

            return{
                searchText: me.searchText,
                searchResults: me.searchResults,
                initialize: me.initialize
            };
        }();

        $(function () {
            $("#filtroPersonal")[0].focus();
            ConsultaParticipantesViewModel.initialize();
        });
    </script>
@stop