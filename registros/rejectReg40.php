<?php


//registro 40
class InstructorDocumentsAndAddress{


//campo 1
 function isRegisterType40($register_type){

	 	if(strlen($register_type) > 2 ){
	 		return array("status"=>false,"erro"=>"Tipo de registro com tamanho invalido");
	 	}

	 	else if($register_type != 40){
	 		return array("status"=>false,"erro"=>"Tipo de registro invalido");
	 	}
	 	else return array("status"=>true,"erro"=>"");
}

//campo 2 ( deve ser igual ao valor informado no campo 2 do registro 00 antecedente)
function isInepIdValid($inep_id){

	 	if($inep_id == null)
	 		return array("status"=>false,"erro"=>"O campo Código de escola - Inep é uma informação obrigatória.");
	 	else if(strlen($inep_id) != 8)
	 		return array("status"=>false,"erro"=>"O campo Código de escola - Inep está com tamanho diferente do especificado.");
	 	else if(!is_numeric($inep_id))
	 		return array("status"=>false,"erro"=>"O campo Código de escola - Inep foi preenchido com valor inválido");
	 	
		else return array("status"=>true,"erro"=>"");
}

//campo 3 (Deve ser igual ao campo 3 do registro 30)Identicacao Unica do Profissional escolar em sala de aula
function isInepCodeValid($code){


	if(strlen($code) != 12)
	 		return array("status"=>false,"erro"=>"O campo Identificação única do Profissional escolar em sala de Aula Inep está com tamanho diferente do especificado.");
	else if(!is_numeric($code))
	 		return array("status"=>false,"erro"=>"O campo Identificação única do Profissional escolar em sala de Aula Inep foi preenchido com valor inválido");
	 else
	 		else return array("status"=>true,"erro"=>"");

}


//campo 4 (deve ser igual ao campo 4 do registro 30)

function isInstructorIdValid($code){
	if($code == null)
	 		return array("status"=>false,"erro"=>"O campo ID unico do instrutor em Sala é uma informação obrigatória.");

	if(strlen($code) > 20)
	 		return array("status"=>false,"erro"=>"O campo ID unico do instrutor em Sala está com tamanho diferente do especificado.");
	 else if(!is_numeric($code))
	 		return array("status"=>false,"erro"=>"O campo ID unico do instrutor em Sala foi preenchido com valor inválido");
	 else return array("status"=>true,"erro"=>"");	

}




//campo 5
 function isCPFValid($cpf){
	 	if($cpf == null)
	 		return array("status"=>false,"erro"=>"O campo Número do CPF é uma informação obrigatória.");


	 	if(strlen($cpf) > 11 )
	 		return array("status"=>false,"erro"=>"O campo Número do CPF está com tamanho diferente do especificado.");
	
	 	// se nao for numerico
		if(!is_numeric($cpf))
			return array("status"=>false,"erro"=>"O campo Número do CPF foi preenchido com valor inválido.");

		// se for 0000000000, 1111111
		else if(preg_match('/^(.)\1*$/', $cpf))
			return array("status"=>false,"erro"=>"O campo Número do CPF foi preenchido com valor inválido.");
	

	 	else if($cpf == "00000000191")
	 		return array("status"=>false,"erro"=>"O campo Número do CPF foi preenchido com valor inválido.");
	 
	 	else return array("status"=>true,"erro"=>"");
}

	//campo 6
	 function isAreaOfResidenceValid($area_of_residence){

	 	if(strlen($area_of_residence) != 1)
	 		return array("status"=>false,"erro"=>"O campo Localizacao/Area de Residencia foi preenchido com tamanho invalido");
	 
	 	if($area_of_residence == 1 || $area_of_residence == 2){
	 		
	 		return array("status"=>true,"erro"=>"");
	 	}
	 	else{
	 		return array("status"=>false,"erro"=>"O campo Localizacao/Area de Residencia  foi preenchido com valor inválido.");
	 	}

	 
	 }


	//campo 7
	function isCEPValid($cep){

		if($cep == null)
			return array("status"=>false,"erro"=>"O campo CEP é uma informação obrigatória.");

		
		if((count($cep) != 8)
			return array("status"=>false,"erro"=>"O campo CEP está com tamanho diferente do especificado.");

		if(!is_numeric($cep))
			return array("status"=>false,"erro"=>"O campo CEP foi preenchido com valor inválido.");
		

		else if(preg_match('/^(.)\1*$/', $cep))
			return array("status"=>false,"erro"=>"O campo CEP foi preenchido com valor inválido.");
		
		
		else return array("status"=>true,"erro"=>"");

	}

	//campo 8,9,10,11,12,13
	function isAddressValid($address, $might_be_null, $allowed_lenght){
		$regex="/^[0-9 a-z.,-ºª ]+$/";

		if(!$might_be_null == true){
			if($address == null){
				return array("status"=>false,"erro"=>"O campo de endereço não pode ser nulo.");
				
			}
		}

		else if(strlen($address) > $allowed_lenght || strlen($address) <= 0){
			return array("status"=>false,"erro"=>"O campo de endereço está com tamanho incorreto.");
		}

		else if(!preg_match($regex, $address){
			return array("status"=>false,"erro"=>"O campo de endereço foi preenchido com valor inválido.");
		}

		else return array("status"=>true,"erro"=>"");
	}
}

?>