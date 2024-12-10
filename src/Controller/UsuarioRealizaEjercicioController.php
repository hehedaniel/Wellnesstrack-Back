<?php

namespace App\Controller;

use App\Entity\UsuarioRealizaEjercicio;
use App\Repository\EjercicioRepository;
use App\Repository\UsuarioRealizaEjercicioRepository;
use App\Repository\UsuarioRepository;
use Proxies\__CG__\App\Entity\Ejercicio;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;
use App\Util\CbbddConsultas;

/**
 * @Route("/usuariorealizaejercicio")
 */
class UsuarioRealizaEjercicioController extends AbstractController
{
   /**
    * @Route("/usuario/{id}", name="usuario_realiza_ejercicio_usuario", methods={"GET"})
    *
    * Método para obtener los ejercicios realizados por un usuario en un día concreto
    * @param UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository
    * @param EjercicioRepository $ejercicioRepository
    * @return Response con los ejercicios realizados por un usuario en formato JSON
    */
   public function getByUsuario($id, UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository, EjercicioRepository $ejercicioRepository): Response
   {

      $fechaActual = new \DateTime();
      $fecha = new \DateTime($fechaActual->format('Y-m-d'));

      $usuarioRealizaEjercicio = $usuarioRealizaEjercicioRepository->findBy([
         'idUsuario' => $id,
         'fecha' => $fecha
      ]);

      $ejerciciosUsuario = [];

      foreach ($usuarioRealizaEjercicio as $ejercicioUsuario) {
         $jercicioNombre = $ejercicioRepository->find($ejercicioUsuario->getIdEjercicio())->getNombre();
         $ejerciciosUsuario[] = $this->usuarioRealizaEjercicioInfoEjercicioJSON($ejercicioUsuario, $jercicioNombre);
      }

      return RespuestaController::format("200", $ejerciciosUsuario);
   }

   /**
    * @Route("/usuario/fecha", name="usuario_realiza_ejercicio_usuario", methods={"POST"})
    *
    * Método para obtener los ejercicios realizados por un usuario en un día concreto
    * @param UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository
    * @param EjercicioRepository $ejercicioRepository
    * @return Response con los ejercicios realizados por un usuario en formato JSON
    */
   public function getByUsuarioYFecha(Request $request, UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository, EjercicioRepository $ejercicioRepository): Response
   {

      $data = json_decode($request->getContent(), true);

      $fecha = new \DateTime($data["fecha"]);

      // $fechaActual = new \DateTime();
      // $fecha = new \DateTime($fechaActual->format('Y-m-d'));

      $id = $data["idUsuario"];

      $usuarioRealizaEjercicio = $usuarioRealizaEjercicioRepository->findBy([
         'idUsuario' => $id,
         'fecha' => $fecha
      ]);

      $ejerciciosUsuario = [];

      foreach ($usuarioRealizaEjercicio as $ejercicioUsuario) {
         $jercicioNombre = $ejercicioRepository->find($ejercicioUsuario->getIdEjercicio())->getNombre();
         $ejerciciosUsuario[] = $this->usuarioRealizaEjercicioInfoEjercicioJSON($ejercicioUsuario, $jercicioNombre);
      }

      return RespuestaController::format("200", $ejerciciosUsuario);
   }

