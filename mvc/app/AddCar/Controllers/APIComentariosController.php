<?php

namespace AddCar\Controllers;

use AddCar\Core\View;
use AddCar\Models\Comentario;
use AddCar\Validation\Validator;

class APIComentariosController extends BaseAPIController
{
    public function listar()
    {
        $comentarios = (new Comentario)->todos();

        View::renderJson([
            'data' => $comentarios
        ]);
    }

    public function todosPorPosteo()
    {

        $id = urlParam('id');

        $comentarios = (new Comentario)->todosPorPosteo($id);

        View::renderJson([
            'data' => $comentarios
        ]);
    }

    public function ver()
    {
        $id = urlParam('id');
        $comentario = (new Comentario())->getByPk($id);
        View::renderJson([
            'data' => $comentario
        ]);
    }


    public function crear()
    {
        $this->requireAuth();
        $id = urlParam('id');

        $json = file_get_contents('php://input');
        $postData = json_decode($json, true);

        $validator = new Validator($postData, [
            'cuerpo' => ['required', 'min:10']
        ]);

        if(!$validator->passes()) {
            View::renderJson([
                'success' => false,
                'errores' => $validator->getErrores()
            ]);
            exit;
        }

        $comentario = new Comentario;
        try {
            $comentario->crear($id, $postData);
            View::renderJson([
                'success' => true,
                'data' => $comentario
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
            (new Comentario())->eliminar($id);
            View::renderJson([
                'success' => true
            ]);
        } catch(\Exception $e) {
            View::renderJson([
                'success' => false,
                'error' => 'Error en la base de datos.'
//                'error' => [
//                    'code' => 10,
//                    'msg' => 'Error en la base de datos.'
//                ],
//                'errorCode' => -10,
            ]);
        }
    }
}
