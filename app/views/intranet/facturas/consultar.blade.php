@extends('intranet.layout.site')
@section('main')
    <div id="facturacion-consultar" class="container facturacion-container">
        <h1>Consultar Factura</h1>
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label>RUC</label>
                    <input type="text"  data-bind="value :ruc,event: { 'keyup': check }" placeholder="Ingrese RUC" class="form-control soloNumeros">
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label>Empresa</label>
                    <input type="text" data-bind="value :empresa,event: { 'keyup': check }" placeholder="Nombre de Empresa" class="form-control">
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
                                <a data-bind="click : $parent.load" href="#">Nº <span data-bind="text :number"></span></a>
                            </td>
                            <td data-bind="text :date"></td>
                            <td>S./ <span data-bind="text :data.total"></span></td>
                            <td>
                                <span data-bind="text :user.username"></span>
                                <span class="anulado" data-bind="visible: estado == 0">Anulado</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="pmodal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Factura
                <!-- ko if: viewfactura -->
                    <span data-bind="text : viewfactura().number"></span>
                <!-- /ko -->
            </h4>
          </div>
          <div class="modal-body">
              <!-- ko if: viewfactura -->
                    <div class="row clearfix">
                        <div class="col-xs-2">
                            <br>
                            <img src="{{asset('assets/img/logo/logo.png')}}" class="img-responsive">
                        </div>
                        <div class="col-xs-6 text-center">
                            <h1>ISSEGUR</h1>
                            <p>INSTITUTO SUPERIOR DE SEGURIDAD Y CIENCIAS APLICADAS</p>
                            <p>Calle Los Tulipanes Mz N Lote 18</p>
                            <p>Asociacion de Viv. san Francisco - Ate - Lima - Lima</p>
                            <p>Telf: 349-5709 / Cel: 940-242-718</p>
                        </div>
                        <div class="col-xs-3">
                            <br>
                            <div class="number text-center">
                                <div class="first">RUC: 20511097321</div>
                                <div class="second">FACTURA</div>
                                <div class="third">
                                    <span data-bind="text : viewfactura().number"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row clearfix">
                        <div class="col-xs-12">
                            <div>Lima <span data-bind="text : viewfactura().date"></span></div>
                            <div>Señores <span data-bind="text : viewfactura().empresa"></span></div>
                            <div>Dirección <span data-bind="text : viewfactura().direccion"></span></div>
                            <div>RUC <span data-bind="text : viewfactura().ruc"></span></div>
                        </div>
                    </div>
                    <br>
                    <div class="row clearfix">
                        <div class="col-xs-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Cant</th>
                                        <th class="text-center">Descripcion</th>
                                        <th class="text-center">P. UNIT</th>
                                        <th class="text-center">Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="input-line">
                                        <td>
                                            S/. <span data-bind="text : viewfactura().data.cant"></span>
                                        </td>
                                        <td>
                                            <span data-bind="text : viewfactura().data.description"></span>
                                        </td>
                                        <td class="text-right">
                                            S/. <span data-bind="text : viewfactura().data.price"></span>
                                        </td>
                                        <td class="text-right">
                                            S/. <span data-bind="text : viewfactura().data.stotal"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" rowspan="3">
                                            <span data-bind="text : viewfactura().letras"></span>
                                        </td>
                                        <td class="text-right">Sub Total</td>
                                        <td class="text-right">
                                            S/. <span data-bind="text : viewfactura().data.stotal"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">IGV 18%</td>
                                        <td class="text-right">
                                            S/. <span data-bind="text : viewfactura().data.igv"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">Total </td>
                                        <td class="text-right">
                                        S/. <span data-bind="text : viewfactura().data.total"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!-- /ko -->
          </div>
          @if($user->rol_id == 1)
            <!-- ko if: viewfactura().estado -->
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" data-bind="click : anular">Anular</button>
              </div>
            <!-- /ko -->
          @endif
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

    @stop
@section('scriptsSection')
    @include('intranet.facturas.consultarJS')
@stop
