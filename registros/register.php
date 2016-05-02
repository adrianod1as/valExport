<?php

/**
* 
*/
class Register

{
	
	function __construct(){
	}

	function isNull($x){
		if($x == null){
			return array("status"=>true,"erro"=>"");
		}
		return array("status"=>false,"erro"=>"Valor não é nulo");
		
	}


	function ifNull($value){
		if($value == null)
			$value = "nulo";
		return $value;
	}

	//campo 1002
	function isEqual($x, $y, $msg){
		
		$result = $this->isNUll($x);

		if($result['status']){
			return array("status"=>false,"erro"=>"valor é nulo");
		}
		if($x != $y){
			return array("status"=>false,"erro"=>$msg);
		}
		return array("status"=>true,"erro"=>"");
	}

	//campo 1003 à 1011, 1033 à 1038
	function atLeastOne($items){
		$number_of_ones = 0;
		for($i = 0; $i < sizeof($items); $i++){
			if($items[$i]=="1")
				$number_of_ones++; 
		}
		if($number_of_ones==0){
			return array("status"=>false,"erro"=>"Não há nenhum valor marcado");
		}
		return array("status"=>true,"erro"=>"");
	}

	function moreThanOne($items){

		$number_of_ones = 0;
		for($i = 0; $i < sizeof($items); $i++){
			if($items[$i]=="1")
				$number_of_ones++; 
		}
		if($number_of_ones<1){
			return array("status"=>false,"erro"=>"Não há mais de um valor marcado");
		}
		return array("status"=>true,"erro"=>"");

	}


	//campo 1001, 3001, 6001
	function isRegister($number, $value){
		$result = $this->isEqual($value, $number, "Valor $value deveria ser $number");
		if(!$result["status"]){
			return array("status"=>false,"erro"=>$result['erro']);
		}
		
		return array("status"=>true,"erro"=>"");
	}

	//campo 1002, 3002, 6002
	function isAllowedInepId($inep_id, $allowed_inep_ids){
		if(!in_array($inep_id, $allowed_inep_ids)){
			return array("status"=>false,"erro"=>"inep_id $inep_id não está entre os permitidos");

		}

		return array("status"=>true,"erro"=>"");
	}

	//campo 3003, 6003
	function isNumericOfSize($allowed_length, $value){
		if(is_numeric($value)){
			$len = strlen($value);
			if($len != $allowed_length){
				return array("status"=>false,"erro"=>"valor deveria ter $allowed_length caracteres ao invés de $len");
			}
		}else{
			$value = $this->ifNull($value);
			return array("status"=>false,"erro"=>"valor $value não é numérico");
		}

		return array("status"=>true,"erro"=>"");
	}

	//1070, 1088
	function isGreaterThan($value, $target){
		
		if($value <= $target){
			$value = $this->ifNull($value);
			return array("status"=>false,"erro"=>"Valor $value não é maior que o alvo.");
		}
		return array("status"=>true,"erro"=>"");
	}

	//3004, 6004
	function isNotGreaterThan($value, $target){
		
		$result = $this->isGreaterThan(strlen($value), $target);
		if($result['status']){
			return array("status"=>false,"erro"=>"Valor $value é maior que o alvo.");
		}
		
		return array("status"=>true,"erro"=>"");
	}

	function onlyAlphabet($value){

		$regex="/^[a-zA-Z ]+$/";
		if (!preg_match($regex, $value)){
			return array("status"=>false,"erro"=>"aqui'$value' contém caracteres inválidos");
		}

		return array("status"=>true,"erro"=>"");

	}

	//3005, 6005
	function isNameValid($value, $target, $cpf){
		
		$result = $this->isGreaterThan(strlen($value), $target);
		if($result['status']){
			return array("status"=>false,"erro"=>"Número de caracteres maior que o permitido.");
		}

		$result = $this->onlyAlphabet($value);
		if (!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		$result = $this->ifCPFNull($cpf, $value);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		return array("status"=>true,"erro"=>"");
	}


	function validateEmailFormat($email){

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			return array("status"=>false,"erro"=>"'$email' contém caracteres inválidos");
		}

		return array("status"=>true,"erro"=>"");

	}

	function validateDateformart($date){

		//separa data em dia, mês e ano
		$mdy = explode('/', $date);

		// verifica se a data é valida. Mês-dia-ano
		if(!checkdate( $mdy[1] , $mdy[0] , $mdy[2] )){
			return array("status"=>false,"erro"=>"'$date' está inválida");
		}
		
		return array("status"=>true,"erro"=>"");
		
	}

	function getAge($birthyear, $currentyear){
		$age = $currentyear - $birthyear;
		return $age;
	}

	function isOlderThan($target_age, $birthyear, $currentyear){
		
		$age = $this->getAge($birthyear, $currentyear);
		$result = $this->isGreaterThan($age, $target_age);
		if(!$result['status']){
			return array("status"=>false,"erro"=>"idade $age é menor que o permitido ($target_age)");
		}

		return array("status"=>true,"erro"=>"");

	}

	function isYoungerThan($target_age, $birthyear, $currentyear){

		$age = $this->getAge($birthyear, $currentyear);
		$result = $this->isNotGreaterThan($age, $target_age);
		if(!$result['status']){
			return array("status"=>false,"erro"=>"idade $age é maior que o permitido ($target_age)");
		}

		return array("status"=>true,"erro"=>"");

	}

	//campo 1020, 3009
	function oneOfTheValues($value){
		if($value == 1 || $value == 2){
			return array("status"=>true,"erro"=>"");
		}
		$value = $this->ifNull($value);
		return array("status"=>false,"erro"=>"Valor $value não está entre as opções");

	}


	//10101, 10105, 10106, 3010

	function isAllowed($value, $allowed_values){

		if(!in_array($value, $allowed_values)){
				$value = $this->ifNull($value);
				return array("status"=>false,
								"erro"=>"Valor $value não está entre as opções");
		}
		return array("status"=>true,"erro"=>"");
	}

	function ifCPFNull($cpf, $value){

		if($cpf == null){
			
			if(str_word_count($value) < 2){
				return array("status"=>false,"erro"=>"'$value' possui cpf nulo e não contém mais que 2 palavras");
			}

			if (preg_match('/(\w)\1{5,}/', $input)) {
				return array("status"=>false,"erro"=>"'$value' possui cpf nulo e contém mais de 4 caracteres repetidos");
			}
			
		}

		return array("status"=>true,"erro"=>"");

	}





}

?>