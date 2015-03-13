<?php 
	// Incluyo la clase "LaClase.php"
	require_once "LaClase.php";
	// Defino la constante con el nombre de la clase que controla las peticiones SOAP
	define('__SERVICIO_CLASE__', 'LaClase');
	
	try{
		// Soap Server sin WSDL
		$wsdl = NULL;
		// Cuando está en modo no-WSDL se debe especificar la opción uri
		$opciones = array('uri'=>'http://NameSpace');
		// Creo un nuevo SOAP server
		$server = new SOAPServer($wsdl,$opciones);
		// Adjunto la clase al servidor SOAP. SetClass define la clase que controla las peticiones SOAP
		$server->setClass(__SERVICIO_CLASE__);
		// Inicio el manejador de peticiones SOAP
		$server->handle();
	}
	catch (SOAPFault $f){
		// Imprimo en el log toda la informacion de la excepcion
		error_log ("Ocurrio una Excepcion en el WSServer\n");
		error_log("Obtiene el mensaje de Excepción: ".$f->getMessage());
		error_log("Devuelve la excepción anterior: ".$f->getPrevious());
		error_log("Obtiene el código de Excepción: ".$f->getCode());
		error_log("Obtiene el fichero en el que ocurrió la excepción: ".$f->getFile());
		error_log("Obtiene la línea en donde ocurrió la excepción: ".$f->getLine());
		error_log("Obtiene el seguimiento de la pila: ".$f->getTrace());
		error_log(" Obtiene el stack trace como cadena: ".$f->getTraceAsString());
		error_log("Obtiene una representación en formato cadena de SoapFault: ".$f->__toString());
		// Lanzo una nueva excepcion
		throw new SOAPFault($f->getCode(),$f->getMessage(), __CLASS__, __METHOD__ ." line ". __LINE__);
	}

?>