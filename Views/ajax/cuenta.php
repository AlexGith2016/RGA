<?php
	require_once("../../core/Entities/Usuario.php");
	use Entities\Usuario;
	use Entities\Rol;
	session_set_cookie_params(0);
	session_cache_limiter('nocache');
	$cache_limiter = session_cache_limiter();
	session_cache_expire(0);
	$cache_expire = session_cache_expire();
	session_start();
	if (!isset($_SESSION['usuario'])) {
		header("location:/");
	}else{
		$usuario = unserialize($_SESSION['usuario']);
		if (!$usuario instanceof Usuario) {
		header("location:/");
		}else{
			$id = (int) $usuario->m_Rol->getRolID();
			if($usuario->m_Rol->getnombreRol() != "admin" && $id != 1)
				header("location:/");
		}
	}
?>
<!--///////////////////////Directorios donde estan los jsp /////////////////////////////// -->
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="?view=Gestor">Principal</a></li>
			<li class="breadcrumb-item active">Usuario</li>
			<li class="breadcrumb-item active">Cuenta</li>
		</ol>
	</div>
</div>
<!--///////////////////////div de formulario de cuenta/////////////////////////////// -->
<!--Cuenta-->
    <div class="container">
      <div class="panel panel-default wow fadeInLeft animated panel-login mx-auto mt-5">
        <div class="panel-heading">
			<h2 class="panel-title" style="font-size: 2rem;"><i class="fa fa-sign-in" aria-hidden="true"></i> Cambiar Cuenta</h2>
		</div>
        <div class="panel-body">
          <div style="padding:0px 10px; text-align:center;">
            <form role="form" action="core/Controllers/SesionController.php" method="POST" id="formCuenta"
            	enctype="application/x-www-form-urlencoded" accept-charset="utf-8">
            	<div class="form-group">
                <label for="userTXTA">Ingresar usuario o correo antiguo</label>
                <input type="text" class="form-control" id="userTXTA" name="userTXTA" aria-describedby="userTXT" 
                  placeholder="Ingresar usuario antiguo"  required autofocus maxlength="30">
              </div>
              <div class="form-group">
                <label for="userTXT">Cambiar nombre de Usuario o Correo</label>
                <input type="text" class="form-control" id="userTXT" name="userTXT" aria-describedby="userTXT" 
                  placeholder="Ingresar usuario"  required autofocus maxlength="30">
              </div>
              <div class="form-group">
                <label for="passTXTA">Contraseña Antigua</label>
                <input type="password" class="form-control" id="passTXTA" name="passTXTA" placeholder="Ingresar contraseña antigua"
                  required maxlength="30">
              </div>
              <div class="form-group">
                <label for="passTXTN">Nueva Contraseña</label>
                <input type="password" class="form-control" id="passTXTN" name="passTXTN" placeholder="Ingresar nueva contraseña"
                  required maxlength="30">
              </div>
              <div class="form-group">
                <label for="passTXTR">Repetir contraseña</label>
                <input type="password" class="form-control" id="passTXTR" name="passTXTR" placeholder="Ingresar nuevamente contraseña"
                  required maxlength="30">
              </div>
              <button style="padding-bottom:8px; padding-top:8px;" class="btn btn-primary btn-block" type="submit">Cambiar Datos</button>
            </form>
          </div>
        </div>
      </div>
    </div>
	
<script type="text/javascript">
	
	var limpiar_texto = function() {///////////////////////////////limpiar texto del formulario
		$("#userTXT").val("");
		$("#userTXTA").val("");
		$("#passTXTA").val("");
		$("#passTXTN").val("");
		$("#passTXTR").val("");
	}
//////////////////////////////////eliminar los datos seteados en el formulario/////////////////////////////////////
	var verificar = function() {
		$("form#formCuenta").on("submit", function(ev) {
			ev.preventDefault();
			if($("form#formCuenta #passTXTN").val() == $("form#formCuenta #passTXTR").val()){
				frm = $("form#formCuenta").serialize();
				$.ajax({
					method:"PUT",
					url:"core/Controllers/SesionController.php",
					data: frm
				}).done(function(info) {
					console.log(info);
					var m = JSON.parse(info);
					if(m.mensaje =="OK"){
					location.href ="/?view=Sesion";
					}else{
					mostrarMensaje("#dialogM", m.titulo, m.mensaje, m.tipo);
					limpiar_texto();
					}
				}).fail(function () {
	                mostrarMensaje("#dialogM", "ERROR", "¡No se logró realizar la acción!", "danger");
					limpiar_texto();
	            });
			}else{
				mostrarMensaje("#dialogM", "INCORRECTO", "Datos incorrectos", "warning");
				limpiar_texto();
			}
		});
	}
/////////////////////////////////////////////////FUNSIÓN PRINCIPAL/////////////////////////////////////////////////
	$(document).ready(function() {
	
		$('.form-control').tooltip();
		//add tooltip
		$('[data-toggle="tooltip"]').tooltip();
		
		verificar();
		$('.cargador').css({visibility: "hidden", display: "none" });
		$('#contenidoPaginas').show();
	});
	
</script>
