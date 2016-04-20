<?php
$var = isset($_GET['year']) ? $_GET['year'] : $argv[1];

$year = date('Y');
if( $var != null 
	&& is_int(intval($var)) 
	&& $var > 2010 
	&& $var < $year){
	$year = $var;
}

require(dirname(__FILE__) . DIRECTORY_SEPARATOR . "schoolStructureValidation.php");

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

	//campos 30 à 32
	$sewages = array($collun["sewage_public"], 
						$collun["sewage_fossa"],
						$collun["sewage_inexistent"]);
	$result = $ssv->supply($sewages);
	if(!$result["status"]) array_push($log, array("sewages"=>$result["erro"]));

	//campos 33 à 38
	$garbage_destinations = array($collun["garbage_destination_collect"], 
									$collun["garbage_destination_burn"],
									$collun["garbage_destination_throw_away"], 
									$collun["garbage_destination_recycle"],
									$collun["garbage_destination_bury"],
									$collun["garbage_destination_other"]);
	$result = $ssv->atLeastOne($garbage_destinations);
	if(!$result["status"]) array_push($log, array("garbage_destinations"=>$result["erro"]));

	//campos 39 à 68
	$dependencies = array($collun["dependencies_principal_room"], 
							$collun["dependencies_instructors_room"],
							$collun["dependencies_secretary_room"], 
							$collun["dependencies_info_lab"],
							$collun["dependencies_science_lab"],
							$collun["dependencies_aee_room"], 
							$collun["dependencies_indoor_sports_court"],
							$collun["dependencies_outdoor_sports_court"],
							$collun["dependencies_kitchen"], 
							$collun["dependencies_library"],
							$collun["dependencies_reading_room"],
							$collun["dependencies_playground"], 
							$collun["dependencies_nursery"],
							$collun["dependencies_outside_bathroom"],
							$collun["dependencies_inside_bathroom"], 
							$collun["dependencies_child_bathroom"],
							$collun["dependencies_prysical_disability_bathroom"],
							$collun["dependencies_physical_disability_support"], 
							$collun["dependencies_bathroom_with_shower"],
							$collun["dependencies_refectory"],
							$collun["dependencies_storeroom"], 
							$collun["dependencies_warehouse"],
							$collun["dependencies_auditorium"],
							$collun["dependencies_covered_patio"], 
							$collun["dependencies_uncovered_patio"],
							$collun["dependencies_student_accomodation"],
							$collun["dependencies_instructor_accomodation"], 
							$collun["dependencies_green_area"],
							$collun["dependencies_laundry"],
							$collun["dependencies_none"]);
	$result = $ssv->supply($dependencies);
	if(!$result["status"]) array_push($log, array("dependencies"=>$result["erro"]));

	//campo 69
	$result = $ssv->schoolsCount($collun["operation_location_building"],
												$collun["classroom_count"]);
	if(!$result["status"]) array_push($log, array("classroom_count"=>$result["erro"]));

	//campo 70
	$result = $ssv->isGreaterThan($collun["used_classroom_count"], "0");
	if(!$result["status"]) array_push($log, array("used_classroom_count"=>$result["erro"]));

	//campo 71 à 83
	$result = $ssv->isGreaterThan($collun["used_classroom_count"], "0");
	if(!$result["status"]) array_push($log, array("used_classroom_count"=>$result["erro"]));

	//campo 84
	$result = $ssv->pcCount($collun["equipments_computer"],
									$collun["administrative_computers_count"]);
	if(!$result["status"]) array_push($log, array("administrative_computers_count"=>$result["erro"]));

	//campo 85
	$result = $ssv->pcCount($collun["equipments_computer"],
									$collun["student_computers_count"]);
	if(!$result["status"]) array_push($log, array("student_computers_count"=>$result["erro"]));

	//campo 86
	$result = $ssv->internetAccess($collun["equipments_computer"],
									$collun["internet_access"]);
	if(!$result["status"]) array_push($log, array("internet_access"=>$result["erro"]));

	//campo 87
	$result = $ssv->bandwidth($collun["internet_access"],
									$collun["bandwidth"]);
	if(!$result["status"]) array_push($log, array("bandwidth"=>$result["erro"]));

	//campo 88
	$result = $ssv->isGreaterThan($collun["employees_count"], "0");
	if(!$result["status"]) array_push($log, array("employees_count"=>$result["erro"]));

	//campo 89
	$result = $ssv->schoolFeeding($school_identification[$key]["administrative_dependence"],
									$collun["feeding"],
									$classroom[$key]["pedagogical_mediation_type"]);
	if(!$result["status"]) array_push($log, array("feeding"=>$result["erro"]));

	//campo 90
	$modalities = array($collun["modalities_regular"], 
									$collun["modalities_especial"],
									$collun["modalities_eja"], 
									$collun["modalities_professional"]);

	$result = $ssv->aee($collun["aee"], $collun["complementary_activities"], $modalities, 
									$classroom[$key]["pedagogical_mediation_type"]);
	if(!$result["status"]) array_push($log, array("aee"=>$result["erro"]));

	//campo 91
	$result = $ssv->aee($collun["complementary_activities"], $collun["aee"], $modalities, 
									$classroom[$key]["pedagogical_mediation_type"]);
	if(!$result["status"]) array_push($log, array("complementary_activities"=>$result["erro"]));

	//campo 92 à 95

	$sql = "SELECT  modalities, COUNT(se.student_fk) as number_of_students
			FROM	edcenso_stage_vs_modality_complementary as esmc 
						INNER JOIN 
					classroom AS cr
						ON esmc.fk_edcenso_stage_vs_modality = cr.edcenso_stage_vs_modality_fk
						INNER JOIN
					student_enrollment AS se
						ON cr.id = se.classroom_fk
			WHERE cr.school_year = '$year'
			GROUP BY esmc.modalities;";
	$studens_by_modalitie = tabletToArray($sql, $link);

	$sql = "SELECT  COUNT(itd.instructor_fk) as number_of_instructors, modalities
			FROM	edcenso_stage_vs_modality_complementary as esmc 
						INNER JOIN 
					classroom AS cr
						ON esmc.fk_edcenso_stage_vs_modality = cr.edcenso_stage_vs_modality_fk
						INNER JOIN
					instructor_teaching_data AS itd
						ON cr.id = itd.classroom_id_fk
			WHERE cr.school_year = '$year'
			GROUP BY esmc.modalities;";

	$instructors_by_modalitie = tabletToArray($sql, $link);


	$result = $ssv->checkModalities($collun["aee"], $collun["complementary_activities"], $modalities);
	if(!$result["status"]) array_push($log, array("modalities"=>$result["erro"]));

	//campo 97
	$result = $ssv->differentiatedLocation($school_identification[$key]["inep_id"], 
											$collun["different_location"]);
	if(!$result["status"]) array_push($log, array("different_location"=>$result["erro"]));

	//campo 98 à 100
	$sociocultural_didactic_materials = array($collun["sociocultural_didactic_material_none"], 
												$collun["sociocultural_didactic_material_quilombola"],
												$collun["sociocultural_didactic_material_native"]);
	$result = $ssv->materials($sociocultural_didactic_materials);
	if(!$result["status"]) array_push($log, array("sociocultural_didactic_materials"=>$result["erro"]));

	//101
	$result = $ssv->isAllowed($collun["native_education"], array("0", "1"));
	if(!$result["status"]) array_push($log, array("native_education"=>$result["erro"]));

	//102 à 103
	$native_education_languages = array($collun["native_education_language_native"], 
												$collun["native_education_language_portuguese"]);
	$result = $ssv->languages($collun["native_education"], $native_education_languages);
	if(!$result["status"]) array_push($log, array("native_education_languages"=>$result["erro"]));

	//104
	$result = $ssv->edcensoNativeLanguages($collun["native_education_language_native"],
											$collun["edcenso_native_languages_fk"],
											$link);
	if(!$result["status"]) array_push($log, array("edcenso_native_languages_fk"=>$result["erro"]));

	//105
	$result = $ssv->isAllowed($collun["brazil_literate"], array("0", "1"));
	if(!$result["status"]) array_push($log, array("brazil_literate"=>$result["erro"]));

	//106
	$result = $ssv->isAllowed($collun["open_weekend"], array("0", "1"));
	if(!$result["status"]) array_push($log, array("open_weekend"=>$result["erro"]));



	//Adicionando log da row
	if($log != null) $school_structure_log["row $key"] = $log;


}

// print_r($school_structure_log);

echo json_encode($school_structure_log);

mysqli_close($link);


?>