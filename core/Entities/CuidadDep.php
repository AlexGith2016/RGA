<?php
namespace Entities;

/**
 * Clase que representa la cuidad o departamento de la empresa.
 * @author Mario Peralta
 * @version 1.0
 * @created 26-jul-2017 03:24:07 a.m.
 */
class CuidadDep
{

	private $nombreCuidad;
	private $cuidadDepID;

	function __construct()
	{
	}

	function __destruct()
	{
	}



	public function getnombreCuidad()
	{
		return $this->nombreCuidad;
	}

	public function setnombreCuidad($newVal)
	{
		$this->nombreCuidad = $newVal;
	}
	
	public function getCuidadDepID()
	{
		return $this->cuidadDepID;
	}

	public function setCuidadDepID($newVal)
	{
		$this->cuidadDepID = $newVal;
	}

}
?>