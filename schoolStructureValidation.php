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
function isEqual($x, $y, $msg){
	if($x != $y){
		echo $msg;
		return false;
	}
	return true;
}

//campo 03 à 11
function atLeastOne($operation_locations){
	$number_of_ones = 0;
	for($i = 0; $i < sizeof($operation_locations); $i++){
		if($operation_locations[$i]=="1")
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

//campo 12
function buildingOccupationStatus($collun3, $collun8, $value){

	if($collun3 == 1){
		if($value == 1 || $value == 2 || $value == 3){
			return true;
		}
	}elseif($collun == 8){
		return true;
	}elseif($collun3 != 1 && $collun8 != 1){
		if($value == null){
			return true;
		}
	}
	echo "Não atende condições";
	return false;
}

//campo 13
function sharedBuildingSchool($collun3, $value){

	if($collun3 == 1){
		if($value == 0 || $value == 1){
			return true;
		}else{
			echo "valor não permitido";
			return false;
		}
	}
	return true;
}

//campo 14









