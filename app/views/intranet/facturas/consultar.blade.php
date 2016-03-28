@extends('intranet.layout.site')
@section('main')
    <div id="facturacion-consultar" class="container facturacion-container">
        <h1>Consultar Factura</h1>
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label>RUC</label>
                    <input type="text"  data-bind="value :ruc" placeholder="Ingrese RUC" class="form-control soloNumeros">
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label>Empresa</label>
                    <input type="text" data-bind="value :empresa" placeholder="Nombre de Empresa" class="form-control">
                </div>
            </div>
            <div class="col-xs-2">
                <button class="btn btn-default" data-bind="click : search">Buscar</button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Factura</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Emitido</th>
                        </tr>
                    </thead>
                    <tbody data-bind="foreach: facturas">
                        <tr class="type" >
                            <td>
                                <a data-bind="attr :{href:'ver/'+id}" target="_blank">Nº <span data-bind="text :number"></span></a>
                            </td>
                            <td data-bind="text :date"></td>
                            <td>S./ <span data-bind="text :data.total"></span></td>
                            <td><span data-bind="text :user.username"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
@section('scriptsSection')
    @include('intranet.facturas.consultarJS')
@stop
