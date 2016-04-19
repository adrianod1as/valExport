<?php

//Registro 00

class SchoolIdentification{

	//campo 1
	 function isRegisterType00($register_type){

	 	if(strlen($register_type) > 2 ){
	 		echo "Tipo de registro com tamanho invalido";
			return false;
	 	}


	 	if($register_type != 00){
	 		echo "Tipo de registro invalido";
	 		return false;
	 	}
	 	return true;

	 }

	//campo 2
	 function isInepIdValid($inep_id){

	 	if($inep_id == null)
	 		return false;
	 	
		return true;
	 }

	//campo 3
	 function isManagerCPFValid($manager_cpf){

	 	if(strlen($manager_cpf) > 11 ){
	 		echo "CPF com tamanho invalido";
			return false;
	 	}


	 	// se nao for numerico
		if(!is_numeric($manager_cpf)){
			echo "CPF deve conter somente numeros";
			return false;
		}

		// se for 0000000000, 1111111
		if(preg_match('/^(.)\1*$/', $manager_cpf)){  
			echo "CPF nao pode ser da forma 00000000000";
			return false;
		}


	 	if($manager_cpf == "00000000191"){
	 		echo "CPF nao pode ser 00000000191";
	 		return false;
	 	}
	 	

	 	return true;
	 }

	//campo 4
	function isManagerNameValid($manager_name){

		if(strlen($manager_name) < 4 &&strlen($manager_name) > 100){
			echo "Nome tem tamanho incorreto";

			return false;
		}

		$regex="/^[a-zA-Z ]+$/";
		if (!preg_match($regex, $manager_name)){
			echo "Nome com padrao incorreto";

			return false;
		}

		return true;


	 }

	//cargo do gestor campo 5
	 function isManagerRoleValid($manager_role){

	 	if($manager_role == 1 || $manager_role == 2){
	 		
	 		return true;
	 	}
	 	else{
	 		echo "Cargo do gestor incorreto";
	 		return false;
	 	}

	 
	 }

	//address eletronico do gestor campo 6
	function isManagerEmailValid ($manager_email){

		return isEmailValid($manager_email);

	}

	//situacao de funcionamento campo 7
	function isSituationValid ($situation){
		if($situation == 1 || $situation == 2 || 
			$situation == 3){
			
			return true;
		}
		else{
			echo "Situação de funcionamento incorreta";
			return false;
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
			return false;
		}
		

		return true;
	}


