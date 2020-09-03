<?php
namespace Entities;

/**
 * Clase donde se representa el sector con el que la empresa trabaja
 * @author Mario Peralta
 * @version 1.0
 * @created 26-jul-2017 03:24:08 a.m.
 */
class SectorEconomico{

	private $nombreSectorEc;
	private $sectorEconomicoID;
	

	function __construct(){
	}

	function __destruct(){
	}



	public function getnombreSectorEc()
	{
		return $this->nombreSectorEc;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setnombreSectorEc($newVal)
	{
		$this->nombreSectorEc = $newVal;
	}
	
	public function getSectorEconomicoID()
	{
		return $this->sectorEconomicoID;
	}

	/**
	 * 
	 * @param newVal
	 */
	public function setSectorEconomicoID($newVal)
	{
		$this->sectorEconomicoID = $newVal;
	}
	
	public function validarDatos($verificarID){
        if(isset($this->nombreSectorEc) && !empty($this->nombreSectorEc) && !is_numeric($this->nombreSectorEc) 
        	&& strlen($this->nombreSectorEc) <= 30){
            if($verificarID){
            	if(isset($this->sectorEconomicoID) && !empty($this->sectorEconomicoID) && is_numeric($this->sectorEconomicoID))
            		$this->sectorEconomicoID = ( int ) $this->sectorEconomicoID;
            	else
            		return false;
            }
            $this->nombreSectorEc = addslashes($this->nombreSectorEc);
            return true;
        }else
            return false;
    }
}
?>