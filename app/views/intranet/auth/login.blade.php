<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    <!-- Meta, title, CSS, favicons, etc. -->
    
    <title>Login | Instituto Superior de Seguridad (Issegur)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    {{HTML::style('assets/app.css')}}

    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}

</head>

<body>

<!-- Start: Main -->
<div id="main" class="container loginSection">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="logo">
                <img src="{{asset("assets/img/logo/logo.png")}}" height="80" class="img-logo" alt="Issegur Logo" />
            </div>
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Iniciar Sesi&oacute;n</h3>
                </div>
                <div class="panel-body">
                    <form role="form">
                        <fieldset>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input type="text" name="username" data-bind="value:username" id="username" class="form-control bordered-field" placeholder="Usuario" autofocus>
                            </div>
                            <label id="usernameRequiredMessage" class="validation-error" style="display:none;width:100%">Ingrese un usuario</label>
                            <span class="margin"></span>

                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input type="password" name="password" data-bind="value:password" id="password" class="form-control bordered-field" placeholder="Contraseña">
                            </div>
                            <label id="passwordRequiredMessage" class="validation-error" style="display:none;width:100%">Ingrese un password</label>
                            <label id="loginFailedMessage" class="validation-error" style="display:none;width:100%">Su usuario o password es incorrecto</label>
                            <span class="margin"></span>

                            <a id="btnLogin" href="javascript:void(0)" role="button" class="btn btn-lg btn-primary btn-block">Ingresar</a>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End: Main -->


<!-- BEGIN: PAGE SCRIPTS -->
<!-- knockout -->
{{HTML::script('assets/js/knockout-3.3.0.js')}}
<!-- jQuery -->
{{HTML::script('assets/vendor/jquery/jquery-2.1.4.min.js')}}
{{HTML::script('assets/vendor/jquery/jquery_ui/jquery-ui.min.js')}}

<!-- Theme Javascript -->
{{HTML::script('assets/bootstrap/js/bootstrap.min.js')}}

<!-- END: PAGE SCRIPTS -->
{{HTML::script('assets/app.js')}}

@include('intranet.auth.loginJS')
</body>

</html>
