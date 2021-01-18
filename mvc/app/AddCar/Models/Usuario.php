<?php
namespace AddCar\Models;

use AddCar\DB\DBConnection;
use Exception;
use JsonSerializable;
use PDO;

class Usuario extends Modelo implements JsonSerializable
{
    protected $table = "usuarios";
    protected $pk = "id";

    protected $id;
    protected $usuario;
    protected $email;
    protected $password;
    protected $id_rol;
    protected $imagen;

    /** @var array Lista de los atributos que permitidos cargar en nuestra clase. */
    protected $atributosPermitidos = ['id', 'usuario', 'email', 'password', 'id_rol', 'imagen'];

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'usuario' => $this->getUsuario(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'id_rol' => $this->getIdRol(),
            'imagen' => $this->getImagen(),
        ];
    }

    /**
     * Carga todos los datos provistos en $data en las propiedades de la instancia, siempre y cuando figuren en la propiedad $atributosPermitidos.
     *
     * @param array $data
     */
    public function massAssignament(array $data)
    {
        foreach($this->atributosPermitidos as $attr) {
            if(isset($data[$attr])) {
                $this->{$attr} = $data[$attr];
            }
        }
    }

    /**
     * Carga los datos del usuario en base al $email provisto.
     *
     * @param $email
     * @return null|Usuario
     */
    public function getByEmail($email)
    {

        $db = DBConnection::getConnection();

        $query = "SELECT * FROM usuarios
                  WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);


        if($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $usuario = new self;
            $usuario->id = $fila['id'];
            $usuario->usuario = $fila['usuario'];
            $usuario->email = $fila['email'];
            $usuario->password = $fila['password'];
            $usuario->id_rol = $fila['id_rol'];
            $usuario->imagen = $fila['imagen'];

            return $usuario;
        }
        return null;
    }

    /**
     * Modifica un registro en la tabla con la $data proporcionada.
     *
     * @param array $data
     * @throws Exception
     */
    public function modificarPerfil($id, array $data){
        $db = DBConnection::getConnection();

        /*$query = "UPDATE `usuarios`
                    SET `usuario` = :usuario, `imagen` = :imagen
                    WHERE (`id` = :id);";*/

        $query = "UPDATE `usuarios` 
                    SET `usuario` = :usuario
                    WHERE (`id` = :id);";

        $stmt = $db->prepare($query);
        $exito = $stmt->execute([
            'id'=> (int) $id,
            'usuario' => $data['usuario'],
            //'imagen' => $data['imagen'],
        ]);

        if(!$exito){
            throw new Exception('No pudimos modificar el user.');
        }
    }

    /**
     * Crea un registro en la tabla con la $data proporcionada.
     *
     * @param array $data
     * @throws Exception
     */
    public function crear($data){
        $db = DBConnection::getConnection();

        $query = "INSERT INTO `db_addcar`.`usuarios` (`email`, `password`)
                    VALUES (:email, :password);";

        $stmt = $db->prepare($query);
        $exito = $stmt->execute([
            'email' => $data['email'],
            'password' => password_hash($data['password'],PASSWORD_DEFAULT),
        ]);

        if(!$exito) {
            throw new Exception('No se pudo crear el Usuario.');
        }

        $data['id'] = $db->lastInsertId();
        $this->massAssignament($data);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * @param mixed $imagen
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }


    /**
     * @return mixed
     */
    public function getIdRol()
    {
        return $this->id_rol;
    }

    /**
     * @param mixed $id_rol
     */
    public function setIdRol($id_rol)
    {
        $this->id_rol = $id_rol;
    }
}
