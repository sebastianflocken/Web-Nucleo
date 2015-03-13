<?php
class ErrorValidacion{

	private $error;
	private $campo;
	private $funcion;
	private $msg;
	
	function __construct($error=false,$campo="",$funcion="",$msg=""){
		$this->error=$error;
		$this->campo=$campo;
		$this->funcion=$funcion;
		$this->msg=$msg;
	}
	
	function setError($error=true,$campo,$funcion,$msg){
		$this->error=$error;
		$this->campo=$campo;
		$this->funcion=$funcion;
		$this->msg=$msg;
	}
		
	function getError(){
		return $this->error;
	}
		
	function getCampo(){
		return $this->campo;
	}
		
	function getFuncion(){
		return $this->funcion;
	}
	
	function getMensaje(){
		return $this->msg;
	}
}

?>
