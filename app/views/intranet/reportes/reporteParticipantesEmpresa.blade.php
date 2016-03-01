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
                    <h5>Total de registros: <strong></strong></h5>
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

        <div class="row" style="margin-top:10px;">
            <div class="col-md-12">
                <input type="text" class="form-control" autocomplete="off"  
                                    id="razonSocialText" data-bind="value: razonSocial">
                <input type="text" class="form-control soloNumeros" autocomplete="off" 
                                    id="rucText" maxlength="11" style="display:none;">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
      
            </div>
        </div>
    </div>
@stop
@section('scriptsSection')
    @include('intranet.reportes.reporteParticipantesEmpresaJS')
@stop
