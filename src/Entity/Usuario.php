<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $correo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $correo_v;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contrasena;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rol;

    /**
     * @ORM\Column(type="integer")
     */
    private $edad;

    /**
     * @ORM\Column(type="float")
     */
    private $altura;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $objetivo_opt;

    /**
     * @ORM\Column(type="float")
     */
    private $objetivo_num;

    /**
     * @ORM\OneToMany(targetEntity=Alimento::class, mappedBy="idUsuario", orphanRemoval=true)
     */
    private $alimentos;

    /**
     * @ORM\OneToMany(targetEntity=ConsumoDia::class, mappedBy="idUsuario")
     */
    private $consumoDias;

    /**
     * @ORM\OneToMany(targetEntity=Recetas::class, mappedBy="idUsuario", orphanRemoval=true)
     */
    private $recetas;

    /**
     * @ORM\OneToMany(targetEntity=Peso::class, mappedBy="idUsuario", orphanRemoval=true)
     */
    private $pesos;

    /**
     * @ORM\OneToMany(targetEntity=UsuarioRealizaEjercicio::class, mappedBy="idUsuario")
     */
    private $usuarioRealizaEjercicios;

    public function __construct()
    {
        $this->alimentos = new ArrayCollection();
        $this->consumoDias = new ArrayCollection();
        $this->recetas = new ArrayCollection();
        $this->pesos = new ArrayCollection();
        $this->usuarioRealizaEjercicios = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity=Ejercicio::class, mappedBy="idUsuario")
     */
    // private $ejercicios;

    // public function __construct()
    // {
    //     $this->ejercicios = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getCorreoV(): ?string
    {
        return $this->correo_v;
    }

    public function setCorreoV(string $correo_v): self
    {
        $this->correo_v = $correo_v;

        return $this;
    }

    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): self
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    public function isRol(): ?bool
    {
        return $this->rol;
    }

    public function setRol(bool $rol = false): self
    {
        $this->rol = $rol;

        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): self
    {
        $this->edad = $edad;

        return $this;
    }

    public function getAltura(): ?float
    {
        return $this->altura;
    }

    public function setAltura(float $altura): self
    {
        $this->altura = $altura;

        return $this;
    }

    public function getObjetivoOpt(): ?string
    {
        return $this->objetivo_opt;
    }

    public function setObjetivoOpt(string $objetivo_opt): self
    {
        $this->objetivo_opt = $objetivo_opt;

        return $this;
    }

    public function getObjetivoNum(): ?float
    {
        return $this->objetivo_num;
    }

    public function setObjetivoNum(float $objetivo_num): self
    {
        $this->objetivo_num = $objetivo_num;

        return $this;
    }

    /**
     * @return Collection<int, Ejercicio>
     */
    // public function getEjercicios(): Collection
    // {
    //     return $this->ejercicios;
    // }

    // public function addEjercicio(Ejercicio $ejercicio): self
    // {
    //     if (!$this->ejercicios->contains($ejercicio)) {
    //         $this->ejercicios[] = $ejercicio;
    //         $ejercicio->setIdUsuario($this);
    //     }

    //     return $this;
    // }

    // public function removeEjercicio(Ejercicio $ejercicio): self
    // {
    //     if ($this->ejercicios->removeElement($ejercicio)) {
    //         // set the owning side to null (unless already changed)
    //         if ($ejercicio->getIdUsuario() === $this) {
    //             $ejercicio->setIdUsuario(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Alimento>
     */
    public function getAlimentos(): Collection
    {
        return $this->alimentos;
    }

    public function addAlimento(Alimento $alimento): self
    {
        if (!$this->alimentos->contains($alimento)) {
            $this->alimentos[] = $alimento;
            $alimento->setIdUsuario($this);
        }

        return $this;
    }

    public function removeAlimento(Alimento $alimento): self
    {
        if ($this->alimentos->removeElement($alimento)) {
            // set the owning side to null (unless already changed)
            if ($alimento->getIdUsuario() === $this) {
                $alimento->setIdUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ConsumoDia>
     */
    public function getConsumoDias(): Collection
    {
        return $this->consumoDias;
    }

    public function addConsumoDia(ConsumoDia $consumoDia): self
    {
        if (!$this->consumoDias->contains($consumoDia)) {
            $this->consumoDias[] = $consumoDia;
            $consumoDia->setIdUsuario($this);
        }

        return $this;
    }

    public function removeConsumoDia(ConsumoDia $consumoDia): self
    {
        if ($this->consumoDias->removeElement($consumoDia)) {
            // set the owning side to null (unless already changed)
            if ($consumoDia->getIdUsuario() === $this) {
                $consumoDia->setIdUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recetas>
     */
    public function getRecetas(): Collection
    {
        return $this->recetas;
    }

    public function addReceta(Recetas $receta): self
    {
        if (!$this->recetas->contains($receta)) {
            $this->recetas[] = $receta;
            $receta->setIdUsuario($this);
        }

        return $this;
    }

    public function removeReceta(Recetas $receta): self
    {
        if ($this->recetas->removeElement($receta)) {
            // set the owning side to null (unless already changed)
            if ($receta->getIdUsuario() === $this) {
                $receta->setIdUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Peso>
     */
    public function getPesos(): Collection
    {
        return $this->pesos;
    }

    public function addPeso(Peso $peso): self
    {
        if (!$this->pesos->contains($peso)) {
            $this->pesos[] = $peso;
            $peso->setIdUsuario($this);
        }

        return $this;
    }

    public function removePeso(Peso $peso): self
    {
        if ($this->pesos->removeElement($peso)) {
            // set the owning side to null (unless already changed)
            if ($peso->getIdUsuario() === $this) {
                $peso->setIdUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UsuarioRealizaEjercicio>
     */
    public function getUsuarioRealizaEjercicios(): Collection
    {
        return $this->usuarioRealizaEjercicios;
    }

    public function addUsuarioRealizaEjercicio(UsuarioRealizaEjercicio $usuarioRealizaEjercicio): self
    {
        if (!$this->usuarioRealizaEjercicios->contains($usuarioRealizaEjercicio)) {
            $this->usuarioRealizaEjercicios[] = $usuarioRealizaEjercicio;
            $usuarioRealizaEjercicio->setIdUsuario($this);
        }

        return $this;
    }

    public function removeUsuarioRealizaEjercicio(UsuarioRealizaEjercicio $usuarioRealizaEjercicio): self
    {
        if ($this->usuarioRealizaEjercicios->removeElement($usuarioRealizaEjercicio)) {
            // set the owning side to null (unless already changed)
            if ($usuarioRealizaEjercicio->getIdUsuario() === $this) {
                $usuarioRealizaEjercicio->setIdUsuario(null);
            }
        }

        return $this;
    }
}
