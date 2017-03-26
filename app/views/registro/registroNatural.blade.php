@extends('master.site')
@section('stylesSection')

@stop
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
                        <h2>INSCRIPCIÓN PARA EL CURSO DE INDUCCIÓN DE SEGURIDAD EN ALMACENES</h2>
                        <p class="lead">RANSA - TRAMARSA - SLI</p>
                    </div>

                    <!-- Begin: Admin Form -->
                    <div class="admin-form theme-primary">
                        <div class="panel heading-border panel-primary">
                            <div class="panel-body bg-light">
                                <div id="registro">
                                    <!-- seccion Datos de Pago -->
<!--
                                    <fieldset class="seccionForm">
                                    <legend class="legend legendDatosPago"> Datos de Pago</legend>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="section">
                                                    <label class="field prepend-icon">
                                                        <input type="text" name="nOperacion" id="nOperacion" data-bind="value: nroOperacion, event: { 'keyup': consultarNroOperacion }" class="gui-input soloNumeros" placeholder="N° Operación">
                                                        <label for="website" class="field-icon">
                                                            <i class="fa fa-credit-card"></i>
                                                        </label>
                                                        <span class="validation-error" data-bind="visible: !supressValidationMessages() && !nroOperacionSupplied()">Ingrese un número de operación.</span>
                                                    </label>
                                                </div>
                                            </div>
                                            {{--<div class="col-md-4">--}}
                                                {{--<a id="btnConsultarNroOperacion" role="button" style="width:235px;" data-bind="click: consultarNroOperacionButton" class="btn btn-info"><i class="fa fa-search"></i> Validar N° Operacion</a>--}}
                                            {{--</div>--}}
                                            <div class="col-md-3">
                                                <a id="btnConozcaNroOperacion" role="button" style="width:235px;" data-toggle="modal" data-target="#nroOperacionModal" class="btn btn-system btn-block"><i class="fa fa-info-circle"></i> Ejemplo de N° Operación</a>
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
-->
                                        <!-- Input Formats -->
<!--
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="section">
                                                    <label class="field prepend-icon">
                                                        <input type="text" name="montoPago" id="montoPago" data-bind="value: montoPago" class="gui-input soloNumeros" placeholder="Ingrese el monto total de su voucher de pago">
                                                        <label for="montoPago" class="field-icon">
                                                            <span class="soles-span">S/.</span>
                                                        </label>
                                                        <span class="validation-error" data-bind="visible: !supressValidationMessages() && !montoPagoSupplied()">Ingrese un monto de pago.</span>
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
                                                        <span class="validation-error" data-bind="visible: !supressValidationMessages() && !fechaOperacionSupplied()">Ingrese la fecha de su voucher.</span>
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
                                                        {{--<span class="validation-error" data-bind="visible: !supressValidationMessages() && !archivoSupplied()">Seleccione un archivo.</span>--}}
                                                    {{--</label>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--{{ Form::close() }}--}}
                                        </div>
                                    </fieldset>
