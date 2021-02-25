<?php
	include("sesiones.php");
    
    if (isLoggedIn()) {
		irA("users.php?error=8");
	}
	
	// Si se ha introduccido usuario y contraseña en el formulario de login
	if (isset($_POST["username"]) && isset($_POST["nombre"]) && isset($_POST["apellidos"]) && isset($_POST["telefono"]) && isset($_POST["password"])) {
		$username = limpiar($_POST["username"]);
        $nombre = limpiar($_POST["nombre"]);
        $apellidos = limpiar($_POST["apellidos"]);
        $telefono = limpiar($_POST["telefono"]);
		$password = limpiar($_POST["password"]);
		
		// Lógica de control de acceso
		if (!registrarse($username, $password, $nombre, $apellidos, $telefono)) {
			irA("registrar.php?error=10");
		} else {
            if (login($username, $password, false)) {
                irA("users.php");
            } else {
                irA("index.php?error=2");
            }
		}
		
	}
?>
<!DOCTYPE html>
<html lang="en" xmlns:th="http://www.thymeleaf.org">
<head>
	<title>Registro al Sistema</title>

<?php
	include("head.php");
?>

</head>
<body>
	<div class="modal-dialog text-center">
		<div class="col-sm-8 main-section">
			<div class="modal-content">
				<div class="col-12 user-img">
					<img src="static/img/user.png" th:src="@{/img/user.png}"/>
				</div>
				
				<form class="col-12" action="registrar.php" method="post">
					<div class="form-group" id="user-group">
						<input type="text" class="form-control" placeholder="Nombre" name="nombre" required="required"/>
					</div>
                    <div class="form-group" id="user-group">
						<input type="text" class="form-control" placeholder="Apellidos" name="apellidos" required="required"/>
					</div>
                    <div class="form-group" id="user-group">
						<input type="text" class="form-control" placeholder="Email" name="username" required="required"/>
					</div>
                    <div class="form-group" id="user-group">
						<input type="text" class="form-control" placeholder="Teléfono" name="telefono" required="required"/>
					</div>
					<div class="form-group" id="contrasena-group">
						<input type="password" class="form-control" placeholder="Contraseña" name="password" required="required"/>
					</div>
					<button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i>  Registrarse </button>
				</form>
				
				<div class="col-12 forgot">
					¿Ya tienes cuenta?<br/><a href="index.php">Inicia sesión</a>.
				</div>
				
				<?php if (isset($_GET["error"])) mostrarError($_GET["error"]); ?>
				
			</div>
		</div>
	</div>
</body>
</html>
