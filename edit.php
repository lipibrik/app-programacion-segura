<?php
	include("sesiones.php");
	include_once("functions.php");
	
	// Esta es una página privada, tenemos que comprobar si el usuario está logueado, y por lo tanto puede acceder a su contenido.
	if (!isLoggedIn()) {
		irA("index.php?error=2");
	}
	
	// Intentamos la conexión con la base de datos, si falla, volvemos al index
	if (!$enlace = conectarDB()) {
		irA("users.php?error=yes");
	}
	
	// Si se han introducido los datos en el formulario y se ha dado a guardar
	if (isset($_POST["id"]) && isset($_POST["nombre"]) && isset($_POST["apellidos"]) && isset($_POST["email"]) && isset($_POST["telefono"]) && isset($_POST["tipo_usuario"]) && isset($_POST["password"])) {
		$id = $_POST["id"]; $nombre = $_POST["nombre"]; $apellidos = $_POST["apellidos"]; $email = $_POST["email"]; $telefono = $_POST["telefono"]; $tipo_usuario = $_POST["tipo_usuario"]; $password = $_POST["password"]; 
		
		if (!comprobarCampos($nombre, $apellidos, $email, $telefono, $tipo_usuario, $password)) {
			irA("users.php?error=3");
		}
		
		$consulta = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos', email = '$email', telefono = '$telefono', tipo_usuario = '$tipo_usuario', contrasena = '$password' WHERE id = $id";
		if ($resultado = mysqli_query($enlace, $consulta)) {
			irA("users.php?saved=1");
		} else {
			irA("users.php?error=3");
		}
	}
	
	// Si no se psasa un id de usuario
	if (!isset($_GET["id"])) {
		irA("users.php?error=4");
	}
	
	// En otro caso...
	$id = $_GET["id"];
	$tipos_de_usuarios = array("Alumno", "Profesor");
	$consulta = "SELECT * FROM usuarios WHERE id = $id";
	$resultado = mysqli_query($enlace, $consulta);
	if (!$row = mysqli_fetch_array($resultado)) {
		irA("users.php?error=4");
	}

?>
<!DOCTYPE html>
<html lang="en" xmlns:th="http://www.thymeleaf.org">
<head>
	<title>Editar Usuario</title>
	
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
					<input type="hidden" name="id" value='<?php echo $row["id"] ?>' />
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Nombre</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" placeholder="John" name="nombre" required="required" maxlength="25" value='<?php echo $row["nombre"] ?>'  />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Apellidos</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" placeholder="Doe" name="apellidos" required="required" maxlength="50" value='<?php echo $row["apellidos"] ?>' />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">E-mail</label>
						<div class="col-lg-9">
							<input type="email" class="form-control" placeholder="john@doe.com" name="email" required="required" maxlength="50" value='<?php echo $row["email"] ?>' />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">teléfono</label>
						<div class="col-lg-9">
							<input type="tel" class="form-control" placeholder="666-666-666" name="telefono" required="required" maxlength="25" value='<?php echo $row["telefono"] ?>'/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Tipo de Usuario</label>
						<div class="col-lg-9">
							<input type="number" class="form-control" placeholder="0" name="tipo_usuario" required="required" min="0" max="1" value='<?php echo $row["tipo_usuario"] ?>'/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Contraseña</label>
						<div class="col-lg-9">
							<input class="form-control" type="password" name="password" placeholder="Contraseña segura" required="required" value='<?php echo $row["contrasena"] ?>' >
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