<?php

namespace App\Controller;

use App\Entity\Ejercicio;
use App\Entity\UsuarioRealizaEjercicio;
use App\Repository\EjercicioRepository;
use App\Repository\EnlaceRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Util\RespuestaController;
use App\Util\CbbddConsultas;
use App\Entity\Enlace;

/**
 * @Route("/ejercicio")
 */
class EjercicioController extends AbstractController
{
    /**
     * @Route("/", name="app_ejercicio_index", methods={"GET"})
     */
    public function index(EjercicioRepository $ejercicioRepository): Response
    {
        $ejercicios = $ejercicioRepository->findAll();

        if (!$ejercicios) {
            return RespuestaController::format("404", "No hay ejercicios registrados");
        }

        $ejerciciosJSON = [];

        foreach ($ejercicios as $ejercicio) {
            $ejerciciosJSON[] = $this->ejercicioJSON($ejercicio);
        }

        return RespuestaController::format("200", $ejerciciosJSON);
    }

    /**
     * @Route("/administrar", name="app_ejercicio_eadministrar", methods={"GET"})
     */
    public function administrar(EnlaceRepository $enlaceRepository, EjercicioRepository $ejercicioRepository, UsuarioRepository $usuasrioRepository)
    {
        $cbbdd = new CbbddConsultas();
        $ejerciciosAdministrar = $cbbdd->consulta("SELECT * FROM ejercicio WHERE estado = 0");

        if (!$ejerciciosAdministrar) {
            return RespuestaController::format("200", "No hay ejercicios por administrar");
        }

        // Devolver los ejercicios en formato JSON con enlaces
        $ejerciciosJSON = [];
        foreach ($ejerciciosAdministrar as $ejercicioData) {
            $ejercicio = new Ejercicio();
            $ejercicio->setId($ejercicioData['id']);
            $ejercicio->setNombre($ejercicioData['nombre']);
            $ejercicio->setDescripcion($ejercicioData['descripcion']);
            $ejercicio->setGrupoMuscular($ejercicioData['grupo_muscular']);
            $ejercicio->setDificultad($ejercicioData['dificultad']);
            $ejercicio->setInstrucciones($ejercicioData['instrucciones']);
            $ejercicio->setValorMET($ejercicioData['valor_met']);
            $ejercicio->setIdUsuario($usuasrioRepository->find($ejercicioData['id_usuario_id']));

            $ejerciciosJSON[] = $this->ejercicioConEnlaceJSON($ejercicio, $enlaceRepository, $ejercicioRepository);
        }

        return RespuestaController::format("200", $ejerciciosJSON);
    }

