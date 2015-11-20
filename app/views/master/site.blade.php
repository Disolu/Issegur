<!DOCTYPE html>

<html lang="es-ES" xmlns="http://www.w3.org/1999/xhtml" class="csstransforms csstransforms3d csstransitions js csstransforms3d csstransitions js no-touch cssanimations csstransitions">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Registro | Instituto Superior de Seguridad (Issegur)</title>

    {{HTML::style('assets/app.css')}}

    <!-- Site Stylesheets-->
    {{ HTML::style('assets/fonts.googleapis.com/cssff98.css?family=Open+Sans:300,400,600,700') }}

    {{ HTML::style('assets/demo/form_plugins/css/1.css') }}
    <!-- Theme CSS -->
    {{ HTML::style('assets/skin/default_skin/css/theme.css') }}

    <!-- Admin Forms CSS -->
    {{ HTML::style('assets/admin-tools/admin-forms/css/admin-forms.css') }}
    <!-- Favicon -->
    {{ HTML::style('assets/img/favicon.ico') }}


    @yield('StylesContainer')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    {{HTML::script('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')}}
    {{HTML::script('https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js')}}
    <![endif]-->

</head>
<body>
  @yield('main')

  <!-- Seccion al final -->
  <!-- knockout -->
  {{HTML::script('assets/js/knockout-3.3.0.js')}}

  <!-- jQuery -->
  {{HTML::script('assets/vendor/jquery/jquery-2.1.4.min.js')}}
  {{HTML::script('assets/vendor/jquery/jquery_ui/jquery-ui.min.js')}}

  {{HTML::script('assets/vendor/plugins/globalize/globalize.min.js')}}
  {{HTML::script('assets/vendor/plugins/moment/moment.min.js')}}

  {{HTML::script('assets/demo/form_plugins/js/1.js')}}
  {{HTML::script('assets/demo/form_plugins/js/2.js')}}
  <!-- Theme Javascript -->
  {{HTML::script('assets/js/utility/utility.js')}}
  {{HTML::script('assets/js/demo/demo.js')}}
  {{HTML::script('assets/js/main.js')}}

  {{HTML::script('assets/app.js')}}

  <!-- Page Javascript -->
  <script type="text/javascript">
    var path = GlobalParameters.appPath;
    var grupoIndex = GlobalParameters.grupoIndex;
  </script>
  @yield('scriptsContainer')

<div id="sf_sb" class="sf_sb" style="position:absolute;display:none;width:315px;z-index:9999"><div class="sf_sb_cont"><div class="sf_sb_top"></div><div id="sf_results" style="width:100%"><div id="sf_val"></div><div id="sf_more"></div></div><div class="sf_sb_bottom"></div></div></div></body></html>