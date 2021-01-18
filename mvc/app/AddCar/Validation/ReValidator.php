<?php

namespace AddCar\Validation;

/*
 * Implementación realizada en clase de nuestra clase de Validación :)
 *
 * Uso:
 *
 * $data = [
       'nombre' => 'xxx',
       'precio' => 'xx',
       'id_marca' => 'xx',
       'id_categoria' => 'xx',
       'descripcion' => 'xx',
       'imagen' => 'xx',
   ];

 * $validator = new Validator($data, [
    'nombre'        => ['required', 'min:2'],
    'precio'        => ['required', 'numeric'],
    'id_marca'      => ['required'],
    'id_categoria'  => ['required'],
]);

if(!$validator->passes()) {
    print_r($validator->getErrores());
}
 * */

use AddCar\Validation\Exceptions\InvalidRuleException;

class ReValidator
{
    /** @var array Los valores a validar. */
    protected $campos = [];

    /** @var array Las reglas de validación a aplicar a los datos. */
    protected $reglas = [];

    /** @var array Los errores de validación. */
    protected $errores = [];

    /**
     * ReValidator constructor.
     * @param array $campos
     * @param array $reglas
     * @throws InvalidRuleException
     */
    public function __construct($campos, $reglas)
    {
        $this->campos = $campos;
        $this->reglas = $reglas;
        $this->validar();
    }

    /**
     * Valida los $campos con las $reglas provistas.
     * @throws InvalidRuleException
     */
    public function validar()
    {
        // Recorremos las reglas de validación para aplicarlas a cada campo.
        // Recordemos el formato de las reglas:
        /*[
            'nombre'        => ['required', 'min:2'],
            'precio'        => ['required', 'numeric'],
            'id_marca'      => ['required'],
            'id_categoria'  => ['required'],
        ]*/
        foreach($this->reglas as $nombreCampo => $reglasCampo) {
            // Para cada regla, tenemos que aplicarlas al campo.
            $this->aplicarReglas($nombreCampo, $reglasCampo);
        }
    }

    /**
     * Aplica todas las reglas de validar al campo.
     *
     * @param string $nombreCampo
     * @param array $reglasCampo
     * @throws InvalidRuleException
     */
    public function aplicarReglas($nombreCampo, $reglasCampo)
    {
        // Ejemplo:
        // $nombreCampo = 'nombre';
        // $reglasCampo = ['required', 'min:2'];
        foreach($reglasCampo as $unaRegla) {
            // $unaRegla = 'required';
            // $unaRegla = 'min:2';
            $this->ejecutarRegla($nombreCampo, $unaRegla);
        }
    }

    /**
     * Ejecuta la validación de la $regla en al valor del campo con el $nombreCampo provisto.
     *
     * @param $nombreCampo
     * @param $regla
     * @throws InvalidRuleException
     */
    public function ejecutarRegla($nombreCampo, $regla)
    {
        // Ejemplo:
        // $nombreCampo = 'nombre';
        // Escenario 1: El nombre de la regla no tiene valores adicionales.
        // $regla = 'required';
        // Escenario 2: El nombre de la regla tiene valores adicionales después del ":".
        // $regla = 'min:2';
        // Para manejar cada regla de validación, vamos a crear un método diferente en la clase.
        // Ver sección "Reglas de validación" abajo.
        // Con los métodos definidos abajo, lo único que queda es llamar al método que corresponda en
        // cada caso.
        // Como cada método se llama igual que la regla de validación, podemos crear el nombre del método
        // a ejecutar a partir del nombre de la regla.
        // Preguntamos en qué escenario estamos.
        if(strpos($regla, ':') === false) {
            // Escenario 1 ejemplo
            // $metodo = "_required";
            $metodo = "_" . $regla;

            // Ahora es cuestión de simplemente ejecutar el método que tiene el nombre del contenido de esa
            // variable.
            // $this->_required($nombreCampo);
            if(!method_exists($this, $metodo)) {
                throw new InvalidRuleException("No existe la regla de validación '{$regla}'.");
            }

            $this->{$metodo}($nombreCampo);
        } else {
            // Escenario 2 ejemplo
            // $metodo = "_min"
            // $parametro = "2";
            // Obtenemos ambas partes de la regla.
            $partes = explode(":", $regla);
            $metodo = "_" . $partes[0];
            if(!method_exists($this, $metodo)) {
                throw new InvalidRuleException("No existe la regla de validación '{$regla}'.");
            }
            $this->{$metodo}($nombreCampo, $partes[1]);
        }
    }

