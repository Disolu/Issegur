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
    <div id="reporteParticipantesOperador" class="container">
        <ul class="breadcrumb">
            <li><a href="{{URL::to('intranet/reportes')}}">Reportes</a> </li>
            <li class="active">Reporte de Participantes por Empresa</li>
        </ul>
        <div class="row">
            <div class="col-md-9">
                <div id="reportedHeader" class="pull-left" style="margin-bottom: 30px;">
                    <h1>Reporte de Participantes por Operador</h1>
                </div>
            </div>
            <div class="col-md-3">
                <div id="reporteTotalRegistros" class="pull-right" style="margin-top: 30px;">
                    <h5>Total de registros: <strong data-bind='text: totalRows'></strong></h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                        <form class="form-inline">
                            <div class="form-group" style="margin-right: 10px">
                                <label for="inputDesdeFecha"> Desde </label>
                                <input type="text"
                                       id="inputDesdeFecha"
                                       class="form-control"
                                       data-bind="value: fechaDesde"/>
                                <br/>
                                <label  class="label label-danger" data-bind="visible: !IsFechaDesdeSupplied() && !suppressValidationMessages()">Ingrese una fecha inicial</label>
                            </div>
                            <div class="form-group" style="margin-right: 10px">
                                <label for="inputHastaFecha"> Hasta </label>
                                <input type="text"
                                       id="inputHastaFecha"
                                       class="form-control"
                                       data-bind="value: fechaHasta"/>
                                <br/>
                                <label  class="label label-danger" data-bind="visible: !IsFechaHastaSupplied() && !suppressValidationMessages()">Ingrese una fecha final</label>
                            </div>

                            <div class="form-group" style="margin-right: 10px">
                                <label for="cboOperadores"> Operador </label>
                                <select id="cboOperadores"
                                        class="form-control"
                                        data-bind="options: operadores,
                                                optionsText: 'op_nombre',
                                                optionsValue: 'op_id',
                                                value: operadorId,
                                                optionsCaption: 'Elegir...'">

                                </select>
                                <br/>
                                <label  class="label label-danger" data-bind="visible: !IsOperadorSupplied() && !suppressValidationMessages()">Seleccione un operador</label>
                            </div>

                            <button type="button"
                                    class="btn btn-primary"
                                    data-bind="click: onBuscarButtonClick">Buscar</button>
                        </form>
                    <div class="pull-right">



                        <label id="validationMessages" class="label label-danger" data-bind="visible: !isSearchValid() && !suppressValidationMessages()">La fecha inicial no puede ser mayor a la fecha final</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
            <!-- ko if: pageNumberArray().length > 3 -->
                <nav class="center paginationContainer">
                    <ul id="pager" class="pagination">
                        <!-- ko foreach: { data : pageNumberArray , as : 'page' } -->
                                <!-- ko if: page.number == $parent.currentPageNumber() -->                                
                                    <li class="active"><a href="#" data-bind="html: page.number, 
                                                                              click: $parent.pageClick,
                                                                              visible: page.visible"></a></li>
                                <!-- /ko -->
                                <!-- ko ifnot: page.number == $parent.currentPageNumber() -->
                                    <!-- ko if: page.number <= $parent.currentPageNumber() - 6 --> 
                                        <!-- ko if: numberOfPages() - page.number  < globalMaxVisiblePages -->  
                                        <li><a href="#" data-bind="html: page.number, click: $parent.pageClick, 
                                                                                visible: true"></a></li>
                                         <!-- /ko --> 
                                         <!-- ko ifnot: numberOfPages() - page.number  <= globalMaxVisiblePages -->  
                                        <li><a href="#" data-bind="html: page.number, click: $parent.pageClick, 
                                                                                visible: false"></a></li>
                                         <!-- /ko --> 
                                    <!-- /ko -->
                                    <!-- ko if: page.number > $parent.currentPageNumber() + 14-->  
                                        <!-- ko if: page.number  <= globalMaxVisiblePages -->  
                                        <li><a href="#" data-bind="html: page.number, click: $parent.pageClick, 
                                                                                visible: true"></a></li>  
                                        <!-- /ko -->                                        
                                    <!-- /ko -->
                                    <!-- ko ifnot: (page.number > $parent.currentPageNumber() + 14) || (page.number <= $parent.currentPageNumber() - 6 )-->  
                                    <li><a href="#" data-bind="html: page.number, click: $parent.pageClick, 
                                                                            visible: page.visible"></a></li>  
                                    <!-- /ko -->
                                <!-- /ko -->
                        <!-- /ko -->
                    </ul>
                </nav>
            <!-- /ko -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="participantesBody" class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <th class="pa-table-header center" style="width: 70px;">N°</th>
                        <th class="sortable pa-table-header center" data-sort="pa_dni"
                        style="width: 120px;">DNI <i class="fa fa-sort"></i></th>
                        <th class="sortable pa-table-header" data-sort="pa_apellido_paterno">Participante <i class="fa fa-sort"></i></th>
                        <th class="sortable pa-table-header center" data-sort="RazonSocial">Empresa <i class="fa fa-sort"></i></th>
                        </thead>
                        <tbody>
                        <!-- ko if: !loadingParticipantes() && participantes().length < 1 -->
                        <tr>
                            <td colspan="5">
                                No hay participantes para mostrar.
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
                        <tr class="participante data-row" data-bind="attr:{'data-id': paInfo.pa_id}">
                            <td data-bind="text: index" class="center"></td>
                            <td data-bind="text: paInfo.pa_dni" class="center"></td>
                            <td data-bind="text: paInfo.pa_nombres + ' ' + paInfo.pa_apellido_paterno + ' ' + paInfo.pa_apellido_materno"></td>
                            <td data-bind="text: paInfo.RazonSocial" class="center"></td>
                        </tr>
                        <!--/ko-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scriptsSection')
    @include('intranet.reportes.reporteParticipantesOperadorJS')
@stop
