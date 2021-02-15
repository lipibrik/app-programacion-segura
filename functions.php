<?php
	
	function conectarDB() {
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
	
	function mostrarError($codigo_error) {
		switch ($codigo_error) {
			case 1:
				echo "<div class='alert alert-danger' role='alert'>Usuario y/o contraseña incorrectos</div>";
			break;
			case 2:
				echo "<div class='alert alert-danger' role='alert'>No estás logueado</div>";
			break;
			case 3:
				echo "<div class='alert alert-danger' role='alert'>Fallo al guardar cambios</div>";
			break;
			case 4:
				echo "<div class='alert alert-danger' role='alert'>Debe indicarse un usuario que exista para editar</div>";
			break;
			default:
				echo "<div class='alert alert-danger' role='alert'>Se ha producido un error desconocido. Contacte con el administrador</div>";
			break;
		}
	}
	
	function mostrarMensaje($codigo_mensaje) {
		switch ($codigo_mensaje) {
			case 1:
				echo "<div class='alert alert-success' role='alert'>Se ha guardado con éxito</div>";
			break;			
			default:
				echo "<div class='alert alert-success' role='alert'>Acción realizada</div>";
			break;
		}
	}
	
	function compbobarCampos($nombre, $apellidos, $email, $telefono, $tipo_usuario, $password) {
		if (strlen($nombre) > 25 || strlen($apellidos) > 50 || strlen($email) > 50 || strlen($telefono) > 20) {
			return false;
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		} elseif (!in_array($tipo_usuario, array("0", "1"))) {
			return false;
		} else {
			return true;
		}
	}

?>