<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SexoRepository")
 */
class Sexo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $tiposexo;

  

      /**
     * @ORM\OneToMany(targetEntity="Cliente", mappedBy="Sexo")
     */
    protected $cliente;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTiposexo(): ?string
    {
        return $this->tiposexo;
    }

    public function setTiposexo(string $tiposexo): self
    {
        $this->tiposexo = $tiposexo;

        return $this;
    }

    public function __toString()
    {
        return $this->tiposexo;
    }
}
