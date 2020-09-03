<?php
namespace Models;

require_once ('Conexion.php');
require_once ('ParticipanteModel.php');
if(file_exists('../Entities/Empresa.php'))
	require_once ('../Entities/Empresa.php');

use Models;
use Models\Conexion;
use Entities\Empresa;

class EmpresaModel extends ParticipanteModel{
    public $results;

	function __construct(){
	}

	function __destruct(){
	}

	public function listarEmpresas($sql){
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
	
	public function guardarRegistro($empresa){
		if($empresa instanceof Empresa){
			$sql = "INSERT INTO `cia`.`empresa` (`nombreEmpresa`, `direccion`, `numeroEmpleados`, `cargo`, `anioConstitucion`, `descripcionEmpresa`, ".
				"`descripcionProblema`, `solucionTemporal`, `planCrecimiento`, `razonSeleccion`, `empresaID`, `cuidadDepID`, `sectorEconomicoID`) ".
				"VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		    try {
		        $this->conClass = Conexion::getInstance();
		        $this->con = Conexion::getConnection();
		        $this->con->beginTransaction();
		        $empresa->setEmpresaID(parent::guardarRegistro($empresa));
		        if($empresa->getEmpresaID() > 0){
		        	$results = $this->con->prepare($sql);
			        $results->execute( array($empresa->getnombreEmpresa(), $empresa->getdireccion(), $empresa->getnumeroEmpleados(), $empresa->getcargo(),
			        $empresa->getanioConstitucion(), $empresa->getdescripcionEmpresa(), $empresa->getdescripcionProblema(), $empresa->getsolucionTemporal(),
			        $empresa->getplanCrecimiento(), $empresa->getrazonSeleccion(), $empresa->getEmpresaID(), $empresa->m_CuidadDep->getCuidadDepID(),
			        $empresa->m_SectorEconomico->getSectorEconomicoID()));
			        $rowsA = (int) $results->rowCount();
			        $this->con->commit();
			        return $rowsA;
		        }else
		        	return 0;
		    } catch (\PDOException $e) {
		    	$this->con->rollback();
        		return 0;
		    }
		}else
			return 0;
	}
}
?>