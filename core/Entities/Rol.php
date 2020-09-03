<?php
namespace Entities;

/**
 * @author Mario Peralta
 * @version 1.0
 * @created 26-jul-2017 03:24:07 a.m.
 */
class Rol
{

	private $nombreRol;
	private $rolID;

	function __construct()
	{
	}

	function __destruct()
	{
	}



	public function getnombreRol()
	{
		return $this->nombreRol;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setnombreRol($newVal)
	{
		$this->nombreRol = $newVal;
	}
	
	public function getRolID()
	{
		return $this->rolID;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setRolID($newVal)
	{
		$this->rolID = $newVal;
	}

}
?>