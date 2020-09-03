<?php
namespace Entities;

require_once ('Participante.php');
require_once ('SectorEconomico.php');
require_once ('CuidadDep.php');

use Entities;
/**
 * Clase hija donde se registran las empresas participantes con proyectos
 * (problemas) que quieren llevar a cabo en el centro.
 * @author Mario Peralta
 * @version 1.0
 * @created 26-jul-2017 03:24:07 a.m.
 */
class Empresa extends Participante
{

	private $nombreEmpresa;
	private $direccion;
	private $numeroEmpleados;
	private $cargo;
	private $anioConstitucion;
	private $descripcionEmpresa;
	private $descripcionProblema;
	private $solucionTemporal;
	private $planCrecimiento;
	private $razonSeleccion;
	public $m_SectorEconomico;
	public $m_CuidadDep;
	private $empresaID;

	function __construct($newVal1, $newVal2, $newVal3, $newVal4, $newVal5, $newVal6){
	    parent::__construct($newVal1, $newVal2, $newVal3, $newVal4, $newVal5, $newVal6);
	}

	function __destruct(){
	}



	public function getnombreEmpresa()
	{
		return $this->nombreEmpresa;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setnombreEmpresa($newVal)
	{
		$this->nombreEmpresa = $newVal;
	}

	public function getdireccion()
	{
		return $this->direccion;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setdireccion($newVal)
	{
		$this->direccion = $newVal;
	}

	public function getnumeroEmpleados()
	{
		return $this->numeroEmpleados;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setnumeroEmpleados($newVal)
	{
		$this->numeroEmpleados = $newVal;
	}

	public function getcargo()
	{
		return $this->cargo;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setcargo($newVal)
	{
		$this->cargo = $newVal;
	}

	public function getanioConstitucion()
	{
		return $this->anioConstitucion;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setanioConstitucion($newVal)
	{
		$this->anioConstitucion = $newVal;
	}

	public function getdescripcionEmpresa()
	{
		return $this->descripcionEmpresa;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setdescripcionEmpresa($newVal)
	{
		$this->descripcionEmpresa = $newVal;
	}

	public function getdescripcionProblema()
	{
		return $this->descripcionProblema;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setdescripcionProblema($newVal)
	{
		$this->descripcionProblema = $newVal;
	}

	public function getsolucionTemporal()
	{
		return $this->solucionTemporal;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setsolucionTemporal($newVal)
	{
		$this->solucionTemporal = $newVal;
	}

	public function getplanCrecimiento()
	{
		return $this->planCrecimiento;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setplanCrecimiento($newVal)
	{
		$this->planCrecimiento = $newVal;
	}

	public function getrazonSeleccion()
	{
		return $this->razonSeleccion;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setrazonSeleccion($newVal)
	{
		$this->razonSeleccion = $newVal;
	}
	
	public function getEmpresaID()
	{
		return $this->empresaID;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setEmpresaID($newVal)
	{
		$this->empresaID = $newVal;
	}
	
	public function validarDatos(){
		$v = parent::validarDatos();
		if($v){
			if(isset($this->nombreEmpresa) && !empty($this->nombreEmpresa) && !is_numeric($this->nombreEmpresa) && strlen($this->nombreEmpresa) <= 70 &&
	            $this->m_CuidadDep->getCuidadDepID() !== null && !empty($this->m_CuidadDep->getCuidadDepID()) && is_numeric($this->m_CuidadDep->getCuidadDepID()) &&
	            isset($this->direccion) && !empty($this->direccion) && !is_numeric($this->direccion) && strlen($this->direccion) <= 200 &&
	            isset($this->numeroEmpleados) && !empty($this->numeroEmpleados) && is_numeric($this->numeroEmpleados) &&
	            isset($this->cargo) && !empty($this->cargo) && !is_numeric($this->cargo) && strlen($this->cargo) <= 60 &&
	            isset($this->anioConstitucion) && !empty($this->anioConstitucion) && is_numeric($this->anioConstitucion) &&
	            $this->m_SectorEconomico->getSectorEconomicoID() !== null && !empty($this->m_SectorEconomico->getSectorEconomicoID()) && is_numeric($this->m_SectorEconomico->getSectorEconomicoID()) &&
	            isset($this->descripcionEmpresa) && !empty($this->descripcionEmpresa) && !is_numeric($this->descripcionEmpresa) && strlen($this->descripcionEmpresa) <= 250 &&
	            isset($this->descripcionProblema) && !empty($this->descripcionProblema) && !is_numeric($this->descripcionProblema) && strlen($this->descripcionProblema) <= 250 &&
	            isset($this->solucionTemporal) && !empty($this->solucionTemporal) && !is_numeric($this->solucionTemporal) && strlen($this->solucionTemporal) <= 200 &&
	            isset($this->razonSeleccion) && !empty($this->razonSeleccion) && !is_numeric($this->razonSeleccion) && strlen($this->razonSeleccion) <= 200){
	            
	            if(isset($this->planCrecimiento)){
                    if(empty($this->planCrecimiento) || strlen($this->planCrecimiento) > 200)
                        return false;
                    else
                    	$this->planCrecimiento = addslashes($this->planCrecimiento);
                }
	            $this->nombreEmpresa = addslashes($this->nombreEmpresa);
	            $cdID = ( int ) $this->m_CuidadDep->getCuidadDepID();
                $seID = ( int ) $this->m_SectorEconomico->getSectorEconomicoID();
                $this->m_CuidadDep->setCuidadDepID($cdID);
                $this->m_SectorEconomico->setSectorEconomicoID($seID);
                $this->direccion = addslashes($this->direccion);
	            $this->numeroEmpleados = ( int ) $this->numeroEmpleados;
	            if($this->numeroEmpleados < 1 || $this->numeroEmpleados > 99999999)
	                return false;
	            $this->cargo = addslashes($this->cargo);
	            $anioHoy = (int) date("Y");
	            $this->anioConstitucion = ( int ) $this->anioConstitucion;
	            if($this->anioConstitucion < 1 || $this->anioConstitucion > $anioHoy)
	                return false;
	            $this->descripcionEmpresa = addslashes($this->descripcionEmpresa);
	            $this->descripcionProblema = addslashes($this->descripcionProblema);
	            $this->solucionTemporal = addslashes($this->solucionTemporal);
	            $this->razonSeleccion = addslashes($this->razonSeleccion);
	            return true;
	        }else
	            return false;
		}else
		    return false;
    }
}
?>