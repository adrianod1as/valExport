<?php
ini_set('memory_limit', '-1');

$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) .  $DS . "db" .  $DS . "database.php");
require_once(dirname(__FILE__) . $DS . "registros" . $DS . "schoolStructureValidation.php");
require_once(dirname(__FILE__) . $DS . "registros" . $DS . "InstructorIdentificationValidation.php");
require_once(dirname(__FILE__) . $DS . "registros" . $DS . "instructorTeachingDataValidation.php");
require_once(dirname(__FILE__) . $DS . "registros" . $DS . "studentIdentificationValidation.php");
require_once(dirname(__FILE__) . $DS . "registros" . $DS . "studentEnrollmentValidation.php");


//Recebendo ano via HTTP ou via argumento no console.
$var = isset($_GET['year']) ? $_GET['year'] : $argv[1];

$year = date('Y');
if( $var != null 
	&& is_int(intval($var)) 
	&& $var > 2010 
	&& $var < $year){
	$year = $var;
}

//Inicializando Objeto de conexão com o banco
$db = new Db();


//Importanto em arrays todas as tabelas referentes ao registros

//Registro 00
$sql = "SELECT * FROM school_identification ORDER BY inep_id";
$school_identification = $db->select($sql);

//Inep ids permitidos
$sql = "SELECT inep_id FROM school_identification;";
$array = $db->select($sql);
foreach ($array as $key => $value) {
	$allowed_school_inep_ids[] = $value['inep_id'];
}

//Registro 10
$sql = "SELECT * FROM school_structure ORDER BY school_inep_id_fk";
$school_structure = $db->select($sql);

//Registro 20 
$sql = "SELECT * FROM classroom";
$classroom = $db->select($sql);

//Registro 30
$sql = "SELECT * FROM instructor_identification";
$instructor_identification = $db->select($sql);

//Registro 40 
$sql = "SELECT * FROM instructor_documents_and_address";
$instructor_documents_and_address = $db->select($sql);

//Registro 50
$sql = "SELECT * FROM instructor_variable_data";
$instructor_variable_data = $db->select($sql);

//Registro 51
$sql = "SELECT * FROM instructor_teaching_data";
$instructor_teaching_data = $db->select($sql);

//Registro 60
$sql = "SELECT * FROM student_identification";
$student_identification = $db->select($sql);

//Inep ids permitidos
$sql = "SELECT inep_id FROM student_identification;";
$array = $db->select($sql);
foreach ($array as $key => $value) {
	$allowed_students_inep_ids[] = $value['inep_id'];
}

//Registro 70
$sql = "SELECT * FROM student_documents_and_address";
$student_documents_and_address = $db->select($sql);

//Registro 80
$sql = "SELECT * FROM student_enrollment";
$student_enrollment = $db->select($sql);




/*
*Checa se há o determinado de grupo de pessoas nas modalidades disponíveis
*uxilia campo 92 à 95 
*/

function areThereByModalitie($people_by_modalitie){
	$modalities_regular	= false;
	$modalities_especial = false;
	$modalities_eja = false;
	$modalities_professional = false;
	foreach ($people_by_modalitie as $key => $item) {
		switch ($item['modalities']) {

			case '1':
				if($item['number_of'] > '0')
					$modalities_regular = true;
				break;
			
			case '2':
				if($item['number_of'] > '0')
					$modalities_especial = true;
				break;

			case '3':
				if($item['number_of'] > '0')
					$modalities_eja = true;
				break;

			case '4':
				if($item['number_of'] > '0')
					$modalities_professional = true;
				break;
		}
	}
	return array("modalities_regular" => $modalities_regular, 
					"modalities_especial" => $modalities_especial, 
					"modalities_eja" => $modalities_eja,
					"modalities_professional" => $modalities_professional);
}

$sql = "SELECT  modalities, COUNT(se.student_fk) as number_of
		FROM	edcenso_stage_vs_modality_complementary as esmc 
					INNER JOIN 
				classroom AS cr
					ON esmc.fk_edcenso_stage_vs_modality = cr.edcenso_stage_vs_modality_fk
					INNER JOIN
				student_enrollment AS se
					ON cr.id = se.classroom_fk
		WHERE cr.school_year = '$year'
		GROUP BY esmc.modalities;";
