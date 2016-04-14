<?php

require($_SERVER['DOCUMENT_ROOT'] ."/valExport/schoolStructureValidation.php");

ini_set('memory_limit', '-1');

//Conexão com o banco
$hostname="db.ipti.org.br";
$database="br.org.ipti.tag";
$username="root";
$password="";

$link = mysqli_connect($hostname, $username, $password, $database); 

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

//Conversão da tabela para array
function tabletToArray($sql, $con){
	$array = null;
	$result = $con->query($sql);
	if ($result->num_rows > 0) {
		$array = mysqli_fetch_all($result,MYSQLI_ASSOC);
	}
	else {
		echo "empty"."</br>";
	}
	return $array;
}

$sql = "SELECT * FROM school_identification";
$school_identification = tabletToArray($sql, $link);

$sql = "SELECT * FROM school_structure";
$school_structure = tabletToArray($sql, $link);

$sql = "SELECT * FROM classroom";
$classroom = tabletToArray($sql, $link);

$sql = "SELECT * FROM instructor_identification";
$instructor_identification = tabletToArray($sql, $link);

$sql = "SELECT * FROM instructor_documents_and_address";
$instructor_documents_and_address = tabletToArray($sql, $link);

$sql = "SELECT * FROM instructor_variable_data";
$instructor_variable_data = tabletToArray($sql, $link);

$sql = "SELECT * FROM instructor_teaching_data";
$instructor_teaching_data = tabletToArray($sql, $link);

$sql = "SELECT * FROM student_identification";
$student_identification = tabletToArray($sql, $link);

$sql = "SELECT * FROM student_documents_and_address";
$student_documents_and_address = tabletToArray($sql, $link);

$sql = "SELECT * FROM student_enrollment";
$student_enrollment = tabletToArray($sql, $link);


$ssv = new SchoolStructureValidation();


foreach ($school_structure as $key => $collun) {
	echo "Registro 20. Index $key"."</br>";
	echo "Campo 1"."</br>";
	$ssv->isRegisterTen($collun['register_type']);
	echo "Campo 2"."</br>";
	$ssv->isEqual($collun["school_inep_id_fk"], $school_identification[$key]["inep_id"], 
					"Inep id's são diferentes");
	echo "Campo 3"."</br>";
	$operation_locations = array($collun["operation_location_building"], 
									$collun["operation_location_temple"],
									$collun["operation_location_businness_room"], 
									$collun["operation_location_instructor_house"],
									$collun["operation_location_other_school_room"],
									$collun["operation_location_barracks"],
									$collun["operation_location_socioeducative_unity"],
									$collun["operation_location_prison_unity"],
									$collun["operation_location_other"]);
	$ssv->atLeastOne($operation_locations);

}


mysqli_close($link);


?>