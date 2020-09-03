<?php
namespace Models;

require_once ('Conexion.php');
require_once ('../Entities/Universidad.php');

use Models;
use Models\Conexion;
use Entities\Universidad;

class UniversidadModel{
    private $conClass;
    public $con;
    public $results;

	function __construct(){
	}

	function __destruct(){
	}

	public function listarUniversidaddes(){
	    $sql = "select * from cia.universidad;";
	    try {
	        $this->conClass = Conexion::getInstance();
	        $this->con = Conexion::getConnection();
	        $this->results = $this->con->prepare($sql);
	        $this->results->execute();
	        $list = $this->results->fetchAll();
	        return $list;
	    } catch (PDOException $e) {
	        print("ERROR: ".$e->getMessage());
	        return null;
	    }
	}
	
	public function guardarRegistro(Universidad $u){
		if($u instanceof Universidad){
			$sql = "INSERT INTO cia.universidad (nombreU) VALUES (?);";
		    try {
		        $this->conClass = Conexion::getInstance();
		        $this->con = Conexion::getConnection();
		        $this->con->beginTransaction();
	        	$results = $this->con->prepare($sql);
		        $results->execute( array($u->getNombreU()));
		        $rowsA = (int) $results->rowCount();
		        $this->con->commit();
		        return $rowsA;
		    } catch (\PDOException $e) {
		    	$this->con->rollback();
        		return 0;
		    }
		}else
			return 0;
	}
	
	public function actualizarDatos(Universidad $u){
		if($u instanceof Universidad){
			$sql = "UPDATE `cia`.`universidad` SET `nombreU`=? WHERE `universidadID`=?;";
		    try {
		        $this->conClass = Conexion::getInstance();
		        $this->con = Conexion::getConnection();
		        $this->con->beginTransaction();
	        	$results = $this->con->prepare($sql);
		        $OK = $results->execute( array($u->getNombreU(), $u->getUniversidadID()));
		        $this->con->commit();
		        if($OK)
		        	return 1;
		        else
		        	return 0;
		    } catch (\PDOException $e) {
		    	$this->con->rollback();
        		return 0;
		    }
		}else
			return 0;
	}
	
	public function eliminarDatos(Universidad $u){
		if($u instanceof Universidad){
			$sql = "DELETE FROM cia.universidad where universidadID = ?;";
		    try {
		        $this->conClass = Conexion::getInstance();
		        $this->con = Conexion::getConnection();
		        $this->con->beginTransaction();
	        	$results = $this->con->prepare($sql);
		        $results->execute( array($u->getUniversidadID()));
		        $rowsA = (int) $results->rowCount();
		        $this->con->commit();
		        return $rowsA;
		    } catch (\PDOException $e) {
		    	$this->con->rollback();
        		return 0;
		    }
		}else
			return 0;
	}

}
?>