$students_by_modalitie = $db->select($sql);
$are_there_students_by_modalitie = areThereByModalitie($students_by_modalitie);

$sql = "SELECT  modalities, COUNT(itd.instructor_fk) as number_of
		FROM	edcenso_stage_vs_modality_complementary as esmc 
					INNER JOIN 
				classroom AS cr
					ON esmc.fk_edcenso_stage_vs_modality = cr.edcenso_stage_vs_modality_fk
					INNER JOIN
				instructor_teaching_data AS itd
					ON cr.id = itd.classroom_id_fk
		WHERE cr.school_year = '$year'
		GROUP BY esmc.modalities;";
$instructors_by_modalitie = $db->select($sql);
$are_there_instructors_by_modalitie = areThereByModalitie($students_by_modalitie);

/*
*Validação da tabela school_structure
*Registro 10
*/

$ssv = new SchoolStructureValidation();
$school_structure_log = array();

foreach ($school_structure as $key => $collun) {

	$school_inep_id_fk = $collun["school_inep_id_fk"];
	$log = array();

	//campo 1
	$result = $ssv->isRegister("10", $collun['register_type']);
	if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

	//campo 2
	$result = $ssv->isAllowed($school_inep_id_fk, 
									$allowed_school_inep_ids);
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
	$result = $ssv->oneOfTheValues($collun["consumed_water_type"]);
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
	$sql = 'SELECT  COUNT(pedagogical_mediation_type) AS number_of 
		FROM 	classroom 
		WHERE 	school_inep_fk = "$school_inep_id_fk" AND
				(pedagogical_mediation_type =  "1" OR pedagogical_mediation_type =  "2");';
	$pedagogical_mediation_type = $db->select($sql);


	$result = $ssv->schoolFeeding($school_identification[$key]["administrative_dependence"],
									$collun["feeding"],
									$pedagogical_mediation_type[0]["number_of"]);
	if(!$result["status"]) array_push($log, array("feeding"=>$result["erro"]));

	//campo 90
	$sql = "SELECT 	COUNT(assistance_type) AS number_of 
			FROM 	classroom  
			WHERE 	assistance_type = '5' AND 
					school_inep_fk = '$school_inep_fk';" ;
	$assistance_type = $db->select($sql);


	$modalities = array("modalities_regular" => $collun["modalities_regular"], 
							"modalities_especial" => $collun["modalities_especial"],
							"modalities_eja" =>	$collun["modalities_eja"], 
							"modalities_professional" => $collun["modalities_professional"]);

	$result = $ssv->aee($collun["aee"], $collun["complementary_activities"], $modalities, 
									$assistance_type[0]["number_of"]);
	if(!$result["status"]) array_push($log, array("aee"=>$result["erro"]));

	//campo 91
	$sql = "SELECT 	COUNT(assistance_type) AS number_of 
			FROM 	classroom  
			WHERE 	assistance_type = '4' AND 
					school_inep_fk = '$school_inep_fk';" ;
	$assistance_type = $db->select($sql);


	$result = $ssv->aee($collun["complementary_activities"], $collun["aee"], $modalities, 
									$assistance_type[0]["number_of"]);
	if(!$result["status"]) array_push($log, array("complementary_activities"=>$result["erro"]));

	//campo 92 à 95

	$result = $ssv->checkModalities($collun["aee"], 
										$collun["complementary_activities"], 
										$modalities,
										$are_there_students_by_modalitie,
										$are_there_instructors_by_modalitie);
	if(!$result["status"]) array_push($log, array("modalities"=>$result["erro"]));

	//campo 96
	$sql = "SELECT 	DISTINCT  COUNT(esm.id) AS number_of, cr.school_inep_fk 
			FROM 	classroom AS cr 
						INNER JOIN 
					edcenso_stage_vs_modality AS esm 
						ON esm.id = cr.edcenso_stage_vs_modality_fk 
			WHERE 	stage IN (2,3,7) AND cr.school_inep_fk = '$school_inep_fk';";
	$number_of_schools = $db->select($sql);

	$result = $ssv->schoolCicle($collun["basic_education_cycle_organized"], $number_of_schools);
	if(!$result["status"]) array_push($log, array("basic_education_cycle_organized"=>$result["erro"]));

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

	//107
	$sql = "SELECT 	COUNT(esm.id ) AS number_of 
			FROM 	classroom AS cr  
						INNER JOIN  
					edcenso_stage_vs_modality AS esm 
						ON esm.id = cr.edcenso_stage_vs_modality_fk
			WHERE 	cr.assistance_type NOT IN (4,5) AND 
					cr.school_inep_fk =  '$school_inep_id_fk' AND 
					esm.stage NOT IN (1,2);";
	$pedagogical_formation_by_alternance = $db->select($sql);

	$result = $ssv->pedagogicalFormation($collun["pedagogical_formation_by_alternance"], 
											$pedagogical_formation_by_alternance[0]["number_of"]);
	if(!$result["status"]) array_push($log, array("pedagogical_formation_by_alternance"=>$result["erro"]));

	//Adicionando log da row
	if($log != null) $school_structure_log["row $key"] = $log;
}

