<!DOCTYPE html>
<html>
<head>
	<title>Información de registro</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style type="text/css">
		.center {
			text-align: center;
		}
		.izq {
			float: right;
			margin: 0 5px;	    
		}
		.btn-block {
			width: 48%;
			height: 100px;
		}
		.nigga {
			font-weight: bold;
		}
		.bg-info{
			padding: 15px;
		}
		.p-15{
			padding-top: 15px;
		}
	</style>
</head>
<body>
	<div class="container">
		<h3 class="center p-15">INFORMACIÓN DE REGISTRO <br />DE INDUCCIÓN DE SST PARA EL INGRESO A ALMACENES</h3>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-7">
				<h3>Información de pago</h3><br/>
				<p>Se aceptan depósitos o transferencias, en caso el depósito provenga de provincia se deberá agregar S./ 7.50 soles. (Las transferencias no tienen cargos adicionales así sean de provincia.)</p>
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td rowspan="2"><img src="https://institutodeseguridad.edu.pe/wp-content/uploads/interbank.gif" style="padding-top:4%"></td>
							<td>CTA 0413030911234</td>
						</tr>
						<tr>
							<td>CCI 003-041-013030911234-15</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-bordered">
					<tbody>
						<tr class="active">
							<td>RANSA - TRAMARSA</td>
							<td>INVERSIÓN</td>
							<td>FOTOCHECK*</td>
						</tr>
						<tr>
							<td>Válido para 1 almacén</td>
							<td>S./ 30.00 por persona</td>
							<td>S./ 5.00 Adicional</td>
						</tr>
						<tr>
							<td>Válido para 2 almacenes</td>
							<td>S./ 50.00 por persona</td>
							<td>S./ 5.00 Adicional</td>
						</tr>
					</tbody>
				</table>
				<p class="bg-info">(*) El Fotocheck es obligatorio para todos los almacenes a excepción de RANSA que es opcional.</p>
				<table class="table table-bordered">
					<tbody>
						<tr class="active">
							<td>RUC</td>
							<td>Razón Social</td>
						</tr>
						<tr>
							<td>20511097321</td>
							<td>INSTITUTO SUPERIOR DE SEGURIDAD</td>
						</tr>
						<tr>
							<td class="warning">TELÉFONOS</td>
							<td>(01) 349-5709 Celular: RPC 940-242718</td>
						</tr>
					</tbody>
				</table>
				<a href="{{URL::to('registro/natural')}}" style="text-decoration:none;  "><button type="button" class="btn btn-info btn-lg btn-block izq">Registro como<br /> PERSONA NATURAL</button></a>
				<a href="{{URL::to('registro/juridica')}}" style="text-decoration:none;  "><button type="button" class="btn btn-success btn-lg btn-block">Registro como<br />PERSONA JURIDICA</button></a>
				<p class="center">Escoja el tipo de registro segun sea su modalidad</p>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-5">
				<h3>Turnos disponibles </h3><h4>(llegar con 15 minutos de anticipación)</h4><br />
				<table class="table table-bordered">
					<tbody>
						<tr class="success">
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
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
						<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
						<li data-target="#carousel-example-generic" data-slide-to="1"></li>
						<li data-target="#carousel-example-generic" data-slide-to="2"></li>
					</ol>

					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						<div class="item active">
							<a href="http://clubgps.net/contacto/" target="_blank"><img src="https://lh3.googleusercontent.com/MWee_DzNocgYdjpUPwrxP0-v-4GAxO6dlCfojChrN90eV688_xh7pt0YC3NlojcUWpSmnYduAMys3gIFRCrKZbEa_6S90z4IV2sRXKcvvAcCQxEyXD5upjsWmpc9ODrjRaWWjkUcnLDUFdvi1Rb2Gk2xcqsTesx8GMyExpKWHCdVzmKEE6Wa0Hvt1Di-_2reckLU6BMHgyvnenmylGsZIZVl6KikXrqaR1jehOkr2BeTTWwU1CsvGkZeP8CTHvGvywKU-db5YHL7DQeA1GYD-3LKV0WLSr4vFV2nTdL_r76lDRB-KiJ-yoH4os1qThoCAxai9wbC-9WrwCfXt5RVidFF7JQ9xFrfinmnluOibj9kwqb3de4SJkB9oueeE32NM3fKAVG5w0Bl5FgDP57Fn6I5PxclOOtBYKtDFkV_UWVvrrgdCSeX53_plYESD_q5nRMsOeemowdqWUDPtB5TmOCYUB4GopF_utBFSmm_3gNBrMOYBDs6FWnR562pYiYsDH6oFFTvBPoq-wFzzM1J-_ZDrgNNaEWvmmb8hrTsvyVW4a3NmsY88sb0cTwIS9pTDfyTTuxYk5BNXyyA-DjMqPmh3mgi6-5zA1d9CtWQTo50jO2U=w620-h372-no" alt=""></a>
							<div class="carousel-caption">
							</div>
						</div>
						<div class="item">
							<a href="http://institutodeseguridad.edu.pe/contrata-tus-seguros-en-el-instituto-de-seguridad/"><img src="https://institutodeseguridad.edu.pe/wp-content/uploads/sctr.jpg" alt="">
								<div class="carousel-caption">
								</div></a>
						</div>
						<div class="item">
							<a href="http://institutodeseguridad.edu.pe/contactenos/">
								<img src="https://institutodeseguridad.edu.pe/wp-content/uploads/inhouse.jpg" alt="">
									<div class="carousel-caption">
									</div></a>
						</div>
					</div>
							<!-- Controls -->
							<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
								<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
								<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</body>
		</html>