<?php
/* la idea es que la etiqueta selected sea del tiopo TEXTO_VALOR
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
class Campo_Select_Multiple{
	
	private $nombre;
	private $valores=array();
	private $etiqueta_selected;
	private $valores_posibles=array();
	
	function __construct($nombre, $etiqueta_selected, $valores=array(), $valores_posibles=array()){
		$this->nombre = $nombre;
		$this->valores = $valores;
		$this->etiqueta_selected = $etiqueta_selected;
		$this->valores_posibles = $valores_posibles;
	}
	
	function setValor($valor){
		if (is_array($valor)){
			$this->valores = $valor;
		}elseif ($valor!=""){ 
			$this->valores[$valor] = $valor;
		}
	}
	
	function getValor(){
		return $this->valores;
	}
	
	function resetValores(){
		$this->valores = array();
	}
	
	function setValorPosible($valor){
		array_push($this->valores_posibles,$valor);
	}
	
	function getValoresPosibles(){
		return $this->valores_posibles;
	}
	
	function resetValoresPosibles(){
		$this->valores_posibles = array();
	}
	
	function getNombre(){
		return $this->nombre;
	} 
	
	function getTipo(){
		return "Select_Multiple";
	}
	
	function validar(){
		return true;
	}
	
	function llenarCampo($tpl,&$form){
		if($this->etiqueta_selected!=""){
			foreach ($this->valores_posibles as $v){
				if(isset($this->valores[$v])){
					$form = $tpl->replace($form, array($this->etiqueta_selected."_".$v=>"SELECTED"));
				}else{
					$form = $tpl->replace($form, array($this->etiqueta_selected."_".$v=>""));
				}
			}
		}
	}
		
}

?>
