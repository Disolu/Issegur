@extends('intranet.layout.site')
@section('main')
    <div id="facturacion" class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="facturacionHeader" class="pull-left">
                    <h1>Facturación</h1>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="col-xs-6 col-xs-offset-3">
                    <div class="text-center">
                        <a class="btn btn-block btn-default" href="{{URL::to('intranet/facturas/generar')}}">Generar Facturas</a>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a class="btn btn-block btn-default" href="{{URL::to('intranet/facturas/consultar')}}">Consultar Facturas</a>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a class="btn btn-block btn-default" href="{{URL::to('intranet/facturas/reporte')}}">Reporte de Facturas</a>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a class="btn btn-block btn-default" href="{{URL::to('intranet/facturas/configurar')}}">Configurar Facturas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
@section('scriptsSection')
@stop
