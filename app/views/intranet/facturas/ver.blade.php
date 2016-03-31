<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    <!-- Meta, title, CSS, favicons, etc. -->

    <title>Intranet | Instituto Superior de Seguridad (Issegur)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}

    {{HTML::style('assets/css/font-awesome.min.css') }}

    {{HTML::style('assets/app.css') }}

    <!--Toastr -->
    <!--{{HTML::style('http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css')}}-->
    {{HTML::style('assets/vendor/toastr/toastr.min.css')}}

    <style>
        .container{
            width: 800px;
        }
        p{
            font-size: 11px;
            margin: 0px;
        }
        .number{
            border:2px solid #000;
            border-radius: 10px;
        }

        .number .second,
        .number .third{
            padding: 5px 0px;
        }

        .number .second{
            background: rgb(3, 48, 118);
            color:#fff;
        }
        .table thead {
            background: #033076;
        }
        .table thead th {
            color: #fff;
            font-weight: normal;
            text-transform: uppercase;
        }
        .input-line{
            height: 100px;
        }
    </style>
    <style type="text/css" media="print">
        .no-print{
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">

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
                    <div class="third">{{$factura->number}}</div>
                </div>
            </div>
        </div>
        <br>
        <div class="row clearfix">
            <div class="col-xs-12">
                <div>Lima {{$factura->date}}</div>
                <div>Señores {{$factura->empresa}}</div>
                <div>Dirección {{$factura->direccion}}</div>
                <div>RUC {{$factura->ruc}}</div>
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
                        @foreach($factura->data['items'] as $item)
                            <tr class="input-line">
                                <td>S/. {{$item['cant']}}</td>
                                <td>{{$item['description']}}</td>
                                <td class="text-right">S/. {{$item['price']}}</td>
                                <td class="text-right">S/. {{$item['ptotal']}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" rowspan="3">{{$factura->letras}}</td>
                            <td class="text-right">Sub Total</td>
                            <td class="text-right">S/. {{$factura->data['stotal']}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">IGV 18%</td>
                            <td class="text-right">S/. {{$factura->data['igv']}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">Total </td>
                            <td class="text-right">S/. {{$factura->data['total']}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row no-print">
            <div class="col-xs-12 text-right">
                <a class="btn btn-default" onclick="window.print();">Imprimir</a>
            </div>
        </div>
    </div>


<!-- BEGIN: PAGE SCRIPTS -->
    <!-- knockout -->
    {{HTML::script('assets/js/knockout-3.3.0.js')}}
    <!-- jQuery -->
    {{HTML::script('assets/vendor/jquery/jquery-2.1.4.min.js')}}
    {{HTML::script('assets/vendor/jquery/jquery_ui/jquery-ui.min.js')}}

    <!-- Theme Javascript -->
    {{HTML::script('assets/bootstrap/js/bootstrap.min.js')}}

    {{HTML::script('assets/js/js-cookie.js')}}

    {{HTML::script('assets/js/JIC.js')}}

    <!--Toastr-->
    <!--{{ HTML::script('http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js') }}-->
    {{HTML::script('assets/vendor/toastr/toastr.min.js')}}

    <!--Bootstrap TypeAhead-->
    {{HTML::script('assets/bootstrap-typeahead/bootstrap3-typeahead.min.js')}}

    {{HTML::script('assets/app.js')}}

    <!-- Page Javascript -->
    <script type="text/javascript">
        var path = GlobalParameters.appPath;
        var globalPageSize = GlobalParameters.globalPageSize;
        var globalMaxVisiblePages = GlobalParameters.globalMaxVisiblePages;

        var currentUrl = window.location.href;
    </script>


</body>
