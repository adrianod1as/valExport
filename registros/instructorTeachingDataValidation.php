<?php

$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) .  $DS . "register.php");

class instructorTeachingDataValidation extends Register{

	function checkRole($value, $pedagogical_mediation_type, $assistance_type){

		$result = $this->isAllowed($value, array('1', '2', '3', '4', '5', '6'));
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		if($pedagogical_mediation_type != '1' || $pedagogical_mediation_type != '2'){
			if(!($value == '5' || $value == '6')){
				return array("status"=>false,"erro"=>"Valor $value indisponível devido à pedagogical_mediation_type");
			}
		}

		if($assistance_type == '4' || $assistance_type == '5'){
			if ($value == '2')){
				return array("status"=>false,"erro"=>"Valor $value indisponível devido à assistance_type");
			}
		}






		return array("status"=>true,"erro"=>"");

	}

}

?>