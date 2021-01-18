<?php

namespace AddCar\Validation\Exceptions;

/**
 * Class InvalidRuleException
 * Exception para identificar los errores cuando se invoquen a reglas de validación para la clase
 * ReValidator que no existan.
 *
 * Para crear nuestra propia Exception, solo hay 2 cosas que hacer:
 * 1. Requerida. Heredar de la clase Exception
 * 2. Muy Recomendada. Que termine el nombre con la palabra Exception.
 *
 * Es bastante común que las Exceptions que creemos no tengan ningún contenido.
 *
 * @package DaVinci\Validation\Exceptions
 */
class InvalidRuleException extends \Exception
{}
