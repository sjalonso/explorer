<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstadoSolicitudvisaRepository")
 */
class EstadoSolicitudvisa
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
    private $tipoestado;

    /**
     * @ORM\OneToMany(targetEntity="SolicitudVisa", mappedBy="EstadoSolicitudvisa")
     */
    protected $SolicitudVisa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoestado(): ?string
    {
        return $this->tipoestado;
    }

    public function setTipoestado(string $tipoestado): self
    {
        $this->tipoestado = $tipoestado;

        return $this;
    }

    public function __toString()
    {
        return $this->tipoestado;
    }
}
