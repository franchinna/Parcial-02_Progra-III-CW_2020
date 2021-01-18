<?php
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');
session_start();

// Antes que nada, requerimos el autoload.
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../autoload.php';

// Guardamos la ruta absoluta de base del proyecto.
$rootPath = realpath(__DIR__ . '/../');

// Normalizamos las \ a /
$rootPath = str_replace('\\', '/', $rootPath);

// Requerimos las rutas de la aplicación.
require $rootPath . '/app/helpers.php';
require $rootPath . '/app/routes.php';

// Instanciamos nuestra App.
$app = new \AddCar\Core\App($rootPath);

// Arrancamos la App.
$app->run();
