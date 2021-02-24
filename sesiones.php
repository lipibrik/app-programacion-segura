<?php
	session_start();
	include_once("functions.php");

	/* Función para hacer login en el sistema, se le pasa un usuario y una contraseña, e indicamos si queremos que la sesión se mantenga abierta en el tiempo */
	// Devuelve verdadero si el login es correcto (existe un usuario en la base de datos con ese usuario y contraseña, y falso en caso contrario
	function login($usuario, $password, $keep_logged_in) {
		// Forzamos una nueva generación de ID de sesión, para evitar posibles robos de sesión (hijacking o fixation).
		newSession();
		
		// Limpiamos las variables para evitar inyección de código
		$usuario = limpiar($usuario);
		$password = limpiar($password);
		
		// Buscamos si existe en la base de datos un usuario con ese usuario y contraseña
		if (!$enlace = conectarDB()) return false;
		$consulta = "SELECT * FROM usuarios WHERE email = '$usuario' AND contrasena = '$password'";
		$resultado = mysqli_query($enlace, $consulta);
		$row = mysqli_fetch_array($resultado);
		
		// Sólo será válido si se ha encontrado uno, y sólo uno, en la tabla de usuarios con ese usuario y contraseña
		if (mysqli_num_rows($resultado) == 1) {
			
			// Creamos las variables de sesión, que darán acceso a las partes protegidas del sistema
			createValidSession($row["id"], $row["nombre"]);
			
			// Si se ha indicado que se mantenga la sesión abierta, creamos la cookie correspondiente
			if ($keep_logged_in) {
				createCookie($row["id"]);
			}
			
			// Usuario y contraseña correctos, y después de crear la sesión, y la cookie (si corresponde), devolvemos TRUE.
			return true;
		} else {
			// No existe un usuario con esos datos.
			return false;
		}
	}
	
	/* Función para comprobar si un usuario está logueado, y tiene permiso para acceder */
	/* Un usuario estará correctamente logueado si tiene una sesión activa o tiene una cookie válida */
	function isLoggedIn() {
		
		// La sesión ha tenido actividad, actualizamos temporizador.
		updateTimeOut();
		
		return isThereValidSession() || isThereValidCookie();
	}
	
	// Para que una sesión sea válida, deberá haberse llamado a la función CreateValidSession y por lo tanto las variables de sesión creadas ahí, deben tener un valor válido
	function isThereValidSession() {		
		// Primera comprobación: ¿Existen las variables de sesión?
		if (isset($_SESSION["ID"]) && isset($_SESSION["nombre"]) && isset($_SESSION["timeout"])) {
			
			// En el ID de sesión del a aplicación hemos almacenado el id del usuario, su navegador y su IP a la hora de crear la sesión
			$id_session = explode(":", $_SESSION["ID"]);
			
			// Segunda comprobación: Comprobamos si el naveador y la IP son distintos que cuando se creó la sesión (Podría significar robo de sesión)
			if ($id_session[1] != md5($_SERVER['HTTP_USER_AGENT'] . get_client_ip())) {
					return false;
			} else {
				
				// Tercera comprobación: calcular el tiempo de vida de la sesión (TTL = Time To Live)
				$sessionTTL = time() - $_SESSION["timeout"];
				if($sessionTTL > SESSION_TIME_ALIVE){
					// Cerramos la sesión
					logout();	
					return false;
				} else {
					// Tras las tres comprobaciones, garantizamos que el usuario está correctamente logueado
					return true;
				}
				
			}
		} else {
			return false;
		}
	}

	// Para que una cookie sea válida, deberá existir la cookie y tener un valor válido almacenado en nuestro sistema
	function isThereValidCookie() {
		// Primera comprobación: ¿Existe la cookie rememberme?
		if (isset($_COOKIE['rememberme'])) {
			
			// Limpiamos la cookie de posible inyección de código
			$cookie = limpiar($_COOKIE['rememberme']);
			
			// Separamos los valores de la cookie
			list($token, $tokenForUserInformation, $time) = explode(":", $cookie);
			
			// Segunda comprobación: la información del usuario (navegador e IP) coinciden con la del usuario cuando se creó la cookie, y que está dentro del tiempo de validez de las cookies
			if (generateTokenForUserInformation() == $tokenForUserInformation && time() < ($time + COOKIE_TIME_ALIVE)) {
				
				// Tercera comprobación: Comprobamos si tenemos en nuestra base de datos un usuario con esa cookie
				if (!$enlace = conectarDB()) return false;
				$consulta = "SELECT * FROM usuario_cookie WHERE cookie = '$cookie'";
				$resultado = mysqli_query($enlace, $consulta);
				$row = mysqli_fetch_array($resultado);
				
				// Sólo será válido si se ha encontrado uno, y sólo uno, en la tabla de cookies
				if (mysqli_num_rows($resultado) == 1) {
					
					// Consultamos los datos de ese usuario para crear la sesión
					$consulta = "SELECT * FROM usuarios WHERE id = '" . $row["id_usuario"] . "'";
					$resultado = mysqli_query($enlace, $consulta);
					$row_usuario = mysqli_fetch_array($resultado);
					
					// Creamos las variables de sesión, que darán acceso a las partes protegidas del sistema
					createValidSession($row["id_usuario"], $row_usuario["nombre"]);
					
					return true;
				} else {
					return false;
				}
				
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/* Función para cerrar la sesión de un usuario que esté logueado */
	function logout() {
		// Si se ha establecido una cookie de recordar sesión
		if (isset($_COOKIE['rememberme'])) {
			 deleteCookie();
		} 
		
		// Borramos todas las variables de sesión
		session_unset();
		
		// Destruimos sesión
		session_destroy();
		session_start();
		
		// Forzamos la creación de una nueva id de sesión, para evitar que se vuelva a usar ese id, y evitar riesgos de robo de sesión (SESSION FIXATION Y SESSION HIJACKING).
		newSession();
	}
	
	/* Función para crear la sesión de un usuario, y que a partir de ahora tenga acceso a los sitios que corresponda */
	function createValidSession($id_usuario, $nombre_usuario) {
		// Creamos el id de la sesión para nuestra aplicación, con el id del usuario logueado, el navegador del usuario y su IP que quedan registrados al crear la sesión
		$_SESSION["ID"] = $id_usuario . ":" . generateTokenForUserInformation();
		updateTimeOut();
		// Guardamos también el nombre del usuario, para mostrarlo en el mensaje de bienvenida
		$_SESSION["nombre"] = $nombre_usuario;
	}
		
	// Función para actualizar el temporizador que nos dirá cuándo fue la última vez que la sesión tuvo actividad
	function updateTimeOut() {
		$_SESSION["timeout"] = time();
	}
	
	/* Función para crear la cookie para que la sesión se mantenga abierta */
	function createCookie($id_usuario) {
		// Creamos un token aleatorio
		$cookie = generateToken() . ":" . generateTokenForUserInformation() . ":" . time();
		
		// Almacenamos la cookie con el token generado en el navegador dle usuario
		setcookie('rememberme', $cookie, time() + COOKIE_TIME_ALIVE);
		
		// Insertamos el token en la base de datos con el token generado
		if (!$enlace = conectarDB()) return false;
		$consulta = "INSERT INTO usuario_cookie VALUES ('$id_usuario', '$cookie')";
		$resultado = mysqli_query($enlace, $consulta);
	}
	
	// Devuelve un token aleatorio para cualquier propósito
	function generateToken() {
		return md5(uniqid(rand(), true));
	}
	
	function generateTokenForUserInformation() {
		return md5($_SERVER['HTTP_USER_AGENT'] . get_client_ip());
	}
	
	/* Borrar la cookie que mantiene la sesión abierta */
	function deleteCookie() {
		// Limpiamos la cookie ante una posible inyección de código
		$cookie = limpiar($_COOKIE['rememberme']);
		
		// Borramos la cookie de la base de datos
		if (!$enlace = conectarDB()) return false;
		$consulta = "DELETE FROM usuario_cookie WHERE cookie = '$cookie'";
		$resultado = mysqli_query($enlace, $consulta);
		
		// Borramos la variable de la cookie de la sesión
		unset($_COOKIE['rememberme']); 
		
		// Borramos la cookie del fichero físico del navegador del cliente
		setcookie('rememberme', null, -1);
	}
	
	// Generar una nueva sesión con id de sesión nuevo para evitar ataques de robo de sesión
	function newSession() {
		session_regenerate_id(true);
	}
	
	/* Muestra el mensaje de bienvenida a los visitantes, y el enlace para cerrar sesión */
	function showHello() {
		if (isset($_SESSION["nombre"]) && isset($_SESSION["ID"])) {
			$id_session = explode(":", $_SESSION["ID"]);
			echo "Hola " . $_SESSION["nombre"] . ", <a href='index.php?logout=yes'>Cerrar sesión</a>";
		}
	}

?>