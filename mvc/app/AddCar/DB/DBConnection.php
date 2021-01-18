<?php
// En la práctica, es muy común que los namespace coincidan, aunque sea en una parte, con las carpetas
// reales "físicas".
namespace AddCar\DB;

// Aclaramos los "uses" que vamos a utilizar. En este caso, la clase PDO.
use PDO;

/**
 * Class DBConnection
 *
 * Singleton "wrapper" de PDO.
 */
class DBConnection
{
    /** @var null|PDO Propiedad estática para almacenar la instancia de PDO que vamos a crear. */
    private static $db = null;

    /**
     * DBConnection constructor.
     *
     * Necesitamos definir el constructor privado para asegurarnos de que no puedan instanciar esta clase.
     */
    private function __construct() {}

    /**
     * Retorna la instancia PDO de la conexión a la base de datos.
     *
     * @return PDO
     * @throws \PDOException
     */
    public static function getConnection()
    {
        // Si no tengo todavía la conexión creada, la creamos.
        if(self::$db === null) {
            $db_host = "localhost";
            $db_user = "root";
            $db_pass = "";
            $db_base = "db_addcar";
            $db_dsn = "mysql:host={$db_host};dbname={$db_base};charset=utf8mb4";
            self::$db = new PDO($db_dsn, $db_user, $db_pass);
        }

        return self::$db;
    }
}