<?php
//Validações para a tabela school_structure

class SchoolStructureValidation {

	function __construct() {
	}

	function isNull($x){
		if($x == null){
			echo "Numéro é nulo";
			return true;
		}
		return false;
	}

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
		if($this->isNUll($x)){
			return false;
		}
		if($x != $y){
			echo $msg."</br>";
			return false;
		}

		return true;
	}

	//campo 03 à 11, 33 à 38
	function atLeastOne($operation_locations){
		$number_of_ones = 0;
		for($i = 0; $i < sizeof($operation_locations); $i++){
			if($operation_locations[$i]=="1")
				$number_of_ones++; 
		}
		if($number_of_ones==0){
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
		echo "Não atende condições"."</br>";
		return false;
	}

	//campo 13
	function sharedBuildingSchool($collun3, $value){

		if($collun3 == 1){
			if($value == 0 || $value == 1){
				return true;
			}else{
				echo "valor não permitido"."</br>";
				return false;
			}
		}else{
			if(($value != null)){
				echo "Coluna 3 não é 1. Valor deve ser nulo";
				return false;
			}
		}
		return true;
	}

	//campo 13, 69, 87
	function isAllowedValue($collun, $value, $allowed_values){

		if($collun == 1){
			if(in_array($value, $allowed_values)){
				return true;
			}else{
				echo "valor não permitido"."</br>";
				return false;
			}
		}
		return true;
	}

	//campo 14 à 19
	function sharedSchoolInep($collun13, $inep_id, $shared_schools_inep_ids){

		if($collun13 == 1){
			foreach($shared_schools_inep_ids as $school_inep_id){
				if($this->isEqual(substr($inep_id, 0, 2), substr($school_inep_id, 0, 2), "Escolas não são do mesmo estado"))
				{	
					if(strlen($school_inep_id)){
						echo "Menos de 8 carácteres";
						return false;
					}
					if($inep_id == $school_inep_id){
						echo "Inep id é igual"."</br>";
						return false;
					}
				}else{
					return false;
				}
			}
		}
		return true;
	}

	//campo 20
	function consumedWater($value){
		if($value == 1 || $value == 2){
			return true;
		}
		echo "Valor não está entre as opções";
		return false;
	}

	//campo 21 à 25, 26 à 29, 30 à 32, 39 à 68

	function checkRange($array, $allowed_values){

		foreach ($array as $key => $value) {
			if(!in_array($value, $allowed_values)){
				echo "Valor $key não está entre os permitidos";
				return false;
			}
		}
		return true;
	}

	function supply($supply_locations, $value){

		$len = sizeof($supply_locations);

		if(!$this->checkRange($supply_locations, array("0", "1"))){
			return false;
		}

		if(!$this->atLeastOne($supply_locations)){
			return false;
		}

		if($supply_locations($len-1) == "1"){ //ultimo campo
			for($i = 0; $i < ($len-1); $i++){ //primeiros campos
				if($supply_locations[$i] == "1"){
					echo "Já que ultimo campo 1 não pode haver outros campos marcados como 1"; 
					return false;
				}
			}
		}

		return true;			
	}

	//70, 88
	function isGreaterThan($value, $target){

		if($value > $target){
			echo "Valor não é maior que o alvo.";
			return false;
		}

		return true;
	}

	//71 à 83
	function equipmentAmounts($amounts){

		foreach ($amounts as $key => $value) {
			if(!$value == null){
				if(!isGreaterThan($value, 0)){
					return false;
				}
				if($this->isGreaterThan(strlen($value), 4)){
					echo "Maior que 4 dígitos";
					return false;
				}
			}
		}
		return true;
	}

	//84, 85
	function pcCount($collun82, $value){
		if($collun82 != null){
			if($this->isGreaterThan(strlen($value), 4)){
					echo "Maior que 4 dígitos";
					return false;
				}
			if(!$this->isGreaterThan($value, 0)){
				return false;
			}
			if($value > $collun82){
				echo "Valor é maior que o permitido";
				return false;
			}
			
		}else{
			if($value != null) {
				echo "Coluna 82 é nulo. Valor deve ser nulo";
				return false;
			}
		}
		return true;
		
	}

	//86
	function internetAccess($collun, $value, $allowed_values){

		if($collun != null){
			if(in_array($value, $allowed_values)){
				return true;
			}else{
				echo "valor não permitido"."</br>";
				return false;
			}
		}else{
			if($value != null) {
				echo "Coluna 82 é nulo. Valor deve ser nulo";
				return false;
			}
		}
		return true;
	}

	//89
	function schoolFeeding($collun1028, $value, $collun206){

		if(in_array($collun1028, array("1", "2", "3"))){
			if($value == "1"){
				if(!in_array($collun206, array("1", "2"))){
					echo "Campo 6 do registro 20 deve ser 1 ou 2";
					return false;
				}
			}else{
				echo "Valor deve ser 1 pois a coluna está entre os valores supostos";
				return false;
			}
		}else{
			if($value != "0"){
				echo "Valor deve ser 0 pois a coluna não está entre os valores supostos";
				return false;
			}
		}

		return true;
	}

	//90, 91
	function aee($value, $collun, $complementar_activities, $collun2018){

		if(!in_array($value, array("0", "1", "2"))){
			echo "Não está entre os valores permitidos";
			return false;
		}else{
			if($value == "1" || $value == "2"){
				if($collun2018 != 1){
					echo "Valor igual à 1 ou 2. Coluna 18 do registro 20 deve ser 1";
					return false;
				}
			}
			if($value == "2"){
				if($collun != 0){
					echo "Já que valor 2 a outra coluna deve ser 0";
					return false;
				}
				foreach ($complementar_activities as $key => $value) {
					if($value != null){
						return false;
					}
				}
			}
		}

		return true;
	}

	//92 à 95
	function modalities($collun90, $collun91, $modalities){

		if($collun90 != 2 && $collun91 != 2){
			if(!($collun90 == 1 && $collun91 == 1)){
				if($this->atLeastOne($modalities)){
					return false;
				}
			}
		}

		return true;
	}

}









