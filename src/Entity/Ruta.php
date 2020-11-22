<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RutaRepository")
 */
class Ruta
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
    private $nombreruta;

    
        /**
     * @ORM\ManyToMany(targetEntity="Areopuerto" , inversedBy="rutas")
     *
     */
    private $areopuertos;

     /**
     * @ORM\OneToMany(targetEntity="Paquete", mappedBy="Paquete")
     */
    protected $Paquete;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNombreruta(): ?string
    {
        return $this->nombreruta;
    }

    public function setNombreruta(string $nombreruta): self
    {
        $this->nombreruta = $nombreruta;

        return $this;
    }

          /**
     * Constructor
     */
    public function __construct() {
       
        $this->areopuertos = new ArrayCollection();
     
        }

    /**
     * @return Collection|Areopuerto[]
     */
    public function getAreopuertos(): Collection
    {
        return $this->areopuertos;
    }

    public function addAreopuertos(Areopuerto $areipuerto): self
    {
        if (!$this->areopuertos->contains($areopuerto)) {
            $this->areopuertos[] = $areopuerto;
        }

        return $this;
    }

    public function removeAreopuerto(Areopuerto $areopuerto): self
    {
        if ($this->areopuertos->contains($areopuerto)) {
            $this->areopuertos->removeElement($areopuerto);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombreruta;
    }
  

}
