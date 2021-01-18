<?php
namespace AddCar\Validation;

use Exception;

class Validator
{
    /** @var array Los campos a validar. */
    protected $campos;

    /** @var array Las reglas a aplicar. */
    protected $reglas;

    /** @var array Los errores que ocurrieron. */
    protected $errores = [];

    /**
     * Validator constructor.
     * @param array $campos
     * @param array $reglas
     */
    public function __construct($campos, $reglas)
    {
        $this->campos = $campos;
        $this->reglas = $reglas;

        // Realizamos la validación.
        $this->validar();
    }

    /**
     * Realiza la validación.
     */
    protected function validar()
    {
        /*
        $validator = new Validator($_POST, [
            'nombre'        => ['required', 'min:2'],
            'precio'        => ['required', 'numeric'],
            'id_marca'      => ['required'],
            'id_categoria'  => ['required'],
        ]);
        */
        // Queremos aplicar las reglas de validación que nos pasaron a los campos que nos pasaron.
        // Recorremos las reglas de validación que nos pasaron.
        foreach($this->reglas as $nombreCampo => $reglasCampo) {
            // Aplicar las reglas del campo al campo.
            $this->aplicarListaReglas($nombreCampo, $reglasCampo);
        }
    }

    /**
     * Aplica la $listaReglas sobre el valor del $campo.
     *
     * @param string $campo
     * @param array $listaReglas
     * @throws Exception
     */
    protected function aplicarListaReglas($campo, $listaReglas)
    {
        // $campo = 'nombre';
        // $listaReglas = ['required', 'numeric', 'min:2']
        // Quermos aplicar esa lista de reglas al campo, así que la recorremos.
        foreach($listaReglas as $regla) {
            // $regla = 'required';
            // $regla = 'min:2';

            // Aplicamos la regla de validación al campo.
            $this->aplicarRegla($campo, $regla);
        }
    }

    /**
     * Aplica la $regla de validación al $campo.
     *
     * @param string $campo
     * @param string $regla
     * @throws Exception
     */
    protected function aplicarRegla($campo, $regla)
    {
        // $campo = 'nombre';
        // $regla = 'required';
        // $regla = 'min:2';
        // Como cada regla de validación se aplica a través de un método interno que se llama
        // igual que la regla, pero prefijada con un "_" (ver más abajo), necesitamos generar
        // el nombre del método que queremos llamar.

        // Existen 2 posibles combinaciones de reglas, una como required, otra como min que lleva parámetros
        // después de un :
        // Así que vamos a verificar en qué caso estamos de los dos, para proceder acordemente.
        if(strpos($regla, ':') !== false) {
            // Separamos el nombre de la validación de sus parámetros.
//            $dataRegla = explode(':', $regla);
//            $nombreRegla = $dataRegla[0];
//            $parametroRegla = $dataRegla[1];
            // Simplificando lo de arriba...
//            list($nombreRegla, $parametroRegla) = explode(':', $regla);
            // Simplificado a php 7...
            [$nombreRegla, $parametroRegla] = explode(':', $regla);

            $nombreMetodo = '_' . $nombreRegla;

            if(!method_exists($this, $nombreMetodo)) {
                throw new Exception('No existe una validación llamada ' . $nombreRegla . '.');
            }

            // Si       $nombreMetodo = '_min'
            // y        $parametroRegla = 2
            // entonces $this->{$nombreMetodo}($campo, $parametroRegla);
            // equivale $this->_min($campo, 2)
            $this->{$nombreMetodo}($campo, $parametroRegla);
        } else {
            $nombreMetodo = '_' . $regla;

            // Necesitamos asegurarnos de que la regla de validación exista.
            // Es decir, necesitamos saber si existe en esta clase un método que tenga como nombre $nombreMetodo.
            // Eso lo podemos lograr con ayuda de la función method_exists().
            if(!method_exists($this, $nombreMetodo)) {
                throw new Exception('No existe una validación llamada ' . $regla . '.');
            }

            // Ejecutamos el método :D
            // Para lograrlo, usamos el contenido de la variable $nombreMetodo como el método a ejecutar.
            // php nos permite usar el contenido de variables para llamar métodos/funciones y para crear
            // propiedades/variables.
            // Si           $nombreMetodo = "_required"
            // entonces     $this->{$nombreMetodo}()
            // equivale     $this->_required()
            $this->{$nombreMetodo}($campo);
        }

//        switch($regla) {
//            case 'required':
//                $this->_required($campo);
//                break;
//
//            case 'numeric':
//                $this->_numeric($campo);
//                break;
//        }
    }

    /**
     * Agrega el $mensaje de error para el $campo.
     *
     * @param string $campo
     * @param string $mensaje
     */
    protected function registrarError($campo, $mensaje)
    {
        // Verificamos si existe ya una posición para el $campo, y sino la creamos.
        if(!isset($this->errores[$campo])) {
            $this->errores[$campo] = [];
        }

        // Pusheamos el mensaje.
        $this->errores[$campo][] = $mensaje;
    }

    /**
     * Retorna true si no hubo errores de validación.
     * false de lo contrario.
     *
     * @return bool
     */
    public function passes()
    {
        return empty($this->errores);
    }

    /**
     * Retorna el array de errores.
     *
     * @return array
     */
    public function getErrores()
    {
        return $this->errores;
    }

    /*--------------------------------------------------
    | Lista de reglas de validación.
    |
    | Para cada regla de validación vamos a crear un método.
    | Cada uno de esos métodos va a llamarse igual que la regla de validación, pero
    | prefijado por un "_".
    | El rol de ese guión bajo va a ser el de identificar los métodos que son reglas
    | de validación válidas.
    | Cada método de validación va a necesitar recibir el nombre del campo que tiene
    | que validar, y en algunos casos como min, algún parámetro más.
    +-------------------------------------------------*/
    /**
     * Valida que el valor del campo no sea vacío.
     *
     * @param string $campo
     */
    protected function _required($campo)
    {
        // Realizamos la validación, y si falla, guardamos un mensaje de error.
        if(empty($this->campos[$campo])) {
            $this->registrarError($campo, 'El campo de ' . $campo . ' debe completarse.');
        }
    }

    /**
     * Valida que el valor del campo sea un número.
     *
     * @param string $campo
     */
    protected function _numeric($campo)
    {
        if(!is_numeric($this->campos[$campo])) {
            $this->registrarError($campo, 'El ' . $campo . ' debe ser un número.');
        }
    }

    /**
     * Valida que el campo tenga al menos $cantidad caracteres.
     *
     * @param string $campo
     * @param int $cantidad
     */
    protected function _min($campo, $cantidad)
    {
        if(strlen($this->campos[$campo]) < $cantidad) {
            $this->registrarError($campo, 'El ' . $campo . ' debe tener al menos ' . $cantidad . ' caracteres.');
        }
    }
}
