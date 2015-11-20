<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <title>Email</title>
        <style>
        img.fb{
                border-width: 0px; border-style: solid; width:35px;height: 35px;
            }
        img.tw{
                border-width: 0px; border-style: solid; width:35px;height: 35px;
            }
        img.link{
                border-width: 0px; border-style: solid; width:35px;height: 35px;
            }
        img.go{
                border-width: 0px; border-style: solid; width:35px;height: 35px;
            }

        </style>
    </head>
    <body style="margin:0;padding:0;">
        <table align="center" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0" style="text-align:center;font-family:Arial, Helvetica, sans-serif;color:#000000; font-size:12px;" width="100%">
            <tbody>
                <tr>
                    <td>
                    <table align="center" cellpadding="0" cellspacing="0" style="font-size:12px;" width="600">
                        <tbody>
                            <tr>
                                <td align="left" height="34" style="font-family:Arial, Helvetica, sans-serif;">Gracias por registrarte | <a href="[web_version]" style="color:#1A4379; text-decoration:none;" target="_blank">Ver on-line</a></td>
                                <td align="right" style="font-family:Arial, Helvetica, sans-serif;">A&ntilde;&aacute;denos a | <a href="mailto:cursos@institutodeseguridad.edu.pe?;subject=Agregar%20instititutodeseguridad.edu.pe%20a%20mi%20lista%20de%20contactos&amp;body=Env%C3%ADa%20este%20email%20para%20agregar%20institutodeseguridad.edu.pe%20a%20tus%20contactos" style="color:#1A4379; text-decoration:none;" target="_blank">tu lista de contactos</a> |</td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
                <tr bgcolor="#1A4379">
                    <td height="10">&nbsp;</td>
                </tr>
                <tr bgcolor="#1A4379">
                    <td>
                    <table align="center" cellpadding="0" cellspacing="0" width="600">
                        <tbody>
                            <tr>
                                <td align="left" class="logotipo"><a href="http://[web_version]" target="_blank"> <img src="http://sist.institutodeseguridad.edu.pe/assets/img/email/logo_header.png" style="border-width:0px;border-style:solid;width:372px;height: 42px;"/></a></td>
                                <td align="right" style="vertical-align: top; padding-top: 5px;"><a href="https://www.facebook.com/issegur" target="_blank"><img class="fb" src="http://sist.institutodeseguridad.edu.pe/assets/img/email/facebook.png" style="border-width: 0px; border-style: solid; width:35px;height: 35px;" /></a> <a href="https://twitter.com/issegur" target="_blank"><img class="tw" src="http://sist.institutodeseguridad.edu.pe/assets/img/email/twitter.png" style="border-width: 0px; border-style: solid; width:35px;height: 35px;" /></a> <a href="#"><img class="link" src="http://sist.institutodeseguridad.edu.pe/assets/img/email/linkedin.png" style="border-width: 0px; border-style: solid; width:35px;height: 35px;" /></a> <a href="#" target="_blank"><img class="go" src="http://sist.institutodeseguridad.edu.pe/assets/img/email/google.png" style="border-width: 0px; border-style: solid; width:35px;height:35px;" /></a></td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
                <tr bgcolor="#1A4379">
                    <td height="10">&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <table align="center" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0" style="text-align:center;" width="100%">
            <tbody>
                <tr class="separador2" height="10">
                    <td>&nbsp;</td>
                </tr>
                <td>
                     <table align="center" bgcolor="#ffffff" cellpadding="0" cellspacing="0" width="600">
                        <tbody>
                            <tr bgcolor="#ffffff" style="color:#1c4c74; font-family:Arial, Helvetica, sans-serif; font-weight:bold; text-align:center; font-size:26px;">
                               <td height="100">Gracias por su registro</td>
                            </tr>
                         </tbody>
                     </table>
                </td>
                <tr>
                    <td>
                    <table align="center" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif;" width="600">
                        <tbody>
                            <tr>
                                <td>
                                <table cellpadding="0" cellspacing="0" width="600">
                                    <tbody>
                                        <tr>
                                            <td>
                                            <table cellpadding="0" cellspacing="0" width="600">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:15px;color:#636363; line-height:20px; text-align:left; font-family:Arial, Helvetica, sans-serif;" width="560">
                                                            <div style="text-align: center;">
                                                            @if($persona == "J")
                                                                Grupo registrado, los participantes han sido inscritos de la siguiente manera:
                                                            @else
                                                                Tu inscripcion se di&oacute; de la siguiente manera:
                                                            @endif
                                                            </div>
                                                        <img src="http://sist.institutodeseguridad.edu.pe/assets/img/email/linea.gif" style="border-width: 0px; border-style: solid;" /><br />
                                                        <br />
                                                        <p style="font-weight: bold; font-size: 18px; margin: 0px; text-align: center; color:#1c4c74;">Detalle:<br />
                                                        &nbsp;</p>
                                                            <div style="text-align: center;">
                                                            @if($persona == "J")
                                                                    <p>Razon Social: {{$razonSocial}}<br />
                                                                @foreach($grupos as $grupo)
                                                                    Fecha: {{$grupo->fecha}}<br />
                                                                    Turno: {{$grupo->turno}}<br />
                                                                    Operador(es): {{$grupo->operadores}}<br />
                                                                    Cantidad de Participantes: {{$grupo->participantes}}<br />
                                                                @endforeach
                                                                    </p>
                                                            @else
                                                                <p>DNI: {{$dni}}<br />
                                                                    Nombre: {{$nombres}} <br />
                                                                    Apellidos: {{$apellidos}} <br />
                                                                    Fecha: {{$fecha}} <br />
                                                                    Turno: {{$turno}} <br />
                                                                </p>
                                                            @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="20">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>
                                            <table cellpadding="0" cellspacing="0" width="600">
                                                <tbody>
                                                    <tr style="font-family:Arial, Helvetica, sans-serif;"><td width="20">&nbsp;</td>
                                                        <td style="font-size:15px;color:#636363; line-height:20px; text-align:center;" width="560">
                                                        <p style="text-align:center color:#cccccc font-family:Arial, Helvetica, sans-serif;">
                                                    Dirección del curso: Av. José de la Riva Agüero N° 550 <br/>
                                                    Urb. Pando San Miguel (altura Plaza San Miguel esquina grifo Primax)</p>
                                                        <br />
                                                        <img src="http://news.mailrelay.com/mailing-manager/domains/news_mailrelay_com/files/img//linea.gif" style="border-width: 0px; border-style: solid;" /><br />
                                                        <br />
                                                        <p style="font-weight:bold; font-size:18px; margin:0;"><span style="color:red; font-family:Arial, Helvetica, sans-serif;">EL DIA DEL CURSO LLEVAR SU VOUCHER ORIGINAL</span></p>
                                                        <br />
                                                        Consultas sobre su inscripci&oacute;n o reprogramaciones comunicarse al email: CURSOS@INSTITUTODESEGURIDAD.EDU.PE<br />Telé&eacute;fono: (01) 349-5709 Celular: RPC 940-242718<br /><br />
                                                        &nbsp;</td>
                                                        <td width="20">&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            <table align="center" bgcolor="#ffffff" cellpadding="0" cellspacing="0" width="600">
                                                <tbody>
                                                    <tr>
                                                        <td><img alt="" src="http://sist.institutodeseguridad.edu.pe/assets/img/email/banner1.jpg" style="width: 600px; height: 200px;" /></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table align="center" bgcolor="#1A4379" cellpadding="0" cellspacing="0" style="color:#ffffff;font-family:'Trebuchet MS', Arial, Helvetica, sans-serif;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td class="separador2" height="16">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="center"><img src="http://sist.institutodeseguridad.edu.pe/assets/img/email/logo.png" style="border-width: 0px; border-style: solid;" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table align="center" bgcolor="#1A4379" cellpadding="0" cellspacing="0" style="color:#ffffff;font-family:'Trebuchet MS', Arial, Helvetica, sans-serif;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td>
                                            <table align="center" cellpadding="0" cellspacing="0" style="border-top:1px dashed #1A4379;" width="600">
                                                <tbody>
                                                    <tr>
                                                        <td width="10">&nbsp;</td>
                                                        <td>
                                                        <p style="font-size: 11px; color: rgb(255, 255, 255); text-align: center; font-family:Arial, Helvetica, sans-serif;"><br /><br />En cumplimiento de lo dispuesto en la Protecci&oacute;n de Datos, para el ejercicio de sus derechos de acceso, rectificaci&oacute;n, cancelaci&oacute;n y oposici&oacute;n al tratamiento de sus datos personales, contenidos en nuestras condiciones de protecci&oacute;n de datos, solamente tiene que hacer <a href="http://[unsubscribe_url_click]" target="_blank"><font color="#00FFFF">clic aqu&iacute;</font></a> y pulsar enviar. Si no puede hacer clic, responda a este e-mail indicando su <span style="color:#00FFFF;">email</span> en el asunto, o bien a trav&eacute;s del env&iacute;o de un correo ordinario a la direcci&oacute;n: Calle Los Tulipanes - Urb. San Francisco de Asis, Ate - Lima - Per&uacute;.</p><br/><br><center><span style="font-size: 11px; font-family:Arial, Helvetica, sans-serif; color:#FFF;">Enviado a trav&eacute;z del servicio de emailing de <a href="http://www.disolu.com" target="_blank" style="text-decoration:none; color:#7EAAFD">Disolu</a></span></center>
                                                        </td>
                                                        <td width="10">&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="40">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
