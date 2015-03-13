<?php
/* la idea es que la etiqueta selected sea del tipo TEXTO_VALOR
 * 
 * por ejemplo, un si/no seria
 * 
 * <select name="sino">
 *	<OPTION value="S" {SINO_S}>Si
 *	<OPTION value="N" {SINO_N}>No
 *	</SELECT>
 *
 * habria que llamar con los valores: new Campo_Select("sino","SINO","",array("S","N"))
 * 
 * */
class Campo_Select{

	private $nombre;
	private $valor;
	private $etiqueta_selected;
	private $valores_posibles;
	private $valores_validos;
	private $msg;
	private $validar;
	
	function __construct($nombre, $etiqueta_selected, $valor="", $msg="", $valores_posibles=array(), $valores_validos="", $validar=true){
		$this->nombre = $nombre;
		$this->valor = $valor;
		$this->etiqueta_selected = $etiqueta_selected;
		$this->valores_posibles = $valores_posibles;
		$this->validar = $validar;
		if($valores_validos==""){
			$valores_validos = $valores_posibles;
		}	
		$this->valores_validos = array();
		foreach ($valores_validos as $v){
			$this->valores_validos[$v] = $v;
		}
		$this->msg = $msg;
	}
	
	function setValor($valor){
		$this->valor=$valor;
	}
	
	function getNombre(){
		return $this->nombre;
	} 
	
	function getValor(){
		return $this->valor;
	}
	
	function getTipo(){
		return "Select";
	}
	
	function validar(){
		if($this->validar){
			return isset($this->valores_validos[$this->valor]);
		}else{
			return true;
		}
	}
	
	function getMensaje(){
		return $this->msg;
	}
	
	function llenarCampo($tpl,&$form){
		if($this->etiqueta_selected!=""){
			$encontro=false;
			foreach ($this->valores_posibles as $v){
				if($v==$this->valor){
					$form = $tpl->replace($form, array($this->etiqueta_selected."_".$v=>"SELECTED"));
					$encontro=true;
				}else{
					$form = $tpl->replace($form, array($this->etiqueta_selected."_".$v=>""));
				}
			}
			if(!$encontro){
				$form = $tpl->replace($form, array($this->etiqueta_selected."_".$this->valor=>"SELECTED"));
			}	
		}
	}

}
?>