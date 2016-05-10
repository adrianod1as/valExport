<?php
$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) .  $DS . "register.php");

class studentEnrollmentValidation extends Register{
	
	function __construct() {
	}

	function multiLevel($value, $demand){
		$result = $this->isAllowed($demand, array('12', '13', '22', '23', '24', '72', '56', '64'));
		if($result['status']){

			if($demand == '12' || $demand == '13'){
				$result = $this->isAllowed($value, array('4', '5', '6', '7', '8', '9', '10', '11'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

			if($demand == '22' || $demand == '23'){
				$result = $this->isAllowed($value, array('14', '15', '16', '17', '18', '19', '20', '21', '41'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

			if($demand == '24'){
				$result = $this->isAllowed($value, array('4', '5', '6', '7', '8', '9', '10', '11', 
															'14', '15', '16', '17', '18', '19', '20', '21', '41'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

			if($demand == '72'){
				$result = $this->isAllowed($value, array('69', '70'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

			if($demand == '56'){
				$result = $this->isAllowed($value, array('1', '2', '4', '5', '6', '7', '8', '9', '10', '11',
															'14', '15', '16', '17', '18', '19', '20', '21', '41'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

			if($demand == '64'){
				$result = $this->isAllowed($value, array('39', '40'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}




		}else{
			if($value != null){
				return array("status"=>false,"erro"=>"value $value deveria ser nulo");
			}
		}

		return array("status"=>true,"erro"=>"");

	}

}

?>