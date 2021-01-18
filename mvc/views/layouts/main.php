<?php
use DaVinci\Session\Session;
use DaVinci\Auth\Auth;
$auth = new Auth;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Proyecto MVC</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= \DaVinci\Core\App::getUrlPath() . 'css/bootstrap.css';?>">
    <link rel="stylesheet" href="<?= \DaVinci\Core\App::getUrlPath() . 'css/estilos.css';?>">
</head>
<body>
    <div class="app">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Da Vinci</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?= \DaVinci\Core\App::getUrlPath() . '';?>">Home</a></li>
                        <li><a href="<?= \DaVinci\Core\App::getUrlPath() . 'quienes-somos';?>">Quiénes somos</a></li>
                        <li><a href="<?= \DaVinci\Core\App::getUrlPath() . 'productos';?>">Productos</a></li>
                        <?php
                        if(!$auth->estaAutenticado()): ?>
                        <li><a href="<?= \DaVinci\Core\App::getUrlPath() . 'login';?>">Iniciar Sesión</a></li>
                        <?php
                        else: ?>
                        <li><a href="<?= \DaVinci\Core\App::getUrlPath() . 'logout';?>"><?= $auth->getUsuario()->getEmail();?> (Cerrar Sesión)</a></li>
                        <?php
                        endif; ?>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <div class="container main-content">
            <?php
            // Preguntamos por los distintos tipos de mensajes de notificación.
            if(Session::has('status')):
                $status = Session::flash('status');
            ?>
                <div class="alert alert-<?= $status['tipo'];?>"><?= $status['mensaje'];?></div>
            <?php
            endif; ?>

            @{{content}}
        </div>

        <div class="footer">
            Copyright &copy; Da Vinci 2020
        </div>
    </div>
    <script src="<?= \DaVinci\Core\App::getUrlPath();?>js/jquery-3.2.1.js"></script>
    <script src="<?= \DaVinci\Core\App::getUrlPath();?>js/bootstrap.js"></script>
</body>
</html>


