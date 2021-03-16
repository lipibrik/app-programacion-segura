<?php
	include("sesiones.php");
    require_once("recaptcha.php");
	
	if (isset($_POST["username"])) {
		$username = limpiar($_POST["username"]);
        
        if (!isset($_POST["g-recaptcha-response"]) ||       empty($_POST["g-recaptcha-response"])) {
            irA("recordar.php?error=12");
        }
        
        $token = $_POST["g-recaptcha-response"];
        $verificado = verificarToken($token, CLAVE_SECRETA);
		
        if ($verificado) {
           // Lógica de recuperación de contraseña
            if (recuperar($username)) {
                irA("index.php");
            } else {
                irA("index.php?error=1");
            }   
        } else {
            irA("recordar.php?error=12");
        }
        
		
		
	}
?>
<!DOCTYPE html>
<html lang="en" xmlns:th="http://www.thymeleaf.org">
<head>
	<title>Recordar Contraseña</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
                    <div
                        class="g-recaptcha"
                        data-sitekey="6LcI2YEaAAAAAGb1b9r0bCPNWzrxffPdbokD_2jb">
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