-->

                                    <fieldset class="seccionForm">
                                        <legend class="legend legendDatosCurso"> Datos del Curso </legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="section">
                                                    <label for="dtpFechaProgramacion" class="field prepend-icon">
                                                        <input type="text"  id="dtpFechaProgramacion" class="gui-input datepicker"  data-bind="value: fechaProgramacion , event: {change : onFechaProgramacionChange}" placeholder="Fecha de programación de curso">
                                                        <label class="field-icon">
                                                            <i class="fa fa-calendar-o"></i>
                                                        </label>
                                                        <span class="validation-error" data-bind="visible: !supressValidationMessages() && !isFechaProgramacionSupplied()">Seleccione una fecha.</span>
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
                                                        <span class="validation-error" data-bind="visible: !supressValidationMessages() && !isTurnoSupplied()">Seleccione un turno.</span>
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
                                                    <div id="operadores" style="margin-bottom: 15px">
                                                    </div>
                                                    <span class="validation-error" data-bind="visible: !supressValidationMessages() && !isOperadorSupplied()">Seleccione un operador.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="alert alert-danger alert-dismissable">
                                        <i class="fa fa-info-circle pr10" style="margin-bottom: 2px"></i>
                                        <span style="font-size: 15px">Es importante llevar el voucher original para su ingreso al aula, como para solicitar su factura.</span>
                                    </div>

                                    <fieldset class="seccionForm">
                                        <legend class="legend legendDatosParticipante">Datos del Participante</legend>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="section">
                                                    <label class="field prepend-icon">
                                                        <input type="text" name="parEmail" id="parEmail" data-bind="value: email" class="gui-input" placeholder="Email">
                                                        <label for="parEmail" class="field-icon">
                                                            <i class="fa fa-envelope-o"></i>
                                                        </label>
                                                        <span class="validation-error" data-bind="visible: !supressValidationMessages() && !isEmailSupplied()">Ingrese un email.</span>
                                                        <span class="validation-error" data-bind="visible: !supressValidationMessages() && !isEmailValid()">El email no es válido.</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">

                                                <div id="Participantes">
                                                <div class="alert alert-info alert-dismissable">
                                            <i class="fa fa-info-circle pr10"></i>
                                            <span style="font-size: 15px">Ingrese el DNI y presione ENTER para verificar si el participante está registrado.</br> Caso contrario, los campos se habilitarán para
                                             el registro del mismo.</span>
                                        </div>
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
                                                        <tr class="paContainer">
                                                            <td>
                                                                <div class="smart-widget sm-right smr-50">
                                                                    <label class="field">
                                                                        <input type="text" data-bind="value: dni , event: { 'keyup': consultarDNI }" class="paData form-control gui-input padni" maxlength="8"/>
                                                                    </label>
                                                                    <button type="button" class="button btn-primary" data-bind="click: consultarDNIButton">
                                                                        <i class="fa fa-search"></i>
                                                                    </button>
                                                                </div>
                                                                <span class="validation-error" data-bind="visible: !supressValidationMessages() && !isDniValid()">El dni es inválido.</span>
                                                                <span class="validation-error" data-bind="visible: !supressValidationMessages() && !isDniSupplied()">Debe ingresar un dni</span>
                                                            </td>
                                                            <td><label class="field"><input type="text" data-bind="value: nombres" class="paData form-control letras" disabled /></label>
                                                                <span class="validation-error" data-bind="visible: !supressValidationMessages() && !isNombresSupplied()">Debe ingresar un nombre</span>
                                                            </td>
                                                            <td><label class="field"><input type="text" data-bind="value: ape_paterno" class="paData form-control letras" disabled/></label>
                                                                <span class="validation-error" data-bind="visible: !supressValidationMessages() && !isApePaternoSupplied()">Debe ingresar un apellido</span>
                                                            </td>
                                                            <td><label class="field"><input type="text" data-bind="value: ape_materno" class="paData form-control letras" disabled/></label>
                                                                <span class="validation-error" data-bind="visible: !supressValidationMessages() && !isApeMaternoSupplied()">Debe ingresar un apellido</span>
                                                            </td>
                                                        </tr>
                                                        <span id="paValidationData" class="validation-error" style="display: none">Ingrese al menos un participante.</span>
                                                        <div id="alertaPa" class="alert alert-danger alert-dismissable" style="display:none;">
                                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                            <i class="fa fa-remove pr10"></i>
                                                            <strong>Error!</strong> <span class="mensajeErrorPa"></span></a>
                                                        </div>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

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

                                    <fieldset id="registroValidationsParticipantes" style="display:none;">
                                        <div class="pull-left">
                                            <div class="validation-error" >Su DNI :  <br/>
                                                <!-- ko foreach: { data: validationParticipante, as: 'val' } -->
                                                <!--ko text: val.pa_dni--><!--/ko--> <br/>
                                                <!--/ko-->
                                                <br/>

                                                ya se encuentra programado, o fue calificado como "Ausente" el dia su capacitacion, <br/>
                                                en caso quiera realizar una reprogramación comuníquese por teléfono o envíe un email <br/>
                                                indicando esta observación junto con sus datos personales. <br/>

                                            </div>
                                        </div>
                                    </fieldset>
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
    @include('registro.registroNaturalJS')
@stop
