<?php

namespace App\Entity;

use App\Repository\EnlaceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EnlaceRepository::class)
 */
class Enlace
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $enlace;

    /**
     * @ORM\ManyToOne(targetEntity=Ejercicio::class, inversedBy="enlaces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idEjercicio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnlace(): ?string
    {
        return $this->enlace;
    }

    public function setEnlace(string $enlace): self
    {
        $this->enlace = $enlace;

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
}
