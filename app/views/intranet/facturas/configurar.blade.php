@extends('intranet.layout.site')
@section('main')
    <div id="facturacion-consultar" class="container facturacion-container">
        <h1>Configurar Factura</h1>
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <input type="text"  data-bind="value :serie"  class="form-control soloNumeros">
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <input type="text"  data-bind="value :number" class="form-control soloNumeros">
                </div>
            </div>
            <div class="col-xs-2">
                <button class="btn btn-default" data-bind="click : save">Guardar</button>
            </div>
        </div>
    </div>
@stop
@section('scriptsSection')
    @include('intranet.facturas.configurarJS')
@stop
