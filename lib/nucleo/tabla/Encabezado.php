<?php

class Encabezado {
	
	private $titulos = array();
	
	function __construct($titulos=array()){
		$this->titulos=$titulos;
		ksort ($this->titulos); // Ordena un array por clave
	}
	
	function addColumna($titulo,$etiqueta,$orden,$clase="",$modo_crear=""){
		if(isset($this->titulos[$orden])){
			return false;
		}
		$this->titulos[$orden]->etiqueta = $etiqueta;
		$this->titulos[$orden]->titulo = $titulo;
		$this->titulos[$orden]->clase = $clase;
		$this->titulos[$orden]->modo_crear = $modo_crear;
		ksort ($this->titulos); // Ordena un array por clave
		return true;
	}
		
	function pertenece($etiqueta){
		while (list($key, $val) = each ($this->titulos)){
			if($val->etiqueta==$etiqueta){
				return true;
			}	
		}	
		return false;
	}
		
	function getEncabezado(){
		$res="<thead><tr>";
		while (list($key, $val) = each ($this->titulos)){
			$res.="<td>".$val->titulo."</td>";
		}
		return $res."</tr></thead>";
	}
		
	function getEtiquetas(){
		$res = array();
		$aux = $this->titulos;
		while ($val = array_shift($aux)){ // array_shift : Quita un elemento del principio del array
			array_push($res,array("etiqueta"=>$val->etiqueta,"clase"=>$val->clase,"modoCrear"=>$val->modo_crear)); // array_push : Inserta uno o más elementos al final de un array
		}
		return $res;
	}
	 
}

?>
