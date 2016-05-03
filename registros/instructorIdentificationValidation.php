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


	

}