<?php
	
	function conectar() {
		$DB_HOST = "localhost";
		$DB_USER = "root";
		$DB_PASS = "root";
		$DB_DB = "usuarios";
		
		return $ENLACE_DB = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_DB);
	}
	
	function irA($pagina) {
		header("Location: " . $pagina);
		exit;
	}

?>