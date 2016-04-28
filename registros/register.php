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
			echo "Numéro é nulo";
			return true;
		}
		return false;
	}


	function ifNull($value){
		if($value == null)
			$value = "nulo";
		return $value;
	}

	//campo 1002
	function isEqual($x, $y, $msg){
		if($this->isNUll($x)){
			return array("status"=>false,"erro"=>"valor é nulo");
		}
		if($x != $y){
			return array("status"=>false,"erro"=>$msg);
		}
		return array("status"=>true,"erro"=>"");
	}

	//campo 1001, 3001
	function isRegister($number, $value){
		$result = $this->isEqual($value, $number, "Valor $value deveria ser $number");
		if(!$result["status"]){
			return array("status"=>false,"erro"=>$result['erro']);
		}
		
		return array("status"=>true,"erro"=>"");
	}

	//campo 3002
	function isAllowedInepId($inep_id, $allowed_inep_ids){
		if(!in_array($inep_id, $allowed_inep_ids)){
			return array("status"=>false,"erro"=>"inep_id $inep_id não está entre os permitidos");

		}

		return array("status"=>true,"erro"=>"");
	}

	//campo 3003
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

	//3004
	function isNotGreaterThan($value, $target){
		
		if($this->isGreaterThan(strlen($value), $target)){
			return array("status"=>false,"erro"=>"Valor $value é maior que o alvo.");
		}
		
		return array("status"=>true,"erro"=>"");
	}

	function isNameValid($value, $target, $cpf){
		
		if($this->isGreaterThan(strlen($value), $target)){
			return array("status"=>false,"erro"=>"Número de caracteres maior que o permitido.");
		}

		$regex="/^[a-zA-Z ]+$/";
		if (!preg_match($regex, $value)){
			return array("status"=>false,"erro"=>"'$value' contém caracteres inválidos");
		}

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