<?php
namespace Auth;

use Models\Usuario;
use Session\Session;

/**
 * Class Auth
 *
 * Administra lo relacionado a la autenticación:
 * - Autenticar.
 * - Cerrar Sesión.
 * - Verificar si está autenticado.
 * - Obtener el usuario autenticado.
 */
class Auth
{
    /**
     * Intenta autenticar al usuario, e informa del resultado.
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login($email, $password)
    {
        $usuario = (new Usuario)->getByEmail($email);

        if($usuario !== null) {
            if(password_verify($password, $usuario->getPassword())) {
                Session::set('id', $usuario->getId());
                return true;
            }
        }

        return false;
    }


    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        Session::remove('id');
    }

    /**
     * Retorna si el usuario está autenticado o no.
     *
     * @return bool
     */
    public function estaAutenticado()
    {
        return Session::has('id');
    }

    /**
     * Retorna el usuario autenticado.
     * Si no está autenticado, retorna null.
     *
     * @return null|Usuario
     */
    public function getUsuario()
    {
        if(!$this->estaAutenticado()) {
            return null;
        }
        return (new Usuario)->getByPk(Session::get('id'));
    }
}

