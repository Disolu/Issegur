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

    @yield('stylesSection')
</head>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <img alt="Issegur" src="{{asset("assets/img/logo/logo.png")}}" style="max-width:45px; max-height:45px;margin-top:-10px">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="#"><i class="fa fa-home"></i></a></li>
                    <li><a id="calendarioLink" href="{{URL::to('intranet/calendario')}}">Calendario </a></li>
                    {{--<li><a id="participantesLink" href="{{URL::to('intranet/participantes')}}">Participantes </a></li>--}}
                    <li><a href="#">Administración</a></li>
                    <li><a id="reportesLink" href="{{URL::to('intranet/reportes')}}">Reportes</a></li>
                    <li><a id="reprogramacionLink" href="{{URL::to('intranet/reprogramacion')}}">Reprogramar
                    <li><a id="facturasLink" href="{{URL::to('intranet/facturas')}}">Facturacion</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->username}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{URL::to('intranet/cerrarSesion')}}">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
             </div>
        </div>
    </nav>
    @yield('main')

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

        if (currentUrl.indexOf("intranet/participantes") >= 0) {
            $("#calendarioLink").closest("li").addClass("active");
        }
        else if (currentUrl.indexOf("/calendario") >= 0) {
            $("#calendarioLink").closest("li").addClass("active");
        }
        else if (currentUrl.indexOf("/reportes") >= 0) {
            $("#reportesLink").closest("li").addClass("active");
        }
        else if (currentUrl.indexOf("/reprogramacion") >= 0) {
            $("#reprogramacionLink").closest("li").addClass("active");
        }
        else if (currentUrl.indexOf("/facturas") >= 0) {
            $("#facturasLink").closest("li").addClass("active");
        }
        else{
            $("#calendarioLink").closest("li").addClass("active");
        }
    </script>

    <!-- END: PAGE SCRIPTS -->
    @yield('scriptsSection')
</body>
