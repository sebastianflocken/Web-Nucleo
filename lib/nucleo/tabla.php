<?php

include_once("tabla/Encabezado.php"); 
include_once("funciones_array.php");

/*
 * 	La clase tabla va a consistir de un encabezado y una serie de renglones.
 *  En principio, la tabla obtenida va a seguir el standard, por lo tanto no es necesario agregarle un estado al titulo, por ejemplo.
 *  Si quiesiera darle propiedades al estilo del titulo de la tabla deberia poner:
 * 		#clase_del_div_contenedor table thead{....}
 *  El caso de los renglones es distinto, en principio se podria poner:
 * 		#clase_del_div_contenedor table tbody{....}
 *  o podemos indicar renglones particulares que queramos resaltar.
 *  En caso de que tengamos estilos alternados para las filas, es posible agregarlos de a uno.
 *  Tambien es posible setear un estilo para los ELEMENTOS (no para el encabezado) de una
 *  columna. La explicación detallada se encuentra en "addEncabezado"
 */

class Tabla {
	
	private $renglones = array();
	private $encabezado = array();
	private $estilos = array();
	private $tope = 0;
	private $modo = "V";
	private $method;
	private $action;
	private $id = "";
	
	function __construct ($id=""){
	// Constructor de la clase	
		$this->encabezado = new Encabezado();
		$this->id = $id;
	}
	
	/* Para agregar un encabezado se debe indicar:
	 * 		titulo => el que aparecerá como titulo de la columna
	 * 		etiqueta => valor por el que se buscará en el hash de renglones
	 * 		orden => orden de la columna en la tabla
	 * 		clase (opcional) => clase que se asignará en cada renglón, 
	 * 							para la nueva columna
	 * 		modo_crear		 => codigo html para modo crear
	 * 							por ejemplo: <input type="text" id="actual" name="actual" tabindex="2">
	 * La funcion devuelve true si no existía otra columna con el mismo orden, 
	 * en cuyo caso no se agrega la nueva columna. 
	 */
	function addEncabezado($titulo,$etiqueta,$orden,$clase="",$modo_crear=""){
		return $this->encabezado->addColumna($titulo,$etiqueta,$orden,$clase,$modo_crear);
	}


	function setEncabezado($encabezado=array()) {
		$this->encabezado = $encabezado;
	}
	
	function columnasValidas($columnas=array()){
	// Devuelve true si las etiquetas son columnas de la tabla
		while (list($key, $val) = each ($columnas)){ // each : Devolver el par clave/valor actual de un array y avanzar el cursor del array
			if(!$encabezado->pertenece($val)){
				return false;
			}	
		}		
		return true;
	}

	/* agrega un renglon a la tabla
	 * los renglones deben ser clases (no arrays) de la forma
	 * 		renglon->"etiqueta_columna" = valor_columna;
	 */
	function addRenglon($renglon=array()){
		$this->renglones[$this->tope] = $renglon;
		$this->tope++;
	}
	
	function addEstilo($estilo){ 
		array_push($this->estilos,$estilo); // Inserta uno o más elementos al final de un array
	}
	
	
	/* devuelve la tabla
	 * en renglones_resaltados se debera indicar
	 * el valor que deberan tener los renglones en determinada(s) columna(s) para ser resaltados
	 *    array("etiqueta"=>"valor",
	 * 			.....) */
	function setModoCrear($method="POST",$action="#"){
		$this->modo = 'C';
		$this->method = $method;
		$this->action = $action;
	}
	
	function setModoVer(){
		$this->modo = 'V';
	}

	function getTabla($renglones_resaltados=array(),$estilo_reslatado=""){
		$res="";
		if($this->modo=='C'){
			$res.='<form method="'.$this->method.'" action="'.$this->action.'">';
		}	
		if ($this->id !=""){ 	
			$res.="<table id='$this->id' >";
		}else{
			$res.="<table>";
		}	
		$res.=$this->encabezado->getEncabezado();
		$res.="<tbody>";
		$etiquetas=$this->encabezado->getEtiquetas();
		if($this->modo=='C'){
			$res.=$this->getRenglonCrear($etiquetas);
		}	
		for($i=0;$i<$this->tope;$i++){
			$estilo="";
			if($estilo_reslatado!="" and $this->cumple_filtro($this->renglones[$i],$renglones_resaltados)){
				$estilo=$estilo_reslatado;
			}	
			if($estilo=="" and count($this->estilos)!=0){
				$estilo=$this->estilos[fmod($i,count($this->estilos))];
			}	
			$res.=$this->getRenglon($this->renglones[$i],$etiquetas,$estilo);
		}
		$res=$res."</tbody></table>";
		if($this->modo=='C'){
			$res.='</form>';
		}
		return $res;
	}

	function getRenglonCrear($etiquetas){
		$res="<tr>";
		while(list($k,$valor)=each($etiquetas)){
			if($valor["clase"]==""){
				$res.="<td>".$valor["modoCrear"]."</td>";			
			}else{
				$res.='<td class="'.$valor["clase"].'">'.$valor["modoCrear"]."</td>";
			}
		}
		return $res."</tr>";
	}
	
	function filtrar($filtros){
	// Elimina de la tabla aquellos renglones que cumplan con los filtros establecidos
		$i=0;
		$eliminados=0;
		while($i<$this->tope){
			if($this->cumple_filtro($this->renglones[$i],$filtros)){
				$this->renglones[$i]=$this->renglones[$this->tope-1];
				$this->tope--;
				unset($this->renglones[$this->tope]);
			}else{
				$i++;
			}	
		}
	}

	function cumple_filtro($renglon,$filtros){
		while(list($etiqueta,$valor)=each($filtros)){
			if($renglon->$etiqueta!=$valor){ 
				return false;
			}	
		}
		return true;
	}
	
	function ordenar($etiqueta,$direccion=0){
		ordenar_array($this->renglones,$etiqueta,$direccion);
	}
	
	function getRenglon($renglon,$etiquetas=array(),$estilo=""){
		$res="";
		if($estilo==""){
			$res.="<tr>";
		}else{
			$res.='<tr class="'.$estilo.'">';
		}	
		while (list($key, $val) = each ($etiquetas)){
			$valor=$renglon->$val["etiqueta"];
			if($val["clase"]==""){
				$res.="<td>".$valor."</td>";			
			}else{
				$res.='<td class="'.$val["clase"].'">'.$valor."</td>";
			}	
		}
		return $res.="</tr>";
	}
	
}

?>