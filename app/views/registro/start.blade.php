@extends('master.site')
@section('StylesContainer')


    <style>
        .divShadow:hover {
            -moz-box-shadow: 0 0 10px rgba(0,0,0,0.5) !important;
            -webkit-box-shadow: 0 0 10px rgba(0,0,0,0.5) !important;
            box-shadow: 0 0 10px rgba(0,0,0,0.5) !important;
        }
        a:hover{
            text-decoration: none;
        }
    </style>
@stop
@section('main')
    <body class="external-page external-alt sb-l-c sb-r-c">

    <!-- Start: Main -->
    <div id="main" class="animated fadeIn">

        <!-- Start: Content-Wrapper -->
        <section id="content_wrapper">

            <!-- Begin: Content -->
            <section id="content">

                <div class="admin-form theme-primary mw600" style="margin-top: 2%;" id="register">

                    <!-- Begin: Content Header -->

                    <div class="row">
                        <div class="content-body">
                            <h2 style="text-align:center">INFORMACION DE CURSOS</h2>
                            <div class="info"><h3>Informacion Importante</h3><br/>
                                <p style="font-size:16px">Antes de iniciar el proceso de registro, Usted deberá haber realizado el abono correspondiente del curso en el banco Interbank<br/>
                                    <br /><b>Cuenta Ahorros Empresa N°: 0413030911234</b><br/><br/>
                                    Costo para Un almacén: S./ 30.00 <br/>
                                    Costo para Dos almacenes S./ 50.00 <br/>
                                    Adicionar S./ 5.00 soles por cada participante para el fotocheck y la toma fotográfica.<br/><br/>

                                    <b>Teléfono: (01) 349-5709 Celular: RPC 940-242718</b>


                                </p>
                            </div>
                            <div class="calendario">
                                <h3>Turnos disponibles </h3><h4>(llegar con 15 minutos de anticipación)</h4>
                                <table border="1">
                                    <tbody>
                                    <tr>
                                        <td><strong>Día</strong></td>
                                        <td><b>1er Turno</b></td>
                                        <td><strong>2 Turno</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Lunes</td>
                                        <td><b>08 am</b> a <b>10 am</b></td>
                                        <td><b>11 am</b> a <b>01 pm</b></td>
                                    </tr>
                                    <tr>
                                        <td>Martes</td>
                                        <td><b>08 am</b> a <b>10 am</b></td>
                                        <td><b>11 am</b> a <b>01 pm</b></td>
                                    </tr>
                                    <tr>
                                        <td>Miercoles</td>
                                        <td><b>08 am</b> a <b>10 am</b></td>
                                        <td><b>02 pm</b> a <b>04 pm</b></td>
                                    </tr>
                                    <tr>
                                        <td>Jueves</td>
                                        <td><b>08 am</b> a <b>10 am</b></td>
                                        <td><b>11 am</b> a <b>01 pm</b></td>
                                    </tr>
                                    <tr>
                                        <td>Viernes</td>
                                        <td><b>08 am</b> a <b>10 am</b></td>
                                        <td><b>11 am</b> a <b>01 pm</b></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-offset-4 col-md-4" style="margin-top:15px;color:#E50000;margin-bottom:15px">
                            <h4>Usar el navegador Google Chrome <img src="https://cdn0.iconfinder.com/data/icons/jfk/512/chrome-512.png" style="height:25px; width:25px"/></h4>	
                        </div>
                        <div class="content-header" style="padding-top:325px !important;">
                            <h2>SELECCIONE UN TIPO DE INSCRIPCION</h2>
                        </div>
                        <div class="col-md-offset-2 col-md-4">
                            <div class="panel panel-tile text-center divShadow">
                                <a href="{{URL::to('registro/juridica')}}">
                                    <div class="panel-body bg-primary light">
                                        <i class="fa fa-suitcase text-muted fs70 mt10"></i>
                                    </div>
                                    <div class="panel-footer bg-primary br-n p12 title">
                                        <span class="fs14 ">
                                          <b> PERSONA JURIDICA</b>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-tile text-center divShadow">
                                <a href="{{URL::to('registro/natural')}}">
                                    <div class="panel-body bg-success light">
                                        <i class="fa fa-user text-muted fs70 mt10"></i>
                                    </div>
                                    <div class="panel-footer bg-success br-n p12 title">
                                        <span class="fs14">
                                          <b> PERSONA NATURAL</b>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
            </section>
        </section>
    </div>