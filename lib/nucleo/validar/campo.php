<?php

class Campo{

	private $nombre;
	private $acepta_nulo;
	private $exp_reg;
	private $etiqueta;
	private $valor;
	private $msg;
	private $escapear;
	private $limpia_espacios;
	
	function __construct($nombre, $etiqueta, $exp_reg="", $acepta_nulo=true, $valor="", $msg, $escapear=true, $limpia_espacios=true){
		$this->nombre = $nombre;
		$this->acepta_nulo = $acepta_nulo;
		$this->exp_reg = $exp_reg;
		$this->etiqueta = $etiqueta;
		$this->valor = $valor;
		$this->msg = $msg;
		$this->escapear = $escapear;
		$this->limpia_espacios = $limpia_espacios;
	}
	
	function setValor($valor){
		if($this->limpia_espacios){
			$valor = ltrim(rtrim($valor)); // ltrim : Retira espacios en blanco (u otros caracteres) del inicio de un string. rtrim : Retira los espacios en blanco (u otros caracteres) del final de un string
		}	
		if($this->escapear){
			$valor = escapeshellcmd($valor); // escapeshellcmd : Escapar meta-caracteres del intérprete de comandos
		}	
		$this->valor = $valor;
	}
	
	function getNombre(){
		return $this->nombre;
	} 
	
	function getAceptaNulo(){
		return $this->acepta_nulo;
	}
	
	function getExpReg(){
		return $this->exp_reg;
	}
	
	function getEtiqueta(){
		return $this->etiqueta;
	}
	
	function getValor(){
		return $this->valor;
	}
	
	function getMensaje(){
		return $this->msg;
	}
	
	function validar(){
		if($this->valor == ""){
			return ($this->acepta_nulo);
		}else{
			if($this->exp_reg==""){
				return true;
			}else{
				return return preg_match("/".$this->exp_reg."/",$this->valor);
			}
		}		
	}
	
	function getTipo(){
		return "Campo";
	}
	
	function llenarCampo($tpl,&$form){
		if($this->etiqueta!=""){
			$form = $tpl->replace($form, array($this->etiqueta=>$this->valor));
		}
	}
			 
}

?>
