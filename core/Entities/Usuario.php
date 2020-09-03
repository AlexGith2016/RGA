<?php
namespace Entities;

require_once ('Rol.php');

use Entities;
/**
 * Clase que representa el usuario dentro del sistema
 * @author Mario Peralta
 * @version 1.0
 * @created 26-jul-2017 03:24:08 a.m.
 */
class Usuario
{

	private $nombreUsuario;
	private $contra;
	public $m_Rol;
	private $usuarioID;

	function __construct()
	{
	}

	function __destruct()
	{
	}



	public function getnombreUsuario()
	{
		return $this->nombreUsuario;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setnombreUsuario($newVal)
	{
		$this->nombreUsuario = $newVal;
	}

	public function getcontra()
	{
		return $this->contra;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setcontra($newVal)
	{
		$this->contra = $newVal;
	}
	
	public function getUsuarioID()
	{
		return $this->usuarioID;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setUsuarioID($newVal)
	{
		$this->usuarioID = $newVal;
	}
	
	public function validarDatos(){
        if(isset($this->nombreUsuario) && !empty($this->nombreUsuario) && !is_numeric($this->nombreUsuario) && strlen($this->nombreUsuario) <= 30 &&
            isset($this->contra) && !empty($this->contra) && strlen($this->contra) <= 30){
            
            $this->nombreUsuario = addslashes($this->nombreUsuario);
            $this->contra = addslashes($this->contra);
            return true;
        }else
            return false;
    }
}
?>