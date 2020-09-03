<?php


namespace Models;

use Models;
use PDO;
use DateTime;
use DateTimeZone;
/**
 * Clase para la conexion
 * @author Mario Peralta
 * @version 1.0
 * @created 31-jul-2017 10:34:34 a.m.
 */
class Conexion {

    private static $con;
    private static $url = 'mysql:host=localhost; dbname:cia;port=3306';
    private static $user = 'root';
    private static $password = ''; //'banca2020.';
    private static $INSTANCE;

    function __destruct() {
        
    }

    private function __construct() {
        self::inicializarDataSource();
    }

    private static function createInstance() {
        if (!isset(self::$INSTANCE)) {
            $myclass = __CLASS__;
            self::$INSTANCE = new $myclass;
        }
    }

    public static function getInstance() {
        if (!isset(self::$INSTANCE))
            self::createInstance();

        return self::$INSTANCE;
    }

    public static function inicializarDataSource() {
        try {
            $mitz = "America/Managua";
            $tz = (new DateTime('now', new DateTimeZone($mitz)))->format('P');
            self::$con = new PDO(self::$url, self::$user, self::$password);
            self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$con->exec('SET CHARACTER SET utf8');
            self::$con->exec("SET time_zone='$tz';");
        } catch (\PDOException $e) {
            // echo "La line de error es: ".$e->getline();
            // echo "\nERROR: ".$e->getMessage();
            return "ERROR";
        }
    }

    public static function verificarConexion() {
        $resp = false;
        try {
            if (self::$con == null)
                $resp = false;
            else
                $resp = true;
        } catch (Exception $e) {
            echo "ERROR m_verficarCon: " . $e->getMessage();
        }

        return $resp;
    }

    public static function getConnection() {
        if (self::verificarConexion() == false) {
            try {
                self::inicializarDataSource();
            } catch (Exception $e) {
                echo "ERROR m_getConection: " . $e->getMessage();
            }
        }
        return self::$con;
    }

    public static function setConnection($value) {
        self::$con = $value;
    }

}

?>