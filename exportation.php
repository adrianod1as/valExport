<?php

require_once(dirname(__FILE__) .  $DS . "db" .  $DS . "database.php");


class Exportation {

	protected static $db;

	function __construct(){
		self::$db = new Db();
	}

	function getTables($inep_id){

		//Registro 00
		$sql = "SELECT * FROM school_identification WHERE inep_id = '$inep_id'";
		$school_identification = self::$db->select($sql);

		//Registro 10
		$sql = "SELECT * FROM school_structure WHERE school_inep_id_fk = '$inep_id'";
		$school_structure = self::$db->select($sql);

		//Registro 20
		$sql = "SELECT * FROM classroom WHERE school_inep_fk = '$inep_id'";
		$classroom = self::$db->select($sql);

		//Registro 30
		$sql = "SELECT * FROM instructor_identification WHERE school_inep_id_fk = '$inep_id'";
		$instructor_identification = self::$db->select($sql);

		//Registro 40
		$sql = "SELECT * FROM instructor_documents_and_address WHERE school_inep_id_fk = '$inep_id'";
		$instructor_documents_and_address = self::$db->select($sql);

		//Registro 50
		$sql = "SELECT * FROM instructor_variable_data WHERE school_inep_id_fk = '$inep_id'";
		$instructor_variable_data = self::$db->select($sql);

		//Registro 51
		$sql = "SELECT * FROM instructor_teaching_data WHERE school_inep_id_fk = '$inep_id'";
		$instructor_teaching_data = self::$db->select($sql);

		//Registro 60
		$sql = "SELECT * FROM student_identification WHERE school_inep_id_fk = '$inep_id'";
		$student_identification = self::$db->select($sql);

		//Registro 70
		$sql = "SELECT * FROM student_documents_and_address WHERE school_inep_id_fk = '$inep_id'";
		$student_documents_and_address = self::$db->select($sql);

		//Registro 80
		$sql = "SELECT * FROM student_enrollment WHERE school_inep_id_fk = '$inep_id'";
		$student_enrollment = self::$db->select($sql);

		return array($school_identification,
						$school_structure,
						$classroom,
						$instructor_identification,
						$instructor_documents_and_address,
						$instructor_variable_data,
						$instructor_teaching_data,
						$student_identification,
						$student_documents_and_address,
						$student_enrollment);
	}

	//Inep ids permitidos
	function getAllowedInepIds($table){
		$sql = "SELECT inep_id FROM $table;";
		$array = self::$db->select($sql);
		foreach ($array as $key => $value) {
			$inep_ids[] = $value['inep_id'];
		}

		return $inep_ids;
	}

	/*
		*Checa se há o determinado de grupo de pessoas nas modalidades disponíveis
		*uxilia campo 92 à 95 no registro 10
	*/
	function areThereByModalitie($sql){
		$people_by_modalitie = self::$db->select($sql);
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
}


 ?>