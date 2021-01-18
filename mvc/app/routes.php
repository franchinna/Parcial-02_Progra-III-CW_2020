<?php
/*
 * Este archivo va a contener TODAS las rutas de
 * nuestra aplicación.
 *
 * Para esto, vamos a crear una clase Route cuya
 * función sea la de registrar y administrar las rutas.
 */

use AddCar\Core\Route;

//Auth
Route::add('POST', '/api/login', 'APIAuthController@login');
Route::add('POST', '/api/logout', 'APIAuthController@logout');

//Usuarios
Route::add('POST', '/api/registrarUsuario', 'APIUserController@crearUsuario');
Route::add('GET', '/api/usuarios/{id}', 'APIUserController@ver');
Route::add('PUT', '/api/usuarios/{id}', 'APIUserController@editar');
Route::add('OPTIONS', '/api/usuarios/{id}', 'APIUserController@editar');

//Posteos
Route::add('GET', '/api/posteos', 'APIPosteosController@listar');
Route::add('POST', '/api/posteos', 'APIPosteosController@crear');
Route::add('GET', '/api/posteos/{id}', 'APIPosteosController@ver');

//Comentarios
Route::add('GET', '/api/comentarios/{id}', 'APIComentariosController@todosPorPosteo');
Route::add('POST', '/api/comentarios/{id}', 'APIComentariosController@crear');

