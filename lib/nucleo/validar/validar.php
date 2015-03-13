<?php
/* Clase Validar
 * 
 * La idea es que la clase sirva tanto para validar campos de un formulario
 * como para llenar los campos con los valores ya ingresados.
 * El funcionamiento normal sería el siguiente:
 * 		1- la primera vez que se presenta el formulario, se crearía una instancia
 * 		   de la clase en la sesion que se este utilizando.
 * 		   Para cada campo del formulario, se llamará a la función addCampo, con los 
 * 		   valores: 
 * 				   	nombre 			=> 	nombre del campo en el form
 * 				   	etiqueta		=> 	etiqueta del campo en el html (para reemplazarla luego
 * 							   	   		por el último valor ingresado
 *				   	sobreescribir	=>  si el campo ya existía, indica si debe descartarse o no
 *					exp_reg			=>  expresión regular que será utilizada en el momento de la
 *										validación.
 *					acepta_nulo		=>	indica si el campo puede quedar vacío
 *					valor			=>  valor inicial del campo
 *					msg				=>	mensaje devuelto en caso de error en la validación de ese
 *										campo
 * 		   Si fuera necesaria alguna función auxiliar de validación que opere sobre varios campos,
 * 		   se deberá llamar a la función addFuncion con los valores:
 * 					funcion		=> nombre de la funcion a llamar
 * 					parametros	=> array con los nombres de los campos sobre los que se validará
 * 		   La función deberá devolver el string vacío si no hubo error, y el mensaje correspondiente 
 * 		   en caso contrario. A su vez la función recibirá un hash de la forma "nombre campo"=> "valor campo". 
 *		   En el momento de correr la validación se validarán primero los campos en forma individual
 *		   para luego llamar a las funciones auxiliares pasandole como parámetros los valores de los
 *		   campos indicados.
 *		1.1- para los otros tipos de campo (por ahora, solo Campo_Select, ver archivos correspondientes.
 *		2- Luego de que el usuario ingrese valores en los campos del formulario y acepte, debe llamarse a 
 *		   la función correrValidacion que devolverá un objeto de tipo error, la funcion getError del objeto 
 *		   devolverá treu sii hubo error, getCampo() y getFuncion() devolverán el campo o función que 
 *		   ocasionó el error y getMensaje() devolverá el mensaje de error correspondiente.
 *		3- Luego de correr la validación, si se volverá a mostrar el formulario, puede llamarse a la función
 *		   llenarCampos pasándole como parámetros un objeto tpl y el form a llenar.
 *		 
 */
include_once("error.php");
include_once("campo.php");
include_once("campo_select.php");
include_once("campo_select_multiple.php");
include_once("campo_check.php");

class Validar{

	private $campos = array();
	private $funciones = array();
	
		
	function __construct(){
		$this->campos=array();
	}
	
	function addCampo($nombre,$etiqueta,$sobreescribir=false,$exp_reg="",$acepta_nulo=true,$valor="",$msg="",$limpia_espacios=true){
		if(!isset($this->campos[$nombre]) or $sobreescribir){
			$this->campos[$nombre] = new Campo($nombre,$etiqueta,$exp_reg,$acepta_nulo,$valor,$msg,$limpia_espacios);
		}
	}
	
	function addCampo_Select($nombre,$etiqueta_selected,$sobreescribir=false,$valor="",$valores_posibles=array(),$msg="",$valores_validos="",$validar=true){	
		if(!isset($this->campos[$nombre]) or $sobreescribir){
			$this->campos[$nombre]=new Campo_Select($nombre,$etiqueta_selected,$valor,$msg,$valores_posibles,$valores_validos,$validar);
		}
	}	
		
	function addCampo_Check($nombre,$etiqueta,$sobreescribir=false,$valor="",$check=false){
		if(!isset($this->campos[$nombre]) or $sobreescribir){
			$this->campos[$nombre]=new Campo_Check($nombre,$etiqueta,$valor="",$check);
		}	
	}
	
	function addCampo_Select_Multiple($nombre,$etiqueta_selected,$valores=array(),$valores_posibles=""){	
		if(!isset($this->campos[$nombre])){
			$this->campos[$nombre]=new Campo_Select_Multiple($nombre,$etiqueta_selected,$valores,$valores_posibles);
		}
	}		
		
	function correrValidacion(){
		error_log("Entroa a correrValidacion()");
		$error = new ErrorValidacion();
		foreach ($this->campos as $k=>$e){
			if($e->getTipo()!="Campo_Check" and $e->getTipo()!="Select_Multiple"){
				$this->campos[$k]->setValor($_POST[$k]);
				if(!$error->getError()){
					if(!$this->campos[$k]->validar()){
						$error->setError(true,$k,"",$e->getMensaje());
					}
				}	
			}
			elseif($e->getTipo()=="Campo_Check"){
				if(isset($_POST[$k])){
					$this->campos[$k]->setValor($_POST[$k]);
					$this->campos[$k]->setCheck(true);
				}else{
					$this->campos[$k]->setValor("");
					$this->campos[$k]->setCheck(false);
				}
			}		
			elseif($e->getTipo()=="Select_Multiple"){
				$this->campos[$k]->resetValores();
				if (isset($_POST[$k])){
					foreach($_POST[$k] as $csm){
						$this->campos[$k]->setValor($csm);
					}
				}
			}												
		}
		if(!$error->getError()){
			foreach($this->funciones as $f=>$p){
				$params = array();
				foreach ($p as $param){
					if(!isset($this->campos[$param])){
						$params[$param]="";
					}else{
						$params[$param]=$this->campos[$param]->getValor();
					}	
				}
				$var=call_user_func($f, $params);
				if($var!=""){
					$error->setError(true,"",$f,$var);
				}
			}
		}
		return $error;
	}

	function llenarCampos($tpl,&$form){
		foreach ($this->campos as $k=>$e){
			$e->llenarCampo($tpl,$form);
		}	
	}

	function addFuncion($funcion,$array_campos){
		foreach ($array_campos as $param){
			if(!isset($this->campos[$param])){
				return 0;
			}						
		}
		if(!isset($this->funciones[$funcion])){
			$this->funciones[$funcion]=$array_campos;
		}else{
			return 0;
		}				
		return 1;
	}

	function delFuncion($nom){
		if(isset($this->funciones[$nom])){
			unset($this->funciones[$nom]);
		}	
	}
		
	function getValorCampo($campo){
		return $this->campos[$campo]->getValor();
	}		
	
	function setValorCampo($campo,$valor){
		$this->campos[$campo]->setValor($valor);
	}
	
	//marca un campo check
	function setCheck($campo){
		if (isset($this->campos[$campo])){
			$this->campos[$campo]->setCheck(true);
		}
	}
}	
	
?>