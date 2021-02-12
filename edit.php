<?php
	include("sesiones.php");
	
	if (!isLoggedIn()) {
		irA("index.php?error=2");
	}
	include_once("conexion.php");
	if (!$ENLACE_DB = conectar()) {
		irA("users.php?error=yes");
	}
	
	// Si se han introducido los datos en el formulario y se ha dado a guardar
	if (isset($_GET["id"]) && isset($_GET["nombre"]) && isset($_GET["apellidos"]) && isset($_GET["email"]) && isset($_GET["telefono"]) && isset($_GET["tipo_usuario"]) && isset($_GET["password"])) {
		$id = $_GET["id"]; $nombre = $_GET["nombre"]; $apellidos = $_GET["apellidos"]; $email = $_GET["email"]; $telefono = $_GET["telefono"]; $tipo_usuario = $_GET["tipo_usuario"]; $password = $_GET["password"]; 
		$consulta = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos', email = '$email', telefono = '$telefono', tipo_usuario = '$tipo_usuario', contrasena = '$password' WHERE id = $id";
		if ($resultado = mysqli_query($ENLACE_DB, $consulta)) {
			irA("users.php?saved=yes");
		} else {
			irA("users.php?error=yes");
		}
	}
	
	// Si no se psasa un id de usuario
	if (!isset($_GET["id"])) {
		irA("users.php?error=yes");
	}
	
	// En otro caso...
	$id = $_GET["id"];
	$tipos_de_usuarios = array("Alumno", "Profesor");
	$consulta = "SELECT * FROM usuarios WHERE id = $id";
	$resultado = mysqli_query($ENLACE_DB, $consulta);
	if (!$row = mysqli_fetch_array($resultado)) {
		irA("users.php?error=yes");
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
				<form class="form" role="form" autocomplete="off" method="get">
					<input type="hidden" name="id" value='<?php echo $row["id"] ?>' />
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Nombre</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" placeholder="John" name="nombre" value='<?php echo $row["nombre"] ?>' />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Apellidos</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" placeholder="Doe" name="apellidos" value='<?php echo $row["apellidos"] ?>' />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">E-mail</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" placeholder="john@doe.com" name="email" value='<?php echo $row["email"] ?>' />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">teléfono</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" placeholder="666-666-666" name="telefono" value='<?php echo $row["telefono"] ?>'/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Tipo de Usuario</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" placeholder="0" name="tipo_usuario" value='<?php echo $row["tipo_usuario"] ?>'/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Contraseña</label>
						<div class="col-lg-9">
							<input class="form-control" type="text" name="password" placeholder="Contraseña segura" value='<?php echo $row["contrasena"] ?>' >
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