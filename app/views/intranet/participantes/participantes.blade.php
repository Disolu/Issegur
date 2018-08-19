@extends('intranet.layout.site')
@section('stylesSection')
    {{ HTML::style('assets/bootstrap-select/css/bootstrap-select.min.css') }}
    {{ HTML::style('assets/bootstrap-datepicker/css/datepicker.css') }}
@stop
@section('main')
    <div id="participantes" class="container">
        <div id="detalleParticipantes">
            <div class="row">
                <div class="col-md-6">
                    <div id="participantesHeader" class="pull-left">
                        <h1 style="margin-top: 10px !important;">Participantes</h1>
                        <p><span style="color: dimgrey">Fecha de curso :</span> <span style="font-size: 18px" data-bind="text: currentFecha"></span></p>
                        <p><span style="color: dimgrey">Turno :</span> <span style="font-size: 18px" data-bind="text: currentTurno"></span></p>
                        <p><span style="color: dimgrey">Ficha Asistencia : </span>
                            <span>
                                <button style="display: inline" id="subirFichaAsistencia" class="btn btn-default btn-xs"><i class="fa fa-upload"></i></button>
                                <span id="fileTextFichaAsistencia" data-bind="text: fichaAsistenciaNombre"></span>
                                <form class="fichaAsistenciaForm">
                                    <input type="file"
                                           name="fichaAsistenciaData"  accept=".jpg, .png, .jpeg, .gif, .bmp, .pdf" class="uploadFichaAsistenciaHidden" style="display: none" />
                                </form>
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <br/>
                        <button id="btnAgregarParticipantes" class="btn btn-info" style="float:right"><i class="fa fa-plus"></i> Agregar Participantes</button>
                        <br/><br/><br/><br/><br/>
                        <button id="btnRegresarACalendario" class="btn btn-default"><i class="fa fa-arrow-circle-o-left"></i> Regresar</button>
                        <!--ko if: guardardandoParticipantes()-->
                        <button class="btn btn-primary" disabled><i class="fa fa-spinner"></i> Cargando...</button>
                        <!--/ko-->
                        <!--ko ifnot: guardardandoParticipantes()-->
                        <button id="btnGuardarParticipantes" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Guardar</button>
                        <!--/ko-->

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3" style="margin-top: 20px">
                    <div class="inner-addon left-addon">
                        <label for="filtroPersonal" class="icon-label"><i class="fa fa-search fa-1x"></i></label>
                        <input id="filtroPersonal" class="form-control input-lg" type="text" placeholder="Ingrese un DNI, nombre o apellido o RUC">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="participantesBody" class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <th class="pa-table-header" style="width:35px;">N°</th>
                            <th class="pa-table-header center" style="width:90px;">DNI/C.Extranjería</th>
                            <th class="pa-table-header" style="max-width:170px;">Nombres</th>
                            <th class="pa-table-header" style="max-width:120px;">Ape. Paterno</th>
                            <th class="pa-table-header" style="max-width:120px;">Ape. Materno</th>
                            <th class="pa-table-header center" style="max-width:220px;">Empresa</th>
                            @if (Auth::user()->rol_id == 2 || Auth::user()->rol_id == 1)
                            <th class="pa-table-header center" style="width:80px;">RUC</th>
                            @endif
                            <th class="pa-table-header" style="width:160px;">Almacén</th>
                            <th class="pa-table-header center" style="width:85px;">Asistencia</th>
                            @if (Auth::user()->rol_id == 3 || Auth::user()->rol_id == 1)
                            <th class="pa-table-header center">Nota</th>
                            @endif
                            <th class="pa-table-header center" style="max-width:120px;">N° Ope.</th>
                            @if (Auth::user()->rol_id == 2 || Auth::user()->rol_id == 1)
                            <th class="pa-table-header center" style="width:100px;">Fecha Ope.</th>
                            <th class="pa-table-header center" style="width:80px;">Monto</th>
                            @endif
                            @if (Auth::user()->rol_id == 3 || Auth::user()->rol_id == 1)
                            <th class="pa-table-header center" style="width:75px;">Foto</th>
                            @endif
                            @if (Auth::user()->rol_id == 3 || Auth::user()->rol_id == 1)
                                <th class="pa-table-header center" style="width:75px;">Examen</th>
                            @endif
                            @if (Auth::user()->rol_id == 3 || Auth::user()->rol_id == 1)
                                <th class="pa-table-header center" style="width:75px;">SCTR</th>
                            @endif
                            </thead>
                            <tbody>
                            <!-- ko if: !loadingParticipantes() && participantes().length < 1 -->
                            <tr>
                                <td colspan="15">
                                    No existen participantes registrados.
                                </td>
                            </tr>
                            <!-- /ko -->
                            <!-- ko if: loadingParticipantes() -->
                            <tr>
                                <td colspan="12" style="text-align: center">
                                    <img src="{{asset("assets/img/ajax-loader.gif")}}" alt=""/>
                                </td>
                            </tr>
                            <!-- /ko -->
                            <!--ko foreach: { data: participantes, as: 'paInfo' } -->
                            <tr class="participante"
                                data-bind="attr:{'data-id': paInfo.pa_id, 'data-ruc': paInfo.RUC, 'data-reg-id': paInfo.RegistroId}">
                                <td data-bind="text: $index() + 1"></td>
                                <td class="tdDni center">
                                    <span class="paDNI" style="cursor:pointer;"><!--ko text: paInfo.pa_dni--><!--/ko--></span>
                                    <input type="text" class="center paDNITextbox form-control input-sm editableField" style="display: none;  width: 100%;"/>
                                </td>
                                <td class="tdNombres">
                                    <span class="paNombres" style="cursor:pointer;"><!--ko text: paInfo.pa_nombres.toUpperCase()--><!--/ko--></span>
                                    <input type="text" class="center paNombresTextbox form-control input-sm editableField" style="display: none;  width: 100%;"/>
                                </td>
                                <td class="tdApePaterno">
                                    <span class="paApePaterno" style="cursor:pointer;"><!--ko text: paInfo.pa_apellido_paterno.toUpperCase()--><!--/ko--></span>
                                    <input type="text" class="center paApePaternoTextbox form-control input-sm editableField" style="display: none;  width: 100%;"/>
                                </td>
                                <td class="tdApeMaterno">
                                    <span class="paApeMaterno" style="cursor:pointer;"><!--ko text: paInfo.pa_apellido_materno.toUpperCase()--><!--/ko--></span>
                                    <input type="text" class="center paApeMaternoTextbox form-control input-sm editableField" style="display: none;  width: 100%;"/>
                                </td>
                                <td class="tdRazonSocial center">
                                    <span class="paRazonSocial" style="cursor:pointer;"><!--ko text: paInfo.RazonSocial == null ? paInfo.pa_nombres.toUpperCase() + ' ' + paInfo.pa_apellido_paterno.toUpperCase() + ' ' + paInfo.pa_apellido_materno.toUpperCase()  : paInfo.RazonSocial.toUpperCase()--><!--/ko--></span>
                                    <input type="text" class="center paRazonSocialTextbox  form-control input-sm editableField" style="display: none;  width: 100%;"/>
                                </td>
                                @if (Auth::user()->rol_id == 2 || Auth::user()->rol_id == 1)
                                <td class="tdRUC center" style="cursor:pointer;">
                                    <span class="paRUC"><!--ko text: paInfo.RUC == null ? '' : paInfo.RUC.toUpperCase()--><!--/ko--></span>
                                    <input type="text" class="center paRUCTextbox  form-control input-sm editableField" maxlength="11" style="display: none;  width: 100%;"/>
                                </td>
                                @endif
                                <td class="tdAlmacen">
                                    {{-- <select class="form-control paAlmacen" data-bind="value: paInfo.OperadorId">
                                        <option value="1">Ransa</option>
                                        <option value="2">Tramarsa</option>
                                        <option value="3">Ransa-Tramarsa</option>
                                    </select> --}}
                                    <div class="btn-group almacenesSeleccion">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <!--ko text: paInfo.koOperadorText --><!--/ko-->
                                        </button>
                                        <ul class="dropdown-menu">
                                          {{-- <li><a href="#" tabIndex="-1" data-value="1"><input type="checkbox"/>&nbsp;Ransa</a></li>
                                          <li><a href="#" tabIndex="-1" data-value="2"><input type="checkbox"/>&nbsp;Tramarsa</a></li>
                                          <li><a href="#" tabIndex="-1" data-value="4"><input type="checkbox"/>&nbsp;SLI</a></li> --}}
                                        </ul>
                                    </div>

                                </td>
                                <td class="center tdAsistencia">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label style="width:30px" data-bind="attr:{ class: paInfo.pa_asistencia == 1? 'btn btn-default btn-xs active paAsis' : 'btn btn-default btn-xs paAsis'}" data-toggle="tooltip" data-placement="left" title="Asistió">
                                            <input type="radio" name="options" class="optionAsis" autocomplete="off"> <span class="textAsis">SI</span>
                                        </label>
                                        <label style="width:30px" data-bind="attr:{ class: paInfo.pa_asistencia == 0? 'btn btn-default btn-xs active paAsis' : 'btn btn-default btn-xs paAsis'}"  data-toggle="tooltip" data-placement="right" title="Inasistió">
                                            <input type="radio" name="options" class="optionAsis" autocomplete="off"> <span class="textAsis">NO</span>
                                        </label>
                                    </div>
                                </td>
                                @if (Auth::user()->rol_id == 3 || Auth::user()->rol_id == 1)
                                <td  style="width: 70px" class="tdNota">
                                    <input type="text" data-bind="value: paInfo.pa_nota, disable: (paInfo.pa_asistencia == null || paInfo.pa_asistencia == 0)" class="center paNota form-control input-sm" maxlength="5"/>
                                </td>
                                @endif
                                <td  class="center tdNroOperacion" data-bind="attr:{'data-id': paInfo.DetOperacionId }" style="cursor:pointer;">
                                    <span class="paNroOperacion" style="cursor:pointer;"><!--ko text: paInfo.NroOperacion--><!--/ko--></span>
                                    <input type="text" class="center paNroOperacionTextbox form-control input-sm editableField" style="display: none; width: 50px;" />
                                </td>
                                @if (Auth::user()->rol_id == 2 || Auth::user()->rol_id == 1)
                                <td class="center tdFechaOperacion" style="cursor:pointer;">
                                    <span class="paFechaOperacion"><!--ko text: paInfo.NroOperacionFecha--><!--/ko--></span>
                                    <input type="text" class="center paFechaOperacionTextbox form-control input-sm editableField" style="display: none;  width: 100%;"/>
                                </td>
                                <td class="center tdMontoOperacion" >
                                    S/.<span class="paMontoOperacion" style="cursor:pointer;"><!--ko text: paInfo.NroOperacionMonto--><!--/ko--></span>
                                    <input type="text" class="center paMontoOperacionTextbox form-control input-sm editableField" style="display: none;  width: 30px;"/>
                                </td>
                                @endif
                                @if (Auth::user()->rol_id == 3 || Auth::user()->rol_id == 1)
                                <td data-bind="attr: {'data-id': paInfo.pa_foto}" class="center tdFoto">
                                    <span class="photoClip"><i class="photoClipIcon"></i></span>
                                    <img src="" class="paImgPhoto" name="photoImg" style="display: none"/>
                                    <button class="btn btn-default btn-xs paUploadPhoto" data-bind="disable: paInfo.pa_asistencia == 0"><i class="fa fa-upload"> </i></button>
                                    <form class="photoForm" style="display: none;">
                                    <input type="file" name="photoImg" class="uploadPhotoHidden" />
                                    </form>

                                    <button class="btn btn-default btn-xs paPhoto" data-bind="visible: paInfo.pa_foto != ''"><i class="fa fa-camera"> </i></button>
                                </td>
                                @endif
                                @if (Auth::user()->rol_id == 3 || Auth::user()->rol_id == 1)
                                <td class="center tdExamen">
                                    <span data-bind="attr:{'title': paInfo.pa_examen.replace('examenes/','')}"
                                          class="examenClip" data-toggle="tooltip" data-placement="left" style="cursor: pointer">
                                        <i class="examenClipIcon"></i>
                                    </span>
                                    <button class="btn btn-default btn-xs paUploadExamen"><i class="fa fa-upload"></i></button>
                                    <a data-bind="visible: paInfo.pa_examen != '', attr:{'href':  '../' + paInfo.pa_examen}" target="_blank" class="btn btn-default btn-xs paExamen"><i class="fa fa-image"></i></a>
                                    <form class="paExamenForm">
                                        <input type="file"
                                               class="uploadExamenHidden" name="examenData" style="display: none"/>
                                    </form>
                                </td>
                                @endif
                                @if (Auth::user()->rol_id == 3 || Auth::user()->rol_id == 1)
                                <td class="center tdSctr">
                                    <span data-bind="attr:{'title': paInfo.pa_sctr.replace('sctr/','')}"
                                          class="sctrClip" data-toggle="tooltip" data-placement="left" style="cursor: pointer">
                                        <i class="sctrClipIcon"></i>
                                    </span>
                                    <button class="btn btn-default btn-xs paUploadSctr"><i class="fa fa-upload"></i></button>
                                    <a data-bind="visible: paInfo.pa_sctr != '', attr:{'href':  '../' + paInfo.pa_sctr}" target="_blank" class="btn btn-default btn-xs paSctr"><i class="fa fa-image"></i></a>
                                    <form class="paSctrForm">
                                        <input type="file"
                                               class="uploadSctrHidden" name="sctrData" style="display: none"/>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            <!--/ko-->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="previewPhotoModal" tabindex="-2" role="dialog">
                        <div class="modal-dialog" role="document" style="width: 400px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title center" id="myModalLabel">Vista Previa</h4>
                                </div>
                                <div class="modal-body center">
                                    <img data-bind="attr: {'src': currentPhoto}" alt="" width="260" height="300"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-success" id="btnGenerarFicha"><i class="fa fa-file-excel-o"></i> Generar Ficha</button>
                    <br/>
                    <br/>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <!--ko if: guardardandoParticipantes()-->
                        <button class="btn btn-primary" disabled><i class="fa fa-spinner"></i> Cargando...</button>
                        <!--/ko-->
                        <!--ko ifnot: guardardandoParticipantes()-->
                        <button id="btnGuardarParticipantesBottom" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Guardar</button>
                        <!--/ko-->
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="detalleOperacionDialog" role="dialog" aria-hidden="true">
            <div class="modal-dialog" style="width: 800px">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title center">Consulta detalle de N° Opearcion</h4>
                    </div>
                    <div class="modal-body">
                        <h3 class="center">Detalles de N° Operacion</h3>
                        <p style="font-size: 30px;margin-top:-7px" class="center" data-bind="text: nroOperacion"></p>
                        <div id="detalleOperacionBody" class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <th class="pa-table-header center">Fecha</th>
                                {{--<th class="pa-table-header center">Hora</th>--}}
                                <th class="pa-table-header center">Monto</th>
                                <th class="pa-table-header center">Participantes</th>
                                <th class="pa-table-header center">Tipo pago</th>
                                </thead>
                                <tbody>
                                <td style="width: 120px"><input data-bind="value: fecha" type="text" id="fechaNroOperacion" class="form-control input-sm soloNumeros" style="width: 100px"/></td>
                                {{--<td style="width: 230px">--}}
                                {{--<input type="text" data-bind="value: horas" class="form-control input-sm soloNumeros horas center" style="width: 55px; display: inline" maxlength="2" max="12"/>--}}
                                {{--&nbsp;:&nbsp;--}}
                                {{--<input type="text" data-bind="value: minutos" class="form-control input-sm soloNumeros minutos center" style="width: 55px; display: inline" maxlength="2" max="12"/>--}}
                                {{--&nbsp;--}}
                                {{--<select  data-bind="value: apm" class="form-control input-sm soloNumeros apm" style="width: 65px; display: inline">--}}
                                {{--<option value="AM">AM</option>--}}
                                {{--<option value="PM">PM</option>--}}
                                {{--</select>--}}
                                {{--</td>--}}
                                <td style="width:100px"><input type="text"  data-bind="value: monto" class="form-control input-sm soloNumeros center monto" maxlength="8"/></td>
                                <td class="center" style="vertical-align: middle" data-bind="text: cantidadParticipantes"></td>
                                <td><select id="tipoPagoOptions" data-bind="value: tipoPago" class="form-control">
                                        <option value="D">Depósito</option>
                                        <option value="T">Transferencia</option>
                                    </select>
                                </td>
                                </tbody>
                            </table>
                            <div id="errores" style="display: none">
                                <label class="label label-danger">Ingrese todos los campos</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="confirm" data-bind="click: guardarDetalleOperacion">Guardar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="IngresoParticipantesDialog" role="dialog" aria-hidden="true">
            <div class="modal-dialog" style="width: 800px;height:1000px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title center">Registro Manual de Participantes</h4>
                    </div>
                    <div class="modal-body">
                        <div id="header" class="center" style="margin-bottom: 25px">
                            <div id="tipoRegistro" class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default active" id="btnRegistroJuridica">
                                    <input type="radio" name="options" id="opJuridica" autocomplete="off"> Jurídica
                                </label>
                                <label class="btn btn-default" id="btnRegistroNatural">
                                    <input type="radio" name="options" id="opNatural" autocomplete="off"> Natural
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="ruc" class="input-group">
                                <input type="text" class="form-control regRUC soloNumeros" placeholder="RUC" data-bind="value: ruc, event: { 'keyup': consultarRUC }" maxlength="11">
                                      <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" data-bind="click: consultarRUCButton"><i class="fa fa-search"></i> Consultar RUC</button>
                                      </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="razonSocial">
                                <input type="text" class="form-control regRazonSocial" placeholder="Razón Social"  data-bind="value: razonSocial" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="nroOperacion" class="input-group">
                                <input type="text" class="form-control regNroOperacion soloNumeros" data-bind="value: nroOperacion, event: { 'keyup': consultarNroOperacion }" placeholder="N° Operación">
                                  {{--<span class="input-group-btn">--}}
                                    {{--<button class="btn btn-default" type="button" data-bind="click: consultarNroOperacionButton"><i class="fa fa-search"></i> Validar N° Operacion</button>--}}
                                  {{--</span>--}}
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="monto">
                                <input type="text" class="form-control regMonto soloNumeros" data-bind="value: monto" placeholder="Monto">
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="almacen" class="btn-group" data-toggle="buttons">

                            </div>
                        </div>
                        <div id="participante">
                            <div class="form-group">
                                <div id="dni" class="input-group">
                                    <input type="text" class="form-control regDNI soloNumeros" data-bind="value: dni, event: { 'keyup': consultarDNI }" placeholder="DNI/C.Extrajería">
                                      <span class="input-group-btn">
                                        <button class="btn btn-default" data-bind="click: consultarDNIButton" type="button"><i class="fa fa-search"></i> Consultar DNI</button>
                                      </span>
                                </div>
                            </div>
                            <div class="form-group nombres">
                                <input type="text" class="form-control paData" data-bind="value: nombres" placeholder="Nombres" disabled/>
                            </div>
                            <div class="form-group apePaterno">
                                <input type="text" class="form-control paData" data-bind="value: ape_paterno" placeholder="Apellido Paterno" disabled/>
                            </div>
                            <div class="form-group apeMaterno">
                                <input type="text" class="form-control paData" data-bind="value: ape_materno" placeholder="Apellido Materno" disabled/>
                            </div>
                        </div>
                        <div id="erroresParticipante" style="display: none">
                            <label class="label label-danger">Existen campos vacíos o inválidos</label>
                            <label class="label label-danger" data-bind="visible: !IsRucValid() && !supressValidationMessages()">El ruc no es válido</label>
                            <label class="label label-danger" data-bind="visible: !IsDniValid() && !supressValidationMessages()">El dni no es válido</label>
                        </div>
                        <div id="registroValidations" style="display: none">
                            <div class="pull-left">
                                <div class="label label-danger" >Lo sentimos, no se puedo completar su registro, no hay cupos disponibles para : <br/>
                                    <!-- ko foreach: { data: validationRegistros, as: 'val' } -->
                                    <!--ko text: val.fecha--><!--/ko-->&nbsp; <!--ko text: val.turno--><!--/ko-->
                                    <!--/ko-->
                                </div>
                            </div>
                        </div>
                        <div id="registroValidationsParticipantes" style="display:none;">
                            <div class="pull-left">
                                <div class="validation-error" >El DNI :  <br/>
                                    <!-- ko foreach: { data: validationParticipante, as: 'val' } -->
                                    <!--ko text: val.pa_dni--><!--/ko--> <br/> ya se encuentra programado para la fecha y turno:
                                    <!--ko text: val.fecha--><!--/ko-->&nbsp; <!--ko text: val.turno--><!--/ko-->
                                    <!--/ko-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="confirm" data-bind="click: registrarParticipante">Guardar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop
@section('scriptsSection')
    {{HTML::script('assets/bootstrap-datepicker/js/bootstrap-datepicker.js')}}
    {{HTML::script('assets/bootstrap-select/js/bootstrap-select.min.js')}}
    @include('intranet.participantes.participantesJS')
@stop
