<?php
use AddCar\Core\Route;

/**
 * Genera una URL absoluta al path indicado a partir del public.
 *
 * @param string $path
 * @return string
 */
function url($path = '') {
    return \AddCar\Core\App::getUrlPath() . $path;
}

/**
 * Retorna los parámetros correspondientes a los segmentos dinámicos de la URL.
 * Si se provee de una $key, se retorna el valor asociado a esa $key.
 * Si se pasa una $key y el valor no existe, se retorna null.
 *
 * @param string|null $key
 * @return array|mixed|null
 */
function urlParam($key = null) {
    if($key === null) {
        return Route::getUrlParameters();
    }
    if(isset(Route::getUrlParameters()[$key])) {
        return Route::getUrlParameters()[$key];
    }
    return null;
}
