<!DOCTYPE html>
<html lang="es">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Página de gestión del Centro de Innovación">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CIA- Gestor</title>

        <!-- CSS -->
        <link rel="stylesheet" href="Views/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="Views/assets/bootstrap/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="Views/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="Views/assets/dataTables/dataTables.bootstrap.css">
        <link rel="stylesheet" href="Views/assets/css/animate.css">
		<link rel="stylesheet" href="Views/assets/css/form-elements.css">
        <link rel="stylesheet" href="Views/assets/css/style.css">
        <link rel="stylesheet" href="Views/assets/css/media-queries.css">
        <link rel="stylesheet" href="Views/assets/css/jquery.fileuploader.css">
        <link rel="stylesheet" href="Views/assets/css/nav.css">

    <!--     Favicon and touch icons -->
        <link rel="shortcut icon" href="Views/assets/ico/favicon.jpg">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="Views/assets/ico/apple-touch-icon-144-precomposed.jpg">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="Views/assets/ico/apple-touch-icon-114-precomposed.jpg">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="Views/assets/ico/apple-touch-icon-72-precomposed.jpg">
        <link rel="apple-touch-icon-precomposed" href="Views/assets/ico/apple-touch-icon-57-precomposed.jpg">
        <!--[if IE]>
		    <style type='text/css'>
		        #cssmenu .has-sub ul {position: relative; }
		    </style>
		<![endif]-->
    </head>

    <body style="text-align:initial;">
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
        if (!isset($_SESSION['usuario'])) {
            header("Location:index.php");
        } else {
            $usuario = unserialize($_SESSION['usuario']);
            if (!$usuario instanceof Usuario) {
                header("Location:index.php");
            } else {
                $usuarioModel = new UsuarioModel();
                $cantidad = $usuarioModel->buscarUsuario($usuario);
                $usuarioModel->con = null;
                Conexion::setConnection(null);
                if ($cantidad != 1) {
                    header("Location:index.php");
                } else {
                    if ($usuario->m_Rol->getnombreRol() != "admin" && $id != 1) {
                        header("Location:index.php");
                    }
                }
            }
        }
        ?>
    	<!--message dialog-->
		<div id="dialogM" style="position:absolute; z-index:5000;"></div>
		
        <!-- Loader -->
    	<div class="loader">
    		<div class="loader-img"></div>
    	</div>
    	
    	<!--top-->
    	<?php include 'nav_gestor.html'; ?>
		<!-- Top content -->
        <div class="top-content" style="background-color: #79ca9c; text-align: center; padding: 80px 0">
            <div class="top-content-text wow fadeInUp">
            	<div class="divider-2"><span></span></div>
            	<h1><a href="/">CENTRO DE INNOVACIÓN ABIERTA</a></h1>
            	<h1>ADMINISTRACIÓN</h1>
            	<div class="divider-2"><span></span></div>
            </div>
        </div>
        
        <!--content -->
        <div class="content-wrapper">
            <!-- Loader -->
            <div class="cargador"><img id="loading" src="Views/assets/img/loading.gif" alt="loading" /></div>

            <!-- AJAX LOADER -->
            <div class="container-fluid" id="contenidoPaginas">
	
	        <!-- Breadcrumbs -->
	        <ol class="breadcrumb" style="text-align: initial;">
	          <li class="breadcrumb-item">
	            <a href="?view=Gestor">Principal</a>
	          </li>
	          <li class="breadcrumb-item active">Inicio</li>
	        </ol>
	
	        <!-- Area Chart Example -->
			<div class="panel panel-primary wow fadeInLeft animated">
				<div class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-area-chart"></i> Gráficos de Estudiantes</h2>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-8 my-auto">
							<canvas id="myBarChart" width="100" height="50"></canvas>
						</div>
						<div class="col-sm-4 text-center" style="line-height: 40px; margin-top: 3em;">
							<hr>
							<div class="h4 mb-0 text-primary">Estudiantes Registrados</div>
							<div class="h4 text-primary" style="display: grid;">
								<input type="text" width=100%; id="anioE" value="<?php echo date('Y');?>"
								style="margin-bottom:inherit; text-align:center; font-size:1.3em; font-family:'Oswald-Medium'; font-weight:200;"/>
								<select id="periodoE" style="text-align: center;">
									<option value="1">primera mitad</option>
									<option value="2">segunda mitad</option>
								</select>
							</div>
							<button onclick="graficar(2);" id="verPeriodo" class="btn btn-default" style="color: steelblue;">Ver Registros</button>
							<hr>
							
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="small text-muted">Estudiantes registrados por periodos de fechas</div>
	          </div>
			</div>
			
			<!-- Services -->
	        <div class="what-we-do-container section-container" style="text-align: center;">
		        <div class="container">
		            <div class="row">
		                <div class="col-sm-12 what-we-do section-description wow fadeIn">
		                    <h2>Cantidad de participantes por categoría</h2>
		                    <div class="divider-1 wow fadeInUp"><span></span></div>
		                </div>
		            </div>
		        </div>
	        </div>
	        <?php
                    require_once("core/Models/SolicitanteModel.php");
                    use Models\SolicitanteModel;
                    $solicitanteModel = new SolicitanteModel();
                    $sql = "select COUNT(*) from solicitante s where s.activo =1;";
                    $l = $solicitanteModel->listarSolicitantes($sql);
	            $cd = $l[0][0];
                    $solicitanteModel->con = null;
                    Conexion::setConnection(null);
	        ?>
	        <!-- Counters -->
	        <div class="counters-container section-container section-container-full-bg" 
	        	style="text-align:center; color:black; background-color:teal; padding: 30px 0px;">
	        	<div class="container">
	        		<div class="row" style="display: inherit;">
		            	<div class="col-sm-4 counter-box wow fadeInUp">
		            		<h4>10</h4>
	                    	<p>Estudiantes participantes</p>
		            	</div>
		            	<div class="col-sm-4 counter-box wow fadeInDown">
	                    	<h4>20</h4>
	                    	<p>Empresas participantes</p>
		            	</div>
		            	<div class="col-sm-4 counter-box wow fadeInUp">
	                    	<h4><?php echo $cd; ?></h4>
	                    	<p>Donantes participantes</p>
		            	</div>
		            </div>
	        	</div>
	        </div>
	
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
	    
        <?php include 'foot.html'; ?>

        <!-- Javascript -->
        <script src="Views/assets/js/jquery-1.11.1.min.js"></script>
        <script src="Views/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="Views/assets/js/wow.min.js"></script>
        <script src="Views/assets/js/Chart.min.js"></script>
        <script src="Views/assets/js/jquery.fileuploader.min.js"></script>
        <script src="Views/assets/js/functions.js"></script>
        <script src="Views/assets/js/scriptsGestor.js"></script>

        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->
    </body>
</html>