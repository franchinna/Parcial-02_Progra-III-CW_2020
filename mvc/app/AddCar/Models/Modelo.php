<?php
namespace AddCar\Models;

use AddCar\DB\DBConnection;
use PDO;

/**
 * Class Modelo
 *
 * Clase de base para todas las clases que vayan a mapearse contra una tabla de SQL.
 */
class Modelo
{
    /** @var string La tabla contra la que mapea la clase. */
    protected $table;
    /** @var string La primary key. */
    protected $pk;
    /** @var array Lista de los atributos permitidos para "asignación masiva", usado en el alta y el editar. */
    protected $atributosPermitidos = [];

    /**
     * Retorna todos los registros de la clase.
     *
     * @return array|static[]
     */
    public function todos()
    {
        $db = DBConnection::getConnection();
        $query = "SELECT * FROM " . $this->table;

        $stmt = $db->prepare($query);
        $stmt->execute();

        return  $stmt->fetchAll(PDO::FETCH_CLASS, static::class);

    }

    /**
     * Obtiene un registro en base a la pk provista.
     *
     * @param $pk
     * @return null|static
     */
    public function getByPk($pk)
    {
        $db = DBConnection::getConnection();

        $query = "SELECT * FROM " . $this->table . "
                  WHERE " . $this->pk . " = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$pk]);

        if($obj = $stmt->fetchObject(static::class)) {
            return $obj;
        }
        return null;
    }
}
