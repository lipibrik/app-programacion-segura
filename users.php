<?php
	include("sesiones.php");
	include_once("functions.php");
	
	// Esta es una página privada, tenemos que comprobar si el usuario está logueado, y por lo tanto puede acceder a su contenido.
	if (!isLoggedIn()) {
		irA("index.php?error=2");
	}
	
	// Intentamos la conexión con la base de datos, si falla, volvemos al index
	if (!$enlace = conectarDB()) {
		irA("index.php?error=yes");
	}
?>
<!DOCTYPE html>
<html lang="en" xmlns:th="http://www.thymeleaf.org">
<head>
	<title>Listado de Usuarios</title>
	
<?php
	include("head.php");
	
	// Si se ha hecho clic en borrar alguno de los usuarios
	if (isset($_GET["borrar"])) {
        if (isProfesor()) {
            $id_borrar = limpiar($_GET["borrar"]);
		
            $consulta = "DELETE FROM usuarios WHERE id = '$id_borrar'";
            $resultado = mysqli_query($enlace, $consulta);
        } else {
            irA("users.php?error=11");
        }
	}
?>
	
</head>
<body>
	<div class="container">
	<div class="mx-auto col-sm-8 main-section" id="myTab" role="tablist">
		
		<div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="list-tab">	
			<div class="card">
				<div class="card-header">
					<?php if (isset($_GET["error"])) mostrarError($_GET["error"]); ?>
					<?php if (isset($_GET["saved"])) mostrarMensaje($_GET["saved"]); ?>
					<?php 
						// Mostrar mensaje de saludo y para cerrar sesión
						showHello(); 
					?>
					<h4>Listado de Usuarios</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="userList" class="table table-bordered table-hover table-striped">
							<thead class="thead-light">
							<tr>
								<th scope="col">#</th>
								<th scope="col">Nombre</th>
								<th scope="col">Apellidos</th>
								<th scope="col">E-mail</th>
								<th scope="col">Teléfono</th>
								<th scope="col">Tipo</th>
								<th></th>
							</tr>
							</thead>
							<tbody>
						<?php
							$tipos_de_usuarios = array("Alumno", "Profesor");
							$consulta = "SELECT * FROM usuarios";
							$resultado = mysqli_query($enlace, $consulta);
							while ($row = mysqli_fetch_array($resultado)) {
						?>
								<tr>
									<th scope="row"><?php echo $row["id"]; ?></th>
									<td><?php echo $row["nombre"]; ?></td>
									<td><?php echo $row["apellidos"]; ?></td>
									<td><?php echo $row["email"]; ?></td>
									<td><?php echo $row["telefono"]; ?></td>
									<td><?php echo $tipos_de_usuarios[$row["tipo_usuario"]]; ?></td>
									<td>
                                        <?php
                                            if (isProfesor()) {
                                        ?>
                                              <a href="edit.php?id=<?php echo $row["id"]; ?>"><i class="fas fa-edit"></i></a> |
										      <a href="users.php?borrar=<?php echo $row["id"]; ?>"><i class="fas fa-user-times"></i></a>|
                                        <?php
                                            } 
                                            if (isAlumno() && $row["id"] == $_SESSION["id_usuario"]) {
                                        ?>
                                                <a href="edit.php?id=<?php echo $row["id"]; ?>"><i class="fas fa-edit"></i></a> |
                                        <?php
                                            }
                                        ?>										
										<a href="ver.php?id=<?php echo $row["id"]; ?>"><i class="fas fa-eye"></i></a>
									</td>
								</tr>
						<?php
							}
						?>
							</tbody>
						</table>
					</div>

				</div>
				 <?php
                    if (isProfesor()) {
                ?>
				<a class="btn btn-primary" href="insert.php">Insertar nuevo</a>
				<?php
					}
				?>
			</div>
		</div>
			
	</div>
</div>
</body>
</html>
