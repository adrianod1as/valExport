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
			return array("status"=>false,"erro"=>"Menos de 8 carácteres");
		}
		
		return array("status"=>true,"erro"=>"");
	}
}

?>