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

		$result = $this->ifCPFNull($cpf, $value);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		return array("status"=>true,"erro"=>"");
	}

	//3006
	function isEmailValid($value, $target){

		if($value != ""){
			$result = $this->isGreaterThan(strlen($value), $target);
			if($result['status']){
				$len = strlen($value);
				return array("status"=>false,"erro"=>"'$value' contém número de caracteres maior que o permitido.");
			}


			$result = $this->validateEmailFormat($value);
			if (!$result['status']){
				return array("status"=>false,"erro"=>$result['erro']);
			}
		}
		

		return array("status"=>true,"erro"=>"");

	}

	//campo 08
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

	function validateParent($parent, $high_limit, $cpf){

		$result = $this->isGreaterThan(strlen($parent), $high_limit);
		if($result['status']){
			return array("status"=>false,"erro"=>"'$filiation_father' $contém número de caracteres maior que o permitido.");
		}


		$result = $this->onlyAlphabet($parent);
		if (!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}


		$result = $this->ifCPFNull($cpf, $parent);
		if (!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		return array("status"=>true,"erro"=>"");

	}
	//11, 12, 13
	function validateFiliation($filiation, $filiation_mother, $filiation_father, $cpf, $high_limit){

		$result = $this->isAllowed($filiation, array("0", "1"));
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		if($filiation == "1"){

			if(!($filiation_mother != "" || $filiation_father != "")){
				return array("status"=>false,"erro"=>"Uma das filiações deve ser preenchida");
			}

			if($filiation_mother != ""){

				$result = $this->validateParent($filiation_mother, $high_limit, $cpf);
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
				
			}

			if($filiation_father != ""){

				$result = $this->validateParent($filiation_father, $high_limit, $cpf);
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}

			}

		}else{

			if(!($filiation_mother == null && $filiation_father == null)){
				return array("status"=>false,"erro"=>"Ambas filiãções deveriam ser nulas campo 11 é 0");
			}

		}

		return array("status"=>true,"erro"=>"");

	}

	function brazil($nationality, $nation){

		if($nationality == 1 || $nationality == 2){
			if($nation != "76"){
				return array("status"=>false,"erro"=>"País de origem deveria ser Brasil");
			}
		}else{
			if($nation == "76"){
				return array("status"=>false,"erro"=>"País de origem não deveria ser Brasil");
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	function ufcity($nationality, $city){

		if($nationality == 1){
			if($nation == "" || $city == null){
				return array("status"=>false,"erro"=>"Cidade deveria ser preenchida");
			}
		}else{
			if($nation != ""){
				return array("status"=>false,"erro"=>"Cidade não deveria ser preenchida");
			}
		}

		return array("status"=>true,"erro"=>"");
	}

	function exclusiveDeficiency( $deficiency, $excludingdeficiencies){

		if($this->atLeastOne($excludingdeficiency)){
			if($deficiency != "0"){
				return array("status"=>false,"erro"=>"Valor $deficiency deveria ser 0");
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	function checkDeficiencies($hasdeficiency, $deficiencies){

		if($hasdeficiency == "1"){
			if(!$this->atLeastOne($deficiencies)){
				return array("status"=>false,"erro"=>$result['erro']);
			}

			foreach ($deficiencies as $deficiency => $excludingdeficiencies) {
				$result = $this->exclusiveDeficiency($deficiency, $excludingdeficiencies);
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

		}elseif ($hasdeficiency == "0"){
			foreach ($deficiencies as $deficiency => $excludingdeficiencies) {
				if($deficiency != null){
					return array("status"=>false,"erro"=>"Valor deveria ser nulo");
				}

			}
		}

		return array("status"=>true,"erro"=>"");

	}

}