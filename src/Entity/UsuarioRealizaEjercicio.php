<?php

namespace App\Entity;

use App\Repository\UsuarioRealizaEjercicioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuarioRealizaEjercicioRepository::class)
 */
class UsuarioRealizaEjercicio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="time")
     */
    private $hora;

    /**
     * @ORM\Column(type="float")
     */
    private $calorias;

    /**
     * @ORM\Column(type="float")
     */
    private $tiempo;

    /**
     * @ORM\ManyToOne(targetEntity=Ejercicio::class, inversedBy="usuarioRealizaEjercicios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idEjercicio;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="usuarioRealizaEjercicios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUsuario;

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(\DateTimeInterface $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function getCalorias(): ?float
    {
        return $this->calorias;
    }

    public function setCalorias(float $calorias): self
    {
        $this->calorias = $calorias;

        return $this;
    }

    public function getTiempo(): ?float
    {
        return $this->tiempo;
    }

    public function setTiempo(float $tiempo): self
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    public function getIdEjercicio(): ?Ejercicio
    {
        return $this->idEjercicio;
    }

    public function setIdEjercicio(?Ejercicio $idEjercicio): self
    {
        $this->idEjercicio = $idEjercicio;

        return $this;
    }

    public function getIdUsuario(): ?Usuario
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(?Usuario $idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }
}