   /**
    * @Route("/usuario/rango", name="usuario_realiza_ejercicio_rango", methods={"POST"})
    *
    * Método para obtener los ejercicios realizados por un usuario en un rango de fechas
    * @param UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository
    * @param EjercicioRepository $ejercicioRepository
    * @return Response con los ejercicios realizados por un usuario en formato JSON
    */
   public function getByUsuarioYRangoFecha(Request $request, EjercicioRepository $ejercicioRepository): Response
   {

      // Compruebo si recibo los datos necesarios

      $data = json_decode($request->getContent(), true);

      if (!$data["fechaInicio"] || !$data["fechaFin"] || !$data["idUsuario"]) {
         return RespuestaController::format("200", "No se han recibido los datos necesarios");
      }

      $fechaInicio = $data["fechaInicio"];
      $fechaFin = $data["fechaFin"];

      $id = $data["idUsuario"];

      $cbbdd = new CbbddConsultas();
      $usuarioRealizaEjercicio = $cbbdd->consulta("SELECT * FROM usuario_realiza_ejercicio WHERE id_usuario_id = $id AND fecha BETWEEN '$fechaInicio' AND '$fechaFin'");

      if (!$usuarioRealizaEjercicio) {
         return RespuestaController::format("200", "No se han encontrado ejercicios realizados por el usuario en el rango de fechas indicado");
      }

      $ejerciciosUsuario = [];

      foreach ($usuarioRealizaEjercicio as $ejercicioUsuario) {
         //Creo un objteto ejercicio para pasarselo a la función
         $ejercicio = new UsuarioRealizaEjercicio();
         $ejercicio->setId($ejercicioUsuario["id"]);
         $ejercicio->setFecha(new \DateTime($ejercicioUsuario["fecha"]));
         $ejercicio->setHora(new \DateTime($ejercicioUsuario["hora"]));
         $ejercicio->setCalorias($ejercicioUsuario["calorias"]);
         $ejercicio->setTiempo($ejercicioUsuario["tiempo"]);
         $ejercicio->setIdEjercicio($ejercicioRepository->find($ejercicioUsuario["id_ejercicio_id"]));
         // $ejercicio->setIdUsuario($ejercicioUsuario["id_usuario_id"]);

         $jercicioNombre = $ejercicioRepository->find($ejercicioUsuario["id_ejercicio_id"])->getNombre();
         $ejerciciosUsuario[] = $this->usuarioRealizaEjercicioInfoEjercicioJSON($ejercicio, $jercicioNombre);
      }

      return RespuestaController::format("200", $ejerciciosUsuario);
   }


   /**
    * @Route("/realiza", name="usuario_realiza_ejercicio_crear", methods={"POST"})
    *
    * Método para añadir un ejercicio realizado por un usuario
    * @param Request $request
    * @param UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository
    * @param EjercicioRepository $ejercicioRepository
    * @param UsuarioRepository $usuarioRepository
    * @return Response con el nuevo ejercicio_realizado por un usuario en formato JSON
    */

   public function crear(Request $request, UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository, EjercicioRepository $ejercicioRepository, UsuarioRepository $usuarioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      $usuarioRealizaEjercicio = new UsuarioRealizaEjercicio();
      //Aqui obtengo la fecha y hora actual formateadas a DD-MM-YYYY y HH:MM:SS
      $fechaActual = new \DateTime();
      $fecha = new \DateTime($fechaActual->format('Y-m-d'));
      $hora = new \DateTime($fechaActual->format('H:i:s'));

      //Le añado la fecha y hora al objeto
      $usuarioRealizaEjercicio->setFecha($fecha);
      $usuarioRealizaEjercicio->setHora($hora);

      //Aqui obtengo el peso del usuario a partir de su id
      $peso = $this->obtenerUltimoPeso($data['idUsuario']);

      //Aqui calculo las calorias quemadas
      $calorias = $data['met'] * $peso * $data['tiempo'];


      $usuarioRealizaEjercicio->setCalorias($calorias);
      $usuarioRealizaEjercicio->setTiempo($data['tiempo']);

      $usuarioRealizaEjercicio->setIdEjercicio($ejercicioRepository->find($data['idEjercicio']));
      $usuarioRealizaEjercicio->setIdUsuario($usuarioRepository->find($data['idUsuario']));

      $usuarioRealizaEjercicioRepository->add($usuarioRealizaEjercicio, true);

      return RespuestaController::format("200", $this->usuarioRealizaEjercicioJSON($usuarioRealizaEjercicio));
   }

   /**
    * @Route("/eliminar", name="usuario_realiza_ejercicio_eliminar", methods={"DELETE", "POST"})
    *
    * Método para eliminar un ejercicio realizado por un usuario
    * @param Request $request
    * @param UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository
    * @return Response con el mensaje de éxito o error
    */
   public function eliminar(UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository, Request $request): Response
   {
      $data = json_decode($request->getContent(), true);

      $usuarioRealizaEjercicio = $usuarioRealizaEjercicioRepository->findOneBy(
         [
            "idUsuario" => $data['idUsuario'],
            "fecha" => new \DateTime($data['fecha']),
            "hora" => new \DateTime($data['hora']),
            "idEjercicio" => $data['idEjercicio']
         ]
      );

      if (!$usuarioRealizaEjercicio) {
         return RespuestaController::format("404", "No existe el ejercicio a eliminar");
      }

      $usuarioRealizaEjercicioRepository->remove($usuarioRealizaEjercicio, true);

      return RespuestaController::format("200", "Ejercicio eliminado correctamente");
   }

