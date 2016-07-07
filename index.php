<?php
	$DS = DIRECTORY_SEPARATOR;

	require_once(dirname(__FILE__) .  $DS . "db" .  $DS . "database.php");

	$db = new Db();

	$sql = "SELECT 	si.inep_id, si.name
				FROM 	school_identification AS si;";

	$values = $db->select($sql);

?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="assets/sweetalert/dist/sweetalert.css">

<link rel="stylesheet" type="text/css" href="assets/css/pre-load.css">


<script src="assets/sweetalert/dist/sweetalert.min.js"></script>


<script type="text/javascript">


 function checkForm(){
 	if(!(document.getElementById("year").value != "" && document.getElementById("inep_id").value != "")){
 		sweetAlert("Preecha todos os campos!", "", "error");
 	}else{
 		document.getElementById("loader-wrapper").style.display = "block";
 		document.getElementById("search").submit();
 	}
 }

</script>


<form action="ValidateExport.php" id="search" method="get" style="padding-top: 30px">
	<div class="container">

		<div id="loader-wrapper" class="no-show" style="display:none">
          <div id="loader" class="loader"></div>
          <span class="texto-load">Carregando....</span>
          <div class="loader-section"></div>
        </div>

		<div class="col-md-8 col-md-offset-2">
			  <div class="form-group">
			    <label for="year">Ano</label>
			    <select id="year" name="year" class="form-control">
				  <option value="2010">2010</option>
				  <option value="2011">2011</option>
				  <option value="2012">2012</option>
				  <option value="2013">2013</option>
				  <option value="2014">2014</option>
				  <option value="2015">2015</option>
				  <option value="2016" selected>2016</option>
				</select>
			  </div>
			  <div class="form-group">
			    <label for="inep_id">Inep Id</label>
			    <select id="inep_id" name="inep_id" class="form-control">
			    	<?
			    		foreach ($values as $key => $value) {
			    			$inep_id = $value['inep_id'];
			    			$name = $value['name'];
			    			echo "<option value='$inep_id'>$name</option>";
			    		}
			    	?>
				</select>
			  </div>
			  <button id="button_search" type="button" onclick="checkForm();" class="btn btn-primary">Pesquisar</button>
		</div>

	</div>
</form>
