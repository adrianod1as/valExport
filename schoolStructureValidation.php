<?php
//Validações para a tabela school_structure

//campo 01
function isRegisterTen($value){
	if($value != "10"){
		echo "valor é diferente de 10"."</br>";
		return false;
	}
	return true;
}

//campo 02
function atLeastOne($array){
	$number_of_ones = 0;
	for($i = 0; $i < sizeof($array); $i++){
		if($array[$i]=="1")
			$number_of_ones++; 
	}
	if($number_of_ones > 1){
		echo "Há mais que um valor marcado"."</br>";
		return false;
	}elseif($number_of_ones==0){
		echo "Não há nenhum valor marcado"."</br>";
		return false;
	}
	return true;
}









