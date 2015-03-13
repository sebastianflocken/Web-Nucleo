<?php


function terminar ($urlRelativa=NULL,$cli=NULL,$parametros=NULL) {
// 
	if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") { // $_SERVER["HTTPS"] : Ofrece un valor no vacío si el script es pedido mediante el protocolo HTTPS. 
		$protocolo = "https://";
	}else {
		$protocolo = "http://";
	}
	$paginaDestino = $protocolo.$_SERVER['HTTP_HOST']; // 'HTTP_HOST' : Contenido de la cabecera Host: de la petición actual, si existe. 
	$pars = "";
	//Armo los parametros
	if (!is_null($parametros)) {
		foreach($parametros as $k=>$v){ 
			$pars .= $k."=".urlencode($v)."&";
		}	
		chop($pars);
	}
    //Agrego los codigos de error como parametros
    if (!is_null($cli) and $cli->error) {
		$pars .= "&codigoError=".urlencode($cli->codigoError);
		if (!is_null($cli->mensajeError)){
			$pars .= "&mensajeError=".urlencode($cli->mensajeError);
		}	
	}
	//Armo la pagina de destino
	if (!is_null($urlRelativa)) {
		//Es una redireccion
		$paginaDestino .= dirname($_SERVER['PHP_SELF'])."/".$urlRelativa."?".$pars; // dirname : Devuelve el directorio padre de la ruta. 
	}else {
		//Se quiere llamar a la ultima pagina valida
		if (!is_null($cli) and !is_null($cli->sesion->urlUltimaPaginaValida)) {
			//Hay una ultima pagina valida
			$paginaDestino = $cli->sesion->urlUltimaPaginaValida.$pars;
			$cli->sesion->urlUltimaPaginaValida = NULL;
			$cli->sesion->salvar();
		}else {
			//Se redirige al inicio del sitio.
			$paginaDestino .= dirname($_SERVER['PHP_SELF'])."?".$pars; // 'PHP_SELF' : El nombre del archivo de script ejecutándose actualmente
		}
	}
   	if(headers_sent()){
		//Redirigo al destino mediante javascript
        echo "<SCRIPT LANGUAGE='JavaScript' TYPE='text/javascript'>";
        echo "    document.location='".$paginaDestino."';";
    	echo "</SCRIPT>";
   	}else{
		//Redirigo al destino mediante headers
       	header("Location: ".$paginaDestino);
   	} 
   	exit;
}


function marcarComoPaginaValida ($cli) {
// 	
	if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {
		$proto = "https://";
	}
	else {
		$proto = "http://";
	}
	$paginaDestino = $proto.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?";
	if (count($_GET) > 0) {
		foreach($_GET as $k=>$v) {
			if ($k != "codigoError" and $k != "mensajeError") {
				$paginaDestino .= $k."=".urlencode($v)."&";
			}
		}
		chop($paginaDestino);
	}
	elseif (count($_POST) > 0) {
		foreach($_POST as $k=>$v) {
			if ($k != "codigoError" and $k != "mensajeError") {
				$paginaDestino .= $k."=".urlencode($v)."&";
			}
		}
		chop($paginaDestino);
	}
	$cli->sesion->urlUltimaPaginaValida = $paginaDestino;
	$cli->sesion->salvar();
}


function parsearError($tpl) {
// Retorna el template del error con el codigo de error y el mensaje de error correspondientes
	$error = "";
	if (isset($_GET['codigoError'])) {
	    $error = $tpl->load("recursos/error_tpl.htm");
		$error = $tpl->replace( $error, array(
				"ERROR_CODIGO" =>$_GET['codigoError'],
				"ERROR_MENSAJE"=>$_GET['mensajeError'])
			 );
	}
	return $error;
}


function terminarFormURLEncoded ( $cli, $parametros = array()) {
// 	
	$pars = "";
	//Armo los parametros, con el formato: clave1=valor1&clave2=valor2.....
	if (!is_null($parametros)) { // Si no es null el arreglo de parametros
		foreach($parametros as $clave=>$valor){ 
			$pars .= $clave."=".urlencode($valor)."&"; // urlencode(): Codifica como URL una cadena
		}	
		chop($pars); //  Alias de rtrim(), rtrim : Retira los espacios en blanco (u otros caracteres) del final de un string
	}
    //Agrego los codigos de error como parametros
    if (!is_null($cli) and $cli->error) {
		$pars .= "&codigoError=".urlencode($cli->codigoError);
		if (!is_null($cli->mensajeError))
			$pars .= "&mensajeError=".urlencode($cli->mensajeError);
	}
	header("Content-type: application/x-www-form-urlencoded");  // indica el tipo de contenido a ser enviado
	header("Content-length: ".strlen($pars)); // indica el tamaño del cuerpo entidad que habría sido enviada.
	print($pars); // Imprime los parametros
	exit;
}


function transformarFecha ($fecha) {
// Cambia el formato de la fecha ingresado como parametro	
	$match = array();
	preg_match('/(\d{4}).(\d{2}).(\d{2}) (.{8})/',$fecha, $match); // Busca en $fecha una coincidencia con la expresión regular dada, en $match retorna los substrings.
	$nueva_fecha = $match[3]."-".$match[2]."-".$match[1]." ".$match[4]; // Le da un nuevo formato a la fecha
	return $nueva_fecha;
}


function loguearError($mensaje) {
// Imprime en el log de PHP el mensaje de error pasado como parametro
	$lineas = preg_split('/\n/',$mensaje); //  Divide el string $mensaje mediante la expresión regular '/\n/'
	for ($index = 0; $index < count($lineas); $index++) { // Para cada linea del mensaje de error
		error_log($lineas[$index]); // La imprimo en el log
	}
}

?>