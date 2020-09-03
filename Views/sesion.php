<!DOCTYPE html>
<html lang="es">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="Sesión del Centro de Innovación -CIA">
    <meta name="author" content="Centro de Innovación">
    <title>Admin - sesión CIA</title>

    <!-- Bootstrap core CSS -->
    <link href="Views/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="Views/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="Views/assets/css/style.css" rel="stylesheet">
    <link href="Views/assets/css/form-elements.css" rel="stylesheet">
    <link href="Views/assets/css/media-queries.css" rel="stylesheet">
    
    <!--     Favicon and touch icons -->
    <link rel="shortcut icon" href="Views/assets/ico/favicon.jpg">

  </head>

  <body class="bg-info">
    <?php
      require_once("core/Entities/Usuario.php");
      require_once("core/Models/UsuarioModel.php");
      require_once("core/Models/Conexion.php");
      use Entities\Usuario;
      use Entities\Rol;
      use Models\UsuarioModel;
      use Models\Conexion;
      session_set_cookie_params(0);
      session_cache_limiter('nocache');
      $cache_limiter = session_cache_limiter();
      session_cache_expire(0);
      $cache_expire = session_cache_expire();
      session_start();
      if (isset($_SESSION['usuario'])) {
          $usuario = unserialize($_SESSION['usuario']);
          if ($usuario instanceof Usuario) {
            $usuarioModel = new UsuarioModel();
            $cantidad = $usuarioModel->buscarUsuario($usuario);
            if($cantidad == 1){
              $usuarioModel->con = null;
              Conexion::setConnection(null);
              header("location:/?view=Gestor");
            }
          }
      }
  ?>
    <!--TOP-->
    <div class="top-content" style="background-color: #79ca9c; text-align: center; padding: 40px 0">
      <div class="top-content-text wow fadeInUp">
      	<div class="divider-2"><span></span></div>
      	<h1><a href="/">CENTRO DE INNOVACIÓN ABIERTA</a></h1>
      	<h1>INICIAR SESIÓN</h1>
      	<div class="divider-2"><span></span></div>
      </div>
    </div>
    
    <!--message dialog-->
		<div id="dialogM"></div>
		
    <!--Login-->
    <div class="container">
      <div class="panel panel-default wow fadeInLeft animated panel-login mx-auto mt-5">
        <div class="panel-heading">
					<h2 class="panel-title" style="font-size: 2rem;"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</h2>
				</div>
        <div class="panel-body">
          <div style="padding:0px 10px;">
            <form role="form" action="core/Controllers/SesionController.php" method="POST" enctype="application/x-www-form-urlencoded" accept-charset="utf-8">
              <div class="form-group">
                <label for="userTXT">Usuario o Correo</label>
                <input type="text" class="form-control" id="userTXT" name="userTXT" aria-describedby="userTXT" 
                  placeholder="Ingresar usuario"  required autofocus maxlength="30">
              </div>
              <div class="form-group">
                <label for="passTXT">Contraseña</label>
                <input type="password" class="form-control" id="passTXT" name="passTXT" placeholder="Ingresar contraseña"
                  required maxlength="30">
              </div>
              <div class="form-group">
                <label for="token">Clave</label>
                <input type="password" class="form-control" id="token" name="token" placeholder="Ingresar clave, No Requerido"
                  maxlength="20">
              </div>
              <button style="padding-bottom:8px; padding-top:8px;" class="btn btn-primary btn-block" type="submit">Iniciar sesión</button>
            </form>
          </div>
          <div class="text-center" style="margin-top: 10px;">
            <a class="small" href="/">¿Olvidó su contraseña?</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="Views/assets/js/jquery-1.11.1.min.js"></script>
    <script src="Views/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="Views/assets/js/popper.min.js"></script>
    <script src="Views/assets/js/functions.js"></script>
    <script type="text/javascript">
    	
    	var limpiar_texto = function() {///////////////////////////////limpiar texto del formulario
    		$("#userTXT").val("");
    		$("#passTXT").val("");
    		$("#token").val("");
    	}
    //////////////////////////////////eliminar los datos seteados en el formulario/////////////////////////////////////
    	var verificar = function() {
    		$("form").on("submit", function(ev) {
    			ev.preventDefault();
    			frm = $("form").serialize();
    			$.ajax({
    				method:"POST",
    				url:"core/Controllers/SesionController.php",
    				data: frm
    			}).done(function(info) {
    			  var m = JSON.parse(info);
    			  if(m.mensaje =="OK"){
    			    location.href ="index.php?view=Gestor";
    			  }else{
    			    mostrarMensaje("#dialogM", m.titulo, m.mensaje, m.tipo);
    			    limpiar_texto();
    			  }
    			});
    		});
    	}
    /////////////////////////////////////////////////FUNSIÓN PRINCIPAL/////////////////////////////////////////////////
    	$(document).ready(function() {
    	
    		$('.form-control').tooltip();
    		//add tooltip
    		$('[data-toggle="tooltip"]').tooltip();
    		
    		verificar();
    	});
    	
    </script>
  </body>

</html>
