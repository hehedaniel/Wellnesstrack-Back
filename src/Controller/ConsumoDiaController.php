<?php

namespace App\Controller;

use App\Entity\ConsumoDia;
use App\Form\ConsumoDiaType;
use App\Repository\AlimentoRepository;
use App\Entity\Alimento;
use App\Repository\ConsumoDiaRepository;
use App\Repository\RecetasRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Util\RespuestaController;
use App\Util\CbbddConsultas;

/**
 * @Route("/consumodia")
 */
class ConsumoDiaController extends AbstractController
{
    /**
     * @Route("/usuario/{id}", name="consumo_dia_usuario_getByUsuario", methods={"GET"})
     */
    public function getByUsuario(ConsumoDiaRepository $consumoDiaRepository, $id): Response
    {
        $consumoDiaUsuario = $consumoDiaRepository->findBy(['idUsuario' => $id]);

        if (!$consumoDiaUsuario) {
            return RespuestaController::format("404", "No se encontraron entradas.");
        }

        foreach ($consumoDiaUsuario as $consumoDia) {
            $consumosDiaJSON[] = $this->consumoDiaJSON($consumoDia);
        }

        return RespuestaController::format("200", $consumosDiaJSON);
    }

    /**
     * @Route("/usuario/rango", name="consumo_dia_usuario_fechas", methods={"POST"})
     */
    public function getByUsuarioAndFechas(Request $request, ConsumoDiaRepository $consumoDiaRepository, AlimentoRepository $alimentoRepository, RecetasRepository $recetasRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $fechaInicio = $data['fechaInicio'];
        $fechaFin = $data['fechaFin'];
        // $fechaInicio = new \DateTime($data['fechaInicio']);
        // $fechaFin = new \DateTime($data['fechaFin']);
        $id = $data['id'];

        $cbbdd = new CbbddConsultas();
        $consumosEncontrados = $cbbdd->consulta("SELECT * FROM consumo_dia WHERE id_usuario_id = $id AND fecha BETWEEN '$fechaInicio' AND '$fechaFin'");

        if (!$consumosEncontrados) {
            return RespuestaController::format("200", "No se encontraron entradas en las fechas indicadas.");
        }

        foreach ($consumosEncontrados as $consumo) {
            $consumoDiaFormatear = new ConsumoDia();
            $consumoDiaFormatear->setId($consumo["id"]);
            $consumoDiaFormatear->setComida($consumo["comida"]);
            $consumoDiaFormatear->setCantidad($consumo["cantidad"]);
            $consumoDiaFormatear->setMomento($consumo["momento"]);
            $consumoDiaFormatear->setFecha(new \DateTime($consumo["fecha"]));
            $consumoDiaFormatear->setHora(new \DateTime($consumo["hora"]));

            $consumosDiaJSON[] = $this->consumoDiaCompletoJSON($consumoDiaFormatear, $alimentoRepository, $recetasRepository);
        }

        return RespuestaController::format("200", $consumosDiaJSON);
    }

    /**
     * @Route("/usuario/fechahora", name="consumo_dia_usuario_fecha_hora", methods={"POST"})
     */
    public function getByUsuarioFechaYHora(Request $request, ConsumoDiaRepository $consumoDiaRepository, AlimentoRepository $alimentoRepository, RecetasRepository $recetasRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $hora = $data['hora'];
        $fecha = $data['fecha'];
        $id = $data['idUsuario'];

        $consumosDia = $consumoDiaRepository->findBy([
            'fecha' => new \DateTime($fecha),
            'hora' => new \DateTime($hora),
            'idUsuario' => $id
        ]);

        if (!$consumosDia) {
            return RespuestaController::format("200", "No se encontró dicho consumo.");
        }

        foreach ($consumosDia as $consumoDia) {
            $consumosDiaJSON[] = $this->consumoDiaCompletoJSON($consumoDia, $alimentoRepository, $recetasRepository);
        }

        return RespuestaController::format("200", $consumosDiaJSON);
    }

