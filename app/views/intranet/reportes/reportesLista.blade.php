@extends('intranet.layout.site')
@section('stylesSection')
    <!--{{HTML::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css')}}-->
    
    <style>
        div.ui-datepicker{
            font-size:12px;
        }
    </style>
@stop
@section('main')
    <div id="reportes" class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="reportesdHeader" class="pull-left">
                    <h1>Reportes</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="reportesLista">
                    <div class="list-group">
                        <a role="button" class="list-group-item" href="{{URL::to('intranet/reportes/participantesPorOperador')}}">
                           <p class="reportTitle list-group-item-heading"> Reporte de Participantes Por Operador</p>
                        </a>
                        <a role="button" class="list-group-item" href="{{URL::to('intranet/reportes/participantesPorEmpresa')}}">
                            <p class="reportTitle list-group-item-heading"> Reporte de Participantes Por Empresa</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
