<?php

//Registro 00

class SchoolIdentification{

	//campo 1
	 function isRegisterType00($register_type){

	 	if(strlen($register_type) > 2 ){
	 		return array("status"=>false,"erro"=>"As linhas devem ser iniciadas com o número do registro.");
	 		
	 	}

	 	if($register_type != 00){
	 		return array("status"=>false,"erro"=>"O registro declarado <tipo de registro> não faz parte do escopo do educacenso.");
	 	
	 	}
	 	else return array("status"=>true,"erro"=>"");

	 }

	//campo 2
	 function isInepIdValid($inep_id){

	 	if($inep_id == null)
	 		return array("status"=>false,"erro"=>"O campo Código de escola - Inep é uma informação obrigatória.");
	 	if(strlen($inep_id) != 8)
	 		return array("status"=>false,"erro"=>"O campo Código de escola - Inep está com tamanho diferente do especificado.");
	 	if(!is_numeric($inep_id))
	 		return array("status"=>false,"erro"=>"O campo Código de escola - Inep foi preenchido com valor inválido");
	 	
		else return array("status"=>true,"erro"=>"");
	 }

	//campo 3
	 function isManagerCPFValid($manager_cpf){
	 	if($manager_cpf == null)
	 		return array("status"=>false,"erro"=>"O campo Número do CPF do Gestor Escolar é uma informação obrigatória.");


	 	if(strlen($manager_cpf) > 11 )
	 		return array("status"=>false,"erro"=>"O campo Número do CPF do Gestor Escolar está com tamanho diferente do especificado.");
	
	 	// se nao for numerico
		if(!is_numeric($manager_cpf))
			return array("status"=>false,"erro"=>"O campo Número do CPF do Gestor Escolar foi preenchido com valor inválido.");

		// se for 0000000000, 1111111
		if(preg_match('/^(.)\1*$/', $manager_cpf))
			return array("status"=>false,"erro"=>"O campo Número do CPF do Gestor Escolar foi preenchido com valor inválido.");
	

	 	if($manager_cpf == "00000000191")
	 		return array("status"=>false,"erro"=>"O campo Número do CPF do Gestor Escolar foi preenchido com valor inválido.");
	 

	 	else return array("status"=>true,"erro"=>"");
	 }

	//campo 4
	function isManagerNameValid($manager_name){
		if($manager_name == null)
			return array("status"=>false,"erro"=>"O campo Nome do Gestor Escolar é uma informação obrigatória.");

		if(strlen($manager_name) > 100)
			return array("status"=>false,"erro"=>"O campo Nome do Gestor Escolar está maior que o especificado.");
	

		$regex="/^[A-Z0-9°ºª\- ]/";
		if (!preg_match($regex, $manager_name))
			return array("status"=>false,"erro"=>"O campo Nome do Gestor Escolar foi preenchido com valor inválido.");
		
		else return array("status"=>true,"erro"=>"");

	 }

	//cargo do gestor campo 5
	 function isManagerRoleValid($manager_role){
	 	if($manager_role == null)
			return array("status"=>false,"erro"=>"O campo Cargo do Gestor Escolar é uma informação obrigatória.");

	 	if($manager_role == 1 || $manager_role == 2){
	 		
	 		return array("status"=>true,"erro"=>"");
	 	}
	 	else{
	 		return array("status"=>false,"erro"=>"O campo Cargo do Gestor Escolar foi preenchido com valor inválido.");
	 	}

	 
	 }

