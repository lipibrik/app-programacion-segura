<?php
	include("conexion.php");
	
	if (isset($_POST["username"])) {
		$username = $_POST["username"]; 
		
		// Lógica de control de acceso
		if (!login($username, $password)) {
			header("Location: recordar.php?enviado=yes");
			exit;
		}
		
	}
?>
<!DOCTYPE html>
<html lang="en" xmlns:th="http://www.thymeleaf.org">
<head>
	<title>Recordar Contraseña</title>

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
						<input type="text" class="form-control" placeholder="Nombre de usuario" name="username"/>
					</div>
					<button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i>  Recordar </button>
				</form>
				
				<div class="col-12 forgot">
					<a href="index.php">Entrar</a>
				</div>
				
				<?php if (isset($_GET["error"]) && $_GET["error"] == 1) { ?>
					<div th:if="${param.error}" class="alert alert-danger" role="alert">Se ha producido un error</div>
				<?php } ?>
				<?php if (isset($_GET["enviado"])) { ?>
					<div th:if="${param.error}" class="alert alert-success" role="alert">Si tu usuario existe, te llegará un correo con instrucciones</div>
				<?php } ?>
				
			</div>
		</div>
	</div>
</body>
</html>
