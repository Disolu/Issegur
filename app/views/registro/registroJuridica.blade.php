@extends('master.site')
@section('main')
<body class="external-page external-alt sb-l-c sb-r-c">

<!-- Start: Main -->
<div id="main" class="animated fadeIn">

    <!-- Start: Content-Wrapper -->
    <section id="content_wrapper">

        <!-- Begin: Content -->
        <section id="content">
            <div class="admin-form theme-primary mw600" style="margin-top: 3%;" id="register">

                <!-- Begin: Content Header -->
                <div class="content-header">
                    <h2>INSCRIPCION PARA EL CURSO DE INDUCCION DE SEGURIDAD EN ALMACENES</h2>
                    <p class="lead">RANSA - TRAMARSA</p>
                </div>

                <!-- Begin: Admin Form -->
                <div class="admin-form theme-primary">

                    <div class="panel heading-border panel-primary">
                        <div class="panel-body bg-light">
                            <div id="registro">
                                <!-- seccion Empresa -->
                                <fieldset class="seccionForm">
                                    <legend class="legend legendEmpresa"> Datos de la Empresa</legend>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <input type="text" name="ruc" id="ruc" data-bind="value: ruc , event: { 'keyup': consultarRUC }" class="gui-input soloNumeros" placeholder="RUC" maxlength="11">
                                                    <label for="ruc" class="field-icon">
                                                        <i class="fa fa-book"></i>
                                                    </label>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !rucSupplied()">Ingrese un ruc.</em>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !isRucValid()">El ruc no es válido.</em>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="pull-left">
                                                <button id="btnConsultarRUC" data-bind="click: consultarRUCButton" style="width:235px;" class="btn btn-info"><i class="fa fa-search"></i> Consultar RUC</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <input type="text" name="razonSocial" id="razonSocial" data-bind="value: razonSocial" class="gui-input letras" placeholder="Razón Social"  disabled>
                                                    <label for="razonSocial" class="field-icon">
                                                        <i class="fa fa-briefcase"></i>
                                                    </label>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !razonSocialSupplied()">Ingrese una razón social.</em>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <!-- seccion Datos de Pago -->
                                <fieldset class="seccionForm">
                                <legend class="legend legendDatosPago"> Datos de Pago</legend>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <input type="text" name="nOperacion" id="nOperacion" data-bind="value: nroOperacion, event: { 'keyup': consultarNroOperacion }"  class="gui-input soloNumeros" placeholder="N° Operación">
                                                    <label for="website" class="field-icon">
                                                        <i class="fa fa-credit-card"></i>
                                                    </label>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !nroOperacionSupplied()">Ingrese un número de operación.</em>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <a id="btnConozcaNroOperacion" role="button" style="width:235px;" data-toggle="modal" data-target="#nroOperacionModal" class="btn btn-system btn-block"><i class="fa fa-info-circle"></i> Ejemplo de N° Operacion</a>
                                        </div>
                                        <div class="modal fade" id="nroOperacionModal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Voucher - Ejemplo</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img src="{{asset('assets/img/voucher/nroOperacionImg.png')}}" alt=""/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Input Formats -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <input type="text" name="montoPago" id="montoPago" data-bind="value: montoPago" class="gui-input soloNumeros" placeholder="Ingrese el monto total de su voucher de pago">
                                                    <label for="montoPago" class="field-icon">
                                                        <span class="soles-span">S/.</span>
                                                    </label>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !montoPagoSupplied()">Ingrese un monto de pago.</em>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <input type="text"  id="dtpFechaOperacion" data-bind="value: fechaOperacion" class="gui-input datepicker" placeholder="Fecha en la que realizo su pago (voucher)">
                                                    <label for="website" class="field-icon">
                                                        <i class="fa fa-calendar-o"></i>
                                                    </label>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !fechaOperacionSupplied()">Ingrese la fecha de su voucher.</em>
                                                </label>
                                            </div>
                                        </div>
                                        {{--{{ Form::open(array('url' => 'upload', 'files' => true , 'id'=> 'voucherForm')) }}--}}
                                        {{--<div class="col-md-12">--}}
                                            {{--<div class="section">--}}
                                                {{--<label class="field prepend-icon append-button file">--}}
                                                    {{--<span class="button btn-info">Seleccionar</span>--}}
                                                    {{--<input type="file" class="gui-file" name="voucher" id="fileVoucher">--}}
                                                    {{--<input type="text" class="gui-input" data-bind="value: archivo" id="uploaderVoucher" placeholder="Cargue su voucher">--}}
                                                    {{--<label class="field-icon">--}}
                                                        {{--<i class="fa fa-file-image-o"></i>--}}
                                                    {{--</label>--}}
                                                    {{--<em class="validation-error" data-bind="visible: !supressValidationMessages() && !archivoSupplied()">Seleccione un archivo.</em>--}}
                                                {{--</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--{{ Form::close() }}--}}
                                    </div>
                                </fieldset>
                                <div class="alert alert-danger alert-dismissable">
                                    <i class="fa fa-info-circle pr10" style="margin-bottom: 2px"></i>
                                    <span style="font-size: 15px">Es importante llevar el voucher original para su ingreso al aula, como para solicitar su factura.</span>
                                </div>
                                <fieldset class="seccionForm">
                                    <legend class="legend legendDatosSolicitante"> Datos de la persona de contacto de la Empresa</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <input type="text" name="solicitanteNombre" id="solicitanteNombre" data-bind="value: soliNombre" class="gui-input letras" placeholder="Nombre del solicitante">
                                                    <label for="solicitanteNombre" class="field-icon">
                                                        <i class="fa fa-user"></i>
                                                    </label>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !soliNombreSupplied()">Ingrese un nombre.</em>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <input type="text" name="solicitanteApellido" id="solicitanteApellido" data-bind="value: soliApellido" class="gui-input letras" placeholder="Apellidos del solicitante">
                                                    <label for="solicitanteApellido" class="field-icon">
                                                        <i class="fa fa-user"></i>
                                                    </label>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !soliApellidoSupplied()">Ingrese un apellido.</em>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <input type="text" name="phone" id="phone" class="gui-input" data-bind="value: soliTelefono" placeholder="Teléfono o celular">
                                                    <label for="phone" class="field-icon">
                                                        <i class="fa fa-phone"></i>
                                                    </label>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !soliTelefonoSupplied()">Ingrese un teléfono.</em>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="section">
                                                <label class="field prepend-icon">
                                                    <input type="text" name="email" id="email" class="gui-input" data-bind="value: soliEmail" placeholder="Ingrese su email">
                                                    <label for="email" class="field-icon">
                                                        <i class="fa fa-envelope-o"></i>
                                                    </label>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !soliEmailSupplied()">Ingrese un email.</em>
                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !isEmailValid()">El email es inválido.</em>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="seccionForm">
                                    <legend class="legend legendGrupos"> Grupos de Participantes</legend>
                                    <a id="createGrupo" role="button" style="width:150px;"  class="btn btn-info">
                                        <i class="fa fa-plus"></i> Agregar Grupo
                                    </a>
                                    <div class="row">
                                        <br/>
                                        <div class="col-md-12">
                                            <em class="validation-error" data-bind="visible: !supressValidationMessages() && !gruposSupplied()">Debe registrar al menos un grupo.</em>
                                            <!-- ko foreach: { data: grupos, as: 'grupo' } -->
                                            <div class="alert alert-sm alert-primary bg-gradient dark alert-dismissable grupoContainer" data-bind="attr: { 'data-id': grupo.id}">
                                                <a type="button" id="eliminarGrupo"  class="close"  aria-hidden="true">×</a>
                                                <i class="fa fa-cubes pr10 hidden"></i>
                                                <strong data-bind="text: grupo.fechaProgramacion + ' - (' + grupo.turnoText + ')'"></strong> &nbsp;
                                                <span style="font-size: 12px" data-bind="text: 'Participantes (' + grupo.currentParticipantes().length + ') - Operadores (' + grupo.selectedOperadoresText().join(',') + ')'"></span>
                                                <a href="editGrupo" class="EditGrupo" style="float:right;color:white;font-size: 14px">
                                                    Editar
                                                </a>
                                            </div>
                                            <!--/ko-->
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset id="registroValidations" style="display:none;">
                                    <div class="pull-left">
                                        <div class="validation-error" >Lo sentimos, no se pudo completar su registro, no hay cupos disponibles para : <br/><br/>
                                            <!-- ko foreach: { data: validationRegistros, as: 'val' } -->
                                            <!--ko text: val.fecha--><!--/ko-->&nbsp; <!--ko text: val.turno--><!--/ko-->
                                            <!--/ko-->
                                            <br/>
                                            <br/>
                                            Pruebe alguna de estas alternativas: <br/>
                                            - Elija una nueva fecha y/o turno para el <br/>
                                            - Disminuya la cantidad de participantes del grupo. <br/>
                                            - Elimine el grupo
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset id="registroValidationsParticipantes" style="display:none;">
                                    <div class="pull-left">
                                        <div class="validation-error" >Los o uno de los participantes con el o los siguientes DNI's :  <br/>
                                            <!-- ko foreach: { data: validationParticipante, as: 'val' } -->
                                            <!--ko text: val.pa_dni--><!--/ko--> <br/>
                                            <!--/ko-->
                                            <br/>

                                            ya se encuentran programados, o fueron calificados como "Ausente" el dia su capacitacion, <br/>
                                            en caso quiera reprogramar alguno de ellos comuníquese por teléfono o envíe un email <br/>
                                            indicando esta observación y listando a los participantes que no asistieron a su programación inicial. <br/>

                                        </div>
                                    </div>
                                </fieldset>

                            </div>
                                <!-- Modal -->
                                <div class="modal fade" id="grupoDialog" tabindex="-1"  aria-labelledby="titleModal">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="titleModal"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <fieldset class="seccionForm">
                                                    <legend class="legend legendDatosCurso"> Datos del Curso</legend>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="section">
                                                                <label for="dtpFechaProgramacion" class="field prepend-icon">
                                                                    <input type="text"  id="dtpFechaProgramacion" class="gui-input datepicker"  data-bind="value: fechaProgramacion , event: {change : onFechaProgramacionChange}" placeholder="Fecha de programación de curso">
                                                                    <label class="field-icon">
                                                                        <i class="fa fa-calendar-o"></i>
                                                                    </label>
                                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !isFechaProgramacionSupplied()">Seleccione una fecha.</em>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="section">
                                                                <label class="field select">
                                                                    <select id="horarios" name="horarios" data-bind="value: turnoId" disabled>
                                                                        <option value="0">Elija su horario</option>
                                                                    </select>
                                                                    <i class="arrow double"></i>
                                                                    <em class="validation-error" data-bind="visible: !supressValidationMessages() && !isTurnoSupplied()">Seleccione un turno.</em>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-md-6">
                                                                <div class="alert alert-default dark alert-dismissable">
                                                                    <i class="fa fa-cog pr10 hidden"></i>
                                                                    Elija el almacén al que desea ingresar <small><em>(puede elegir entre uno o más almacenes de acuerdo a su pago).</em></small></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 vcenter pl15">
                                                                <div id="operadores">
                                                                </div>
                                                                <em class="validation-error" data-bind="visible: !supressValidationMessages() && !isOperadorSupplied()">Seleccione un operador.</em>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="seccionForm">
                                                    <legend class="legend legendDatosParticipantes">Datos de los Participantes</legend>
                                                    <div class="alert alert-info alert-dismissable">
                                                        <i class="fa fa-info-circle pr10"></i>
                                                        <span style="font-size: 15px">Ingrese el DNI y presione ENTER para verificar si el participante está registrado.</br> Caso contrario, los campos se habilitarán para
                                                         el registro del mismo.</span>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div id="Participantes">
                                                                <table class="table table-condensed">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="center">DNI</th>
                                                                        <th class="center">Nombres</th>
                                                                        <th class="center">Apellido Paterno</th>
                                                                        <th class="center">Apellido Materno</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <!-- ko foreach: { data: participantes, as: 'pa' } -->
                                                                        <tr class="paContainer">
                                                                            <td>
                                                                                <div class="smart-widget sm-right smr-50">
                                                                                    <label class="field">
                                                                                        <input type="text" data-bind="value: pa.dni , event: { 'keyup': pa.consultarDNI }" class="paData form-control gui-input padni" maxlength="8"/>
                                                                                    </label>
                                                                                    <button type="button" class="button btn-primary" data-bind="click: pa.consultarDNIButton">
                                                                                        <i class="fa fa-search"></i>
                                                                                    </button>
                                                                                </div>
                                                                                <em class="validation-error" data-bind="visible: !pa.supressValidationMessages() && !pa.isDniValid()">El dni es inválido.</em>
                                                                                <em class="validation-error" data-bind="visible: !pa.supressValidationMessages() && !pa.isDniSupplied()">Debe ingresar un dni</em>
                                                                            </td>
                                                                            <td><input type="text" data-bind="value: pa.nombres" class="paData form-control letras" disabled/>
                                                                                <em class="validation-error" data-bind="visible: !pa.supressValidationMessages() && !pa.isNombresSupplied()">Debe ingresar un dni</em>
                                                                            </td>
                                                                            <td><input type="text" data-bind="value: pa.ape_paterno" class="paData form-control letras" disabled/>
                                                                                <em class="validation-error" data-bind="visible: !pa.supressValidationMessages() && !pa.isApePaternoSupplied()">Debe ingresar un apellido</em>
                                                                            </td>
                                                                            <td><input type="text" data-bind="value: pa.ape_materno" class="paData form-control letras" disabled/>
                                                                                <em class="validation-error" data-bind="visible: !pa.supressValidationMessages() && !pa.isApeMaternoSupplied()">Debe ingresar un apellido</em>
                                                                            </td>
                                                                        </tr>
                                                                    <!-- /ko-->
                                                                    <em id="paValidationData" class="validation-error" style="display: none">Ingrese al menos un participante.</em>
                                                                    <div id="alertaPa" class="alert alert-danger alert-dismissable" style="display:none;">
                                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                                        <i class="fa fa-remove pr10"></i>
                                                                        <strong>Error!</strong> <span class="mensajeErrorPa"></span></a>
                                                                    </div>
                                                                    <tr>
                                                                        <td colspan="4">
                                                                            <button id="btnAgregarPa" data-bind="click: agregarParticipante" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar participantes</button>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                <button id="btnAgregarGrupo" data-bind="click: guardarGrupo"  type="button" class="btn btn-primary" >Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Eliminar Grupo Modal Dialog -->
                                <div class="modal fade" id="confirmDeleteGrupo" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Eliminar Grupo</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Esta seguro que desea eliminar este grupo ?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bind="click: deleteGrupo" id="confirm">Eliminar</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="pull-right">
                                    <img src="{{asset('assets/img/clock-loader/loading.gif')}}" id="loadingClock" style="width:40px;height:40px;display:none;" alt=""/>&nbsp;
                                    <button id="btnRegistrar" class="btn btn-alert"><i class="fa fa-floppy-o"></i> Enviar Formulario de Registro</button>
                                </div>

                        </div>
                    </div>
                 </div>
            </div>
        </section>
    </section>
</div>
@stop
@section('scriptsContainer')
    @include('registro.registroJuridicaJS')
@stop
