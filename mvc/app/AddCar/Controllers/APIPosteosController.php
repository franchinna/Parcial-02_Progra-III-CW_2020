<?php


namespace AddCar\Controllers;

use AddCar\Core\View;
use AddCar\Models\Posteo;
use AddCar\Models\Comentario;
use AddCar\Validation\Validator;

class APIPosteosController extends BaseAPIController
{
    public function listar()
    {
        $posteos = (new Posteo)->todos();

        View::renderJson([
            'data' => $posteos
        ]);
    }

    public function ver()
    {
        $id = urlParam('id');
        $posteo = (new Posteo)->traerPosteo($id);

        View::renderJson([
            'data' => $posteo
        ]);
    }


    public function crear()
    {
        $this->requireAuth();

        $json = file_get_contents('php://input');
        $postData = json_decode($json, true);

        $validator = new Validator($postData, [
            'titulo' => ['required', 'min:10'],
            'cuerpo' => ['required', 'min: 30'],
            'id_usuario' => ['required'],
            'email' => ['required'],
        ]);

        if(!$validator->passes()) {
            View::renderJson([
                'success' => false,
                'errores' => $validator->getErrores()
            ]);
            exit;
        }

        if($postData['titulo'] > 30){
            View::renderJson([
                'success' => false,
                'errores' => [
                    'titulo' => ['El título no puede ser mayor a 30 caracteres.'],
                ]
            ]);
            exit;
        }

        $posteo = new Posteo;
        try {
            $posteo->crear($postData);
            View::renderJson([
                'success' => true,
                'data' => $posteo
            ]);
        } catch(\Exception $e) {
            View::renderJson([
                'success' => false,
            ]);
        }
    }

    public function eliminar()
    {
        $id = urlParam('id');
        try {
            (new Posteo())->eliminar($id);
            View::renderJson([
                'success' => true
            ]);
        } catch(\Exception $e) {
            View::renderJson([
                'success' => false,
                'error' => 'Error en la base de datos.'
            ]);
        }
    }
}