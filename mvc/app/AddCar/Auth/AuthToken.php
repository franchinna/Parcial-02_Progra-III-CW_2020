<?php
namespace AddCar\Auth;

use AddCar\Models\Usuario;
use AddCar\Session\Session;
// Registramos la clase de Builder para crear el Token.
use Lcobucci\JWT\Builder;
// Registramos las clases para la encriptación.
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

/**
 * Class Auth
 *
 * Administra lo relacionado a la autenticación:
 * - Autenticar.
 * - Cerrar Sesión.
 * - Verificar si está autenticado.
 * - Obtener el usuario autenticado.
 */
class AuthToken
{
    // Esto podría, aún mejor, estar en un archivo externo de configuración (ej: ".env") que php levante.
    const JWT_ISSUER = 'https://davinci.edu.ar';
    const JWT_SECRET = '54oiuhgj0s4ero5pjgm345pñwkosjgp+s34k';

    /** @var int|null El id del usuario autenticado */
    protected $id;

    /** @var bool Si está autenticado ya o no. */
    protected $isLogged = false;

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

        // Verificamos si el usuario existe.
        if($usuario !== null) {
            // Comparamos el password.
            if(password_verify($password, $usuario->getPassword())) {
//                Session::set('id', $usuario->getId());
                $this->isLogged = true;
                $this->id = $usuario->getId();
                $token = $this->generateToken($this->id);
                setcookie('token', (string) $token, [
                    'httponly' => true,
                    'samesite' => 'Lax',
                    'expires' => time() + 60*60*24
                ]);
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
        $this->isLogged = false;
        setcookie('token', null, [
            'httponly' => true,
            'samesite' => 'Lax',
            'expires' => time() - 60*60*24
        ]);
    }

    /**
     * Retorna si el usuario está autenticado o no.
     *
     * @return bool
     */
    public function estaAutenticado()
    {
        if($this->isLogged) {
            return true;
        }

        $token = $_COOKIE['token'] ?? null;

        if(!is_string($token) || !$this->verificarToken($token)) {
            return false;
        }

        $this->isLogged = true;
        return true;
    }

    /**
     * Genera el token de JWT.
     *
     * @param int $id
     * @return \Lcobucci\JWT\Token
     */
    protected function generateToken($id)
    {
        $signer = new Sha256();
        $token = (new Builder)->issuedBy(self::JWT_ISSUER)
            ->withClaim('id', $id)
            ->getToken($signer, new Key(self::JWT_SECRET));
        return $token;
    }

    /**
     * Verifica si el $token es válido.
     * Si lo es, retorna un array con los datos del usuario (por ahora, el id).
     * De lo contrario, retorna false.
     *
     * @param string $token
     * @return bool|array
     */
    protected function verificarToken($token) {
        $token = (new Parser)->parse($token);

        $signer = new Sha256();
        $validationData = new ValidationData();
        $validationData->setIssuer(self::JWT_ISSUER);

        if($token->validate($validationData) && $token->verify($signer, self::JWT_SECRET)) {
            $this->id = $token->getClaim('id');
            return true;
        }
        return false;
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
        return (new Usuario)->getByPk($this->id);
    }
}
