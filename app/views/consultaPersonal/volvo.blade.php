@extends('master.siteConsulta')
@section('main')
    <div class="row">
      <div class="col-md-6 center">
        <img 
          height="50"
          width="50"
          src="https://pbs.twimg.com/profile_images/956988626902151169/wSRi1yid_400x400.jpg" 
          alt="Issegur">
      </div>
      <div class="col-md-6 center">
        <img 
          height="30"
          width="140"
          src="http://www.carlogos.org/logo/Volvo-text-logo-2100x350.png" 
          alt="Volvo">
      </div>
    </div>
    <div id="consultaPersonal" class="consultaPersonalSection">
        <div class="row" id="headerSection">
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
                    <!-- ko if: init() && loadingParticipantes() -->
                        <div class="center">
                            <img src="{{asset("assets/img/ajax-loader.gif")}}" alt=""/>
                        </div>
                    <!-- /ko -->
                    <!--ko if: init() && !loadingParticipantes() && searchResults().length < 1 -->
                    <div class="alert alert-info" role="alert">No se encontraron participantes.</div>
                    <!--/ko-->
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
            <div class="col-md-8 col-md-offset-2 marginSection ">
                <div class="detallesContainer">
                    <div class="barra_azul">
                        <img style="padding-left: 10px; padding-top: 10px; width: 78px;" src="https://institutodeseguridad.edu.pe/anuncios/logo_issegur.png">
                        <span
                        class="titulo_fotocheck">INSTITUTO SUPERIOR DE SEGURIDAD</span>
                    </div>
                    <div class="row detallesHeader">
                        <div class="col-md-9">
                            <div class="cont_datos">

                                <span class="n_s">NOMBRES</span><br />
                                <span class="T_n" data-bind="text: nombre"></span><br />
                                <span class="n_s">APELLIDOS</span><br>
                                <span class="T_n" data-bind="text: apellidoPaterno() + ' ' + apellidoMaterno()"></span><br />
                                <span class="n_s">DNI</span><br />
                                <span class="T_n" data-bind="text: dni()"></span><br />
                                <span class="n_s">EMPRESA</span><br />
                                <span class="T_e" data-bind="text: aditionalInfoArray()[aditionalInfoArray().length - 1].empresa"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="pic_fotocheck">
                                <img id="paPhoto" data-bind="attr: {'src': fotoUrl()}" height="180px" width="140px"/>
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
                                        <!--ko if: isUsuarioAuth -->
                                        <th class="center">Ficha</th>
                                        <th class="center">Examen</th>
                                        <th class="center">SCTR</th>
                                        <th class="center">Nota</th>
                                        <!--/ko-->
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
                                        <!--ko if: $parent.isUsuarioAuth -->
                                        <td class="center">
                                        <!--ko if: $parent.fichaAsistencia() != ''-->
                                            <a class="btn btn-default btn-xs" data-bind="attr: {'href' : $parent.fichaAsistencia }" target="blank">
                                                <i class="fa fa-download"></i>
                                            </a>
                                         <!--/ko-->
                                         <!--ko if: $parent.fichaAsistencia() == ''-->
                                         N/E
                                         <!--/ko-->
                                        </td>
                                        <td class="center">
                                        <!--ko if: $parent.examen() != ''-->
                                            <a class="btn btn-default btn-xs" data-bind="attr: {'href' : $parent.examen }" target="blank">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        <!--/ko-->
                                        <!--ko if: $parent.examen() == ''-->
                                         N/E
                                         <!--/ko-->
                                        </td>
                                        <td class="center">
                                        <!--ko if: $parent.sctr() != ''-->
                                            <a class="btn btn-default btn-xs" data-bind="attr: {'href' : $parent.sctr }" target="blank">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        <!--/ko-->
                                        <!--ko if: $parent.sctr() == ''-->
                                         N/E
                                         <!--/ko-->
                                        </td>
                                        <td class="center" data-bind="text: $parent.nota"></td>
                                        <!--/ko-->
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
            me.fichaAsistencia = ko.observable(null);
            me.examen = ko.observable(null);
            me.sctr = ko.observable(null);
            me.nota = ko.observable(null);
            me.aditionalInfoArray = ko.observableArray([]);
            me.hasBeenInitialized = false;
            me.isUsuarioAuth = ko.observable({{Session::has('ransa_user')}});


            me.initialize = function () {
                if (!me.hasBeenInitialized) {
                    ko.applyBindings(DetalleParticipanteViewModel, $("#detallesSection")[0]);
                    ko.applyBindings(DetalleParticipanteViewModel, $("#headerSection")[0]);
                    me.hasBeenInitialized = true;
                }
            };

            me.setSelectedParticipanteInfo = function (participante) {
                me.nombre(participante.nombre);
                me.apellidoPaterno(participante.apellidoPaterno);
                me.apellidoMaterno(participante.apellidoMaterno);
                me.dni(participante.dni);
                me.fotoUrl(participante.fotoUrl);
                me.fichaAsistencia(participante.fichaAsistencia);
                me.examen(participante.examen);
                me.sctr(participante.sctr);
                me.nota(participante.nota);
                me.aditionalInfoArray(participante.aditionalInfoDetails.slice(0));
            };

            return{
                nombre: me.nombre,
                apellidoPaterno: me.apellidoPaterno,
                apellidoMaterno: me.apellidoMaterno,
                dni: me.dni,
                fotoUrl: me.fotoUrl,
                fichaAsistencia:  me.fichaAsistencia,
                examen: me.examen,
                sctr: me.sctr,
                nota: me.nota,
                aditionalInfoArray: me.aditionalInfoArray,
                initialize: me.initialize,
                setSelectedParticipanteInfo: me.setSelectedParticipanteInfo,
                isUsuarioAuth: me.isUsuarioAuth
            }
        });
        var ConsultaParticipantesViewModel = function () {
            var me = this;

            me.searchText = ko.observable(null);
            me.searchResults = ko.observableArray([]);
            me.loadingParticipantes = ko.observable(false);
            me.init = ko.observable(false);

            me.initialize = function () {
                $(document.body).on('keydown', '#filtroPersonal' , me.search);
                $(document.body).on('click', '.participanteItem' , me.onParticipanteItemClick);
                $(document.body).on('click', '#btnRegresar' , me.onRegresarButtonClick);
                $(document.body).on('click', '#btnRansaIniciarSesion' , me.onIniciarSesionClick);
                $(document.body).on('click', '#btnRansaCerrarSesion' , me.onCerrarSesionClick);
                $(document.body).on('click', '#btnRansaLogin' , me.onRansaLogin);

                $("#RansaSesion").hide();

                ko.applyBindings(ConsultaParticipantesViewModel, $("#filtroSection")[0]);
            };

            me.search = function (e) {
                var inputValue = e.keyCode;
                //si se presiona enter
                if(inputValue == 13){
                    me.loadingParticipantes(true);
                    var operadorId = 1; //volvo
                    $.ajax({
                        type: "GET",
                        url: path + "/api/v1/buscarPersonal/" + operadorId,
                        data: { searchText: $("#filtroPersonal").val()},
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (data) {
                            var participantesRaw = data.participantes;
                            me.init(true);
                            me.loadingParticipantes(false);
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

                me.init(false);
                $("#RansaSesion").hide();
            };

            me.onIniciarSesionClick = function(){
                $("#username").val('');
                $("#password").val('');

                $("#RansaSesionDialog").modal("show");
            }

            me.onCerrarSesionClick = function(){
                $("#btnRansaCerrarSesion").hide();
                $("#btnRansaIniciarSesion").show();

                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/ransaLogout",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        //algo aqui cuando se desloguea
                        DetalleParticipanteViewModel.isUsuarioAuth(false);
                        DetalleParticipanteViewModel.isUsuarioAuth.valueHasMutated();
                    },
                    error: function (data) {
                        console.log('error');
                        console.log(data);
                    }
                });
            }

            me.onRansaLogin = function(){
                var usuario = $("#username").val();
                var password = $("#password").val();

                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/ransaLogin",
                    data: { usuario: usuario, password: password },
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        if(data.resultado == false){
                            $("#loginFailedMessage").show();
                        }
                        else{
                            $("#loginFailedMessage").hide();
                            $("#RansaSesionDialog").modal("hide");
                            $("#btnRansaCerrarSesion").show();
                            $("#btnRansaIniciarSesion").hide();
                            me.mostrarInformacionAdicional();
                        }
                    },
                    error: function (data) {
                        console.log('error');
                        console.log(data);
                    }
                });
            }

            me.mostrarInformacionAdicional = function(){
                DetalleParticipanteViewModel.isUsuarioAuth(true);
            }

            me.getParticipanteInfobyDni = function (dni) {
                $.ajax({
                    type: "GET",
                    url: path + "/api/v1/getParticipanteInfoByDNI",
                    data: { dni: dni},
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        $("#RansaSesion").show();
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
                    fichaAsistencia: $.trim(firstParticipante.pa_ficha_asistencia),
                    examen: $.trim(firstParticipante.pa_examen),
                    sctr: $.trim(firstParticipante.pa_sctr),
                    nota: firstParticipante.pa_nota,
                    aditionalInfoDetails:[]
                };

                for (var i = 0; i < participanteRaw.length; i++) {
                    var current = participanteRaw[i];
                    var fechaCurrent = new Date(current.FechaProgramacion);
                    fechaCurrent.setDate(fechaCurrent.getDate() + 1);

                    var currentParticipante = {
                        operador: current.Operador,
                        empresa: $.trim(current.RazonSocial) == "" ? "N/A" : current.RazonSocial,
                        fechaCurso: (fechaCurrent.getDate()) + '/' + (fechaCurrent.getMonth() + 1) + '/' + (fechaCurrent.getFullYear()),
                        fechaVigencia: (fechaCurrent.getDate()) + '/' + (fechaCurrent.getMonth() + 1) + '/' + (fechaCurrent.getFullYear() + 1),
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
                initialize: me.initialize,
                loadingParticipantes: me.loadingParticipantes,
                init: me.init
            };
        }();

        $(function () {
            $("#filtroPersonal")[0].focus();
            ConsultaParticipantesViewModel.initialize();
        });
    </script>
@stop
