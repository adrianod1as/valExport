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
$school_structure_log = array();


foreach ($school_structure as $key => $collun) {
	$log = array();
	//campo 1
	$result = $ssv->isRegisterTen($collun['register_type']);
	if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

	//campo 2
	$result = $ssv->isEqual($collun["school_inep_id_fk"], 
					$school_identification[$key]["inep_id"], 
					"Inep id's são diferentes");
	if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

	//campo 3 à 11
	$operation_locations = array($collun["operation_location_building"], 
									$collun["operation_location_temple"],
									$collun["operation_location_businness_room"], 
									$collun["operation_location_instructor_house"],
									$collun["operation_location_other_school_room"],
									$collun["operation_location_barracks"],
									$collun["operation_location_socioeducative_unity"],
									$collun["operation_location_prison_unity"],
									$collun["operation_location_other"]);
	$result = $ssv->atLeastOne($operation_locations);
	if(!$result["status"]) array_push($log, array("operation_locations"=>$result["erro"]));

	//campo 12
	$result = $ssv->buildingOccupationStatus($collun["operation_location_building"],
												$collun["operation_location_barracks"],
												$collun["building_occupation_situation"]);
	if(!$result["status"]) array_push($log, array("building_occupation_situation"=>$result["erro"]));

	//campo 13
	$result = $ssv->sharedBuildingSchool($collun["operation_location_building"],
												$collun["shared_building_with_school"]);
	if(!$result["status"]) array_push($log, array("shared_building_with_school"=>$result["erro"]));

	//campos 14 à 19
	$shared_school_inep_ids = array($collun["shared_school_inep_id_1"], 
									$collun["shared_school_inep_id_2"],
									$collun["shared_school_inep_id_3"], 
									$collun["shared_school_inep_id_4"],
									$collun["shared_school_inep_id_5"],
									$collun["shared_school_inep_id_6"]);
	$result = $ssv->sharedSchoolInep($collun["shared_building_with_school"],
										$school_identification[$key]["inep_id"],
										$shared_school_inep_ids);
	if(!$result["status"]) array_push($log, array("shared_school_inep_ids"=>$result["erro"]));

	//campo 20
	$result = $ssv->consumedWater($collun["consumed_water_type"]);
	if(!$result["status"]) array_push($log, array("consumed_water_type"=>$result["erro"]));

	//campos 21 à 25
	$water_supplys = array($collun["water_supply_public"], 
								$collun["water_supply_artesian_well"],
								$collun["water_supply_well"], 
								$collun["water_supply_river"],
								$collun["water_supply_inexistent"]);
	$result = $ssv->supply($water_supplys);
	if(!$result["status"]) array_push($log, array("water_supplys"=>$result["erro"]));

	//campos 26 à 29
	$energy_supplys = array($collun["energy_supply_public"], 
								$collun["energy_supply_generator"],
								$collun["energy_supply_other"], 
								$collun["energy_supply_inexistent"]);
	$result = $ssv->supply($energy_supplys);
	if(!$result["status"]) array_push($log, array("energy_supplys"=>$result["erro"]));

	


	if($log != null) $school_structure_log["row $key"] = $log;

}

// print_r($school_structure_log);

echo json_encode($school_structure_log);

mysqli_close($link);


?>