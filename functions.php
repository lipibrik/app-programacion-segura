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
		if (in_array($codigo_error, array("1", "2", "3", "4"))) {
			$codigo_error = htmlspecialchars($codigo_error);
			echo "<div class='alert alert-danger' role='alert'>Error $codigo_error: ";
			switch ($codigo_error) {
				case 1:
					echo "Usuario y/o contraseña incorrectos";
				break;
				case 2:
					echo "No estás logueado";
				break;
				case 3:
					echo "Fallo al guardar cambios";
				break;
				case 4:
					echo "Debe indicarse un usuario que exista para editar";
				break;
				default:
					echo "Se ha producido un error desconocido. Contacte con el administrador";
				break;
			}
			echo "</div>";
		}
	}
	
	function mostrarMensaje($codigo_mensaje) {
		echo "<div class='alert alert-success' role='alert'>";
		switch ($codigo_mensaje) {
			case 1:
				echo "Se ha guardado con éxito";
			break;			
			default:
				echo "Acción realizada";
			break;
		}
		echo "</div>";
	}
	
	function comprobarCampos($nombre, $apellidos, $email, $telefono, $tipo_usuario, $password) {
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