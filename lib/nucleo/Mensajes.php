<?php

/*
 * Lista de mensajes que puede ser desplegados al usuario final y/o archivos de log
 * Todos los mensajes desplegados por el cliente web deberan estar definidos en este archivo.
 *
 *	El array asociativo MENSAJES_LOG sera de la forma {Cddd=><mensaje>,....}
 *	El array asociativo MENSAJES_USR sera de la forma {Cddd=><mensaje>,....}
 *
 *	Donde Cddd sera un string, por ejemplo: "C001", "C123".
 *	La C indica que es un codigo de error del cliente web (capa presentacion) para diferenciarlo con los
 *	codigos de error del servidor (capa de logica) que seran de la forma Sddd.
 */


$MENSAJE_LOG = array();
$MENSAJE_USR = array();

$MENSAJE_LOG["C000"] = "Funcionalidad en Construcci&oacute;n";
$MENSAJE_USR["C000"] = "Funcionalidad en Construcci&oacute;n";

$MENSAJE_LOG["C001"] = "No se pudo establecer la conexi&oacute;n con el servidor de aplicaci&oacute;n";
$MENSAJE_USR["C001"] = "En este momento no se puede procesar la solicitud.<br />Vuelva a intentarlo en unos minutos.";

$MENSAJE_LOG["C002"] = "No se pudo establecer la conexi&oacute;n con el servidor de aplicaci&oacute;n";
$MENSAJE_USR["C002"] = "En este momento no se puede procesar la solicitud.<br />Vuelva a intentarlo en unos minutos.";

$MENSAJE_LOG["C003"] = "Error en la comunicaci&oacute;n o invocaci&oacute;n al m&eacute;todo del web service";
$MENSAJE_USR["C003"] = "En este momento no se puede procesar la solicitud.<br />Vuelva a intentarlo en unos minutos.";

$MENSAJE_LOG["C004"] = "Error en la l&oacute;gica de invocaci&oacute;n al m&eacute;todo del web service";
$MENSAJE_USR["C004"] = "En este momento no se puede procesar la solicitud.<br />Vuelva a intentarlo en unos minutos.";

$MENSAJE_LOG["C005"] = "No se pudo iniciar la sesi&oacute;n en el web";
$MENSAJE_USR["C005"] = "En este momento no se puede procesar la solicitud.<br />Vuelva a intentarlo en unos minutos.";

$MENSAJE_LOG["C006"] = "Error al guardar la sesi&oacute;n en el web";
$MENSAJE_USR["C006"] = "En este momento no se puede procesar la solicitud.<br />Vuelva a intentarlo en unos minutos.";

$MENSAJE_LOG["C007"] = "No se pudo abrir la sesi&oacute;n en el web (posible TIMEOUT o PHPSESSIONID incorrecto)";
$MENSAJE_USR["C007"] = "El tiempo de la sesion ha expirado, ingrese nuevamente";

$MENSAJE_USR["C008"] = "No existen muestras para los datos ingresados.";

$MENSAJE_USR["V001"] = "La confirmaci&oacute;n de la contrase&ntilde;a ingresada es inv&aacute;lida.";
$MENSAJE_USR["V002"] = "Contrase&ntilde;a actual incorrecta.";
$MENSAJE_USR["V004"] = "La nueva contrase&ntilde;a es inv&aacute;lida.";
$MENSAJE_USR["V005"] = "La nueva contrase&ntilde;a y confirmaci&oacute;n son distintas.";
$MENSAJE_USR["V006"] = "Nueva contrase&ntilde;a igual a la actual.";
$MENSAJE_USR["V011"] = "El usuario o la contrase&ntilde;a no es correcto.";
$MENSAJE_USR["V012"] = "Error al ingrsar el usuario.";
$MENSAJE_USR["V013"] = "No existen usuarios para los datos ingresados.";
$MENSAJE_USR["V014"] = "No tiene permisos para ejectar esta funcionalidad.";
$MENSAJE_USR["V015"] = "La nueva password del usuario <span id='usr_resetpwd'>*USR*</span> es <span id='pwd_resetpwd'>*PWD*</span>";
$MENSAJE_USR["V016"] = "Se elimin&oacute; el usuario <span id='usr_new'><strong>*USR*</strong></span> correctamente.";
$MENSAJE_USR["V017"] = "Se modific&oacute; la password correctamente.";
$MENSAJE_USR["V018"] = "Debe ingresa el login del usuario.";
$MENSAJE_USR["V019"] = "Se cre&oacute; el usuario <span id='usr_new'><strong>*USR*</strong></span> correctamente.<br>".
					   "La password del usuario es <span id='pwd_new'><strong>*PWD*</strong></span>";
$MENSAJE_USR["V020"] = "El login del usuario no tiene el formato adecuado.";
$MENSAJE_USR["V021"] = "No existen datos con la informaci&oacute;n ingresada.";
$MENSAJE_USR["V022"] = "Debe ingresar un n&uacute;mero de cuenta o la cuenta no tiene el formato adecuado ([n/nnnnnnnnn]n).";
$MENSAJE_USR["V023"] = "Debe ingresar al menos la fecha de inicio.";
$MENSAJE_USR["V024"] = "Formato de fecha incorrecto (aaaa-mm-dd).";
$MENSAJE_USR["V025"] = "Rango de fechas incorrecto.";
$MENSAJE_USR["V026"] = "Debe ingresar un n&uacute;mero de preimpreso o el preimpreso no tiene el formato adecuado (Preimpreso se compone de letras y d&iacute;gitos).";
$MENSAJE_USR["V027"] = "El a&ntilde;o no es correcto";
$MENSAJE_USR["V028"] = "El n&uacute;mero de orden no es correcto";
$MENSAJE_USR["V029"] = "El perfil de usuario no es correcto";
$MENSAJE_USR["V030"] = "La nueva password del usuario <span id='usr_new'><strong>*USR*</strong></span> es <span id='pwd_new'><strong>*PWD*</strong></span>";

?>