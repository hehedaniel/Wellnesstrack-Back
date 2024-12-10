<?php

namespace App\Entity;

use App\Repository\ConsumoDiaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsumoDiaRepository::class)
 */
class ConsumoDia
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
     * @ORM\Column(type="string", length=255)
     */
    private $comida;

    /**
     * @ORM\Column(type="float")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $momento;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="consumoDias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUsuario;

    /**
     * @ORM\Column(type="time")
     */
    private $hora;

    public function setId($id)
    {
        $this->id = $id;
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

    public function getComida(): ?string
    {
        return $this->comida;
    }

    public function setComida(string $comida): self
    {
        $this->comida = $comida;

        return $this;
    }

    public function getCantidad(): ?float
    {
        return $this->cantidad;
    }

    public function setCantidad(float $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getMomento(): ?string
    {
        return $this->momento;
    }

    public function setMomento(string $momento): self
    {
        $this->momento = $momento;

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

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(\DateTimeInterface $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function isFechaBetween($fechaInicio, $fechaFin)
    {
        return $this->getFecha() >= $fechaInicio && $this->getFecha() <= $fechaFin;
    }
}
