<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservaRepository")
 */
class Reserva
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fechacreacion;

        /**
         * 
     * @ORM\ManyToOne(targetEntity="Paquete" , inversedBy="reserva")
     * @ORM\JoinColumn(name="paquete_id", referencedColumnName="id", onDelete="CASCADE")
     *
     */
    protected $paquete;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="EstadoReserva" , inversedBy="reserva")
     *
     */
    protected $EstadoReserva;

     /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Cliente" , inversedBy="reserva")
     *
     */
    protected $Cliente;

    /**
     *@Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Hospedaje" , inversedBy="reserva")
     *
     */
    protected $hospedaje;

    /**
     * @ORM\Column(type="boolean")
     */
    private $infante;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     *
     */
    protected $user;

     /**
     * @ORM\Column(type="string", length=255, nullable= true)
     */
    private $estadoreceptor;

      /**
     * @ORM\Column(type="string", length=255, nullable= true)
     */
    private $tipopaquete;

    /**
     * @ORM\Column(type="string", length=255, nullable= true)
     */
    private $descripcion;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechacreacion(): ?\DateTimeInterface
    {
        return $this->fechacreacion;
    }

    public function setFechacreacion(\DateTimeInterface $fechacreacion): self
    {
        $this->fechacreacion = $fechacreacion;

        return $this;
    }

    public function getPaquete() {
        return $this->paquete;
    }


    public function setPaquete( $paquete) {
        $this->paquete = $paquete;

        return $this;
    }

      /**
     * Get EstadoReserva
     *
     * @return App\Entity\EstadoReserva
     */
    public function getEstadoReserva() {
        return $this->EstadoReserva;
    }

    public function setEstadoReserva( $tipoestado ) {
        $this->EstadoReserva = $tipoestado;

        return $this;
    }
    
     /**
     * Get Cliente
     *
     * @return App\Entity\Cliente
     */
    public function getCliente() {
        return $this->Cliente;
    }

    public function setCliente( $nombre ) {
        $this->Cliente = $nombre;

        return $this;
    }

    public function __construct($paquete) {
        $this->paquete = $paquete;
        
    }

     /**
      * Get Hospedaje
      *
      * @return App\Entity\Hospedaje
      */
    public function getHospedaje() {
    return $this->hospedaje;
}

    public function setHospedaje( $hospedaje ) {
    $this->hospedaje = $hospedaje;

    return $this;
}

    public function getInfante()
    {
        return $this->infante;
    }

    public function setInfante($infante)
    {
        $this->infante = $infante;

        return $this;
    }



    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }


    public function getEstadoreceptor(): ?string
    {
        return $this->estadoreceptor;
    }

    public function setEstadoreceptor(string $estadoreceptor): self
    {
        $this->estadoreceptor = $estadoreceptor;

        return $this;
    }

    public function getTipopaquete(): ?string
    {
        return $this->tipopaquete;
    }

    public function setTipopaquete(string $tipopaquete): self
    {
        $this->tipopaquete = $tipopaquete;

        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion( $descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }


}