	//address eletronico do gestor campo 6
	function isManagerEmailValid ($manager_email){

		if($manager_email == null)
			return array("status"=>false,"erro"=>"O campo Endereço eletrônico (e-mail) do Gestor Escolar é uma informação obrigatória.");


		if(strlen($manager_email) > 50 )
			return array("status"=>false,"erro"=>"O campo Endereço eletrônico (e-mail) do Gestor Escolar está maior que o especificado.");
		
		
		if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $manager_email))

			return array("status"=>false,"erro"=>"O campo Endereço eletrônico (e-mail) do Gestor Escolar  foi preenchido com valor inválido.");


		else return array("status"=>true,"erro"=>"");

	}

	//situacao de funcionamento campo 7
	function isSituationValid ($situation){

	if($situation == null)
		return array("status"=>false,"erro"=>"O campo Situação de funcionamento é uma informação obrigatória.");

		if($situation == 1 || $situation == 2 || 
			$situation == 3){
			
			return array("status"=>true,"erro"=>"");
		}
		else{
			return array("status"=>false,"erro"=>"O campo Situação de funcionamento foi preenchido com valor inválido.");
		}

	}


	//auxiliar dos campos 8 e 9
	function isDateValid($date){
		$data = explode('/', $date);
		$dia = $data[0];
		$mes = $data[1];
		$ano = $data[2];


		// verifica se a data é valida
		if(!checkdate( $mes , $dia , $ano )){
			return array("status"=>false,"erro"=>"Data no formato incorreto");
		}
		else return array("status"=>true,"erro"=>"");
		
	}


	//campo 8 e 9
	function isSchoolYearValid($initial_date,$final_date){

		if(isDateValid($initial_date) == false && isDateValid($final_date) == false){
			return array("status"=>false,"erro"=>"Data no formato incorreto");
			
		}

		else{

			$dataInicial = explode('/', $initial_date);
			$diaInicial = $dataInicial[0];
			$mesInicial = $dataInicial[1];
			$anoInicial = $dataInicial[2];

			$dataFinal = explode('/', $final_date);
			$diaFinal = $dataFinal[0];
			$mesFinal = $dataFinal[1];
			$anoFinal = $dataFinal[2];

			
			//A data de inicio nao pode ser inferior a 2014 nem superior a 2015
			if(!($anoInicial <= "2015" && $anoInicial >= "2014")){
				return array("status"=>false,"erro"=>"Data de inicio do ano letivo nao deve ser inferior a 2014 e superior a 2015");
				
			}

			//A data de termino nao pode ser inferior a data de referencia 
			//do Censoem 2015 nem superior a 2016
			if(!($anoFinal <= "2016" && $anoFinal >= "2015")){
				return array("status"=>false,"erro"=>"A data de termino do ano letivo nao pode ser inferior a 2015 nem superior a 2016");
				
			}

			// se a data inicial do periodo letivo é menor que a data final
			if($anoInicial < $anoFinal){
				return array("status"=>true,"erro"=>"");
				
			}
			else if ($anoInicial == $anoFinal){
				if($mesInicial < $mesFinal){
					return array("status"=>true,"erro"=>"");
					
						
				}
				if($mesInicial == $mesFinal){
					if($diaInicial < $diaFinal){
						return array("status"=>true,"erro"=>"");
						
					}
					if($diaInicial >= $diaFinal)
						return array("status"=>false,"erro"=>"Dia inicial é maior ou igual a Dia Final");
					
				}
							
				if($mesInicial > $mesFinal){
					return array("status"=>false,"erro"=>"Mes Inicial está maior que Mes Final");
							
				}

			}
					
			else{
				return array("status"=>false,"erro"=>"Ano letivo inicial está maior que o ano final");
				
			}

		}

	}


	//campo 10
	function isNameValid($name){
		//deve ser no minimo 4

		if(strlen($name) == 0)
			return array("status"=>false,"erro"=>"O campo Nome da escola é uma informação obrigatória.");

		if(strlen($name) < 4 )
			return array("status"=>false,"erro"=>"O campo Nome da escola não contém o mínimo de caracteres especificado.");

		if(strlen($name) > 100)
			return array("status"=>false,"erro"=>"O campo Nome da escola está maior que o especificado.");
			
		
		if (!preg_match('/^[a-z\d°ºª\- ]{4,28}$/i', $name))
			return array("status"=>false,"erro"=>"O campo Nome da escola foi preenchido com valor inválido.");


		else return array("status"=>true,"erro"=>"");

	}


	//campo 11
	function isLatitudeValid($latitude){

		if(strlen($latitude) > 20 )
			return array("status"=>false,"erro"=>"O campo Latitude está maior que o especificado.");

		$regex="/^[0-9.-]+$/";
		if(!preg_match($regex, $longitude))
			return array("status"=>false,"erro"=>"O campo Laitude contém caractere(s) inválido(s).");


		if($latitude >= -33.750833 && $latitude <= 5.272222)
			return array("status"=>true,"erro"=>"");
		else{
			return array("status"=>false,"erro"=>"O campo Latitude foi preenchido com valor inválido.");
		}
		

	}
	//campo 12

	function isLongitudeValid($longitude){

		if(strlen($longitude) > 20 )
			return array("status"=>false,"erro"=>"O campo Longitude está maior que o especificado.");

		$regex="/^[0-9.-]+$/";
		if(!preg_match($regex, $longitude))
			return array("status"=>false,"erro"=>"O campo Longitude contém caractere(s) inválido(s).");

		if($longitude >= -73.992222 && $longitude <= -32.411280)
			 return array("status"=>true,"erro"=>"");
		else{
			return array("status"=>false,"erro"=>"O campo Longitude foi preenchido com valor inválido.");
		}
		

		
	}


	//campo 13
	function isCEPValid($cep){

		if($cep == null)
			return array("status"=>false,"erro"=>"O campo CEP é uma informação obrigatória.");

		
		if((count($cep) != 8)
			return array("status"=>false,"erro"=>"O campo CEP está com tamanho diferente do especificado.");

		if(!is_numeric($cep))
			return array("status"=>false,"erro"=>"O campo CEP foi preenchido com valor inválido.");
		

		if(preg_match('/^(.)\1*$/', $cep))
			return array("status"=>false,"erro"=>"O campo CEP foi preenchido com valor inválido.");
		
		
		else return array("status"=>true,"erro"=>"");

	}

	//campo 14,campo 15,campo 16,campo 17,campo 18,campo 19,campo 20
	function isAddressValid($address, $might_be_null, $allowed_lenght){
		$regex="/^[0-9 a-z.,-ºª ]/";

		if(!$might_be_null){
			if($address == null){
				return array("status"=>false,"erro"=>"O campo de endereço não pode ser nulo.");
				
			}
		}

		if(strlen($address) > $allowed_lenght || strlen($address) <= 0){
			return array("status"=>false,"erro"=>"O campo de endereço está com tamanho incorreto.");
		}

		if(!preg_match($regex, $address){
			return array("status"=>false,"erro"=>"O campo de endereço foi preenchido com valor inválido.");
		}

		else return array("status"=>true,"erro"=>"");
	}

	//campo 21,22,23,24,25
	function isPhoneValid($ddd,$phone_number,$allowed_lenght){
		
		if(strlen($ddd) != 2){
			return array("status"=>false,"erro"=>"DDD incorreto");
		}
		else{
			if(strlen($phone_number) != $allowed_lenght)
				return array("status"=>false,"erro"=>"Telefone com tamanho incorreto");

		
			if(preg_match('/^(.)\1*$/', $phone_number)) {
				return array("status"=>false,"erro"=>"Telefone com padrao incorreto");
			} 

		}
		else return array("status"=>true,"erro"=>"");
	}

	//campo 26
	function isEmailValid($email){ 
		if(strlen($email) > 50 ){
			return array("status"=>false,"erro"=>"Email com tamanho invalido");
			
		}
		
		if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email)){
			return array("status"=>false,"erro"=>"Email com padrão invalido");
		}


		else return array("status"=>true,"erro"=>"");
	}


	//campo 27
	function isEdcensoRegionalEducationOrganValid($inepCode, $value){
		if(isInepIdValid($inepCode) == true){
			if(strlen($value) != 5 ){
				return array("status"=>false,"erro"=>"Email com tamanho invalido");
			}
		}
		else return array("status"=>false,"erro"=>"Codigo inep inválido");
			

	}

	//campo 28
	function isAdministrativeDependenceValid($inep_id,$value){

		if(isInepIdValid($inep_id) == false)
			return array("status"=>false,"erro"=>"Codigo inep inválido");
		else{

			if($value == 1 || $value == 2 || $value == 3|| $value == 4){
				return array("status"=>true,"erro"=>"");

			else
				return array("status"=>false,"erro"=>"Dependencia Administrativa inválida");
		}

	 }
	}

	//campo 29
	function isLocationValid($inep_id,$value){

		if(isInepIdValid($inep_id) == false)
			return array("status"=>false,"erro"=>"Codigo inep inválido");
		else{
				if($value == 1 || $value == 2){
					return array("status"=>true,"erro"=>"");
				else
					return array("status"=>false,"erro"=>"Lozalização inválida");
		}
	 }
	}

	//auxiliar nos campos 30,31,32
	function isField7And28Valid($inep_id,$schoolSituation,$dependency){

		//campo 7 deve ser igual a 1.. Campo 28 deve ser igual a 4
		if($schoolSituation == 1 && 
			isSituationValid($schoolSituation)==true && 
			isAdministrativeDependenceValid($inep_id,$dependency) == true
			&& $dependency == 4 )
				return true;

		else
			return false;

	}


	//campo 30
	function isPrivateSchoolCategoryValid($inep_id,$schoolSituation,$dependency,$privateSchoolCategory){
		if(isField7And28Valid($inep_id,$schoolSituation,$dependency) == true){
			if($privateSchoolCategory == 1 || $privateSchoolCategory == 2 || 
			   $privateSchoolCategory == 3 || $privateSchoolCategory == 4)
				return array("status"=>true,"erro"=>"");
			else return array("status"=>false,"erro"=>"O valor public contrat deve ser 1,2,3 ou 4");
		}
		else return false;
	}

	//campo 31

	function isPublicContractValid($inep_id,$schoolSituation,$dependency,publicSchool){
		if(isField7And28Valid($inep_id,$schoolSituation,$dependency) == true){
			if($privateSchoolCategory == 1 || $privateSchoolCategory == 2 || 
			   $privateSchoolCategory == 3)
					return array("status"=>true,"erro"=>"");
			else return array("status"=>false,"erro"=>"O valor public contrat deve ser 1,2 ou 3");
		}
		
		else return false;

	}

	//campos 32 a 36
	function isPrivateSchoolMaintainerValid($inep_id,$schoolSituation,$dependency,$maintainerValue){

		if(isField7And28Valid($inep_id,$schoolSituation,$dependency) == true){

			//campo 32
			if($maintainerValue == 0 || $maintainerValue == 1){
				return array("status"=>true,"erro"=>"");

			}
				
			else return array("status"=>false,"erro"=>"O valor para mantainer deve ser 0 ou 1");
		}
					

	}

	
	//para os campos 37 e 38
	function isCNPJValid($inep_id,$schoolSituation,$dependency,$cnpj){
		if(!is_numeric($cnpj)){
			return array("status"=>false,"erro"=>"CNPJ está com padrão inválido");
		}

		if(strlen($cnpj) != 14 || isField7And28Valid($inep_id,$schoolSituation,$dependency) == false){
			return array("status"=>false,"erro"=>"O CNPJ está com tamanho errado");

		}
			return array("status"=>true,"erro"=>"");

	}

	//campo 39
	function isRegulationValid($schoolSituation,$value){
		//campo 7 deve ser igual a 1
		if($schoolSituation != 1)
			return array("status"=>false,"erro"=>"Situação da escola errada");
		if($value == 0  || $value == 1  || $value == 2)
			return array("status"=>true,"erro"=>"");
		else return array("status"=>false,"erro"=>"Regulamentação da escola errada");
	}

	//campo 40,41 e 42
	function isOfferOrLinkedUnity($value,$InepCode,$HeadSchool,$schoolSituation,
		$hostedcenso_city_fk,$atualedcenso_city_fk,$hostDependencyAdm,$atualDependencyAdm,$IESCode){

		if($value == 1){
			return isInepHeadSchoolValid($InepCode,$HeadSchool,$schoolSituation,$hostedcenso_city_fk,$atualedcenso_city_fk,$hostDependencyAdm,$atualDependencyAdm);

		}
		if($value == 2){
			return isIESCodeValid($IESCode,$schoolSituation);

		}

	}

	//auxiliar  no campo 41
	function isInepHeadSchoolValid($InepCode,$HeadSchool,$schoolSituation,
		$hostedcenso_city_fk,$atualedcenso_city_fk,$hostDependencyAdm,$atualDependencyAdm){

			if(strlen($HeadSchool) != 8)
				return array("status"=>false,"erro"=>"Situacao da escola inválida");

			//deve ser uma escola em atividade
			if($schoolSituation != 1)
				return array("status"=>false,"erro"=>"Deve ser uma escola em atividade");

			//deve ser diferente da atual escola
			if($InepCode == $HeadSchool)
				return array("status"=>false,"erro"=>"deve ser diferente da atual escola");
			//deve ser da mesma dependencia administrativa e mesmo edcenso_city_fk
			if($hostedcenso_city_fk != $atualedcenso_city_fk || $hostDependencyAdm != $atualDependencyAdm)
				return array("status"=>false,"erro"=>"deve ser da mesma dependencia administrativa e mesmo edcenso_city_fk");

			else return array("status"=>true,"erro"=>"");
				
	}

	//auxiliar  no campo 42
	function isIESCodeValid($IESCode,$schoolSituation){
			//nao pode ser IES paralisada ou extinta
			if($schoolSituation == 1 || $schoolSituation == 2)
				return array("status"=>false,"erro"=>"Situacao da escola inválida");

			//iES VALIDA
			if(!is_numeric($IESCode) || strlen($IESCode) != 14)
				return array("status"=>false,"erro"=>"Codigo IES com tamanho inválido");

			else return array("status"=>true,"erro"=>"");

	}

}

?>