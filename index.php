<?php
	include("sesiones.php");
	
	// Se ha hecho clic en "Cerrar Sesión"
	if (isset($_GET["logout"])) {
		logout();
	}
    
    if (isLoggedIn()) {
		irA("users.php?error=8");
	}
	
	// Si se ha introduccido usuario y contraseña en el formulario de login
	if (isset($_POST["username"]) && isset($_POST["password"])) {
		$username = limpiar($_POST["username"]);
		$password = limpiar($_POST["password"]);
		
		// Lógica de control de acceso
		if (!login($username, $password)) {
			irA("index.php?error=1");
		} else {
			irA("users.php");
		}
		
	}
?>
<!DOCTYPE html>
<html lang="en" xmlns:th="http://www.thymeleaf.org">
<head>
	<title>Acceso al Sistema</title>

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
				
				<form class="col-12" action="index.php" method="post">
					<div class="form-group" id="user-group">
						<input type="text" class="form-control" placeholder="Email" name="username" required="required"/>
					</div>
					<div class="form-group" id="contrasena-group">
						<input type="password" class="form-control" placeholder="Contraseña" name="password" required="required"/>
					</div>
					<button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i>  Entrar </button>
				</form>
				
				<div class="col-12 forgot">
					¿Has olvidado tu contraseña?<br/><a href="recordar.php">Recordar contraseña</a>.
				</div>
				<div class="col-12 forgot">
					¿Todavía no estás registrado?<br/><a href="recordar.php">Registrarse</a>.
				</div>
				
				<?php if (isset($_GET["error"])) mostrarError($_GET["error"]); ?>
				
			</div>
		</div>
	</div>
</body>
</html>
