<?php
namespace Models;

require_once ('Conexion.php');
if(file_exists('../Entities/Solicitante.php'))
	require_once ('../Entities/Solicitante.php');

use Models;
use Models\Conexion;
use Entities\Solicitante;

class SolicitanteModel{
    public $results;

	function __construct(){
	}

	function __destruct(){
	}

	public function listarSolicitantes($sql){
	    try {
	        $this->conClass = Conexion::getInstance();
	        $this->con = Conexion::getConnection();
	        $this->results = $this->con->prepare($sql);
	        $this->results->execute();
	        $list = $this->results->fetchAll();
	        return $list;
	    } catch (\PDOException $e) {
	        print("ERROR: ".$e->getMessage());
	        return null;
	    }
	}
	
	public function guardarRegistro($solicitante){
		if($solicitante instanceof Solicitante){
			$sql = "INSERT INTO cia.solicitante (nombreCompleto, fechaRegistro, correo, telefono, identificacion) ".
			"VALUES (?, ?, ?, ?, ?);";
		    try {
		    	$now =  date("Y-m-d");
                        $solicitante->setFechaRegistro($now);
		        $this->conClass = Conexion::getInstance();
		        $this->con = Conexion::getConnection();
		        $results = $this->con->prepare($sql);
		        $results->execute( array($solicitante->getNombreCompleto(), $solicitante->getFechaRegistro(),
		        	$solicitante->getCorreo(), $solicitante->getTelefono(), $solicitante->getIdentificacion()));
		        $id = (int) $this->con->lastInsertId();
		        return $id;
		    } catch (\PDOException $e) {
		        $this->con->rollback();
		        print("ERROR: ".$e->getMessage());
        		return 0;
		    }
		}else
			return 0;
	}
}
?>