/*
*Validação da tabela instructor_identification
*Registro 30
*/

$iiv = new InstructorIdentificationValidation();
$instructor_identification_log = array();



foreach ($instructor_identification as $key => $collun) {

	$school_inep_id_fk = $collun["school_inep_id_fk"];
	$log = array();

	//campo 1
	$result = $iiv->isRegister("30", $collun['register_type']);
	if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

	//campo 2
	$result = $iiv->isAllowedInepId($school_inep_id_fk, 
									$allowed_school_inep_ids);
	if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

		
	//campo 3
	$result = $iiv->isNumericOfSize(12, $collun['inep_id']);
	if(!$result["status"]) array_push($log, array("inep_id"=>$result["erro"]));

	//campo 4
	$result = $iiv->isNotGreaterThan($collun['id'], 20);
	if(!$result["status"]) array_push($log, array("id"=>$result["erro"]));

	//campo 5
	$result = $iiv->isNameValid($collun['name'], 100, 
								$instructor_documents_and_address[$key]["cpf"]);
	if(!$result["status"]) array_push($log, array("name"=>$result["erro"]));

	//campo 6
	$result = $iiv->isEmailValid($collun['email'], 100);
	if(!$result["status"]) array_push($log, array("email"=>$result["erro"]));

	//campo 7
	$result = $iiv->isNull($collun['nis']);
	if(!$result["status"]) array_push($log, array("nis"=>$result["erro"]));

	//campo 8
	$result = $iiv->validateBirthday($collun['birthday_date'], "13", "96", $year);
	if(!$result["status"]) array_push($log, array("birthday_date"=>$result["erro"]));

	//campo 9
	$result = $iiv->oneOfTheValues($collun['sex']);
	if(!$result["status"]) array_push($log, array("sex"=>$result["erro"]));

	//campo 10
	$result = $iiv->isAllowed($collun['color_race'], array("0", "1", "2", "3", "4", "5"));
	if(!$result["status"]) array_push($log, array("sex"=>$result["erro"]));

	//campo 11, 12, 13
	$result = $iiv->validateFiliation($collun['filiation'], $collun['filiation_1'], $collun['filiation_2'], 
								$instructor_documents_and_address[$key]["cpf"], 100);
	if(!$result["status"]) array_push($log, array("filiation"=>$result["erro"]));

	//campo 14, 15
	$result = $iiv->checkNation($collun['edcenso_nation_fk'], $collun['nationality'], array("1", "2", "3") );
	if(!$result["status"]) array_push($log, array("nationality_nation"=>$result["erro"]));

	//campo 16
	$result = $iiv->ufcity($collun['edcenso_uf_fk'], $collun['nationality']);
	if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));

	//campo 17
	$result = $iiv->ufcity($collun['edcenso_city_fk'], $collun['nationality']);
	if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));

	//campo 18
	$result = $iiv->isAllowed($collun['deficiency'], array("0", "1"));
	if(!$result["status"]) array_push($log, array("deficiency"=>$result["erro"]));

	//campo 19 à 25
	$deficiencies = array($collun['deficiency_type_blindness'],
							$collun['deficiency_type_low_vision'],
							$collun['deficiency_type_deafness'],
							$collun['deficiency_type_disability_hearing'],
							$collun['deficiency_type_deafblindness'],
							$collun['deficiency_type_phisical_disability'],
							$collun['deficiency_type_intelectual_disability']);

	$excludingdeficiencies = array($collun['deficiency_type_blindness'] => 
								array($collun['deficiency_type_low_vision'], $collun['deficiency_type_deafness'], 
										$collun['deficiency_type_deafblindness']), 
							$collun['deficiency_type_low_vision'] => 
								array($collun['deficiency_type_deafblindness']), 
							$collun['deficiency_type_deafness'] => 
								array($collun['deficiency_type_disability_hearing'], $collun['deficiency_type_disability_hearing']), 
							$collun['deficiency_type_disability_hearing'] => 
								array($collun['deficiency_type_deafblindness']));

	$result = $iiv->checkDeficiencies($collun['deficiency'], $deficiencies, $excludingdeficiencies);
	if(!$result["status"]) array_push($log, array("deficiencies"=>$result["erro"]));

	//campo 26
	
	$result = $iiv->checkMultiple($collun['deficiency'], $collun['deficiency_type_multiple_disabilities'], $deficiencies);
	if(!$result["status"]) array_push($log, array("deficiency_type_multiple_disabilities"=>$result["erro"]));
	
	//Adicionando log da row
	if($log != null) $instructor_identification_log["row $key"] = $log;
}


