<?php

function ordenar_array(&$array,$clave,$direccion){
	/* ambos algoritmos son buenos dependiendo de la
	 * cantidad de elementos.
	 *
	 * $array es el array a ordenar
	   $clave es la clave del hash por la que queremos ordenar
	   $direccion => 0 si el ordenamiento es de menor a mayor
	   				 y 1 en caso contrario
	 */
	if (count($array)>10){
		ord_QS_hash_optimizado($array,0,count($array)-1,$clave,$direccion);
	}else{
		ord_INS_hash($array,0,count($array)-1,$clave,$direccion);
	}
}

function ordenar_array_a_trompadas($array,$clave,$direccion){
	/*es una primera prueba
	* por ahora no la borro porque me da lastima
	*/
	$salida=array();
	while (list($key, $val) = each ($array)){
		if (!isset($salida[$val->$clave])){
			$salida[$val->$clave]=array();
		}	
		array_push($salida[$val->$clave],$val);
	}
	if ($direccion=="ascendente"){
		ksort($salida);
	}else{		
		krsort($salida);
	}	
	$salida_posta=array();
	while (list($key, $val) = each ($salida)){
		while (list($key2, $val2) = each ($val)){
			array_push($salida_posta,$val2);
		}
	}
	return $salida_posta;
}

function ord_QS_hash(&$array,$inf,$sup,$clave,$direccion){
	/*
		Quick Sort 
			Sugerencia: utilizar siempre ord_QS_hash_optimizado
		El algoritmo es eficiente para arrays relativamente grandes (mas 
		de 10 elementos), para arrays mas chicos se recomienda el ordenamiento
		por insercion 
	
	   $array es el array a ordenar
	   $inf es el indice inferior (en general 0)
	   $sup es el indice superior (en general count($array)-1)
	   $clave es la clave del hash por la que queremos ordenar
	   $direccion => 0 si el ordenamiento es de menor a mayor
	   				 y 1 en caso contrario

	   Optimizaciones:
	   			seria bueno que cuando se llega a un "subarray" de menos
	   			de 10 elementos, en lugar de seguir llamando al mismo metodo
	   			llame al insertion sort	   
	*/
	$izq=$inf;
	$der=$sup;
	$x=$array[$inf];
	while($izq<$der){
	 	if($direccion==0){
	 		while($array[$izq]->$clave<=$x->$clave and $izq<$der){
	 			$izq++;
	 		}
	 		while($array[$der]->$clave>$x->$clave){
	 			$der--;
	 		}
	 	}else{
	 		while($array[$izq]->$clave>=$x->$clave and $izq<$der){
	 			$izq++;
			}
	 		while($array[$der]->$clave<$x->$clave){
	 			$der--;
			}
		}
      	if ($izq<$der){
            $aux= $array[$izq];
            $array[$izq]=$array[$der];
            $array[$der]=$aux;
		}
	}
	$array[$inf]=$array[$der];
	$array[$der]=$x;
	if($inf<($der-1)){
		ord_QS_hash($array,$inf,($der-1),$clave,$direccion);
	}
	if(($der+1)<$sup){
		ord_QS_hash($array,($der+1),$sup,$clave,$direccion);
	}
}
	  
function ord_INS_hash(&$array,$inf,$sup,$clave,$direccion){
		/*  Ordenamiento por insercion
		El algoritmo es eficiente para arrays relativamente chicos (menos 
		de 10 elementos)
		mantiene el orden relativo en el array, para los casos en que las claves
		son iguales
	
	    $array es el array a ordenar
	    $inf es el indice inferior (en general 0)
	    $sup es el indice superior (en general count($array)-1)
	    $clave es la clave del hash por la que queremos ordenar
	    $direccion => 0 si el ordenamiento es de menor a mayor
	   				 y 1 en caso contrario
	   
	    */
	for ($i=1;$i<=$sup;$i++){
		$temp = $array[$i];
		$j = $i-1;
		while( ($j>=$inf) and ((($direccion==0)and($array[$j]->$clave>$temp->$clave)) or (($direccion==1)and($array[$j]->$clave<$temp->$clave)))){
			$array[$j+1] = $array[$j];
			$j--;
		}
		$array[$j+1] = $temp;
	}
}

function ord_QS_hash_optimizado(&$array,$inf,$sup,$clave,$direccion){
	/*
	Quick Sort:
		El algoritmo es eficiente para arrays relativamente grandes (mas 
		de 10 elementos), para arrays mas chicos se recomienda el ordenamiento
		por insercion 
		Cuando se llega a un "subarray" de menos de 10 elementos, 
		en lugar de seguir llamando al mismo metodo	llama al insertion sort
	
	Parametros:	
	   $array es el array a ordenar
	   $inf es el indice inferior (en general 0)
	   $sup es el indice superior (en general count($array)-1)
	   $clave es la clave del hash por la que queremos ordenar
	   $direccion => 0 si el ordenamiento es de menor a mayor
	   				 y 1 en caso contrario
	*/
	$izq = $inf;
	$der = $sup;
	$x = $array[$inf];
	while($izq<$der){
	 	if($direccion==0){
	 		while($array[$izq]->$clave<=$x->$clave and $izq<$der){
	 			$izq++;
	 		}
	 		while($array[$der]->$clave>$x->$clave){
	 			$der--;
			}
		}else{
	 		while($array[$izq]->$clave>=$x->$clave and $izq<$der){
	 			$izq++;
			}
	 		while($array[$der]->$clave<$x->$clave){
	 			$der--;
			}
		}
      	if($izq<$der){
            $aux= $array[$izq];
            $array[$izq]=$array[$der];
            $array[$der]=$aux;
		}
	}
    $array[$inf]=$array[$der];
    $array[$der]=$x;
    if($inf<($der-1)){
		if($der-$inf>10){
			ord_QS_hash_optimizado($array,$inf,($der-1),$clave,$direccion);
		}else{
			ord_INS_hash($array,$inf,($der-1),$clave,$direccion);
		}
	}
    if(($der+1)<$sup){
		if($sup-$der>10){
			ord_QS_hash_optimizado($array,($der+1),$sup,$clave,$direccion);
		}else{
			ord_INS_hash($array,($der+1),$sup,$clave,$direccion);
		}	
	}
}
 
function pertenece_hash($valor,$lista,$clave){
	while(list($key,$val)=each($lista)){
		if (($val->$clave)==$valor or ($val[$clave])==$valor){
			return true;
		}
	}
	return false;
}

function borrar_elem($valor,&$lista){
	while(list($key,$val)=each($lista)){
		if ($val==$valor){
			unset($lista[$key]); // Destruye el elemento de la lista
		}	
	}
	return false;
}

function borrar_elem_hash($valor,&$lista,$clave){
	while(list($key,$val)=each($lista)){
		if ($val->$clave==$valor or $val[$clave]==$valor){
			unset($lista[$key]); // Destruye el elemento de la lista
		}	
	}
}


?>