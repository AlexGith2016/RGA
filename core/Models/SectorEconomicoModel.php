<?php
namespace Models;

require_once ('Conexion.php');
require_once ('../Entities/SectorEconomico.php');

use Models;
use Models\Conexion;
use Entities\SectorEconomico;

class SectorEconomicoModel{
    private $conClass;
    public $con;
    public $results;

	function __construct(){
	}

	function __destruct(){
	}

	public function listarSectoresEconomicos(){
	    $sql = "select * from sectoreconomico;";
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
	
	public function guardarRegistro(SectorEconomico $se){
		if($se instanceof SectorEconomico){
			$sql = "INSERT INTO sectoreconomico (nombreSectorEc) VALUES (?);";
		    try {
		        $this->conClass = Conexion::getInstance();
		        $this->con = Conexion::getConnection();
		        $this->con->beginTransaction();
	        	$results = $this->con->prepare($sql);
		        $results->execute( array($se->getnombreSectorEc()));
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
	
	public function actualizarDatos(SectorEconomico $se){
		if($se instanceof SectorEconomico){
			$sql = "UPDATE `sectoreconomico` SET `nombreSectorEc`=? WHERE `sectorEconomicoId`=?;";
		    try {
		        $this->conClass = Conexion::getInstance();
		        $this->con = Conexion::getConnection();
		        $this->con->beginTransaction();
	        	$results = $this->con->prepare($sql);
		        $OK = $results->execute( array($se->getnombreSectorEc(), $se->getSectorEconomicoID()));
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
	
	public function eliminarDatos(SectorEconomico $se){
		if($se instanceof SectorEconomico){
			$sql = "DELETE FROM cia.sectoreconomico where sectorEconomicoId = ?;";
		    try {
		        $this->conClass = Conexion::getInstance();
		        $this->con = Conexion::getConnection();
		        $this->con->beginTransaction();
	        	$results = $this->con->prepare($sql);
		        $results->execute( array($se->getSectorEconomicoID()));
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
