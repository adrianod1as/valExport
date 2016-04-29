<?php
$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) .  $DS . "register.php");
/**
* 
*/
class InstructorIdentificationValidation extends Register
{
	
	function __construct()
	{
		# code...
	}

	//3005
	function isNameValid($value, $target, $cpf){
		
		$result = $this->isGreaterThan(strlen($value), $target);
		if($result['status']){
			return array("status"=>false,"erro"=>"Número de caracteres maior que o permitido.");
		}

		$result = $this->onlyAlphabet($value);
		if (!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
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

	//3006
	function isEmailValid($value, $taget){

		$result = $this->isGreaterThan(strlen($value), $target);
		if($result['status']){
			return array("status"=>false,"erro"=>"Número de caracteres maior que o permitido.");
		}


		$result = $this->validateEmailFormat($value);
		if (!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		return array("status"=>true,"erro"=>"");

	}

	function validateBirthday($date, $low_limit, $high_limit, $currentyear){

		$result = $this->validateDateformart($date);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		$mdy = explode('/', $date);

		$result = $this->isOlderThan($low_limit, $mdy[2], $currentyear);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		$result = $this->isYoungerThan($high_limit, $mdy[2], $currentyear);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		return array("status"=>true,"erro"=>"");

	}

	function filiation($filiation,  $filiation_mother, $filiation_father){

		$result = $this->isAllowed($filiation, array("0", "1"));
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		if($filiation == "1"){
			if(!($filiation_mother != "" || $filiation_father != "")){
				return array("status"=>false,"erro"=>"Uma das filiações deve ser preenchida");
			}
		}

		return array("status"=>true,"erro"=>"");
		
	}


}