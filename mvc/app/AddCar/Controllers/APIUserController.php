<?php


namespace AddCar\Controllers;

use AddCar\Auth\AuthToken;
use AddCar\Core\View;
use AddCar\Models\Usuario;
use AddCar\Validation\Validator;

class APIUserController
{
    public function crearUsuario()
    {
        $json = file_get_contents('php://input');
        $postData = json_decode($json, true);


        $validator = new Validator($postData, [
            'email' => ['required'],
            'password' => ['required'],
            'verificarEmail' => ['required'],
            'verificarPassword' => ['required'],
        ]);


        if(!$validator->passes()) {
            View::renderJson([
                'success' => false,
                'errores' => $validator->getErrores()
            ]);
            exit;
        }

        if($postData['email'] !== $postData ['verificarEmail']){
            View::renderJson([
                'success' => false,
                'errores' => [
                    'email' => ['El email debe coincidir'],
                    'verificarEmail' => ['El email debe coincidir']
                ]
            ]);
            exit;
        }

        if($postData['password'] !== $postData ['verificarPassword']){
            View::renderJson([
                'success' => false,
                'errores' => [
                    'password' => ['Las password debe coincidir'],
                    'verificarPassword' => ['Las password debe coincidir']
                ]
            ]);
            exit;
        }


        $usuario = new Usuario;

        if($usuario->getByEmail($postData['email']) !== null){

            View::renderJson([
                'success' => false,
                'errores' => [
                    'email' => ['El email ya se encuentra registrado en el foro'],
                ]
            ]);
            exit;
        }

        try {
            $usuario->crear($postData);
            View::renderJson([
                'success' => true,
                'data' => $usuario
            ]);
        } catch(\Exception $e) {
            View::renderJson([
                'success' => false,
            ]);
        }
    }

    public function ver()
    {
        $id = urlParam('id');
        $usuario = (new Usuario)->getByPk($id);

        View::renderJson([
            'data' => $usuario
        ]);
    }

    public function editar()
    {
        $json = file_get_contents('php://input');
        $postData = json_decode($json, true);

        $validator = new Validator($postData, [
            'usuario' => ['required', 'min:3'],
        ]);

        $usuario = new Usuario;

        if($usuario->getUsuario() === $postData['usuario']){
            View::renderJson([
                'success' => false,
                'errores' => [
                    'email' => ['El usuario es el mismo, debe elegir otro'],
                ]
            ]);
            exit;
        }

        if(!$validator->passes()) {
            View::renderJson([
                'success' => false,
                'errores' => $validator->getErrores()
            ]);
            exit;
        }

        try {
            $usuario->modificarPerfil($postData['id'],$postData);
            View::renderJson([
                'success' => true,
                'data' => $usuario->getByPk($postData['id'])
            ]);
        } catch(\Exception $e) {
            View::renderJson([
                'success' => false,
            ]);
        }


    }

}