    /**
     * Retorna true si la validación tuvo éxito, es decir, si no ocurrieron errores.
     * Retorna false en caso contrario.
     *
     * @return bool
     */
    public function passes()
    {
        return count($this->errores) === 0;
    }

    /**
     * Retorna los errores de validación ocurridos.
     *
     * @return array
     */
    public function getErrores()
    {
        return $this->errores;
    }

    /**
     * Agrega el $mensaje de error para el $campo.
     *
     * @param string $campo
     * @param string $mensaje
     */
    public function addError($campo, $mensaje)
    {
        if(!isset($this->errores[$campo])) {
            $this->errores[$campo] = [];
        }
        $this->errores[$campo][] = $mensaje;
    }

    /*
     |--------------------------------------------------------------------------
     | Reglas de validación
     |--------------------------------------------------------------------------
     | Con el fin de poder agregar fácilmente reglas en un futuro, y tener listadas las posibles
     | reglas de validación actuales, vamos a definir cada regla como un método protegido (para que
     | sea solo de uso interno).
     | Esos métodos tienen como nombre, el mismo nombre que recibimos del string, sin sus parámetros
     | extras. Pero con el agregado de que les vamos a poner de prefijo "_" al método.
     | Por ejemplo, si tenemos la regla 'required', tendremos un método:
     | _required()
     | Si tenemos la regla 'min:2', el método será:
     | _min()
     | Como cada validación necesita sí o sí saber el campo que tiene que validar, lo va a pedir entre sus
     | parámetros. Ejemplo:
     | _required($campo)
     | Y además, si es una regla que recibe datos extras, también se los pasaremos como argumentos.
     | _min($campo, $cantidad)
     */
    /**
     * Verifica que el $campo no esté vacío.
     *
     * @param string $campo
     */
    public function _required($campo)
    {
        // Obtenemos el valor a validar del array de la propiedad $campos.
        $valor = $this->campos[$campo];
        if(empty($valor)) {
//            if(!isset($this->errores[$campo])) {
//                $this->errores[$campo] = [];
//            }
//            // Agregamos un mensaje de error a nuestro array de errores.
//            $this->errores[$campo][] = "El {$campo} no puede estar vacío.";
            $this->addError($campo, "El {$campo} no puede estar vacío.");
        }
    }

    /**
     * Verifica que el $campo sea un valor numérico.
     *
     * @param string $campo
     */
    public function _numeric($campo)
    {
        // Obtenemos el valor a validar del array de la propiedad $campos.
        $valor = $this->campos[$campo];
        if(!is_numeric($valor)) {
//            if(!isset($this->errores[$campo])) {
//                $this->errores[$campo] = [];
//            }
//            // Agregamos un mensaje de error a nuestro array de errores.
//            $this->errores[$campo][] = "El {$campo} debe ser un valor numérico.";
            $this->addError($campo, "El {$campo} debe ser un valor numérico.");
        }
    }

    /**
     * Verifica que el $campo tenga al menos $cantidad caracteres.
     *
     * @param string $campo
     * @param int $cantidad
     */
    public function _min($campo, $cantidad)
    {
        // Obtenemos el valor a validar del array de la propiedad $campos.
        $valor = $this->campos[$campo];
        if(strlen($valor) < $cantidad) {
//            if(!isset($this->errores[$campo])) {
//                $this->errores[$campo] = [];
//            }
//            // Agregamos un mensaje de error a nuestro array de errores.
//            $this->errores[$campo][] = "El {$campo} debe tener al menos {$cantidad} caracteres.";
            $this->addError($campo, "El {$campo} debe tener al menos {$cantidad} caracteres.");
        }
    }
}