$stiv = new studentIdentificationValidation();
$student_identification_log = array();



foreach ($student_identification as $key => $collun) {

	$school_inep_id_fk = $collun["school_inep_id_fk"];
	$log = array();

	//campo 1
	$result = $stiv->isRegister("60", $collun['register_type']);
	if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

	//campo 2
	$result = $stiv->isAllowedInepId($school_inep_id_fk, 
									$allowed_school_inep_ids);
	if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

	//campo 3
	$result = $stiv->isNumericOfSize(12, $collun['inep_id']);
	if(!$result["status"]) array_push($log, array("inep_id"=>$result["erro"]));

	//campo 4
	$result = $stiv->isNotGreaterThan($collun['id'], 20);
	if(!$result["status"]) array_push($log, array("id"=>$result["erro"]));

	//campo 5
	$result = $stiv->isNameValid($collun['name'], 100, 
								$student_documents_and_address[$key]["cpf"]);
	if(!$result["status"]) array_push($log, array("name"=>$result["erro"]));

	//campo 6
	$result = $stiv->validateBirthday($collun['birthday'], 1910, $year);
	if(!$result["status"]) array_push($log, array("birthday"=>$result["erro"]));

	//campo 7
	$result = $stiv->oneOfTheValues($collun['sex']);
	if(!$result["status"]) array_push($log, array("sex"=>$result["erro"]));

	//campo 8
	$result = $stiv->isAllowed($collun['color_race'], array("0", "1", "2", "3", "4", "5"));
	if(!$result["status"]) array_push($log, array("sex"=>$result["erro"]));

	//campo 9, 10, 11
	$result = $stiv->validateFiliation($collun['filiation'], $collun['filiation_1'], $collun['filiation_2'], 
								$student_documents_and_address[$key]["cpf"], 100);
	if(!$result["status"]) array_push($log, array("filiation"=>$result["erro"]));

	//campo 12, 13
	$result = $stiv->checkNation($collun['nationality'], $collun['edcenso_nation_fk'], array("1", "2", "3") );
	if(!$result["status"]) array_push($log, array("nationality_nation"=>$result["erro"]));

	//campo 14
	$result = $stiv->ufcity($collun['edcenso_uf_fk'], $collun['nationality']);
	if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));

	//campo 15
	$result = $stiv->ufcity($collun['edcenso_city_fk'], $collun['nationality']);
	if(!$result["status"]) array_push($log, array("edcenso_uf_fk"=>$result["erro"]));

	//campo 16
	$student_id = $collun['id'];

	$sql = "SELECT 	COUNT(cr.id) AS status
			FROM 	student_identification as si 
						INNER JOIN 
					student_enrollment AS se 
						ON si.id = se.student_fk
          				INNER JOIN  
          			classroom AS cr 
          				ON se.classroom_fk = cr.id
			WHERE si.id = '$student_id' AND (cr.assistance_type = 5 OR cr.modality = 2)
			GROUP BY si.id;";

	$hasspecialneeds = $db->select($sql);

	$result = $stiv->specialNeeds($collun['deficiency'], array("0", "1"),
										$hasspecialneeds[0]["status"]);
	if(!$result["status"]) array_push($log, array("pedagogical_formation_by_alternance"=>$result["erro"]));

	//campo 17 à 24 e 26 à 29

	$deficiencies_whole = array($collun['deficiency_type_blindness'],
								$collun['deficiency_type_low_vision'],
								$collun['deficiency_type_deafness'],
								$collun['deficiency_type_disability_hearing'],
								$collun['deficiency_type_deafblindness'],
								$collun['deficiency_type_phisical_disability'],
								$collun['deficiency_type_intelectual_disability'],
								$collun['deficiency_type_autism'],
								$collun['deficiency_type_aspenger_syndrome'], 
								$collun['deficiency_type_rett_syndrome'],
								$collun['deficiency_type_childhood_disintegrative_disorder'],
								$collun['deficiency_type_gifted']);

	$excludingdeficiencies = array(	$collun['deficiency_type_blindness'] => 
										array($collun['deficiency_type_low_vision'], $collun['deficiency_type_deafness'], 
												$collun['deficiency_type_deafblindness']), 
									$collun['deficiency_type_low_vision'] => 
										array($collun['deficiency_type_deafblindness']), 
									$collun['deficiency_type_deafness'] => 
										array($collun['deficiency_type_disability_hearing'], $collun['deficiency_type_disability_hearing']), 
									$collun['deficiency_type_disability_hearing'] => 
										array($collun['deficiency_type_deafblindness']), 
									$collun['deficiency_type_autism'] => 
										array($collun['deficiency_type_aspenger_syndrome'], $collun['deficiency_type_rett_syndrome'], 
												$collun['deficiency_type_childhood_disintegrative_disorder']),  
									$collun['deficiency_type_aspenger_syndrome'] => 
										array($collun['deficiency_type_rett_syndrome'], $collun['deficiency_type_childhood_disintegrative_disorder']), 
									$collun['deficiency_type_rett_syndrome'] => 
										array($collun['deficiency_type_childhood_disintegrative_disorder']));

	$result = $stiv->checkDeficiencies($collun['deficiency'], $deficiencies_whole, $excludingdeficiencies);
	if(!$result["status"]) array_push($log, array("deficiencies"=>$result["erro"]));

	//campo 25	

	$deficiencies_sample = array($collun['deficiency_type_blindness'],
									$collun['deficiency_type_low_vision'],
									$collun['deficiency_type_deafness'],
									$collun['deficiency_type_disability_hearing'],
									$collun['deficiency_type_deafblindness'],
									$collun['deficiency_type_phisical_disability'],
									$collun['deficiency_type_intelectual_disability']);

	$result = $stiv->checkMultiple($collun['deficiency'], $collun['deficiency_type_multiple_disabilities'], $deficiencies_sample);
	if(!$result["status"]) array_push($log, array("deficiency_type_multiple_disabilities"=>$result["erro"]));
			
	//campo 30 à 39
	$sql = "SELECT  COUNT(si.id) AS status
			FROM 	student_identification AS si 
						INNER JOIN 
					student_enrollment AS se 
						ON si.id = se.student_fk
			WHERE 	se.edcenso_stage_vs_modality_fk in (16, 7, 18, 11, 41, 27, 28, 32, 33, 37, 38)  
					AND si.id = '$student_id';";
	$demandresources = $db->select($sql);
	
	$resources = array($collun['resource_aid_lector'],
						$collun['resource_interpreter_guide'],
						$collun['resource_interpreter_libras'],
						$collun['resource_lip_reading'],
						$collun['resource_zoomed_test_16'],
						$collun['resource_zoomed_test_20'],
						$collun['resource_zoomed_test_24'],
						$collun['resource_braille_test'],
						$collun['resource_none'],
						$collun['resource_aid_transcription']);

	array_pop($deficiencies_whole);
	$result = $stiv->inNeedOfResources($deficiencies_whole, 
										$demandresources, 
										$resources, 
										$collun['deficiency_type_blindness'],
										$collun['deficiency_type_deafblindness']);
	if(!$result["status"]) array_push($log, array("resources"=>$result["erro"]));

	//Adicionando log da row
	if($log != null) $student_identification_log["row $key"] = $log;

}

