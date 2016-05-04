<?php

class studentIdentificationValidation extends Register{
	
	function __construct() {
	}

	//campo 08
	function validateBirthday($date, $lowyear_limit, $currentyear){

		$result = $this->validateDateformart($date);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		$mdy = explode('/', $date);

		$result = $this->isGreaterThan($mdy[2], 1910);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		$result = $this->isNotGreaterThan($mdy[2], $currentyear);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		return array("status"=>true,"erro"=>"");

	}

	function specialNeeds($value, $allowedvalues, $requirement){

		$result = $this->isAllowed($value, $allowedvalues);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		if($requirement == '1'){
			if($value != '1'){
				return array("status"=>false,"erro"=>"Valor deveria ser 1 pois estudante possui deficiência");
			}
		}

		return array("status"=>true,"erro"=>"");

	}
}

?>