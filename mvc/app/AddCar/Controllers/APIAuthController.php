<?php
namespace AddCar\Controllers;


use AddCar\Auth\AuthToken;
use AddCar\Core\View;

class APIAuthController
{
    public function login()
    {
        $jsonData = file_get_contents('php://input');
        $postData = json_decode($jsonData, true);

        $auth = new AuthToken();

        if(!$auth->login($postData['email'], $postData['password'])) {
            View::renderJson([
                'success' => false,
                'error' => 'Las credenciales ingresadas no coinciden con nuestros registros.'
            ]);
            exit;
        }

        $usuario = $auth->getUsuario();
        View::renderJson([
            'success' => true,
            'data' => [
                'usuario' => [
                    'id' => $usuario->getId(),
                    'email' => $usuario->getEmail(),
                    'usuario' => $usuario->getUsuario(),
                    'imagen' => $usuario->getImagen(),
                ]
            ]
        ]);
    }

    public function logout()
    {
        (new AuthToken())->logout();
        View::renderJson([
            'success' => true
        ]);
    }
}