<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TituloRepository")
 */
class Titulo
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
    private $prefijo;

    /**
     * @ORM\OneToMany(targetEntity="Cliente", mappedBy="Titulo")
     */
    protected $cliente;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrefijo(): ?string
    {
        return $this->prefijo;
    }

    public function setPrefijo(string $prefijo): self
    {
        $this->prefijo = $prefijo;

        return $this;
    }

    public function __toString()
    {
        return $this->prefijo;
    }
}
