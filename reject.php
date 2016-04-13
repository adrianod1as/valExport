<?php

//Registro 00

//campo 1
 function isRegister00($registertype){
 	if($registertype != 00){
 		return false;
 	}
 	return true;

 }

//campo 2
 function isSchoolCodeValid($schoolInepCode){
 	if($schoolInepCode)


	return true;
 }

//campo 3
 function isManagerCPFValid($managerCpf){

 	// se nao for numerico
	if(!is_numeric($cep)){
		return false;
	}

	// se for 0000000000, 1111111
	if(preg_match('/^(.)\1*$/', $cep))  
		return false;


 	if($managerCpf == "00000000191")
 		return false;
 	

 	return true;
 }

//campo 4
function isManagerNameValid($managerName){

if (!preg_match('/^[a-z\d_]{4,28}$/i', $string))
	return false;

return true;


 }


//cargo do gestor campo 5
 function isManagerPostValid($managerPost){
 	if($managerPost != 1 || $managerPost != 2){
 		return false;
 	}
 	return true;
 }


 

//endereco eletronico do gestor campo 6
function isManagerEmailValid ($managerEmail){
	
if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email))
	return false;


	return true;
}






//situacao de funcionamento campo 7
function isWorkSituationValid ($workSituation){
	if($workSituation != 1 || $workSituation != 2 || 
		$workSituation != 3){
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


//campo 8
function isSchoolYearValid($startDate,$endDate){

	if(isDateValid($startDate) == false && isDateValid($endDate) == false){
		return false;
	}

	$dataInicial = explode('/', $startDate);
	$diaInicial = $dataInicial[0];
	$mesInicial = $dataInicial[1];
	$anoInicial = $dataInicial[2];

	$dataFinal = explode('/', $endDate);
	$diaFinal = $dataFinal[0];
	$mesFinal = $dataFinal[1];
	$anoFinal = $dataFinal[2];

	if()







	//A data de inicio nao pode ser inferior a 2014 nem superior a 2015
	if(!($ano <= "2015" && $ano >= "2014"))
		return false;

	return true;



	//A data de inicio nao pode ser inferior a data de referencia 
	//do Censoem 2015 nem superior a 2016
	if(!($ano <= "2016" && $ano >= "2015"))
		return false;

}






//campo 10
function isSchoolNameValid($schoolName){
	//deve ser no minimo 4

	if (!preg_match('/^[a-z\d_]{4,32}$/i', $string))
		return false;
	return true;


}

//campo 13
function isCEPValid($cep){
	
	if((count($cep) != 8)
		return false;

	if(!is_numeric($cep))
		return false;
	
	//se for do mesmo caracteres
	if(00000000000000000000000)  
		return false;
			

	return true;

}



