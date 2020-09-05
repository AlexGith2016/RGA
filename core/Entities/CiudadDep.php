<?php
namespace Entities;

/**
 * Clase que representa la cuidad o departamento de la empresa.
 * @author Mario Peralta
 * @version 1.0
 * @created 26-jul-2017 03:24:07 a.m.
 */
class CiudadDep {

    private $nombreCiudad;
    private $ciudadDepID;

    function __construct() {
    }

    function __destruct() {
    }
    
    function getNombreCiudad() {
        return $this->nombreCiudad;
    }

    function getCiudadDepID() {
        return $this->ciudadDepID;
    }

    function setNombreCiudad($nombreCiudad) {
        $this->nombreCiudad = $nombreCiudad;
    }

    function setCiudadDepID($ciudadDepID) {
        $this->ciudadDepID = $ciudadDepID;
    }



}

?>