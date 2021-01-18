<?php

namespace AddCar\Core;

class View
{
    protected static $mainLayout = "layouts/main";

    /**
     * Renderiza la vista usando el layout indicado.
     *
     * @param string $__vista       El nombre del archivo de la vista, sin la extensión ".php".
     * @param array $__data         Array asociativo con los valores que quieren proveerse a la vista. Las claves son los nombres de las variables que van a proporcionarse.
     * @param null|string $layout   El nombre del archivo del layout que quiere usarse. De no especificarse, se usa el default.
     * @todo Agregar que detecte si está la extensión .php o no.
     */
    public static function render($__vista, $__data = [], $layout = null)
    {
        $layout = $layout ?? self::$mainLayout;

        ob_start();
        // Cargamos los datos para la vista.
        foreach ($__data as $key => $value) {
            ${$key} = $value;
        }

        require App::getViewsPath() . "/" . View::$mainLayout . ".php";

        $__content__ = ob_get_contents();
        ob_clean();

        require App::getViewsPath() . "/" . $__vista . ".php";

        $__view__ = ob_get_contents();

        ob_end_clean();

        $__content__ = str_replace("@{{content}}", $__view__, $__content__);

        echo $__content__;
    }

    /**
     * Retorna la $data con formato JSON.
     *
     * @param mixed $data
     */
    public static function renderJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