	//campo 8 e 9
	function isSchoolYearValid($initial_date,$final_date){

		if(isDateValid($initial_date) == false && isDateValid($final_date) == false){
			echo "Data no formato incorreto";
			return false;
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
				echo "Data de inicio do ano letivo deve ser inferior a 2014 e superior a 2015";
				return false;
			}

			//A data de inicio nao pode ser inferior a data de referencia 
			//do Censoem 2015 nem superior a 2016
			if(!($anoFinal <= "2016" && $anoFinal >= "2015")){
				echo "O ano de inicio do ano letivo nao pode ser inferior a 2015 nem superior a 2016";
				return false;
			}

			// se a data inicial do periodo letivo é menor que a data final
			if($anoInicial <= $anoFinal){

				if($mesInicial < $mesFinal){
							
					return true;
									
				}
				else if ($mesInicial > $mesFinal){
					echo "Data incorreta";
					return false;
				}
							
				else if ($mesInicial == $mesFinal){
						
					if($diaInicial < $diaFinal)
						return true;
					else{
						echo "Data incorreta";
						return false;
					}
						
				}
								

			}
			else{
				echo "Ano letivo inicial está maior que o ano final";
				return false;
			}



		}
		return true;

	}


	//campo 10
	function isNameValid($name){
		//deve ser no minimo 4
		if(strlen($name) < 4 || strlen($name) > 100){
			echo "Nome da escola tem tamanho incorreto";
			return false;
		}

		$regex="/^[0-9 a-z-ºª ]+$/";
		if (!preg_match($regex, $name))
			echo "Nome da escola incorreto";
			return false;
		return true;

	}


	//campo 11
	function isLatitudeValid($latitude){

		if(strlen($latitude) > 20 ){
			echo "Latitude tem tamanho invalido";
			return false;
		}

		$regex="/^[0-9.-]+$/";
		if(!preg_match($regex, $longitude)){
			echo "Latitude tem padrão invalido";
			return false;
		}

		if($latitude >= -33.750833 && $latitude <= 5.272222)
			return true;
		else{
			echo "Latitude deve estar entre -33.750833 e 5.272222 ";
			return false;
		}



	}
	//campo 12

	function isLongitudeValid($longitude){

		if(strlen($longitude) > 20 ){
			echo "longitude tem tamanho invalido";
			return false;
		}

		$regex="/^[0-9.-]+$/";
		if(!preg_match($regex, $longitude)){
			echo "longitude tem padrão invalido";
			return false;
		}

		if($longitude >= -73.992222 && $longitude <= -32.411280)
			return true;
		else{
			echo "longitude deve estar entre -73.9922223 e -32.411280 ";
			return false;
		}

		
	}


	//campo 13
	function isCEPValid($cep){
		
		if((count($cep) != 8){
			echo "CEP tem tamanho invalido";
			return false;
		}

		if(!is_numeric($cep)){
			echo "CEP deve ser somente numerico";
			return false;
		}

		if(preg_match('/^(.)\1*$/', $cep)){
			echo "CEP nao pode ter repeticao do tipo 00000000";
			return false;


		}
		
			

		return true;

	}

	//campo 14,campo 15,campo 16,campo 17,campo 18,campo 19,campo 20
	function isAddressValid($address,$address_number,$address_complement,$address_neighborhood,$edcenso_edcenso_uf_fk_fk,$edcenso_city_fk,$edcenso_district_fk){

		$regex="/^[0-9 a-z.,-ºª ]+$/";

		if(strlen($address) > 100){
			echo "address com tamanho invalido";
			return false;
		}
		if(!preg_match($regex, $address)){
			echo "address com padrao invalido";
			return false;
		}

		if(strlen($address_number) > 10){
			echo "address Numero com tamanho invalido";
			return false;
		}
		if(!preg_match($regex, $address_number)){
			echo "address Numero com padrao invalido";
			return false;
		}

		if(strlen($address_complement) > 20){
			echo "address_complement com tamanho invalido";
			return false;
		}

		if(!preg_match($regex, $address_complement)){
			echo "address_complement com padrao invalido";
			return false;
		}

		if(strlen($address_neighborhood) > 50){
			echo "address_neighborhood com tamanho invalido";

			return false;
		}
		if(!preg_match($regex, $address_neighborhood)){
			echo "address_neighborhood com padrao invalido";
			return false
		}

		if(strlen($edcenso_uf_fk) > 2){
			echo "edcenso_uf_fk com tamanho invalido";
			return false;
		}
		if(!preg_match($regex, $edcenso_uf_fk)){
			echo "edcenso_uf_fk com padrao invalido";
			return false
		}

		if(strlen($edcenso_city_fk) > 7){
			echo "edcenso_city_fk com tamanho invalido";
			return false;
		}
		if(!preg_match($regex, $edcenso_city_fk)){
			echo "edcenso_city_fk com padrao invalido";
			return false
		}

		if(strlen($edcenso_district_fk) > 2){
			echo "edcenso_district_fk com tamanho invalido";
			return false;
		}
		if(!preg_match($regex, $edcenso_district_fk))
			echo "edcenso_district_fk com padrao invalido";
			return false

	}

	function isPhoneValid($ddd,$phone_number,$public_phone_number,$other_phone_number,$fax_number){
		//campo 21
		if(strlen($ddd) > 2){
			return false;
		}
		else{
			//campo 22
			if(phone_number($phone_number) == false){

				return false;
			}
			//campo 23
			if(public_phone_numberEfax_number($public_phone_number) == false)
			 	return false;
			 //campo24
			if(phone_number($other_phone_number) = false)
				return false;
			//campo25
			if(public_phone_numberEfax_number($fax_number) == false);
			 return false;

		}
		return true;
	}

	//para os campos 22 e 24
	function phone_number($phone_number){

			if(strlen($phone_number) < 8 || strlen($phone_number) > 9 ){
					return false;
			}
			if (strlen($phone_number) == 9){
				//o primeiro algarismo deve ser 9
				if($phone_number[0] != 9)
					return false;

			}

			//se tem repeticao do numero: 11111111, 2222222
			if(preg_match('/^(.)\1*$/', $phone_number)) {
				return false;
			} 

	}
	//para os campos 23 e 25
	function public_phone_numberEfax_number($phone_number){

			if(strlen($phone_number) != 8 ){
				echo "phone_number com tamanho invalido";
				return false;
			}

			if(preg_match('/^(.)\1*$/', $phone_number)) {
				echo "phone_number com padrao invalido";
				return false;
			} 

	}

	//campo 26
	function isEmailValid($email){ 
		if(strlen($email) > 50 ){
			echo "Email com tamanho invalido";
			return false;
		}
		
		if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email)){
			echo "Email com padrao invalido";
			return false;
		}


		return true;
	}


	//campo 27
	function isEdcensoRegionalEducationOrganValid($inepCode, $value){
		if(isInepIdValid($inepCode) == true){
			if(strlen($value) != 5 ){
				return false;
			}
		}
		else return false;

	}

	//campo 28
	function isAdministrativeDependenceValid($inep_id,$value){

		if(isInepIdValid($inep_id) == false)
			return false;
		else{

			if($value == 1 || $value == 2 || $value == 3|| $value == 4){
				return true;

			else
				return false;
		}

	 }
	}

	//campo 29
	function isLocationValid($inep_id,$value){

		if(isInepIdValid($inep_id) == false)
			return false;
		else{
				if($value == 1 || $value == 2){
					return true;
				else
					return false;
		}
	 }
	}

	//para os campos 30,31,32,
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
				return true;
			else return false;
		}
		else return false;
	}

	//campo 31

	function isPublicContractValid($inep_id,$schoolSituation,$dependency,publicSchool){
		if(isField7And28Valid($inep_id,$schoolSituation,$dependency) == true){
			if($privateSchoolCategory == 1 || $privateSchoolCategory == 2 || 
			   $privateSchoolCategory == 3)
				return true;
			else return false;

		}
		else return false;

	}

	//campos 32 a 36
	function isPrivateSchoolMaintainerValid($inep_id,$schoolSituation,$dependency,$business_or_individual,
		$syndicate_or_association,$ong_or_oscip,$non_profit_institutions,$s_system){

		if(isField7And28Valid($inep_id,$schoolSituation,$dependency) == true){

			//campo 32
			if($business_or_individual == 0 || $business_or_individual == 1){

			}
				
			else return false;
			//campo 33
			if($syndicate_or_association == 0 || $syndicate_or_association == 1){
				
			}
			else return false;
			//campo 34
			if($ong_or_oscip == 0 || $ong_or_oscip == 1){
				
			}
			else return false;
			//campo 35
			if($non_profit_institutions == 0 || $non_profit_institutions == 1){
				
			}
			else return false;
			//campo 36
			if($s_system == 0 || $s_system == 1){
				
			}
			else return false;
		}
		else
			return false;

	}

	//campo 37
	function isPrivateSchoolMaintainerCNPJValid($inep_id,$schoolSituation,$dependency,$cnpj){
		if(isCNPJValid($inep_id,$schoolSituation,$dependency,$cnpj) == false)
			return false;
		return true;
	}

	//campo 38
	function isPrivateSchoolCNPJValid($inep_id,$schoolSituation,$dependency,$cnpj){
		if(isCNPJValid($inep_id,$schoolSituation,$dependency,$cnpj) == false)
			return false;
		return true;
	}

	//para os campos 37 e 38
	function isCNPJValid($inep_id,$schoolSituation,$dependency,$cnpj){
		if(!is_numeric($cnpj)){
			return false;
		}

		if(strlen($cnpj) != 14 || isField7And28Valid($inep_id,$schoolSituation,$dependency) == false){
			return false;

		}
		return true;

	}

	//campo 39
	function isRegulationValid($schoolSituation,$value){
		//campo 7 deve ser igual a 1
		if($schoolSituation != 1)
			return false;
		if($value == 0  || $value == 1  || $value == 2)
			return true;
		else return false;
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

	//para o campo 41
	function isInepHeadSchoolValid($InepCode,$HeadSchool,$schoolSituation,
		$hostedcenso_city_fk,$atualedcenso_city_fk,$hostDependencyAdm,$atualDependencyAdm){

			if(strlen($HeadSchool) != 8)
				return false;

			//deve ser uma escola em atividade
			if($schoolSituation != 1)
				return false;

			//deve ser diferente da atual escola
			if($InepCode == $HeadSchool)
				return false;
			//deve ser da mesma dependencia administrativa e mesmo edcenso_city_fk
			if($hostedcenso_city_fk != $atualedcenso_city_fk || $hostDependencyAdm != $atualDependencyAdm)
				return false;
	}

	//para o campo 42
	function isIESCodeValid($IESCode,$schoolSituation){
			//nao pode ser IES paralisada ou extinta
			if($schoolSituation == 1 || $schoolSituation == 2)
				return false;

			//iES VALIDA
			if(!is_numeric($IESCode) || strlen($IESCode) != 14)
				return false;

	}

}

?>