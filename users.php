<?php
	include("sesiones.php");
	
	if (!isLoggedIn()) {
		irA("index.php?error=2");
	}
?>
<!DOCTYPE html>
<html lang="en" xmlns:th="http://www.thymeleaf.org">
<head>
	<title>Listado de Usuarios</title>
	
<?php
	include_once("conexion.php");
	include("head.php");
	
	if (isset($_GET["borrar"])) {
		if (!$ENLACE_DB = conectar()) {
			irA("index.php?error=yes");
		}
		$id_borrar = $_GET["borrar"];
		
		$consulta = "DELETE FROM usuarios WHERE id = $id_borrar";
		$resultado = mysqli_query($ENLACE_DB, $consulta);
		
	}
?>
	
</head>
<body>
	<?php if (isset($_GET["error"])) { ?>
		<div th:if="${param.error}" class="alert alert-danger" role="alert">Se ha producido un error</div>
	<?php } ?>
	<?php if (isset($_GET["saved"])) { ?>
		<div th:if="${param.error}" class="alert alert-success" role="alert">Se ha guardado con éxito</div>
	<?php } ?>
	<div class="container">
	<div class="mx-auto col-sm-8 main-section" id="myTab" role="tablist">
		
		<div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="list-tab">	
			<div class="card">
				<div class="card-header">
					<?php showHello(); ?>
					<h4>Usuarios</h4>
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
							if (!$ENLACE_DB = conectar()) {
								irA("index.php?error=yes");
							}
							$tipos_de_usuarios = array("Alumno", "Profesor");
							$consulta = "SELECT * FROM usuarios";
							$resultado = mysqli_query($ENLACE_DB, $consulta);
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
										<a href="edit.php?id=<?php echo $row["id"]; ?>"><i class="fas fa-edit"></i></a> | <a href="users.php?borrar=<?php echo $row["id"]; ?>"><i class="fas fa-user-times"></i></a>
									</td>
								</tr>
						<?php
							}
						?>
							</tbody>
						</table>
					</div>

				</div>
				<a class="btn btn-primary" href="insert.php">Insertar nuevo</a>
			</div>
		</div>
			
	</div>
</div>
</body>
</html>