    /**
     * @Route("/crear/usuario", name="consumo_dia_usuario", methods={"POST"})
     */
    public function crear(Request $request, ConsumoDiaRepository $consumoDiaRepository, UsuarioRepository $usuarioRepository, AlimentoRepository $alimentoRepository, RecetasRepository $recetasRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $consumoDia = new ConsumoDia();
        $alimento = new Alimento();

        $nombre = $data['comida'];

        $cbbdd = new CbbddConsultas();
        $alimentosEncontrados = $cbbdd->consulta("SELECT * FROM recetas WHERE nombre LIKE '%$nombre%'");
        if (!$alimentosEncontrados) {
            $alimentosEncontrados = $cbbdd->consulta("SELECT * FROM alimento WHERE nombre LIKE '%$nombre%'");
            if (!$alimentosEncontrados) {
                return RespuestaController::format("404", "No se encontró la comida.");
            } else {
                $consumoDia->setComida("1_" . $alimentosEncontrados[0]["id"]);
            }
        } else {
            $consumoDia->setComida("2_" . $alimentosEncontrados[0]["id"]);
        }

        $cantidadFloat = floatval($data['cantidad']);
        $consumoDia->setCantidad($cantidadFloat);
        $consumoDia->setMomento($data['momento']);
        $consumoDia->setIdUsuario($usuarioRepository->find($data['idUsuario']));
        $consumoDia->setFecha(new \DateTime($data['fecha']));
        $consumoDia->setHora(new \DateTime($data['hora']));

        $consumoDiaRepository->add($consumoDia, true);

        $consumoDiaJSON = $this->consumoDiaJSON($consumoDia);

        return RespuestaController::format("200", $consumoDiaJSON);
    }

    /**
     * @Route("/editar", name="editar_consumo_dia_usuario", methods={"PUT"})
     */
    public function editar(Request $request, ConsumoDiaRepository $consumoDiaRepository, UsuarioRepository $usuarioRepository, AlimentoRepository $alimentoRepository, RecetasRepository $recetasRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        // Determino si es alimento o es receta
        if ($alimentoRepository->findBy(['nombre' => $data['comidaInicial']])) {
            $comidaBuscar = "1_" . $alimentoRepository->findBy(['nombre' => $data['comidaInicial']])[0]->getId();
        } else if ($recetasRepository->findBy(['nombre' => $data['comidaInicial']])) {
            $comidaBuscar = "2_" . $recetasRepository->findBy(['nombre' => $data['comidaInicial']])[0]->getId();
        } else {
            return RespuestaController::format("404", "No se encontró la comida.");
        }

        $consumoDia = $consumoDiaRepository->findOneBy([
            'comida' => $comidaBuscar,
            'fecha' => new \DateTime($data['fecha']),
            'hora' => new \DateTime($data['hora']),
            'idUsuario' => $data['idUsuario']
        ]);


        if (!$consumoDia) {
            return RespuestaController::format("404", "No se encontró la entrada correspondiente.");
        }

        if ($data['comidaInicial'] == $data['comida']) {
            $nombreBuscar = $data['comidaInicial'];
        } else {
            $nombreBuscar = $data['comida'];
        }

        $cbbdd = new CbbddConsultas();
        $alimentosEncontrados = $cbbdd->consulta("SELECT * FROM recetas WHERE nombre LIKE '%$nombreBuscar%'");
        if (!$alimentosEncontrados) {
            $alimentosEncontrados = $cbbdd->consulta("SELECT * FROM alimento WHERE nombre LIKE '%$nombreBuscar%'");
            if (!$alimentosEncontrados) {
                return RespuestaController::format("404", "No se encontró la comida.");
            } else {
                $consumoDia->setComida("1_" . $alimentosEncontrados[0]["id"]);
            }
        } else {
            $consumoDia->setComida("2_" . $alimentosEncontrados[0]["id"]);
        }

        $cantidadFloat = floatval($data['cantidad']);
        $consumoDia->setCantidad($cantidadFloat);

        $consumoDia->setCantidad($data['cantidad']);
        $consumoDia->setFecha(new \DateTime($data['fecha']));
        $consumoDia->setHora(new \DateTime($data['hora']));
        $consumoDia->setIdUsuario($usuarioRepository->find($data['idUsuario']));

        $consumoDiaRepository->add($consumoDia, true);

        $consumoDiaJSON = $this->consumoDiaJSON($consumoDia);

        return RespuestaController::format("200", $consumoDiaJSON);
    }