   /**
    * @Route("/editar", name="usuario_realiza_ejercicio_editar", methods={"PUT"})
    *
    * Método para editar un ejercicio realizado por un usuario
    * @param Request $request
    * @param UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository
    * @return Response con el mensaje de éxito o error
    */
   public function editar(Request $request, UsuarioRealizaEjercicioRepository $usuarioRealizaEjercicioRepository, EjercicioRepository $ejercicioRepository): Response
   {
      $data = json_decode($request->getContent(), true);

      if (!$data) {
         return RespuestaController::format("400", "No se ha recibido información");
      }

      $usuarioRealizaEjercicio = $usuarioRealizaEjercicioRepository->findOneBy(
         [
            "idUsuario" => $data['idUsuario'],
            "fecha" => new \DateTime($data['fecha']),
            "hora" => new \DateTime($data['hora']),
            "idEjercicio" => $data['idEjercicioViejo']
         ]
      );

      if (!$usuarioRealizaEjercicio) {
         return RespuestaController::format("404", "No existe el ejercicio a editar");
      }

      //Busco el ejercicio nuevo
      $ejercicioNuevo = $ejercicioRepository->find($data['idEjercicioNuevo']);
      if (!$ejercicioNuevo) {
         return RespuestaController::format("404", "No existe el ejercicio nuevo");
      }

      $usuarioRealizaEjercicio->setIdEjercicio($ejercicioNuevo);

      //Aqui obtengo el peso del usuario a partir de su id
      $peso = $this->obtenerUltimoPeso($data['idUsuario']);

      //Aqui calculo las calorias quemadas
      $calorias = $data['met'] * $peso * $data['tiempo'];


      $usuarioRealizaEjercicio->setCalorias($calorias);

      $usuarioRealizaEjercicio->setTiempo($data['tiempo']);

      $usuarioRealizaEjercicioRepository->add($usuarioRealizaEjercicio, true);

      return RespuestaController::format("200", $this->usuarioRealizaEjercicioJSON($usuarioRealizaEjercicio));
   }


   /**
    * Métodos creados para ayudar a la realización de la API
    */

   /**
    * Método para convertir un objeto UsuarioRealizaEjercicio en un array JSON
    * Solo devuelve la información de la entidad UsuarioRealizaEjercicio
    * @param UsuarioRealizaEjercicio $usuarioRealizaEjercicio
    * @return array con la información del objeto UsuarioRealizaEjercicio
    */
   public function usuarioRealizaEjercicioJSON(UsuarioRealizaEjercicio $usuarioRealizaEjercicio)
   {
      return [
         "id" => $usuarioRealizaEjercicio->getId(),
         "fecha" => $usuarioRealizaEjercicio->getFecha(),
         "hora" => $usuarioRealizaEjercicio->getHora(),
         "calorias" => $usuarioRealizaEjercicio->getCalorias(),
         "tiempo" => $usuarioRealizaEjercicio->getTiempo(),
         "idEjercicio" => $usuarioRealizaEjercicio->getIdEjercicio(),
         "idUsuario" => $usuarioRealizaEjercicio->getIdUsuario(),
      ];
   }

   public function obtenerUltimoPeso(string $idUsuario): string
   {
      $cbbdd = new CbbddConsultas();
      $ultimoPeso = $cbbdd->consulta("
           SELECT *
           FROM peso
           WHERE id_usuario_id = '$idUsuario'
           ORDER BY fecha DESC, hora DESC
           LIMIT 1
       ");

      if (!$ultimoPeso) {
         return '0';
      }

      return $ultimoPeso[0]["peso"];
   }


   /**
    * Método para convertir un objeto UsuarioRealizaEjercicio en un array JSON
    * Devuelve la información de la entidad UsuarioRealizaEjercicio y el nombre del ejercicio
    * @param UsuarioRealizaEjercicio $usuarioRealizaEjercicio
    * @param string $nombreEjercio
    * @return array con la información del objeto UsuarioRealizaEjercicio
    */
   public function usuarioRealizaEjercicioInfoEjercicioJSON(UsuarioRealizaEjercicio $usuarioRealizaEjercicio, string $nombreEjercio)
   {

      return [
         "id" => $usuarioRealizaEjercicio->getId(),
         "fecha" => $usuarioRealizaEjercicio->getFecha(),
         "hora" => $usuarioRealizaEjercicio->getHora(),
         "calorias" => $usuarioRealizaEjercicio->getCalorias(),
         "tiempo" => $usuarioRealizaEjercicio->getTiempo(),
         "idEjercicio" => $usuarioRealizaEjercicio->getIdEjercicio(),
         "idUsuario" => $usuarioRealizaEjercicio->getIdUsuario(),
         "ejNombre" => $nombreEjercio
      ];
   }
}