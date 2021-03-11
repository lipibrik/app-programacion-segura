<?php
	include("sesiones.php");
	
    if (!isset($_GET["token"])) {
        irA("index.php?error=1");
    }
    
    $token = limpiar($_GET["token"]);

    if (!$enlace = conectarDB()) return false;
    $consulta = "SELECT * FROM usuarios WHERE token = '$token'";
    $resultado = mysqli_query($enlace, $consulta);
    $row = mysqli_fetch_array($resultado);
    if (mysqli_num_rows($resultado) != 1) {
        irA("index.php?error=1");
    }

	if (isset($_POST["password"])) {
		$password = generarHash(limpiar($_POST["password"]));
        $email = $row["email"];
		$consulta = "UPDATE usuarios SET contrasena = '$password' WHERE email = '$email'";
        $resultado = mysqli_query($enlace, $consulta);
        irA("index.php");
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
				
				<form class="col-12" action="" method="post">
					<div class="form-group" id="user-group">
						<input type="password" class="form-control" placeholder="Nueva contraseña" name="password"/>
					</div>
					<button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i>  Guardar</button>
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
