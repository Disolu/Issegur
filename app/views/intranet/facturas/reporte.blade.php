@extends('intranet.layout.site')
@section('stylesSection')
    {{ HTML::style('assets/bootstrap-datepicker/css/datepicker.css') }}
@stop
@section('main')
    <div id="facturacion-reporte" class="container facturacion-container">
        <h1>Reporte Global</h1>
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label>Desde</label>
                    <input type="text"  data-name="inidate" placeholder="01/03/2016" class="form-control datepicker">
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label>Hasta</label>
                    <input type="text" data-name="enddate" placeholder="31/03/2016" class="form-control datepicker">
                </div>
            </div>
            <div class="col-xs-2">
                <button class="btn btn-default" data-bind="click : consultar">Consultar</button>
            </div>
        </div>
        <!-- ko if: loaded -->
        <div class="row clearfix">
            <div class="col-xs-offset-2 col-xs-8 border">
                <span data-bind="text :total"></span> Facturas emitidas para el rango solicitado
            </div>
        </div>
        <br>
        <div class="row clearfix">
            <div class="col-xs-offset-4 col-xs-8 text-right">
                <div class="row clearfix">
                    <div class="col-xs-4">Total </div>
                    <div class="col-xs-4 border">S/. <span data-bind="text :valor"></span></div>
                </div>
            </div>
        </div>
        <br>
        <div class="row clearfix">
            <div class="col-xs-offset-2 col-xs-8 border">
                <span data-bind="text :anuladas"></span> Facturas Anuladas para el rango solicitados
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-xs-12 text-right">
            <button class="btn btn-default" data-bind="click : reporte">Generar Cuadro</button>
            </div>
        </div>
        <!-- /ko -->
    </div>
@stop
@section('scriptsSection')
    {{HTML::script('assets/bootstrap-datepicker/js/bootstrap-datepicker.js')}}
    @include('intranet.facturas.reporteJS')
@stop
