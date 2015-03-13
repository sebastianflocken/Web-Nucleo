<?php

require_once("Mensajes.php");

class WSCliente {
	
	private $error        = false;
	private $codigoError  = 0;
	private $mensajeError = "";
	public $parametrosGrandes = array(); /* Se utiliza para pasarle al metodo call parametros cuyos valores sean muy grandes en el orden de varios kilo bytes. */
	private $webServiceURL = null;
	private $webServiceNameSpace = null;
	private $timeOut = null;
	private $servidor = null;
	public $reemplazos_filtro_claves= array('_space_'=>' '); /* hash para utilizar en caso de que esté filtrando las claves de hash en Servicio.pl */

	
	function __construct ($url, $nameSpace, $timeOut=30) {
	// Constructor de la clase	
		try{
			$this->webServiceURL = $url;
			$this->webServiceNameSpace = $nameSpace;
			$this->timeOut = $timeOut;
			// wsdl : URI de el fichero WSDL o NULL si funciona en modo non-WSDL. 
			// location : es el URL del servidor SOAP donde enviar la petición. 
			// uri : es el espacio de nombres destino del servicio SOAP.
			// trace : activa el seguimiento de la petición para que los fallos puedan ser trazados. Por defecto es FALSE
			$this->servidor = new SoapClient(null, array( 'location' => $this->webServiceURL, 'uri' => $this->webServiceNameSpace, 'trace' => true, 'connection_timeout' => $this->timeOut ));
			if (!$this->servidor) {
				$this->setError("C001");
			}
		}catch(SOAPFault $f){
			echo "<br>=========== Ocurrio un excepcion en el constructor del WSCliente ============<br>";
			var_dump($f);
			echo "ERROR ".$f->faultstring . " ## " . $f->detail;
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
			echo "<br>=============================================================================<br>";	
			throw new Exception( $f->getMessage( ) , (int)$f->getCode( ) );
		}
	}

	function call($metodo, $params) {
	// Realiza la llamada al metodo del webservice indicado.
	// $metodo: es el nombre del metodo que se expone como web service
	// $params: es un array asociativo contentiendo los parametros de la llamada en el formato clave=>valor
		try{
			error_log("Entro al metodo call del WSCliente");
			$parametro = array($params); // string2xmlString: le da al juego de parámetros un formato xml válido. Creo un array que en la posicion 0 tiene los datos pasados como parametro.
			foreach(array_keys($this->parametrosGrandes) as $clave) { // Devuelve todas las claves de un array
				$parametro[0][$clave] =& $this->parametrosGrandes[$clave];
			}
			$resultado = $this->servidor->__soapCall($metodo,$parametro);
			$resultado = json_decode($resultado); // Decodifico el resultado del llamado al metodo pasado como parametro
			
			
			error_log(print_r($resultado,1));
			$this->parametrosGrandes = array(); // Reseteo el array de parametros Grandes
			$error = $this->checkError($resultado);
			if(!$this->isError()){ // Si no ocurrio ningun error
				$resultado = $this->inv_filtrar($resultado);
			}
			return $resultado;
		}catch(SOAPFault $f){
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
			throw new Exception( $f->getMessage( ) , (int)$f->getCode( ) );
		}	
	}
	
	function inv_filtrar($param){
	// Toma una estructura generica y, modifica las claves de hash que contenga 
	// basándose en el hash $reemplazos_filtro_claves
		if(is_array($param)){ // Comprueba si una variable es un array
			$nuevas_claves = array();
			foreach ($param as $clave => $valor){
				$newk = $this->cambiarClave($clave);
				$nuevas_claves[$newk] = 1;
				$param[$newk] = $this->inv_filtrar($valor);
			}
			foreach ($param as $clave => $valor){
				if(!isset($nuevas_claves[$clave])){ // Determina si una variable está definida y no es NULL
					unset($param[$clave]); // destruye las variables especificadas. 
				}	
			}
		}
		if(is_object($param)){ // Comprueba si una variable es un objeto
			$nuevas_claves = array();
			foreach( $param as $clave => $valor ){
				$newk = $this->cambiarClave($clave);
				$nuevas_claves[$newk] = 1;
				$param->$newk = $this->inv_filtrar($valor);
			}
			foreach( $param as $clave => $valor ){
				if(!isset($nuevas_claves[$clave])){ // Determina si una variable está definida y no es NULL
					unset($param->$clave); // destruye las variables especificadas.
				}	
			}
		}
		return $param;
	}
		
	function cambiarClave($param){
	// Realiza reemplazos en un string basándose en el hash $reemplazos_filtro_claves
		foreach( $this->reemplazos_filtro_claves as $clave => $valor ){
			$param = preg_replace($clave,$valor,$param); // Realiza una búsqueda y sustitución de una expresión regular
		}
		$param = preg_replace("_id([0-9]+)_","\\1",$param); // Realiza una búsqueda y sustitución de una expresión regular
		return $param;
	}
	
	function checkError($response) {
	// Chequea si la respuesta a una llamada de metodo remoto genero algun error.
	// Retorna true si hubo error y setea los atributos correspondientes del objeto.
		if(!is_object($response)){ // Comprueba si una variable es un objeto
			$this->setError("C002");
			return true;
		}else{
			if (is_a($response,"soap_fault")){ // Comprueba si un objeto es de una clase o tiene esta clase como una de sus madres
				$this->setError("C003");
				return true;
			}	
		}
		return false;
	}
	
	function setError($codigoError = NULL) {
	// Setea los atributos de error segun el codigo pasado por parametro si el mismo es null, se setean los atributos a "No Error"
		global $MENSAJE_USR; // todas las referencias a la variable se referirán a la versión global
		global $MENSAJE_LOG; // todas las referencias a la variable se referirán a la versión global
		if (is_null($codigoError)){ // Comprueba si la variable dada es NULL. 
			$this->error = false;
			$this->codigoError = 0;
			$this->mensajeError = "";
		}else{
			$this->error = true;
			$this->codigoError = $codigoError;
			$this->mensajeError = $MENSAJE_USR[$codigoError];
		}
	}

	function isError () {
		return $this->error;
	}
	
	function getCodigoError () {
		return $this->codigoError;
	}

	function getMensajeError () {
		return $this->mensajeError;
	}
	
} //End Class WSCliente




?>