<?php
namespace Session;

/**
 * Class Session
 *
 * Abstracción para el uso de sesiones.
 *
 * Beneficios:
 * - Nuestro programa no depende de la implementación específica de php de sesiones.
 * - Por extensión, si esa implementación cambia, solo tengo que cambiar esta clase.
 * - Si deseo usar una implementación distinta, lo puedo hacer cambiando esta clase.
 * - El resto del programa no necesita saber cómo se guarda internamente las variables de sesión.
 */
class Session
{
    /**
     * Guarda el $valor con la $key en la sesión.
     *
     * @param string $key
     * @param mixed $valor
     */
    public static function set($key, $valor)
    {
        $_SESSION[$key] = $valor;
    }

    /**
     * Retorna el valor asociado a la $key.
     *
     * @param string $key
     * @return null|mixed
     */
    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Retorna true si la $key existe y tiene un valor.
     * false de lo contrario.
     *
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Elimina el valor asociado a la $key, y la $key.
     *
     * @param string $key
     */
    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Retorna el valor asociado a la $key si existe, y lo elimina.
     * De no existir, retorna el $default.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public static function flash($key, $default = null)
    {
        if(!self::has($key)) return $default;

        $valor = self::get($key);
        self::remove($key);
        return $valor;
    }
}
