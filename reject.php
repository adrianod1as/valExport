<?php

//Registro 00

//campo 1
 function isRegister00($registertype){

 	if(strlen($registertype) > 2 ){
 		echo "Tipo de registro com tamanho invalido";
		return false;
 	}


 	if($registertype != 00){
 		echo "Tipo de registro invalido";
 		return false;
 	}
 	return true;

 }

//campo 2
 function isSchoolCodeValid($schoolInepCode){
 	
	return true;
 }

//campo 3
 function isManagerCPFValid($managerCpf){

 	if(strlen($managerCpf) > 11 ){
 		echo "CPF com tamanho invalido";
		return false;
 	}


 	// se nao for numerico
	if(!is_numeric($cep)){
		echo "CPF deve conter somente numeros";
		return false;
	}

	// se for 0000000000, 1111111
	if(preg_match('/^(.)\1*$/', $cep)){  
		echo "CPF nao pode ser da forma 00000000000";
		return false;
	}


 	if($managerCpf == "00000000191"){
 		echo "CPF nao pode ser da forma 00000000191";
 		return false;
 	}
 	

 	return true;
 }

//campo 4
function isManagerNameValid($managerName){

	if(strlen($managerName) < 4 &&strlen($managerName) > 100){
		echo "Nome tem tamanho incorreto";

		return false;
	}

	$regex="/^[a-zA-Z ]+$/";
	if (!preg_match($regex, $managerName)){
		echo "Nome com padrao incorreto";

		return false;
	}

	return true;


 }

//cargo do gestor campo 5
 function isManagerPostValid($managerPost){

 	if($managerPost != 1 || $managerPost != 2){
 		echo "Cargo do gestor incorreto";
 		return false;
 	}
 	return true;
 }



//endereco eletronico do gestor campo 6
function isManagerEmailValid ($managerEmail){

	return isEmailValid($managerEmail);

}

//situacao de funcionamento campo 7
function isWorkSituationValid ($workSituation){
	if($workSituation != 1 || $workSituation != 2 || 
		$workSituation != 3){
		echo "Situação de funcionamento incorreta";
		return false;
	}
	return true;
}


function isDateValid($date){
	$data = explode('/', $startDate);
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
function isSchoolYearValid($startDate,$endDate){

	if(isDateValid($startDate) == false && isDateValid($endDate) == false){
		echo "Data no formato incorreto";
		return false;
	}

	else{

		$dataInicial = explode('/', $startDate);
		$diaInicial = $dataInicial[0];
		$mesInicial = $dataInicial[1];
		$anoInicial = $dataInicial[2];

		$dataFinal = explode('/', $endDate);
		$diaFinal = $dataFinal[0];
		$mesFinal = $dataFinal[1];
		$anoFinal = $dataFinal[2];

		
		//A data de inicio nao pode ser inferior a 2014 nem superior a 2015
		if(!($anoInicial <= "2015" && $anoInicial >= "2014")){
			echo "Data de inicio do ano letivo deve ser inferior a 2014 e superior a 2015";
			return false;
		}

		return true;

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
function isSchoolNameValid($schoolName){
	//deve ser no minimo 4
	if(strlen($schoolName) < 4 || strlen($schoolName) > 4){
		echo "Nome da escola tem tamanho incorreto";
		return false;
	}

	$regex="/^[0-9 a-z-ºª ]+$/";
	if (!preg_match($regex, $schoolName))
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
function isAddressValid($endereco,$enderecoNumero,$complemento,$bairro,$uf,$municipio,$distrito){

	$regex="/^[0-9 a-z.,-ºª ]+$/";

	if(strlen($endereco) > 100){
		echo "Endereco com tamanho invalido";
		return false;
	}
	if(!preg_match($regex, $endereco)){
		echo "Endereco com padrao invalido";
		return false;
	}

	if(strlen($enderecoNumero) > 10){
		echo "Endereco Numero com tamanho invalido";
		return false;
	}
	if(!preg_match($regex, $enderecoNumero)){
		echo "Endereco Numero com padrao invalido";
		return false;
	}

	if(strlen($complemento) > 20){
		echo "Complemento com tamanho invalido";
		return false;
	}

	if(!preg_match($regex, $complemento)){
		echo "Complemento com padrao invalido";
		return false;
	}

	if(strlen($bairro) > 50){
		echo "Bairro com tamanho invalido";

		return false;
	}
	if(!preg_match($regex, $bairro)){
		echo "Bairro com padrao invalido";
		return false
	}

	if(strlen($uf) > 2){
		echo "UF com tamanho invalido";
		return false;
	}
	if(!preg_match($regex, $uf)){
		echo "UF com padrao invalido";
		return false
	}

	if(strlen($municipio) > 7){
		echo "Municipio com tamanho invalido";
		return false;
	}
	if(!preg_match($regex, $municipio)){
		echo "Municipio com padrao invalido";
		return false
	}

	if(strlen($distrito) > 2){
		echo "Distrito com tamanho invalido";
		return false;
	}
	if(!preg_match($regex, $distrito))
		echo "Distrito com padrao invalido";
		return false

}

function isPhoneValid($ddd,$telefone,$telefonePublico,$outroTelefone,$fax){

	if(strlen($ddd) > 2){
		return false;
	}
	else{
		//campo 22
		if(telefone($telefone) == false){

			return false;
		}
		//campo 23
		if(telefonePublicoOuFax($telefonePublico) == false)
		 	return false;
		 //campo24
		if(telefone($outroTelefone) = false)
			return false;
		//campo25
		if(telefonePublicoOuFax($fax) == false);
		 return false;

	}
}

function telefone($telefone){

		if(strlen($telefone) < 8 || strlen($telefone) > 9 ){
				return false;
		}
		if (strlen($telefone) == 9){
			//o primeiro algarismo deve ser 9
			if($telefone[0] != 9)
				return false;

		}

		//se tem repeticao do numero: 11111111, 2222222
		if(preg_match('/^(.)\1*$/', $telefone)) {
			return false;
		} 

}

function telefonePublicoOuFax($telefone){

		if(strlen($telefone) != 8 ){
			echo "telefone com tamanho invalido";
			return false;
		}

		if(preg_match('/^(.)\1*$/', $telefone)) {
			echo "Telefone com padrao invalido";
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














