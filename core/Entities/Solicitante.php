<?php
namespace Entities;

use Entities;

/**
 * @author Mario Peralta
 * @version 1.0
 * @created 26-jul-2017 03:24:07 a.m.
 */
class Solicitante {

    private $nombreCompleto;
    private $fechaRegistro;
    private $correo;
    private $telefono;
    private $activo;
    private $solicitanteId;
    public $m_CiudadDep;
    public $m_SectorEconomico;
    private $direccion;

    function __construct() {
    }
    function __destruct() {
    }
    
    function getDireccion() {
        return $this->direccion;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    
    public function getNombreCompleto() {
        return $this->nombreCompleto;
    }

    public function setNombreCompleto($newVal) {
        $this->nombreCompleto = $newVal;
    }

    function getFechaRegistro() {
        return $this->fechaRegistro;
    }

    function getCorreo() {
        return $this->correo;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getActivo() {
        return $this->activo;
    }

    function getSolicitanteId() {
        return $this->solicitanteId;
    }

    function setFechaRegistro($fechaRegistro) {
        $this->fechaRegistro = $fechaRegistro;
    }

    function setCorreo($correo) {
        $this->correo = $correo;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function setSolicitanteId($solicitanteId) {
        $this->solicitanteId = $solicitanteId;
    }

    public function validarDatos() {
        if (isset($this->nombreCompleto) && !empty($this->nombreCompleto) && !is_numeric($this->nombreCompleto) &&
                isset($this->fechaRegistro) && !empty($this->fechaRegistro) && !is_numeric($this->fechaRegistro) &&
                isset($this->correo) && !empty($this->correo) && !is_numeric($this->correo) && strlen($this->correo) <= 90 &&
                isset($this->telefono) && !empty($this->telefono) && is_numeric($this->telefono)  &&
                $this->m_CiudadDep->getCiudadDepID() !== null && !empty($this->m_CiudadDep->getCiudadDepID()) && is_numeric($this->m_CiudadDep->getCiudadDepID()) &&
                $this->m_SectorEconomico->getSectorEconomicoID() !== null && !empty($this->m_SectorEconomico->getSectorEconomicoID()) && is_numeric($this->m_SectorEconomico->getSectorEconomicoID()) &&
                isset($this->direccion) && !empty($this->direccion) && !is_numeric($this->direccion) && strlen($this->direccion) <= 200 ) {

            $this->nombreCompleto = addslashes($this->nombreCompleto);
            $this->correo = addslashes($this->correo);
            $this->telefono = addslashes($this->telefono);
            $this->direccion = addslashes($this->direccion);
            return true;
        } else
            return false;
    }

}

?>