    /**
     * @Route("/eliminar", name="eliminar_consumo_dia_usuario", methods={"DELETE", "POST"})
     */
    public function eliminar(Request $request, ConsumoDiaRepository $consumoDiaRepository, AlimentoRepository $alimentoRepository, RecetasRepository $recetasRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $comidaBuscar = '';

        // Busco el alimento o receta que recibo por el nombre
        if (AlimentoController::buscarNombreSinPeticionID($data['comida'], $alimentoRepository)) {
            $comidaBuscar = "1_" . AlimentoController::buscarNombreSinPeticionID($data['comida'], $alimentoRepository);
        } else if (RecetasController::buscarNombreSinPeticionID($data['comida'], $recetasRepository)) {
            $comidaBuscar = "2_" . RecetasController::buscarNombreSinPeticionID($data['comida'], $recetasRepository);
        } else {
            return RespuestaController::format("404", "No se encontró la comida.");
        }

        $consumoDia = $consumoDiaRepository->findOneBy([
            'comida' => $comidaBuscar,
            'fecha' => new \DateTime($data['fecha']),
            'hora' => new \DateTime($data['hora']),
            'idUsuario' => $data['idUsuario']
        ]);

        if (!$consumoDia) {
            return RespuestaController::format("404", "No se encontró la entrada correspondiente.");
        }

        $consumoDiaRepository->remove($consumoDia, true);

        return RespuestaController::format("200", "Entrada eliminada correctamente.");
    }

    /**
     * @Route("/datosnombre", name="consumo_dia_datos_nombre", methods={"POST"})
     * 
     */
    public function datosNombre(Request $request, ConsumoDiaRepository $consumoDiaRepository, AlimentoRepository $alimentoRepository, RecetasRepository $recetasRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        // Encuentro el consumo
        $consumoDia = $consumoDiaRepository->findOneBy([
            'fecha' => new \DateTime($data['fecha']),
            'hora' => new \DateTime($data['hora']),
            'idUsuario' => $data['idUsuario']
        ]);

        if (!$consumoDia) {
            return RespuestaController::format("200", "No se encontró la entrada correspondiente.");
        }

        //Determino si es alimento o es receta a partir de los datos encontrados antes
        $comidaParts = explode("_", $consumoDia->getComida());
        $tipoComida = $comidaParts[0];
        $idComida = $comidaParts[1];
        $alimentoReceta = false;
        //Busco el alimento o receta
        if ($tipoComida == "1") {
            $alimento = $alimentoRepository->find($idComida);
            if ($alimento) {
                $nombre = $alimento->getNombre();
                $nutrientes = [
                    'calorias' => $this->calcularCalorias($alimento->getCantidad(), $consumoDia->getCantidad(), $alimento->getCalorias()),
                    'proteinas' => $alimento->getProteinas(),
                    'grasas' => $alimento->getGrasas(),
                    'carbohidratos' => $alimento->getCarbohidratos(),
                    'azucares' => $alimento->getAzucares(),
                    'vitaminas' => $alimento->getVitaminas(),
                ];
                $descripcion = $alimento->getDescripcion();
                $marca = $alimento->getMarca();
                $imagen = $alimento->getImagen();
                $alimentoReceta = true;
            }
        } else if ($tipoComida == "2") {
            $receta = $recetasRepository->find($idComida);
            if ($receta) {
                $nombre = $receta->getNombre();
                $nutrientes = [
                    'calorias' => $this->calcularCalorias($receta->getCantidadFinal(), $consumoDia->getCantidad(), $receta->getCalorias()),
                    'proteinas' => $receta->getProteinas(),
                    'grasas' => $receta->getGrasas(),
                    'carbohidratos' => $receta->getCarbohidratos(),
                    'azucares' => $receta->getAzucares(),
                    'vitaminas' => $receta->getVitaminas(),
                ];
                $descripcion = $receta->getDescripcion();
                $instrucciones = $receta->getInstrucciones();
                $imagen = $receta->getImagen();
            }
        }

        if (!isset($nutrientes)) {
            return RespuestaController::format("404", "No se encontraron nutrientes para la comida");
        }

        if ($alimentoReceta) {
            $consumoDiaJSON = [
                "id" => $consumoDia->getId(),
                "comida" => $nombre,
                "cantidad" => $consumoDia->getCantidad(),
                "momento" => $consumoDia->getMomento(),
                "fecha" => $consumoDia->getFecha(),
                "hora" => $consumoDia->getHora(),
                "marca" => $marca,
                "descripcion" => $descripcion,
                "nutrientes" => $nutrientes,
                "imagen" => $imagen
            ];
        } else {
            $consumoDiaJSON = [
                "id" => $consumoDia->getId(),
                "comida" => $nombre,
                "cantidad" => $consumoDia->getCantidad(),
                "momento" => $consumoDia->getMomento(),
                "fecha" => $consumoDia->getFecha(),
                "hora" => $consumoDia->getHora(),
                "descripcion" => $descripcion,
                "instrucciones" => $instrucciones,
                "nutrientes" => $nutrientes,
                "imagen" => $imagen
            ];
        }

        return RespuestaController::format("200", $consumoDiaJSON);
    }

