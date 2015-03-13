<?php


class TPL {

	function __construct(){
	// Constructor de la clase
	}
	
	function load($tpls) {
	// Carga las plantillas (templates) indicadas en $tpls en memoria
	// Se le pasa un array de strings indicando los archivos a leer o un solo string. 
	// Para cada uno se devuelve un string con su contenido
		if (!is_array($tpls)){ // Si no es un arreglo de templates
			$auxTpls = array($tpls); // Creo un arreglo que en la primer posicion tiene el template pasado como parametro
		}else{
			$auxTpls = $tpls; // Si es un arreglo lo dejo igualito
		}	
		$stringTpls = array(); // Arreglo de templates a retornar
		foreach($auxTpls as $tpl){ 
			$stringTpls[] = join("", file($tpl)); // Agrego al arreglo a retornar el contenido de todo el archivo (template).
		}	
		if (!is_array($tpls)){ // Si no es un arreglo de templates
			$stringTpls = $stringTpls[0]; // Retorno un string en vez de un arreglo de strings con un solo elemento
		}	
		return $stringTpls;
	}
	
	function replace ($subject, $search, $replace=NULL) {
	// Realiza reemplazos en el string subject, donde search puede ser un array asociativo con valores de busqueda y reemplazo o se le puede pasar en search
	// un string de busqueda y en replace un string de reemplazo. Retorna el string con los cambios producidos. NOTA: Los reemplazos son simultaneos no repetitivos.
		if (!is_array($search)){ 
			$search = array($search=>$replace);
		}	
		$searchAndReplace = array();
		foreach($search as $clave=>$valor){
			$searchAndReplace["{".$clave."}"] = $valor;
		}
		$subject_con_reemplazos = strtr($subject, $searchAndReplace);
		return $subject_con_reemplazos;
	}
	
	function unComment ($subject, $tags) {
	// Descomenta codigo abarcado por la tag indicada ("<!--{tag}" y "{tag}-->"), tag puede ser una sola tag (un string) o muchas tags (array de string).
	// Retorna el string con los cambios producidos. NOTA: Los reemplazos son simultaneos no repetitivos.
		if (!is_array($tags)){ // Si no es un arreglo de tags
			$tags = array($tags); // Creo un arreglo que en la primer posicion tiene el tag pasado como parametro
		}	
		$searchAndReplace = array(); // Arreglo de cadenas a buscar y remplazar en $subject
		foreach($tags as $tag){ // para cada tag del arreglo
			$searchAndReplace["<!--{".$tag."}"] = "";
			$searchAndReplace["{".$tag."}-->"]  = "";
		}
		$subject_sin_tags = strtr($subject, $searchAndReplace);// todas las apariciones de las claves del array han sido reemplazadas por los valores correspondientes
		return $subject_sin_tags;	
	}

}

?>