<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SucursalRepository")
 */
class Sucursal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

      /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="Sucursal")
     */
    protected $User;

    /**
     * @ORM\OneToMany(targetEntity="Cliente", mappedBy="Sucursal")
     */
    protected $cliente;

    /**
     * @ORM\OneToMany(targetEntity="SolicitudVisa", mappedBy="Sucursal")
     */
    protected $solicitudVisa;

    /**
     * @ORM\OneToMany(targetEntity="Reserva", mappedBy="Sucursal")
     */
    protected $reserva;

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

    public function __toString()
    {
        return $this->nombre;
    }

}
