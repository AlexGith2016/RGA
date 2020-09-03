<?php
namespace Models;

require_once ('Conexion.php');
if(file_exists('../Entities/Usuario.php'))
	require_once ('../Entities/Usuario.php');

use Models;
use Models\Conexion;
use Entities\Usuario;

class UsuarioModel{
    private $conClass;
    public $con;
    public $results;

	function __construct(){
	}

	function __destruct(){
	}

	public function listarUsuarios(){
	    $sql = "select * from cia.usuario;";
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
	
	public function buscarUsuario(Usuario $usuario){
		if($usuario instanceof Usuario){
			$sql = "SELECT u.nombreUsuario, u.contra, u.rolID, r.nombreRol FROM cia.usuario u 
				inner join cia.rol r on u.rolID = r.rolID 
				where u.nombreUsuario = ? and u.contra = ?;";
		    try {
		        $this->conClass = Conexion::getInstance();
		        $this->con = Conexion::getConnection();
		        $this->results = $this->con->prepare($sql);
		        $this->results->execute( array($usuario->getnombreUsuario(), $usuario->getcontra()));
		        $num = (int) $this->results->rowCount();
		        return $num;
		    } catch (\PDOException $e) {
        		return -1;
		    }
		}else
			return 0;
	}
	
	public function actualizarDatos(Usuario $usuario, $newNom){
		if($usuario instanceof Usuario){
			$sql = "UPDATE `cia`.`usuario` SET `nombreUsuario`=?, `contra`=? WHERE `nombreUsuario`=?;";
		    try {
		        $this->conClass = Conexion::getInstance();
		        $this->con = Conexion::getConnection();
		        $this->con->beginTransaction();
	        	$results = $this->con->prepare($sql);
		        $OK = $results->execute( array($newNom, $usuario->getcontra(), $usuario->getnombreUsuario() ));
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

}
?>