<?php 


class PHPSesion {
// Esta clase encapsula el manejo de Sesion en PHP mediante un objeto, al cual se le pueden definir atributos y manejarlo en forma persistente. 
	
	private $es_null = true;
	
	function PHPSesion ($nueva = false) { //Si vale false indica que se debe abrir una session existente.
	// Constructor de la clase	
		if (session_id()) { //Si la sesion ya fue abierta, retorno pues no la puedo abrir mas de una vez. session_id(): Obtener y/o establecer el id de sesión actual
			unset($GLOBALS[$this]); // Destruye una variable especificada
			return;	
		}
		if (!$nueva) {
			session_name($this->getNombreSesion()); //  Obtener y/o establecer el nombre de la sesión actual
			//Abrir sesion existente.
			$ret = session_start(); //  Iniciar una nueva sesión o reanudar la existente
			if (!isset($_SESSION['PHPSesion'])) { // Si no existe la sesion
				$this->cerrar();
				unset($this); // Destruye una variable especificada
				return;
			}
			$cargo = $this->load();
			if (!is_object($this) || !$cargo) { // Si no se recupero correctamente la clase
				$this->cerrar();
				return;
			}
		}else {
			//Crear Nueva sesion, aun si existe una previa.
			list($usec, $sec) = explode(' ', microtime()); // explode : Divide un string en varios string. microtime : Devuelve la fecha Unix actual con microsegundos
    		mt_srand( (float) $sec + ((float) $usec * 100000) ); 
			if (function_exists("posix_getpid")){ // Devuelve TRUE si la función dada ha sido definida
				#Linux
				session_id( md5( uniqid(mt_rand().posix_getpid(),true) ) );// mt_rand : Genera un mejor número entero aleatorio
			}else {
				#Windows
				session_id( md5( uniqid(mt_rand(),true) ) ); // uniqid : Generar un ID único
			}
			session_name($this->getNombreSesion()); //  Obtener y/o establecer el nombre de la sesión actual
			session_start(); //  Iniciar una nueva sesión o reanudar la existente
			session_unset(); // session_unset : Libera todas las variables de sesión
			$this->salvar();
		}
		$this->es_null = false;
	}	

	function load(){
	// Crea un valor PHP a partir de una representación almacenada
		$aux = unserialize($_SESSION['PHPSesion']);
		foreach ($aux as $nombre => $valor){
			if(strcmp($nombre,'PHPSesion')!=0){
				$this->$nombre = $valor;
			}
		}
		return true;
	}
	
	function es_null(){
		return $this->es_null;
	}

	function salvar(){
	//Guarda el objeto PHPSesion, es decir, lo serializa y almacena en $_SESSION
		$_SESSION['PHPSesion'] = serialize($this); //  Genera una representación apta para el almacenamiento de un valor
		return true;
	}

	function cerrar(){
		// Si se desea matar a la sesión, también suprimir la cookie de sesión.
		// Nota: Esto destruirá la sesión, y no sólo los datos de la sesión!
		if (isset($_COOKIE[session_name()])) {
		   setcookie(session_name(), '', time()-42000, '/'); // setcookie(): define una cookie para ser enviada junto con el resto de las cabeceras de HTTP
		}
		// Finalmente, destruye la sesión.
		session_destroy(); // Destruye toda la información registrada de una sesión
		//$this = null;
		unset($this); // Destruye una variable especificada
	}
	
	function getNombreSesion(){
	// NOMBRE_SESION variable global, debe estar definida en Constantes.php
		if (!defined('NOMBRE_SESION')){ // defined: Comprueba si existe una constante con nombre dada
			define('NOMBRE_SESION',"PHPSESSID"); // Define una constante con nombre
		}
		$nombre = NOMBRE_SESION;
		return $nombre;
	}
	
}//End Class PHPSesion

?>