$sev = new studentEnrollmentValidation();
$student_enrollment_log = array();


foreach ($student_enrollment as $key => $collun) {

	$school_inep_id_fk = $collun["school_inep_id_fk"];
	$student_inep_id_fk = $collun["student_inep_id"];
	$classroom_fk = $collun['classroom_fk'];
	$log = array();

	//campo 1
	$result = $sev->isRegister("80", $collun['register_type']);
	if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

	//campo 2
	$result = $sev->isAllowedInepId($school_inep_id_fk, 
									$allowed_school_inep_ids);
	if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

	//campo 3
	$result = $sev->isAllowedInepId($student_inep_id_fk, 
									$allowed_students_inep_ids);
	if(!$result["status"]) array_push($log, array("student_inep_id"=>$result["erro"]));

	//campo 4
	$sql = "SELECT COUNT(inep_id) AS status FROM student_identification WHERE inep_id = '$student_inep_id';";
	$check = $db->select($sql);

	$result = $sev->isEqual($check[0]['status'],'1', 'Não há tal student_inep_id $student_inep_id');
	if(!$result["status"]) array_push($log, array("student_fk"=>$result["erro"]));

	//campo 05
	$result = $sev->isNull($collun['classroom_inep_id']);
	if(!$result["status"]) array_push($log, array("classroom_inep_id"=>$result["erro"]));
	
	//campo 6
	
	$sql = "SELECT COUNT(id) AS status FROM classroom WHERE id = '$classroom_fk';";
	$check = $db->select($sql);

	$result = $sev->isEqual($check[0]['status'],'1', 'Não há tal classroom_id $classroom_fk');
	if(!$result["status"]) array_push($log, array("classroom_fk"=>$result["erro"]));

	//campo 07
	$result = $sev->isNull($collun['enrollment_id']);
	if(!$result["status"]) array_push($log, array("enrollment_id"=>$result["erro"]));

	//campo 8
	
	$sql = "SELECT COUNT(id) AS status FROM classroom WHERE id = '$classroom_fk' AND edcenso_stage_vs_modality_fk = '3';";
	$check = $db->select($sql);

	$result = $sev->ifDemandsCheckValues($check[0]['status'], $collun['unified_class'], array('1', '2'));
	if(!$result["status"]) array_push($log, array("unified_class"=>$result["erro"]));

	//campo 9

	$sql = "SELECT edcenso_stage_vs_modality_fk FROM classroom WHERE id = '$classroom_fk';";
	$check = $db->select($sql);

	$edcenso_svm = $check[0]['edcenso_stage_vs_modality_fk'];

	$result = $sev->multiLevel($collun['edcenso_stage_vs_modality_fk'], $edcenso_svm);
	if(!$result["status"]) array_push($log, array("edcenso_stage_vs_modality_fk"=>$result["erro"]));

	//campo 10
	$sql = "SELECT assistance_type, pedagogical_mediation_type FROM classroom WHERE id = '$classroom_fk';";
	$check = $db->select($sql);
	$assistance_type = $check[0]['assistance_type'];
	$pedagogical_mediation_type = $check[0]['pedagogical_mediation_type'];

	$result = $sev->anotherScholarizationPlace($collun['another_scholarization_place'], $assistance_type, $pedagogical_mediation_type);
	if(!$result["status"]) array_push($log, array("another_scholarization_place"=>$result["erro"]));

	//campo 11
	$result = $sev->publicTransportation($collun['public_transport'], $pedagogical_mediation_type);
	if(!$result["status"]) array_push($log, array("public_transport"=>$result["erro"]));

	//campo 12
	$result = $sev->ifDemandsCheckValues($collun['public_transport'], $collun['transport_responsable_government'], array('1', '2'));
	if(!$result["status"]) array_push($log, array("transport_responsable_government"=>$result["erro"]));

	//campo 13 à 23
	
	$vehicules_types = array($collun['vehicle_type_van'],
								$collun['vehicle_type_microbus'],
								$collun['vehicle_type_bus'],
								$collun['vehicle_type_bike'],
								$collun['vehicle_type_other_vehicle'],
								$collun['vehicle_type_waterway_boat_5'],
								$collun['vehicle_type_waterway_boat_5_15'],
								$collun['vehicle_type_waterway_boat_15_35'],
								$collun['vehicle_type_waterway_boat_35'],
								$collun['vehicle_type_metro_or_train']);

	$result = $sev->vehiculesTypes($collun['public_transport'], $vehicules_types);
	if(!$result["status"]) array_push($log, array("vehicules_types"=>$result["erro"]));


	//24

	$sql = "SELECT se.administrative_dependence
			FROM school_identification AS se 
			WHERE se.inep_id = '$school_inep_id_fk';";

	$check = $db->select($sql);

	$administrative_dependence = $check[0]['administrative_dependence'];

	$result = $sev->studentEntryForm($collun['student_entry_form'], $administrative_dependence, $edcenso_svm);
	if(!$result["status"]) array_push($log, array("student_entry_form"=>$result["erro"]));

	//Adicionando log da row
	if($log != null) $student_enrollment_log["row $key"] = $log;

}

