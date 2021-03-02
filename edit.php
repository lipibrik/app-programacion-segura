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
	if (isset($_POST["id"]) && isset($_POST["nombre"]) && isset($_POST["apellidos"]) && isset($_POST["email"]) && isset($_POST["telefono"])) {
		$id = limpiar($_POST["id"]); 
        $nombre = limpiar($_POST["nombre"]); 
        $apellidos = limpiar($_POST["apellidos"]);
        $email = limpiar($_POST["email"]);
        $telefono = limpiar($_POST["telefono"]);
        if (isProfesor() && isset($_POST["tipo_usuario"])) {
	        $tipo_usuario = limpiar($_POST["tipo_usuario"]);
        } else {
	        $tipo_usuario = 0;
        }
		
		if (!comprobarCampos($nombre, $apellidos, $email, $telefono, $tipo_usuario)) {
			irA("users.php?error=3");
		}
        if($_FILES["foto"]["name"] == "") {
            $ruta_foto = "";
        } else {
            $ruta_foto = comprobarFoto($_FILES);
        }
        
        $consulta = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos', email = '$email', telefono = '$telefono', tipo_usuario = '$tipo_usuario'"; 
        if ($ruta_foto !== false && $ruta_foto !== "") {
            $consulta .= ", imagen='$ruta_foto'";               
        } 
        $consulta .= " WHERE id = '$id'";
        if ($resultado = mysqli_query($enlace, $consulta)) {
            irA("users.php?saved=1");
        } else {
            irA("edit.php?error=3");
        }  
        
	}

	
	// Si no se psasa un id de usuario
	if (!isset($_GET["id"])) {
		irA("users.php?error=4");
	}

    if (!isProfesor() && $_GET["id"] != $_SESSION["id_usuario"]) {
        irA("users.php?error=11");
    }
	
	// En otro caso...
	$id = limpiar($_GET["id"]);
	$tipos_de_usuarios = array("Alumno", "Profesor");
	$consulta = "SELECT * FROM usuarios WHERE id = '$id'";
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
	<title>Editar Usuario</title>
	
<?php
	include("head.php");
?>

</head>
<body>
    <?php if (isset($_GET["error"])) mostrarError($_GET["error"]); ?>
	<div class="container">
		<div class="card">
			<div class="card-header">
				<?php showHello(); ?>
				<h4>Datos del Usuario</h4>
			</div>
			<div class="card-body">
                <center><img width="200" src="<?php echo $imagen; ?>" title="<?php echo $row["nombre"] ?>" alt="<?php echo $row["nombre"] ?>" /></center>
				<form class="form" role="form" autocomplete="off" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" value='<?php echo $row["id"] ?>' />
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Imagen</label>
						<div class="col-lg-9">
							<input type="file" class="form-control" name="foto" accept=".gif,.jpg,.png" />
						</div>
					</div>
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
					<?php
						if (isProfesor()) {
					?>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label form-control-label">Tipo de Usuario</label>
						<div class="col-lg-9">
							<select class="form-control" name="tipo_usuario">
								<option value="0" <?php if ($row["tipo_usuario"] == 0) echo "selected='selected'"; ?>>Alumno</option>
								<option value="1" <?php if ($row["tipo_usuario"] == 1) echo "selected='selected'"; ?>>Profesor</option>
							</select>
						</div>
					</div>
					<?php
						}
					?>
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
</body>
</html>