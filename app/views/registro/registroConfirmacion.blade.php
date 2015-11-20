<!DOCTYPE html>
<html>
<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>Confirmación de Registro | Instituto Superior de Seguridad (Issegur)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font CSS (Via CDN) -->
    {{--<script type="text/javascript">--}}
        {{--//<![CDATA[--}}
        {{--try{if (!window.CloudFlare) {var CloudFlare=[{verbose:0,p:1431556313,byc:0,owlid:"cf",bag2:1,mirage2:0,oracle:0,paths:{cloudflare:"/cdn-cgi/nexp/dok3v=1613a3a185/"},atok:"060886b4283d553c10fc8cbd925fbef8",petok:"6a766df38af30e4160c496df641dde4b4e40ad7f-1431611578-86400",zone:"admindesigns.com",rocket:"0",apps:{}}];CloudFlare.push({"apps":{"ape":"6a9becf13c76022e33c7396eaf3d2697"}});!function(a,b){a=document.createElement("script"),b=document.getElementsByTagName("script")[0],a.async=!0,a.src="../../../../ajax.cloudflare.com/cdn-cgi/nexp/dok3v%3d7e13c32551/cloudflare.min.js",b.parentNode.insertBefore(a,b)}()}}catch(e){};--}}
        {{--//]]>--}}
    {{--</script>--}}
    <!-- Style - estilos globales -->
    {{ HTML::style('assets/fonts.googleapis.com/cssff98.css?family=Open+Sans:300,400,600,700') }}

    <!-- Theme CSS -->
    {{ HTML::style('assets/skin/default_skin/css/theme.css') }}

</head>

<body class="external-page external-alt sb-l-c sb-r-c">

<!-- Start: Main -->
<div id="main" class="animated fadeIn">

    <!-- Start: Content-Wrapper -->
    <section id="content_wrapper">

        <!-- Begin: Content -->
        <section id="content">

            <div class="center-block mt70" style="max-width: 625px">

                <!-- Login Logo + Meta -->
                <div class="row table-layout">

                    <div class="col-xs-7 pln">
                        <h2 class="text-dark mbn confirmation-header">
                            <i class="fa fa-check text-success"></i>
                            Formulario Enviado.
                        </h2>
                    </div>

                    <div class="col-xs-5 text-right va-b">
                    </div>

                </div>

                <!-- Confirmation Panel -->
                <div class="panel mt15">
                    <div class="panel-body pt30 p25 pb15">

                        <p class="lead">Estimado Usuario,</p>

                        <hr class="alt short mv25">

                        <p class="lh25 text-muted fs15">
                            Hemos enviado una confirmación de su registro al correo electrónico que indicó en el formulario.
                            Actualmente su registro se encuentra <strong>CONFIRMADO</strong>. <span style="color:red">
                            No se olvide de llevar su voucher original el dia de su capacitación.</span></p>

                        <p class="text-right mt20">
                            <a href="http://institutodeseguridad.edu.pe"
                               class="btn btn-primary btn-rounded ph40"
                               role="button">Volver a la página principal</a></p>

                    </div>
                </div>
            </div>

        </section>
        <!-- End: Content -->

    </section>
    <!-- End: Content-Wrapper -->

</div>
<!-- End: Main -->


<!-- BEGIN: PAGE SCRIPTS -->

<!-- jQuery -->
{{HTML::script('http://code.jquery.com/jquery-2.1.4.min.js')}}
{{HTML::script('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js')}}

<!-- Theme Javascript -->
{{HTML::script('assets/js/utility/utility.js')}}
{{HTML::script('assets/js/demo/demo.js')}}
{{HTML::script('assets/js/main.js')}}

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
{{HTML::script('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')}}
{{HTML::script('https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js')}}
<![endif]-->

<!-- END: PAGE SCRIPTS -->

</body>

</html>