    private function consumoDiaJSON(ConsumoDia $consumoDia)
    {

        $consumoDiaJSON = [
            "id" => $consumoDia->getId(),
            "comida" => $consumoDia->getComida(),
            "cantidad" => $consumoDia->getCantidad(),
            "momento" => $consumoDia->getMomento(),
            "fecha" => $consumoDia->getFecha(),
            "hora" => $consumoDia->getHora(),
            "idUsuario" => $consumoDia->getIdUsuario(),
        ];

        return $consumoDiaJSON;
    }

    private function consumoDiaCompletoJSON(ConsumoDia $consumoDia, AlimentoRepository $alimentoRepository, RecetasRepository $recetasRepository)
    {

        //Si getComida empieza por 1_ buscar alimento
        //Si getComida empieza por 2_ buscar receta
        $comidaParts = explode("_", $consumoDia->getComida());
        $tipoComida = $comidaParts[0];
        $idComida = $comidaParts[1];
        $esAlimento = false;

        // Diferencio entre alimento y receta para obtener el nombre y los nutrientes de cada uno
        if ($tipoComida == "1") {
            $alimento = $alimentoRepository->find($idComida);
            $esAlimento = true;
            if ($alimento) {
                $nombre = $alimento->getNombre();
                $nutrientes = [
                    'calorias' => $this->calcularCalorias($alimento->getCantidad(), $consumoDia->getCantidad(), $alimento->getCalorias()),
                    'proteinas' => $alimento->getProteinas(),
                    'grasas' => $alimento->getGrasas(),
                    'carbohidratos' => $alimento->getCarbohidratos(),
                    'azucares' => $alimento->getAzucares(),
                    'vitaminas' => $alimento->getVitaminas(),
                ];
                $imagen = $alimento->getImagen();
                $descripcion = $alimento->getDescripcion();
                $marca = $alimento->getMarca();
            }
        } else if ($tipoComida == "2") {
            $receta = $recetasRepository->find($idComida);
            if ($receta) {
                $nombre = $receta->getNombre();
                $nutrientes = [
                    'calorias' => $this->calcularCalorias($receta->getCantidadFinal(), $consumoDia->getCantidad(), $receta->getCalorias()),
                    'proteinas' => $receta->getProteinas(),
                    'grasas' => $receta->getGrasas(),
                    'carbohidratos' => $receta->getCarbohidratos(),
                    'azucares' => $receta->getAzucares(),
                    'vitaminas' => $receta->getVitaminas(),
                ];
                $imagen = $receta->getImagen();
                $descripcion = $receta->getDescripcion();
                $instrucciones = $receta->getInstrucciones();
            }
        }

        if (!isset($nutrientes)) {
            return RespuestaController::format("404", "No se encontraron nutrientes para la comida");
        }

        if ($esAlimento) {
            $consumoDiaJSON = [
                "id" => $consumoDia->getId(),
                "comida" => $nombre,
                "cantidad" => $consumoDia->getCantidad(),
                "momento" => $consumoDia->getMomento(),
                "fecha" => $consumoDia->getFecha(),
                "hora" => $consumoDia->getHora(),
                "marca" => $marca,
                "descripcion" => $descripcion,
                "nutrientes" => $nutrientes,
                "imagen" => $imagen
            ];
        } else {
            $consumoDiaJSON = [
                "id" => $consumoDia->getId(),
                "comida" => $nombre,
                "cantidad" => $consumoDia->getCantidad(),
                "momento" => $consumoDia->getMomento(),
                "fecha" => $consumoDia->getFecha(),
                "hora" => $consumoDia->getHora(),
                "descripcion" => $descripcion,
                "instrucciones" => $instrucciones,
                "nutrientes" => $nutrientes,
                "imagen" => $imagen
            ];
        }

        return $consumoDiaJSON;
        // return $nutrientes;
    }


    private function calcularCalorias(float $cantidadBase, float $cantidadConsumida, float $caloriasBase)
    {
        //Aqui calculo las calorias en funcion de los datos de la consumicion
        //y del alimento en cuestion

        $caloriasConsumidas = ($cantidadConsumida * $caloriasBase) / $cantidadBase;

        return round($caloriasConsumidas, 2);
    }
}
