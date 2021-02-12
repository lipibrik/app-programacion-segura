<?php
	include("sesiones.php");
	
	if (isset($_GET["logout"])) {
		logout();
	}
	
	if (isset($_POST["username"]) && isset($_POST["password"])) {
		$username = $_POST["username"]; 
		$password = $_POST["password"];
		
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
						<input type="text" class="form-control" placeholder="Nombre de usuario" name="username" required="required"/>
					</div>
					<div class="form-group" id="contrasena-group">
						<input type="password" class="form-control" placeholder="Contrasena" name="password" required="required"/>
					</div>
					<button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i>  Entrar </button>
				</form>
				
				<div class="col-12 forgot">
					<a href="recordar.php">¿Recordar contrasena?</a>
				</div>
				
				<?php if (isset($_GET["error"]) && $_GET["error"] == 1) { ?>
					<div th:if="${param.error}" class="alert alert-danger" role="alert">Usuario y/o contraseña incorrectos</div>
				<?php } ?>
				<?php if (isset($_GET["error"]) && $_GET["error"] == 2) { ?>
					<div th:if="${param.error}" class="alert alert-danger" role="alert">No estás logueado</div>
				<?php } ?>
				<?php if (isset($_GET["error"]) && $_GET["error"] == "yes") { ?>
					<div th:if="${param.error}" class="alert alert-danger" role="alert">Se ha producido un error desconocido, contacte con el administrador.</div>
				<?php } ?>
				
			</div>
		</div>
	</div>
</body>
</html>