    /**
     * @Route("/aceptar", name="app_ejercicio_aceptar", methods={"POST"})
     */
    public function aceptar(Request $request, EjercicioRepository $ejercicioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data["idEjercicio"])) {
            return RespuestaController::format("400", "No se ha recibido el ID del ejercicio");
        }

        //Mediante la clase base de datos actualizo el estado
        $cbbdd = new CbbddConsultas();
        $respuestaConsulta = $cbbdd->consulta("UPDATE ejercicio SET estado = '1' WHERE ejercicio.id = " . $data['idEjercicio'] . ";");

        if ($respuestaConsulta == 0) {
            return RespuestaController::format("400", "No se ha podido aceptar el ejercicio");
        }

        return RespuestaController::format("200", "Ejercicio aceptado correctamente");
    }

    /**
     * @Route("/rechazar", name="app_ejercicio_rechazar", methods={"PUT"})
     */
    public function rechazarEjercicio(Request $request, EjercicioRepository $ejercicioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['idEjercicio'])) {
            // Buscar ejercicio por ID
            $ejercicio = $ejercicioRepository->find($data['idEjercicio']);
        } else {
            return RespuestaController::format("400", "ID no recibido");
        }

        if (!$ejercicio) {
            return RespuestaController::format("404", "Ejercicio no encontrado");
        }

        $ejercicioRepository->remove($ejercicio, true);

        return RespuestaController::format("200", "Ejercicio aceptado correctamente");
    }

    /**
     * @Route("/{id}", name="app_ejercicio_buscar", methods={"GET"})
     */
    public function buscar($id, EjercicioRepository $ejercicioRepository): Response
    {
        $fechaActual = new \DateTime();
        $fechaActualFormatted = $fechaActual->format('Y-m-d');

        // Busco todos los ejercicios con el mismo id de usuario y de la misma fecha
        $ejercicio = $ejercicioRepository->findBy([
            'idUsuario' => $id,
            'fecha' => $fechaActualFormatted
        ]);

        if (!$ejercicio) {
            return RespuestaController::format("404", "Ejercicio no encontrado");
        }

        //Recorro los ejercicios y los guardo en un array
        $ejerciciosJSON = [];
        foreach ($ejercicio as $ejercicio) {
            if ($ejercicio->getEstado() == 1) {
                $ejerciciosJSON[] = $this->ejercicioJSON($ejercicio);
            }
        }

        // $ejercicioJSON = $this->ejercicioJSON($ejercicio);

        return RespuestaController::format("200", $ejerciciosJSON);
    }

    /**
     * @Route("/nombreConEnlaces", name="app_ejercicio_buscarnombre", methods={"POST"})
     */
    public function buscarnombre(Request $request, EnlaceRepository $enlaceRepository, EjercicioRepository $ejercicioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data["nombre"])) {
            return RespuestaController::format("400", "No se ha recibido el nombre del ejercicio");
        }

        $nombreBuscar = $data["nombre"];

        $cbbdd = new CbbddConsultas();
        $ejerciciosEncontrados = $cbbdd->consulta("SELECT * FROM ejercicio WHERE nombre LIKE '%$nombreBuscar%'");
        if (!$ejerciciosEncontrados) {
            // Cambié el código de respuesta por un 404 porque no se encuentra el ejercicio.
            return RespuestaController::format("404", "No se ha encontrado el ejercicio");
        } else {
            $ejerciciosConEnlaces = []; // Inicializar correctamente el array vacío
            foreach ($ejerciciosEncontrados as $ejercicionuevo) {
                if ($ejercicionuevo["estado"] == 1) {
                    $ejercicioDevolver = new Ejercicio();
                    $ejercicioDevolver->setId($ejercicionuevo["id"]);
                    $ejercicioDevolver->setNombre($ejercicionuevo["nombre"]);
                    $ejercicioDevolver->setDescripcion($ejercicionuevo["descripcion"]);
                    $ejercicioDevolver->setGrupoMuscular($ejercicionuevo["grupo_muscular"]);
                    $ejercicioDevolver->setDificultad($ejercicionuevo["dificultad"]);
                    $ejercicioDevolver->setInstrucciones($ejercicionuevo["instrucciones"]);
                    $ejercicioDevolver->setValorMET($ejercicionuevo["valor_met"]);
                    $ejercicioDevolver->setIdUsuario(null);

                    // Añadir el ejercicio con los enlaces al array
                    $ejerciciosConEnlaces[] = $this->ejercicioConEnlaceJSON($ejercicioDevolver, $enlaceRepository, $ejercicioRepository);
                }
            }
            return RespuestaController::format("200", $ejerciciosConEnlaces);
        }
    }


    /**
     * @Route("/crear", name="app_ejercicio_crear", methods={"POST"})
     */
    public function crear(Request $request, EjercicioRepository $ejercicioRepository, UsuarioRepository $usuasrioRepository, EnlaceRepository $enlaceRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        $existingEjercicio = $ejercicioRepository->findOneBy(['nombre' => $data['nombre']]);
        if ($existingEjercicio) {
            return RespuestaController::format("400", "Ya existe un ejercicio con el mismo nombre");
        }

        $ejercicio = new Ejercicio();
        $ejercicio->setNombre($data['nombre']);
        $ejercicio->setDescripcion($data['descripcion']);
        $ejercicio->setGrupoMuscular($data['grupoMuscular']);
        $ejercicio->setDificultad($data['dificultad']);
        $ejercicio->setInstrucciones($data['instrucciones']);
        $ejercicio->setValorMET($data['valorMET']);
        $ejercicio->setEstado(0);

        $ejercicio->setIdUsuario($usuasrioRepository->find($data['idUsuario']));

        $ejercicioRepository->add($ejercicio, true);

        //Aqui ya he guardado el ejercicio en la base de datos, ahora debo añadir los enlaces
        //Como se que unicamente son 2 enlaces, los añado directamente
        $enlace1 = new Enlace();
        $enlace1->setEnlace($data['enlace1']);
        $enlace1->setIdEjercicio($ejercicio);
        $enlaceRepository->add($enlace1, true);

        $enlace2 = new Enlace();
        $enlace2->setEnlace($data['enlace2']);
        $enlace2->setIdEjercicio($ejercicio);
        $enlaceRepository->add($enlace2, true);

        // Devolver el ejercicio creado en formato JSON con los enlaces
        $ejercicioJSON = $this->ejercicioConEnlaceJSON($ejercicio, $enlaceRepository, $ejercicioRepository);

        return RespuestaController::format("200", $ejercicioJSON);
    }

    /**
     * @Route("/editar/{id}", name="app_ejercicio_editar", methods={"PUT"})
     */
    public function editar($id, Request $request, EjercicioRepository $ejercicioRepository, UsuarioRepository $usuasrioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        $ejercicio = $ejercicioRepository->find($id);

        if (!$ejercicio) {
            return RespuestaController::format("404", "Ejercicio no encontrado");
        }

        $ejercicio->setNombre($data['nombre']);
        $ejercicio->setDescripcion($data['descripcion']);
        $ejercicio->setGrupoMuscular($data['grupoMuscular']);
        $ejercicio->setDificultad($data['dificultad']);
        $ejercicio->setInstrucciones($data['instrucciones']);
        $ejercicio->setValorMET($data['valorMET']);
        $ejercicio->setIdUsuario($usuasrioRepository->find($data['idUsuario']));

        $ejercicioRepository->add($ejercicio, true);

        $ejercicioJSON = $this->ejercicioJSON($ejercicio);

        return RespuestaController::format("200", $ejercicioJSON);
    }

    /**
     * @Route("/eliminar", name="app_ejercicio_eliminar", methods={"DELETE"})
     */
    public function eliminar(Request $request, EjercicioRepository $ejercicioRepository): Response
    {

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return RespuestaController::format("400", "No se han recibido datos");
        }

        if (isset($data['id'])) {
            // Buscar ejercicio por ID
            $ejercicio = $ejercicioRepository->find($data['id']);
        } else {
            return RespuestaController::format("400", "ID no recibidos");
        }

        if (!$ejercicio) {
            return RespuestaController::format("404", "Ejercicio no encontrado");
        }

        $ejercicioRepository->remove($ejercicio, true);

        return RespuestaController::format("200", "Ejercicio eliminado correctamente");
    }

    private function ejercicioConEnlaceJSON(Ejercicio $ejercicio, EnlaceRepository $enlaceRepository, EjercicioRepository $ejercicioRepository)
    {
        // Buscar los enlaces usando la relación 'idEjercicio'
        $enlaces = $enlaceRepository->findBy(["idEjercicio" => $ejercicio->getId()]);

        // Si no hay enlaces, devolver un mensaje de error
        if (!$enlaces) {
            return RespuestaController::format("404", "No existen enlaces para este ejercicio");
        }

        // Crear un array para almacenar los enlaces formateados
        $enlacesFormateados = [];
        foreach ($enlaces as $enlace) {
            $enlacesFormateados[] = [
                "id" => $enlace->getId(),
                "url" => $enlace->getEnlace() // Asegúrate de que 'getEnlace()' sea el método correcto para obtener la URL del enlace
            ];
        }

        // Crear el arreglo con los detalles del ejercicio y sus enlaces
        $ejercicioConEnlaceJSON = [
            "id" => $ejercicio->getId(),
            "nombre" => $ejercicio->getNombre(),
            "descripcion" => $ejercicio->getDescripcion(),
            "grupoMuscular" => $ejercicio->getGrupoMuscular(),
            "dificultad" => $ejercicio->getDificultad(),
            "instrucciones" => $ejercicio->getInstrucciones(),
            "valorMET" => $ejercicio->getValorMET(),
            "idUsuario" => $ejercicio->getIdUsuario() ? $ejercicio->getIdUsuario()->getId() : null,
            "enlaces" => $enlacesFormateados // Devolver la lista de enlaces formateados
        ];

        return $ejercicioConEnlaceJSON;
    }

    // Función para convertir un objeto Ejercicio a formato JSON
    private function ejercicioJSON(Ejercicio $ejercicio)
    {
        $ejercicioJSON = [
            "id" => $ejercicio->getId(),
            "nombre" => $ejercicio->getNombre(),
            "descripcion" => $ejercicio->getDescripcion(),
            "grupoMuscular" => $ejercicio->getGrupoMuscular(),
            "dificultad" => $ejercicio->getDificultad(),
            "instrucciones" => $ejercicio->getInstrucciones(),
            "valorMET" => $ejercicio->getValorMET(),
            "idUsuario" => $ejercicio->getIdUsuario()->getId()
        ];

        return $ejercicioJSON;
    }

}