$itdv = new instructorTeachingDataValidation();
$instructor_teaching_data_log = array();


foreach ($instructor_teaching_data as $key => $collun) {

	$school_inep_id_fk = $collun["school_inep_id_fk"];
	$instructor_inep_id = $collun["instructor_inep_id"];
	$instructor_fk = $collun['instructor_fk'];
	$classroom_fk = $collun['classroom_id_fk'];
	$log = array();

	//campo 1
	$result = $itdv->isRegister("51", $collun['register_type']);
	if(!$result["status"]) array_push($log, array("register_type"=>$result["erro"]));

	//campo 2
	$result = $itdv->isAllowedInepId($school_inep_id_fk, 
									$allowed_school_inep_ids);
	if(!$result["status"]) array_push($log, array("school_inep_id_fk"=>$result["erro"]));

	//campo 03
	$sql = "SELECT COUNT(inep_id) AS status FROM instructor_identification WHERE inep_id =  '$instructor_inep_id'";
	$check = $db->select($sql);

	$result = $itdv->isEqual($check[0]['status'],'1', 'Não há tal instructor_inep_id $instructor_inep_id');
	if(!$result["status"]) array_push($log, array("instructor_inep_id"=>$result["erro"]));

	//campo 4
	$sql = "SELECT COUNT(id) AS status FROM instructor_identification WHERE id =  '$instructor_fk'";
	$check = $db->select($sql);

	$result = $itdv->isEqual($check[0]['status'],'1', 'Não há tal instructor_fk $instructor_fk');
	if(!$result["status"]) array_push($log, array("instructor_inep_id"=>$result["erro"]));

	//campo 5
	$result = $iiv->isNull($collun['classroom_inep_id']);
	if(!$result["status"]) array_push($log, array("nis"=>$result["erro"]));

	//campo 6
	$sql = "SELECT COUNT(id) AS status FROM classroom WHERE id = '$classroom_fk';";
	$check = $db->select($sql);

	$result = $iiv->isEqual($check[0]['status'],'1', 'Não há tal classroom_id $classroom_fk');
	if(!$result["status"]) array_push($log, array("classroom_fk"=>$result["erro"]));


	//Adicionando log da row
	if($log != null) $instructor_teaching_data_log["row $key"] = $log;
}



$register_log = array('Register 10' => $school_structure_log, 
						'Register 30' => $instructor_identification_log,
						'Register 51' => $instructor_teaching_data_log,
						'Register 60' => $student_identification_log,
						'Register 80' => $student_enrollment_log);
echo json_encode($register_log);




?>