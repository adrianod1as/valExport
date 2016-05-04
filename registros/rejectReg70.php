<?php


//registro 70
class StudentDocumentsAndAddress{


//campo 1
 function isRegisterType70($register_type){
 		if(strlen($register_type)==0)
 			return array("status"=>false,"erro"=>"Tipo de registro com tamanho invalido");

	 	if(strlen($register_type) > 2 ){
	 		return array("status"=>false,"erro"=>"Tipo de registro com tamanho invalido");
	 	}

	 	if($register_type != 70){
	 		return array("status"=>false,"erro"=>"Tipo de registro invalido");
	  	}
		 return array("status"=>true,"erro"=>"");

}

//campo 2 
function isInepIdValid($inep_id){

	 	if($inep_id == null)
	 		return array("status"=>false,"erro"=>"O campo Código de escola - Inep é uma informação obrigatória.");
	 	if(strlen($inep_id) != 8)
	 		return array("status"=>false,"erro"=>"O campo Código de escola - Inep está com tamanho diferente do especificado.");
	 	if(!is_numeric($inep_id))
	 		return array("status"=>false,"erro"=>"O campo Código de escola - Inep foi preenchido com valor inválido");
	 	
		 return array("status"=>true,"erro"=>"");
}

//campo 3 
function isInepCodeValid($code){


	if(strlen($code) != 12)
	 		return array("status"=>false,"erro"=>"O campo Identificação única do Profissional escolar em sala de Aula Inep está com tamanho diferente do especificado.");
	 	if(!is_numeric($code))
	 		return array("status"=>false,"erro"=>"O campo Identificação única do Profissional escolar em sala de Aula Inep foi preenchido com valor inválido");
	 	else
	 		return array("status"=>true,"erro"=>"");
}

//campo 4
function isStudentSchoolCodeValid($code){

	if(strlen($code) > 20)
		return array("status"=>false,"erro"=>" Codigo do Aluno na Escola está com tamanho incorreto");
	else
		return array("status"=>true,"erro"=>"");
}

//campo 5
function isRgNumberValid($rg,$Reg60Field12){

		if($Reg60Field12 == 1 || $Reg60Field12 == 2 ){

			if(strlen($rg) > 20)
				return array("status"=>false,"erro"=>"Numero da Identidade não está com tamanho correto");

			if (!preg_match('/^[a-z\d°ºª\- ]{4,20}$/i', $rg))
				return array("status"=>false,"erro"=>"Numero da Identidade está com padrão incorreto");

			else
				return array("status"=>true,"erro"=>"");
		}
		else
			return array("status"=>false,"erro"=>" Campo 12 do registro 60 deve ser igual a 1 ou 2");
}

//campo 6
function isRgEmissorOrganValid($EmissorOrgan,$Reg60Field12,$Reg70Field5){

	if(strlen($Reg70Field5) != 0){
		if($Reg60Field12 == 1 || $Reg60Field12 == 2 ){
			if(strlen($EmissorOrgan) == 0 )
				return array("status"=>false,"erro"=>" Orgao emissor deve ser  preenchido");
			if(strlen($EmissorOrgan) != 2)
				return array("status"=>false,"erro"=>" Orgao emissor preenchido com tamanho inválido");
			else
				return array("status"=>true,"erro"=>"");

		}
		else
			return array("status"=>false,"erro"=>" Campo 12 do registro 60 deve ser igual a 1 ou 2");
	}
	//deve ser nulo quando campo 5 for nulo
	else
		if(strlen($EmissorOrgan) != 0 )
			return array("status"=>false,"erro"=>" Orgao emissor deve ser nulo");
}


//campo 7
function isRgUfValid($rgUF,$Reg60Field12,$Reg70Field5,$Reg70Field6){

	if($Reg60Field12 == 1 || $Reg60Field12 == 2 ){
		//os campos 5 e 6 devem ser preenchidos
		if(strlen($Reg70Field5) != 0 && strlen($Reg70Field6) != 0){
			if(strlen($rgUF) == 0)
				return array("status"=>false,"erro"=>"UF da identidade deve ser  preenchido");
			else
				return array("status"=>true,"erro"=>"");

			if(strlen($rgUF) != 2)
				return array("status"=>false,"erro"=>"UF da identidade preenchido com tamanho inválido");
			else
				return array("status"=>true,"erro"=>"");
		}
		else
			if(strlen($rgUF) != 0 )
				return array("status"=>false,"erro"=>" UF da identidade deve ser nulo");
			else
				return array("status"=>true,"erro"=>"");
	}
	else
		return array("status"=>false,"erro"=>" Campo 12 do registro 60 deve ser igual a 1 ou 2");	
}

//campos 8 e 14
function isDateValid($$Reg60Field12,$expeDate,$birthDate,$currentDate,$Reg70Field9,$currentField){

	if($Reg60Field12 == 1 ||  $Reg60Field12 == 2){

		//SE FOR PARA O CAMPO 8 OU PARA O CAMPO 14 COM CAMPO 9 SENDO 1
	  if(($currentField == 8) || ( $currentField == 14 && $Reg70Field9 == 1)){

		if($isDateValid($expeDate) == true){

				$dataExpedicao = explode('/', $expeDate);
				$diaExpedicao = $dataExpedicao[0];
				$mesExpedicao = $dataExpedicao[1];
				$anoExpedicao = $dataExpedicao[2];

				$dataNasceu = explode('/', $birthDate);
				$diaNasceu = $dataNasceu[0];
				$mesNasceu = $dataNasceu[1];
				$anoNasceu = $dataNasceu[2];


				$dataAtual = explode('/', $currentDate);
				$diaAtual = $dataAtual[0];
				$mesAtual = $dataAtual[1];
				$anoAtual = $dataAtual[2];

				//$DataNasceu < $DataExpedicao < $DataAtual
				if($anoExpedicao > $anoNasceu){
					if($anoExpedicao < $anoAtual){
						return array("status"=>true,"erro"=>"");
					}
					if($anoExpedicao > $anoAtual)
						return array("status"=>false,"erro"=>"Data de expedicao superior a data atual");

					if($anoExpedicao == $anoAtual){
						//comparar os meses
						if($mesExpedicao < $mesAtual){
							return array("status"=>true,"erro"=>"");
						}
						if($mesExpedicao > $mesAtual)
							return array("status"=>false,"erro"=>"Data de expedicao superior a data atual");
						
						if($mesExpedicao == $mesAtual){
							//comparar dias
							if($diaExpedicao < $diaAtual){
								return array("status"=>true,"erro"=>"");
							}
							if($diaExpedicao >=$diaAtual)
								return array("status"=>false,"erro"=>"Data de expedicao superior a data atual");
						}

					}		

				}
				if($anoExpedicao < $anoNasceu){
					return array("status"=>false,"erro"=>"Data de expedicao inferior a data de nascimento");
				}
				if($anoExpedicao == $anoNasceu){
					//comparar os meses
					if($mesExpedicao > $mesNasceu){ return array("status"=>true,"erro"=>"");}
					if($mesExpedicao < $mesNasceu){ 
							return array("status"=>false,"erro"=>"Data de expedicao inferior a data de nascimento");
					}
					if($mesExpedicao == $mesNasceu){
						//comparar os dias
						if($diaExpedicao > $diaNasceu){return array("status"=>true,"erro"=>"");}
						else
							return array("status"=>false,"erro"=>"Data de expedicao inferior a data de nascimento");
					}
				}
		}
		else
			return array("status"=>false,"erro"=>"Data de expedicao no formato incorreto");
	 }
	 else
	 	return array("status"=>false,"erro"=>"Campo 9 deve ser 1");
    }
	else
		return array("status"=>false,"erro"=>" Campo 12 do registro 60 deve ser igual a 1 ou 2");
}

//auxiliar do campo 8
function isDateValid($date){
		$data = explode('/', $date);
		$dia = $data[0];
		$mes = $data[1];
		$ano = $data[2];

		// verifica se a data é valida
		if(!checkdate( $mes , $dia , $ano )){
			return array("status"=>false,"erro"=>"Data no formato incorreto");
		}
		else 
			return array("status"=>true,"erro"=>"");
}


//campo 9
function isCivilCertificationValid($Reg70Field5,$Reg60Field12){

	if($Reg60Field12 == 1 || $Reg60Field12 == 2 ){
		if($Reg70Field5 == 1 || $Reg70Field5 == 2 ){
			return array("status"=>true,"erro"=>"");
		}
		else
			return array("status"=>false,"erro"=>" Certificacao Civil deve ser igual a 1 ou 2");	
	}
	else
		return array("status"=>false,"erro"=>" Campo 12 do registro 60 deve ser igual a 1 ou 2");
}

//campo 10
function isCivilCertificationTypeValid($type,$Reg70Field5,$Reg60Field12,$birthday,$currentDate){

	if($Reg60Field12 == 1 || $Reg60Field12 == 2 ){
		if($Reg70Field5 == 1){
			if($type == 1){
				return array("status"=>true,"erro"=>"");
			}
			else if ($type == 2){
				//data

					$data = explode('/', $currentDate);
					$diaAtual = $data[0];
					$mesAtual = $data[1];
					$anoAtual = $data[2];

					$dataNiver = explode('/', $birthday);
					$diaNiver = $dataNiver[0];
					$mesNiver= $dataNiver[1];
					$anoNiver = $dataNiver[2];

					$idade = $anoAtual - $anoNiver;

					if($mesAtual < $mesNiver){
						$idade--;
						echo $idade;
					}
					else if($mesAtual == $mesNiver and $diaAtual < $diaNiver){
						$idade--;
					}
					else{}
				
					if($birthday < 10)
						return array("status"=>false,"erro"=>"Aluno com menos de 10 anos não pode ter certidão de casamento.");
					return array("status"=>true,"erro"=>"");
			} 
			else
				return array("status"=>false,"erro"=>"O Tipo de Certificacao Civil deve ser igual a 1");
		}
		else
			return array("status"=>false,"erro"=>" Campo 9 do registro 70 deve ser igual a 1");
	}
	else
		return array("status"=>false,"erro"=>" Campo 12 do registro 60 deve ser igual a 1 ou 2");
}

//campos 11,12,13,15,16,17
function isFieldValid($$allowedSize,$value,$Reg60Field12,$Reg70Field5){
	if($Reg60Field12 == 1 || $Reg60Field12 == 2 ){
		if($Reg70Field5 == 1){
			if(strlen($value) <= $allowedSize){
				return array("status"=>true,"erro"=>"");
			}
			else
				return array("status"=>false,"erro"=>"Campo com tamanho incorreto");
		}
		else
			return array("status"=>false,"erro"=>"Campo deve ser nulo");
			
	}
	else
		return array("status"=>false,"erro"=>" Campo 12 do registro 60 deve ser igual a 1 ou 2");
}

//campo 18
function isCivilRegisterNumberValid($value,Reg60Field12,Reg70Field5){
	if($Reg60Field12 == 1 || $Reg60Field12 == 2 ){
		if($Reg70Field5 == 2){
			if(strlen($value) != 32)
				return array("status"=>false,"erro"=>"Com tamanho invalido");

		}
			return array("status"=>false,"erro"=>" Campo 5 do registro 70 deve ser igual a 2");
	}
	else
		return array("status"=>false,"erro"=>" Campo 12 do registro 60 deve ser igual a 1 ou 2");
}


//campo 19
 function isCPFValid($cpf){
	 	if($cpf == null)
	 		return array("status"=>false,"erro"=>"O campo Número do CPF é uma informação obrigatória.");

	 	else if(strlen($cpf) > 11 )
	 		return array("status"=>false,"erro"=>"O campo Número do CPF está com tamanho diferente do especificado.");
	
	 	// se nao for numerico
		else if(!is_numeric($cpf))
			return array("status"=>false,"erro"=>"O campo Número do CPF foi preenchido com valor inválido.");

		// se for 0000000000, 1111111
		else if(preg_match('/^(.)\1*$/', $cpf))
			return array("status"=>false,"erro"=>"O campo Número do CPF foi preenchido com valor inválido.");
	
	 	else if($cpf == "00000000191")
	 		return array("status"=>false,"erro"=>"O campo Número do CPF foi preenchido com valor inválido.");

	 	else return array("status"=>true,"erro"=>"");
}

//campo 20
function isPassportValid($passport,Reg60Field12){
	if($Reg60Field12 == 3){

		if(strlen($passport) > 20)
			return array("status"=>false,"erro"=> "Passaporte com tamanho incorreto");

		else return array("status"=>true,"erro"=>"");
	}
	else
		return array("status"=>false,"erro"=>" Campo 12 do registro 60 para estrangeiros deve ter valor 3");
}

//campo 21
function isNISValid($nis){
	if(strlen($nis) != 11)
		return array("status"=>false,"erro"=>"NIS tem tamanho inválido");
	
	else return array("status"=>true,"erro"=>"");
}

//campo 22
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

	//campo 23
	function isCEPValid($cep){

		if($cep == null)
			return array("status"=>false,"erro"=>"O campo CEP é uma informação obrigatória.");
		
		else if(strlen($cep) != 8)
			return array("status"=>false,"erro"=>"O campo CEP está com tamanho diferente do especificado.");

		else if(!is_numeric($cep))
			return array("status"=>false,"erro"=>"O campo CEP foi preenchido com valor inválido.");

		else if(preg_match('/^(.)\1*$/', $cep))
			return array("status"=>false,"erro"=>"O campo CEP foi preenchido com valor inválido.");

		else return array("status"=>true,"erro"=>"");
		
	}

	//campo 24,25,26,27,28,29
	function isAddressValid($address, $might_be_null, $allowed_lenght){
		$regex="/^[0-9 a-z.,-ºª ]+$/";

		if(!$might_be_null == true){
			if($address == null){
				return array("status"=>false,"erro"=>"O campo de endereço não pode ser nulo.");
			
			}
			else return array("status"=>true,"erro"=>"");

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