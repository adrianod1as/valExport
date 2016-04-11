<?php

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

mysqli_close($link);


?>