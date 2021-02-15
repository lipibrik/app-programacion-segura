<?php
	session_start();
	include_once("functions.php");
	
	function login($usuario, $password) {
		if (!$enlace = conectarDB()) return false;
		$consulta = "SELECT * FROM usuarios WHERE email = '$usuario' AND contrasena = '$password'";
		$resultado = mysqli_query($enlace, $consulta);
		$row = mysqli_fetch_array($resultado);
		if (mysqli_num_rows($resultado) == 1) {
			$_SESSION["usuario"] = $usuario;
			$_SESSION["nombre"] = $row["nombre"];
			return true;
		} else {
			return false;
		}
	}
	
	function isLoggedIn() {
		return isset($_SESSION["usuario"]);
	}
	
	function showHello() {
		echo "Hola " . $_SESSION["nombre"] . ", <a href='index.php?logout=yes'>Cerrar sesión</a>";
	}
	
	function logout() {
		session_destroy();
	}

?>