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
	if ($row["imagen"] == "") {
		$imagen = "static/img/user.png";
	} else {
		$imagen = $row["imagen"];
	}

?>
<!DOCTYPE html>
<html lang="en" xmlns:th="http://www.thymeleaf.org">
<head>
	<title>Ver Usuario</title>
	
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
				<center><img width="200" src="<?php echo $imagen; ?>" title="<?php echo $row["nombre"] ?>" alt="<?php echo $row["nombre"] ?>" /></center>
				<form class="form" role="form" autocomplete="off" method="post">
					<input type="hidden" name="id" value='<?php echo $row["id"] ?>' />
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Nombre</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" disabled="disabled" value='<?php echo $row["nombre"] ?>'  />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Apellidos</label>
						<div class="col-lg-9">
							<input type="text" class="form-control" disabled="disabled" value='<?php echo $row["apellidos"] ?>' />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">E-mail</label>
						<div class="col-lg-9">
							<input type="email" class="form-control" disabled="disabled" value='<?php echo $row["email"] ?>' />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">teléfono</label>
						<div class="col-lg-9">
							<input type="tel" class="form-control" disabled="disabled" value='<?php echo $row["telefono"] ?>'/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Tipo de Usuario</label>
						<div class="col-lg-9">
							<input type="number" class="form-control" disabled="disabled" value='<?php echo $row["tipo_usuario"] ?>'/>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Contraseña</label>
						<div class="col-lg-9">
							<input class="form-control" type="password" disabled="disabled" value='<?php echo $row["contrasena"] ?>' >
						</div>
					</div>
					<div class="form-group row">
						<div class="col-lg-12 text-center">
							<a href='users.php'>Volver</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>