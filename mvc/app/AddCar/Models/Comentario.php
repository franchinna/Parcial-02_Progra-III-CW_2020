<?php
namespace AddCar\Models;

use AddCar\DB\DBConnection;
use Exception;
use JsonSerializable;
use PDO;

/**
 * Class Comentario
 *
 * Esta clase se "mapea" con la tabla comentarios.
 * Su función es representar un registro de la tabla de comentarios.
 *
 * Para poder serializar los datos felizmente a JSON, vamos a implementar la interfaz JsonSerializable.
 *
 * Una interfaz (interface) es una variante de las clases, que tienen un par de características:
 *
 * 1. No se pueden instanciar.
 * 2. No se pueden heredar.
 * 3. En su cuerpo solo pueden definir métodos, pero sin cuerpo.
 * 4. Las clases pueden "implementar" (implements) una o más interfaces.
 * 5. Las clases que implementen alguna interfaz, se COMPROMETEN (es decir, están OBLIGADAS) a implementar los métodos que la interfaz definió.
 */
class Comentario extends Modelo implements JsonSerializable
{
    /** @var string La tabla contra la que mapea la clase. */
    protected $table = "comentarios";
    /** @var string La primary key. */
    protected $pk = "id_comentario";

    protected $id_comentario;
    protected $cuerpo;
    protected $fecha;
    protected $id_usuario;
    protected $id_posteo;


    /** @var array Lista de los atributos que permitidos cargar en nuestra clase. */
    protected $atributosPermitidos = ['id_comentario', 'cuerpo', 'fecha', '$id_usuario', 'id_posteo'];

    public function jsonSerialize()
    {
        return [
            'id_comentario' => $this->getIdComentario(),
            'cuerpo' => $this->getCuerpo(),
            'fecha' => $this->getFecha(),
            '$id_usuario' => $this->getIdUsuario(),
            '$id_posteo' => $this->getIdPosteo(),
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
     * Retorna un array con todos los comentarios.
     * Cada comentario va a esta representado como una instancia de Comentario.
     *
     * @return Comentario[]
     */
    public function todosPorPosteo($id_posteo)
    {
        $db = DBConnection::getConnection();

        $query = "select 
                        c.cuerpo,
                        c.fecha,
                        u.email
                    from comentarios c
                    inner join posteos_has_comentarios phc
                    on phc.id_comentario = c.id_comentario
                    inner join usuarios u
                    on u.id = c.id_usuario
                    where phc.id_posteo = ?;";

        $stmt = $db->prepare($query);
        $stmt->execute([$id_posteo]);

        $salida = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $posteo = new Posteo;
            $posteo->massAssignament($fila);
            $salida[] = $posteo;
        }
        return $salida;
    }

    /**
     * Crea un registro en la tabla con la $data proporcionada.
     *
     * @param array $data
     * @param int $id
     * @throws Exception
     */
    public function crear($id, array $data)
    {

        $db = DBConnection::getConnection();
        $query = "INSERT INTO comentarios (cuerpo, fecha, id_usuario)
              VALUES (:cuerpo, :fecha, :id_usuario)";

        $stmt = $db->prepare($query);
        $exito = $stmt->execute([
            'cuerpo' => $data['cuerpo'],
            'id_usuario' => $data['id_usuario'],
            'fecha' => date("Y-m-d H:i:s")
        ]);


        if(!$exito) {
            throw new Exception('No se pudo crear el Comentario.');
        }

        $data['id_comentario'] = $db->lastInsertId();

        $this->massAssignament($data);
        $this->cargarTablaPosteosHasComentarios($id,$data['id_comentario']);
    }

    /**
     * Crea un registro en la tabla con la $data proporcionada.
     *
     * @param int $id_comentario
     * @param int $id_posteo
     * @throws Exception
     */
    public function cargarTablaPosteosHasComentarios( $id_posteo, $id_comentario){

        $db = DBConnection::getConnection();

        $query = "INSERT INTO posteos_has_comentarios (id_posteo, id_comentario)
              VALUES (:id_posteo, :id_comentario)";


        $stmt = $db->prepare($query);
        $exito = $stmt->execute([
            'id_posteo' => (string) $id_posteo,
            'id_comentario' => (string) $id_comentario,
        ]);


        if(!$exito) {
            throw new Exception('No se pudo crear el Comentario.');
        }

        $data['id_comentario'] = $db->lastInsertId();
        $this->massAssignament($data);
    }

    /**
     * @return mixed
     */
    public function getIdComentario()
    {
        return $this->id_comentario;
    }

    /**
     * @param mixed $id_comentario
     */
    public function setIdComentario($id_comentario)
    {
        $this->id_comentario = $id_comentario;
    }

    /**
     * @return mixed
     */
    public function getCuerpo()
    {
        return $this->cuerpo;
    }

    /**
     * @param mixed $cuerpo
     */
    public function setCuerpo($cuerpo)
    {
        $this->cuerpo = $cuerpo;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param mixed $id_usuario
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    /**
     * @return mixed
     */
    public function getIdPosteo()
    {
        return $this->id_posteo;
    }

    /**
     * @param mixed $id_posteo
     */
    public function setIdPosteo($id_posteo)
    {
        $this->id_posteo = $id_posteo;
    }



}
