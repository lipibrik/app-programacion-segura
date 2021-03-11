<?php
	include("sesiones.php");
	
	if (isset($_POST["username"])) {
		$username = limpiar($_POST["username"]); 
		
		// Lógica de recuperación de contraseña
		if (recuperar($username)) {
            irA("index.php");
        } else {
            irA("index.php?error=1");
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
				
				<form class="col-12" action="recordar.php" method="post">
					<div class="form-group" id="user-group">
						<input type="text" class="form-control" placeholder="Nombre de usuario" name="username"/>
					</div>
					<button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i>  Recordar </button>
				</form>
				
				<div class="col-12 forgot">
					<a href="index.php">Entrar</a>
				</div>
				
                <?php if (isset($_GET["error"])) mostrarError($_GET["error"]); ?>
				
			</div>
		</div>
	</div>
</body>
</html>
