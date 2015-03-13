<?php
	class LaClase {
		
		private $error = 0;
	
		public function __construct(){
			error_log("------------CONSTRUCTOR----------------");
		}
		
		public function helloWorld() {
			$this->nombre = "Clase1";
			
			return json_encode( 'Hallo Welt '. print_r(func_get_args(), true));
			
			$this->error = 0;
		}
		
		public function byeWorld($parametros) {
			error_log("Entro a la clase \"LaClase\"");
			$cosa = new stdClass();
			$cosa->una_variable 		= "EL valor de la variable es: ".$parametros[0];
			$cosa->otra_variable 		= "La otra variable es : ".$parametros[1];
			$cosa->variable_numerica 	= 125;
			$cosa->variable_array 		= array(1,2,3,4);
			//$this->cosa = json_encode($cosa);
			//error_log(json_encode($cosa));
			
			$this->error = 0;
			
			return json_encode($cosa);
			/*
			$numargs = func_num_args();
			if($numargs != 3){
				$this->error = 1;
				throw new SOAPFault("333", "algun error", __CLASS__, __METHOD__ ." line ". __LINE__);
			}
			return 'Otra funcion sin registrarla con ' . $numargs . ' argumentos => ' . print_r(func_get_args(), true);
			*/
		}
		
		public function finalizar($commit = 0){
			if(isset($this->nombre)){
				error_log("#######" . $this->nombre . "###########");
			}
			
			if(isset($this->cosa)){
				error_log("===>##".$this->cosa."##");
			}
			error_log("------------FIN----------------");
			if($commit == 0){
				error_log("FINALIZAR ==> 0");
			}
			else{
				error_log("FINALIZAR ==> 1");
			}
		}
		
		// Funcion obtener_archivo
		function obtener_archivo($nombre){
			// Lee el contenido de un archivo y lo guarda en un string	
			error_log("Nombre: $nombre[0]");
			$archivo = file_get_contents($nombre[0]);
			if(!$archivo){
				error_log("Archivo no encontrado");
				return json_encode("Archivo no encontrado");
			}else{
				// Escribe el contenido al fichero
				file_put_contents('Imagen_copia_antes_de_enviar.JPG', $archivo);
				return  json_encode(base64_encode($archivo));
			}
		}
		
		function __destruct(){ 
			if($this->error == 0){
				error_log(" me voy de la clase COMMITEANDO");
			}
			else{
				error_log(" me voy de la clase ROLLBACK");
			}
		}
		
		function ingresar($parametros){
			error_log(print_r($parametros,1));
			$res =  array("error"=>0,
						"sesion"=>"123456",
						"id_usuario_logueado"=>1,
						"resetear"=>"N",
						"funciones"=>array("get","set","push","pop"),
						"grupos"=>array("amigos","conocidos","familiares")
					);
			
			$resultado = new stdClass();
			$resultado->error = 0;
			$resultado->sesion = "Session 123456";
			$resultado->id_usuario_logueado 	= 1;
			$resultado->usuario	= $parametros["login"];
			$resultado->pass 	= $parametros["clave"];
			$resultado->resetear 		= array(1,2,3,4);
			$resultado->funciones 		= array(1,2,3,4);
			$resultado->grupos 		=  array(1,2,3,4);
			
			return json_encode($res);
			
		}	
		
		function getPdf($parametros){
			// Lee el contenido de un archivo y lo guarda en un string	
			error_log("Nombre: $nombre[0]");
			$archivo = file_get_contents("archivo_prueba.pdf");
			$resultado->error = 0;
			if(!$archivo){
				error_log("Archivo no encontrado");
				$resultado->dato = "Archivo no encontrado";
				return json_encode($resultado);
			}else{
				// Escribe el contenido al fichero
				$resultado->dato = base64_encode($archivo);
				file_put_contents('archivo_prueba_copia.pdf', $archivo);
				return  json_encode($resultado);
			}
		}
		
		function logout($parametros){
			$resultado = new stdClass();
			$resultado->error = 0;
			return  json_encode($resultado);
		}
		
		function cambiarPwd($parametros){
			$resultado = new stdClass();
			$resultado->error = 0;
			$resultado->mensaje = "";
			return  json_encode($resultado);
		}
		
		
	}
?>