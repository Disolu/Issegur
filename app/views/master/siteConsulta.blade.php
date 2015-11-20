<!DOCTYPE html>
<html>
<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>Consulta | Instituto Superior de Seguridad (Issegur)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}

    {{ HTML::style('assets/css/font-awesome.min.css') }}

    {{ HTML::style('assets/app.css') }}

    {{ HTML::style('assets/bootstrap-datepicker/css/datepicker.css') }}

    @yield('stylesSection')
</head>
<body>

    @yield('main')

    <!-- BEGIN: PAGE SCRIPTS -->
    <!-- knockout -->
    {{--{{HTML::script('http://knockoutjs.com/downloads/knockout-3.3.0.js')}}--}}
    {{HTML::script('assets/js/knockout-3.3.0.js')}}
    <!-- jQuery -->
    {{--{{HTML::script('http://code.jquery.com/jquery-2.1.4.min.js')}}--}}
    {{--{{HTML::script('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js')}}--}}
    {{HTML::script('assets/vendor/jquery/jquery-2.1.4.min.js')}}
    {{HTML::script('assets/vendor/jquery/jquery_ui/jquery-ui.min.js')}}

    <!-- Theme Javascript -->
    {{--{{HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js')}}--}}
    {{HTML::script('assets/bootstrap/js/bootstrap.min.js')}}
    {{HTML::script('assets/bootstrap-datepicker/js/bootstrap-datepicker.js')}}

    {{HTML::script('assets/app.js')}}

    <!-- Page Javascript -->
    <script type="text/javascript">
        var path = GlobalParameters.appPath;
    </script>

    <!-- END: PAGE SCRIPTS -->
    @yield('scriptsSection')
</body>