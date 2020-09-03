<?php
namespace Entities;

use Entities;
/**
 * @author Mario Peralta
 * @version 1.0
 * @created 26-jul-2017 03:24:07 a.m.
 */
class Solicitante{

	private $nombreCompleto;
	private $fechaRegistro;
	private $correo;
	private $telefono;
	private $activo;
	private $identificacion;
	private $solicitanteId;
        private $empresaId;

	function __construct() {
	}

	function __destruct(){
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

        function getIdentificacion() {
            return $this->identificacion;
        }

        function getSolicitanteId() {
            return $this->solicitanteId;
        }

        function getEmpresaId() {
            return $this->empresaId;
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

        function setIdentificacion($identificacion) {
            $this->identificacion = $identificacion;
        }

        function setSolicitanteId($solicitanteId) {
            $this->solicitanteId = $solicitanteId;
        }

        function setEmpresaId($empresaId) {
            $this->empresaId = $empresaId;
        }

        	
    public function validarDatos() {
        if (isset($this->nombreCompleto) && !empty($this->nombreCompleto) && !is_numeric($this->nombreCompleto) && strlen($this->pais) <= 100 &&
                isset($this->fechaRegistro) && !empty($this->fechaRegistro) && !is_numeric($this->fechaRegistro) &&
                isset($this->correo) && !empty($this->correo) && !is_numeric($this->correo) && strlen($this->correo) <= 90 &&
                isset($this->telefono) && !empty($this->telefono) && is_numeric($this->telefono) &&
                isset($this->identificacion) && !empty($this->identificacion) && strlen($this->identificacion) <= 30) {
            
            $this->nombreCompleto = addslashes($this->pais);
            $this->correo = addslashes($this->conocimientoCIA);
            $this->identificacion = addslashes($this->motivoApoyo);
            return true;
        } else
            return false;
    }

}
?>