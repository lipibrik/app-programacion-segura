<?php
	include("sesiones.php");
	include_once("functions.php");
	
	// Esta es una página privada, tenemos que comprobar si el usuario está logueado, y por lo tanto puede acceder a su contenido.
	if (!isLoggedIn()) {
		irA("index.php?error=2");
	}
	
	// Si se han introducido los datos en el formulario y se ha dado a guardar
	if (isset($_POST["nombre"]) && isset($_POST["apellidos"]) && isset($_POST["email"]) && isset($_POST["telefono"]) && isset($_POST["tipo_usuario"]) && isset($_POST["password"])) {
		
		// Intentamos la conexión con la base de datos, si falla, volvemos al index
		if (!$enlace = conectarDB()) {
			irA("users.php?error=yes");
		}
		
		$nombre = $_POST["nombre"]; $apellidos = $_POST["apellidos"]; $email = $_POST["email"]; $telefono = $_POST["telefono"]; $tipo_usuario = $_POST["tipo_usuario"]; $password = $_POST["password"]; 
		if (!compbobarCampos($nombre, $apellidos, $email, $telefono, $tipo_usuario, $password)) {
			irA("users.php?error=3");
		}
		$consulta = "INSERT INTO usuarios (nombre, apellidos, email, telefono, tipo_usuario, contrasena) VALUES ('$nombre', '$apellidos', '$email', '$telefono', '$tipo_usuario','$password')";
		
		if ($resultado = mysqli_query($enlace, $consulta)) {
			irA("users.php?saved=1");
		} else {
			irA("users.php?error=3");
		}
	}

?>
<!DOCTYPE html>
<html lang="en" xmlns:th="http://www.thymeleaf.org">
<head>
	<title>Introducir Usuario</title>
	
<?php
	include("head.php");
?>

</head>
<body>
	<div class="container">
		<div class="card">
			<div class="card-header">
				<?php showHello(); ?>
				<h4>Datos del Usuario</h4>
			</div>
			<div class="card-body">
				<form class="form" role="form" autocomplete="off" method="post">
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Nombre</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" placeholder="John" name="nombre" value='' required="required" maxlength="25" />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Apellidos</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" placeholder="Doe" name="apellidos" value='' required="required" maxlength="50" />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">E-mail</label>
						<div class="col-lg-9">
							<input type="email" class="form-control" placeholder="john@doe.com" name="email" value='' required="required" required="required" maxlength="50" />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">teléfono</label>
						<div class="col-lg-9">
							<input type="tel" class="form-control" placeholder="666-666-666" name="telefono" value='' required="required" maxlength="20"/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Tipo de Usuario</label>
						<div class="col-lg-9">
							<input type="number" class="form-control" placeholder="0" name="tipo_usuario" value='' required="required" pattern="0|1" min="0" max="1" />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Contraseña</label>
						<div class="col-lg-9">
							<input class="form-control" type="password" name="password" placeholder="Contraseña segura" value='' required="required" >
						</div>
					</div>
					<div class="form-group row">
						<div class="col-lg-12 text-center">
							<a href='users.php'>Volver</a>
							<input type="submit" class="btn btn-primary" value="Guardar">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>