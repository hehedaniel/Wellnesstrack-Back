<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;

/**
 * @Route("/main")
 */
class Inicio
{
  /**
   * @Route("/", name="app_main", methods={"GET"})
   */

  public function index(): Response
  {
    // Ruta principal de la api
    // Informo al usurio de donde se encuentra
    // Informo al usuario de que para obtenerifnormacion visite https://github.com/hehedaniel/WellnessTrack-api

    $respuesta = [
      'message' => 'Bienvenido a WellnessTrack API',
      'info' => 'Para más información, visite el repositorio en GitHub.',
      'url_documentacion' => 'https://github.com/hehedaniel/WellnessTrack-api'
    ];

    return RespuestaController::format("200", $respuesta);
  }
}