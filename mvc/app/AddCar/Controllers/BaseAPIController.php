<?php

namespace AddCar\Controllers;

use AddCar\Auth\Auth;
use AddCar\Auth\AuthToken;
use AddCar\Core\App;
use AddCar\Core\View;
use AddCar\Session\Session;

/**
 * Class BaseController
 * @package AddCar\Controllers
 *
 * Clase base para todos los controladores con métodos y funcionalidades útiles.
 */
class BaseAPIController
{
    public function requireAuth()
    {
        // Requerimos que el usuario esté autenticado.
        $auth = new AuthToken;
        if(!$auth->estaAutenticado()) {
            View::renderJson([
                'status' => false,
                'error' => 'Tenés que iniciar sesión para poder acceder a esta pantalla.',
            ]);
            exit;
        }
    }
}
