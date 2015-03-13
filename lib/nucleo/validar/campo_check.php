<?php

/*<FORM>
¿Quiénes son amargos?<BR>
<BR><INPUT TYPE="checkbox" NAME="cuervo" VALUE="YES">San Lorenzo
<BR><INPUT TYPE="checkbox" NAME="gallina" VALUE="YES" CHECKED>River PLate
<BR><INPUT TYPE="checkbox" NAME="bostero" VALUE="YES">Boca Juniors
<BR><INPUT TYPE="checkbox" NAME="rojo" VALUE="YES" CHECKED>Independiente
</FORM>*/

class Campo_Check{
	
	private $nombre;
	private $etiqueta;
	private $valor;
	private $check = false;
	
	function __construct($nombre, $etiqueta, $valor="", $check=false){
		$this->nombre = $nombre;
		$this->etiqueta = $etiqueta;
		$this->valor = $valor;
		$this->check = $check;
	}
	
	function setValor($valor){
		$this->valor = $valor;
	}
	
	function setCheck($valor){
		$this->check = $valor;
	}
	
	function getCheck(){
		return $this->check;
	}
			
	function getNombre(){
		return $this->nombre;
	} 
	
	function getEtiqueta(){
		return $this->etiqueta;
	}
	
	function getValor(){
		return $this->valor;
	}
	
	function validar(){
		return true;
	}

	function getTipo(){
		return "Campo_Check";
	}

	function llenarCampo($tpl,&$form){
		if($this->check){
			$form = $tpl->replace($form, array($this->etiqueta=>"CHECKED"));
		}else{
			$form = $tpl->replace($form, array($this->etiqueta=>""));
		}
	}
			 
}

?>