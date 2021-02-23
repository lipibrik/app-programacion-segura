<?php
	session_start();
	include_once("functions.php");

	function login($usuario, $password, $keep_logged_in) {
        session_regenerate_id(true);
        $usuario = limpiar($usuario);
        $password = limpiar($password);
		if (!$enlace = conectarDB()) return false;
		$consulta = "SELECT * FROM usuarios WHERE email = '$usuario' AND contrasena = '$password'";
		$resultado = mysqli_query($enlace, $consulta);
		$row = mysqli_fetch_array($resultado);
		if (mysqli_num_rows($resultado) == 1) {
			$_SESSION["usuario"] = $usuario;
			$_SESSION["nombre"] = $row["nombre"];
            //$_SESSION["timeout"] = time();
            $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['IPaddress'] = get_client_ip();
            if ($keep_logged_in) {
                $cookie = md5(uniqid(rand(), true));
                setcookie('rememberme', $cookie, time()+60*60*24*365);
                $consulta = "INSERT INTO usuario_cookie VALUES ('" . $row["id"] . "', '$cookie')";
		        $resultado = mysqli_query($enlace, $consulta);
            }
			return true;
		} else {
			return false;
		}
	}
	
	function isLoggedIn() {
        // Comprobamos que si se ha accedido, el navegador y la IP sean las mismas 
        if (!isset($_SESSION['userAgent']) || !isset($_SESSION['IPaddress'])) {
            return false;
        } else {
            // Comprobamos si el naveador es el mismo
            if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']) {
                return false;
            // Comprobamos si la IP es la misma
            } elseif ($_SESSION['IPaddress'] != get_client_ip()) {
                return false;
            }
        }
        /*// Establecer tiempo de vida de la sesión en segundos
        $inactividad = 600;
        // Comprobar si $_SESSION["timeout"] está establecida
        if(isset($_SESSION["timeout"])){
            // Calcular el tiempo de vida de la sesión (TTL = Time To Live)
            $sessionTTL = time() - $_SESSION["timeout"];
            if($sessionTTL > $inactividad){
                logout();
            }
        }
        // El siguiente key se crea cuando se inicia sesión
        $_SESSION["timeout"] = time();*/
		if (isset($_SESSION["usuario"])) {
            return true;
        } else {
            $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
            $cookie = limpiar($cookie);
            if ($cookie) {
                if (!$enlace = conectarDB()) return false;
                $consulta = "SELECT * FROM usuario_cookie WHERE cookie = '$cookie'";
                $resultado = mysqli_query($enlace, $consulta);
                $row = mysqli_fetch_array($resultado);
                if (mysqli_num_rows($resultado) == 1) {
                    $consulta = "SELECT * FROM usuarios WHERE id = '" . $row["id_usuario"] . "'";
                    $resultado = mysqli_query($enlace, $consulta);
                    $row_usuario = mysqli_fetch_array($resultado);
                    $_SESSION["usuario"] = $row["id_usuario"];
                    $_SESSION["nombre"] = $row_usuario["nombre"];
                    $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
                    $_SESSION['IPaddress'] = get_client_ip();
                    return true;
                }
            } else {
                return false;
            }
        }
	}

    function logout() {
        if (isset($_COOKIE['rememberme'])) {
            $cookie = limpiar($_COOKIE['rememberme']);
            if (!$enlace = conectarDB()) return false;
            $consulta = "DELETE FROM usuario_cookie WHERE cookie = '$cookie'";
            $resultado = mysqli_query($enlace, $consulta);
            unset($_COOKIE['rememberme']); 
            setcookie('rememberme', null, -1); 
        } 
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);
	}
	
	function showHello() {
		echo "Hola " . $_SESSION["nombre"] . ", <a href='index.php?logout=yes'>Cerrar sesión</a>";
	}

    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

?>