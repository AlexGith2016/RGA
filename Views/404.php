<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Página no encontrada -CIA">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Not Found - CIA</title>

        <!-- CSS -->
        <link rel="stylesheet" href="Views/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="Views/assets/font-awesome/css/font-awesome.min.css">

        <!--     Favicon and touch icons -->
        <link rel="shortcut icon" href="Views/assets/ico/favicon.jpg">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="Views/assets/ico/apple-touch-icon-144-precomposed.jpg">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="Views/assets/ico/apple-touch-icon-114-precomposed.jpg">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="Views/assets/ico/apple-touch-icon-72-precomposed.jpg">
        <link rel="apple-touch-icon-precomposed" href="Views/assets/ico/apple-touch-icon-57-precomposed.jpg">
    </head>
    <style type="text/css">
    @font-face { font-family: "Oswald-Medium"; src: url("Views/assets/fonts/Oswald-Medium.ttf") format("truetype");}
    body { background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMC8yOS8xMiKqq3kAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzVxteM2AAABHklEQVRIib2Vyw6EIAxFW5idr///Qx9sfG3pLEyJ3tAwi5EmBqRo7vHawiEEERHS6x7MTMxMVv6+z3tPMUYSkfTM/R0fEaG2bbMv+Gc4nZzn+dN4HAcREa3r+hi3bcuu68jLskhVIlW073tWaYlQ9+F9IpqmSfq+fwskhdO/AwmUTJXrOuaRQNeRkOd5lq7rXmS5InmERKoER/QMvUAPlZDHcZRhGN4CSeGY+aHMqgcks5RrHv/eeh455x5KrMq2yHQdibDO6ncG/KZWL7M8xDyS1/MIO0NJqdULLS81X6/X6aR0nqBSJcPeZnlZrzN477NKURn2Nus8sjzmEII0TfMiyxUuxphVWjpJkbx0btUnshRihVv70Bv8ItXq6Asoi/ZiCbU6YgAAAABJRU5ErkJggg==);
        font-family: 'Oswald-Medium', sans-serif;
        font-weight: 400;
        color: #027372;
    }
    .error-template {padding: 40px 15px;text-align: center;}
    .error-actions {margin-top:15px;margin-bottom:15px;}
    .error-actions .btn { margin-right:10px; }
    /***** Footer *****/
    footer { padding: 25px 0 20px 0; text-align: center; background-color: aliceblue;}
    .footer-copyright {
    	margin: 11px 0 0 0;
    	font-size: 18px;
        line-height: 32px;
        text-align: left;
    }
    .footer-social div{ width: 100%; display: block; margin-top: 8%;}
    .footer-social a { 
    	font-size: 3em;
    	color: #001b36; 
    	display: inline-grid;
    	margin: 0 auto;
    }
    .footer-social iframe{
    	width: 3em;
      	height: 1.5em;
      	padding: 0 5px;
    }
    .footer-social a:hover, .footer-social a:focus { color: #434469; opacity: 0.6; }
    </style>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="error-template">
                        <h1>Oops!</h1>
                        <img src="Views/assets/img/404-1.png" class="img-responsive" alt="404-CIA" style="margin: auto;">
                        <h2>Error 404: Página no encontrada</h2>
                        <div class="error-details" style="font-size: 1.3em">
                            ¡Lo sentimos ;(, la página que usted esta buscando no está disponible o no existe!
                        </div>
                        <div class="error-actions">
                            <a href="/" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
                                Ir a Inicio </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('foot.html'); ?>
        <script src="Views/assets/js/jquery-1.11.1.min.js"></script>
        <script src="Views/assets/bootstrap/js/bootstrap.js"></script>
    </body>
</html>