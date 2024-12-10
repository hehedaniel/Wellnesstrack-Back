<?php

namespace App\Util;

use PDO;

class CbbddConsultas
{

  private $conexion;

  public function __construct()
  {
    $this->conexion = new PDO("mysql:dbname=api;host=127.0.0.1", "root", "");
  }

  public function consulta($sentencia)
  {
    $salida = $this->conexion->prepare($sentencia);
    $salida->execute();
    return $salida->fetchAll();
  }

}