@extends('intranet.layout.site')
@section('main')
    <div id="facturacion-generar" class="container facturacion-container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="row">
                    <div class="col-xs-5">
                        <div class="row">
                            <div class="col-xs-4">
                                <button class="btn btn-default" data-bind="click: newcompany">Nueva Empresa</button>
                            </div>
                            <div class="col-xs-8">
                                <h2>ISSEGUR</h2>
                                <p data-bind="text: currentday"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="row">
                            <div class="text-right">
                                <div class="fnumber">
                                    Factura N <label>{{$serie->serie}}</label>
                                    <label>{{$serie->number}}</label>
                                </div>
                                <div class="type" data-bind="foreach: types">
                                    <label>
                                        <input type="radio" value="1" name="exonerada"
                                        data-bind="value: id, checked: $root.type" />
                                        <span data-bind="text: name"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>RUC</label>
                            <input id="ruc" name="ruc" class="soloNumeros form-control" placeholder="Ingrese RUC" maxlength="11" data-bind="value: ruc, event: { 'keyup': loadbyruc }">
                        </div>
                        <div class="form-group">
                            <label>Dirección</label>
                            <input id="address" name="address" class="form-control" placeholder="Dirección de la empresa" data-bind="value: address" readonly>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Empresa</label>
                            <input id="name" name="name" class="form-control" placeholder="Nombre de Empresa" data-bind="value: empresa">
                        </div>
                        <a class="btn btn-default" data-bind="click: editcompany">Editar</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Cant</th>
                                    <th>Descripcion</th>
                                    <th>P. UNIT</th>
                                    <th>Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="input-line">
                                    <td>
                                        <input type="text" name="cantidad[]" class="form-control soloNumeros" data-bind="value : cant">
                                    </td>
                                    <td>
                                        <textarea class="form-control" data-bind="value : description"></textarea>
                                    </td>
                                    <td>
                                        <input type="text" name="punit[]" class="form-control soloNumeros" data-bind="value : price">
                                    </td>
                                    <td>
                                        <input type="text" name="ptotal[]" class="form-control" readonly data-bind="value: stotal">
                                    </td>
                                </tr>
                                <tr class="input-line">
                                    <td colspan="2" class="exoneracion" data-bind="css: {toshow: type() == '1'} ">
                                        <span>Exonerado de IGV segun el D.L. 821</span>
                                    </td>
                                    <td>Sub Total</td>
                                    <td>
                                        <input type="text" name="stotal" class="form-control" readonly data-bind="value: stotal">
                                    </td>
                                </tr>
                                <tr class="input-line">
                                    <td colspan="2"></td>
                                    <td>IGV 18%</td>
                                    <td>
                                        <input type="text" name="igv" class="form-control" readonly data-bind="value: igv">
                                    </td>
                                </tr>
                                <tr class="input-line">
                                    <td colspan="2">
                                        <textarea class="form-control" data-bind="value : letters"></textarea>
                                    </td>
                                    <td>Total </td>
                                    <td>
                                        <input type="text" name="total" class="form-control" readonly data-bind="value: total">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="actions text-right">
                            <a class="btn btn-default" href="{{URL::to('intranet/facturas')}}"> Cancelar</a>
                            <button class="btn btn-default" data-bind="click: print">Imprimir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editcreate" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title center">Nuevo/Editar Empresa</h4>
                </div>
                <div class="modal-body">
                    <div class="row center">
                        <div class="col-xs-12">
                           <input type="hidden"  data-bind="value: cobj.id" >
                            <div class="form-group">
                               <input type="text" class="form-control soloNumeros" data-bind="value: cobj.ruc" placeholder="RUC">
                            </div>
                            <div class="form-group">
                               <input type="text" class="form-control" data-bind="value: cobj.name" placeholder="Razon social">
                            </div>
                            <div class="form-group">
                               <input type="text" class="form-control" data-bind="value: cobj.address" placeholder="Dirección">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bind="click: createedit" id="confirm">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalerror" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title center">Complete todos los Campos</h4>
                </div>
            </div>
        </div>
    </div>

@stop
@section('scriptsSection')
    @include('intranet.facturas.generarJS')
@stop
