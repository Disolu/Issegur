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
    <div id="reporteParticipantesEmpresa" class="container">
        <ul class="breadcrumb">
            <li><a href="{{URL::to('intranet/reportes')}}">Reportes</a> </li>
            <li class="active">Reporte de Participantes por Empresa</li>
        </ul>
        <div class="row">
            <div class="col-md-9">
                <div id="reportedHeader" class="pull-left" style="margin-bottom: 30px;">
                    <h1>Reporte de Participantes por Empresa</h1>
                </div>
            </div>
            <div class="col-md-3">
                <div id="reporteTotalRegistros" class="pull-right" style="margin-top: 30px;">
                    <h5>Total de Participantes: <strong data-bind="text: participantes().length"></strong></h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline">       
                    <div class="pull-left">
                        <div class="btn-group chkOptions" data-toggle="buttons">
                            <label class="btn btn-default active searchOption">
                                <input type="radio" name="options" id="chkEmpresa"> 
                                Razón Social
                            </label>
                            <label class="btn btn-default searchOption">
                                <input type="radio" name="options" id="chkRuc">
                                RUC
                            </label>
                        </div>  
                    </div>  
                
                    <div class="pull-right">
                        <button type="button"
                            class="btn btn-primary" data-bind="click: onBuscarButtonClick">Buscar</button>
                    </div> 
                </form>
            </div>
        </div>

        <div class="row" style="margin-top:10px;margin-bottom: 10px;">
            <div class="col-md-12">
                <input type="text" class="form-control" autocomplete="off"  
                                    id="razonSocialText" data-bind="value: razonSocial">
                <input type="text" class="form-control soloNumeros" autocomplete="off" 
                                    id="rucText" data-bind="value: ruc" maxlength="11" style="display:none;">
            </div>
        </div>

        <div class="row" data-bind="visible: currentEmpresa() != null">
            <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading center">
                            <!--ko if: currentEmpresa() != null-->
                            <!--ko text: currentEmpresa().emp_ruc + ' - ' + currentEmpresa().emp_razon_social--><!--/ko-->
                            <!--/ko-->
                        </div>
                        <div class="panel-body">
                        <!--ko if: solicitantes().length > 0-->
                            <fieldset>
                                <legend>Último Solicitante</legend>
                                <p>
                                    <b>Nombre: </b>
                                    <span data-bind="text: solicitantes()[0].soliNombre"></span>
                                </p>
                                <p>
                                    <b>Email:</b>
                                    <span data-bind="text: solicitantes()[0].soliEmail"></span>
                                </p>
                                <p>
                                    <b>Teléfono:</b>
                                    <span data-bind="text: solicitantes()[0].soliTelefono"></span>
                                </p>
                            </fieldset>
                            <!--ko if: solicitantes().length > 1-->
                            <fieldset>
                                <legend>Otros Datos</legend>
                                <!--ko if: hayAlgunEmailExtra-->
                                <p>
                                    <b>Email:</b>
                                <!--ko foreach: { data: solicitantes, as: 'sol' } -->
                                <!--ko if: $index() > 0-->                                       
                                    <!-- ko if: sol.soliEmail != null -->
                                        <span data-bind="text: sol.soliEmail"></span>
                                        <!--ko if: $index() != $parent.solicitantesLength() - 1-->
                                            <span> &nbsp; </span>
                                        <!--/ko-->                                            
                                    <!--/ko-->
                                <!--/ko-->
                                <!--/ko-->
                                </p>
                                <!--/ko-->
                                
                                <!--ko if: hayAlgunTelefonoExtra-->
                                <p>                                    
                                    <b>Teléfono:</b>
                                    <!--ko foreach: {data: solicitantes , as: 'sol'} -->
                                    <!--ko if: $index() > 0-->
                                    <!-- ko if: sol.soliTelefono != null -->
                                        <span data-bind="text: sol.soliTelefono"></span>
                                        <!--ko if: $index() != $parent.solicitantesLength() - 1-->
                                                <span> &nbsp; </span>
                                        <!--/ko-->
                                    <!--/ko-->
                                    <!--/ko-->
                                    <!--/ko-->
                                </p>  
                                <!--/ko-->
                            </fieldset>
                            <!--/ko--> 
                        <!--/ko--> 
                        <!--ko ifnot: solicitantes().length > 0-->
                        <p>No hay solicitantes para mostrar.</p>
                        <!--/ko-->   
                        </div>
                    </div>
            </div>
        </div>

        <div class="row" style="margin-top:10px;" data-bind="visible: participantes().length != 0">
            <div class="col-md-12">
                <div id="participantesBody" class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <th class="pa-table-header">N°</th>
                            <th class="pa-table-header center">DNI</th>
                            <th class="pa-table-header">Nombres</th>
                            <th class="pa-table-header">Ape. Paterno</th>
                            <th class="pa-table-header">Ape. Materno</th>
                            <th class="pa-table-header">Nota</th>
                        </thead>
                        <tbody>
                            <!-- ko if: participantes().length < 1 -->
                            <tr>
                                <td colspan="6">
                                    No existen participantes registrados.
                                </td>
                            </tr>
                            <!-- /ko -->
                            <!--ko foreach: { data: participantes, as: 'paInfo' } -->
                            <tr>
                                <td data-bind="text: $index() + 1"></td>
                                <td data-bind="text: paInfo.pa_dni"></td>
                                <td data-bind="text: paInfo.pa_nombres.toUpperCase()"></td>
                                <td data-bind="text: paInfo.pa_apellido_paterno.toUpperCase()"></td>
                                <td data-bind="text: paInfo.pa_apellido_materno.toUpperCase()"></td>
                                <td data-bind="text: paInfo.pa_nota"></td>
                            </tr>
                            <!-- /ko -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scriptsSection')
    @include('intranet.reportes.reporteParticipantesEmpresaJS')
@stop
