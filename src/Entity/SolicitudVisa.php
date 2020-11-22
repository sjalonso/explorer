<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SolicitudVisaRepository")
 */
class SolicitudVisa
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
    private $fechasolicitud;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccioncuba;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccionextranjero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pais;

   

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ocupacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lugartrabajo;

    /**
     * @ORM\Column(type="integer")
     */
    private $duracionestancia;

    /**
     * @ORM\Column(type="date")
     */
    private $fecharegreso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccionhaiti;

    /**
     * @ORM\Column(type="boolean")
     */
    private $viajehaiti;

     /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Cliente" , inversedBy="SolicitudVisa")
     *
     */
    protected $Cliente;

     /**
     *
     * @ORM\ManyToOne(targetEntity="Estadocivil" , inversedBy="SolicitudVisa")
     *
     */
    protected $Estadocivil;

   

    /**
     *  @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="EstadoSolicitudvisa" , inversedBy="SolicitudVisa")
     *
     */
    protected $EstadoSolicitudvisa;

    /**
     * @ORM\Column(type="date", nullable=true )
     */
    private $fechainiciotramite;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechafinaltramite;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     *
     */
    protected $user;

     /**
     * @ORM\Column(type="string", length=255)
     */
    private $motivo;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechasalidaviaje;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDireccioncuba(): ?string
    {
        return $this->direccioncuba;
    }

    public function setDireccioncuba(string $direccioncuba): self
    {
        $this->direccioncuba = $direccioncuba;

        return $this;
    }

    public function getDireccionextranjero(): ?string
    {
        return $this->direccionextranjero;
    }

    public function setDireccionextranjero(string $direccionextranjero): self
    {
        $this->direccionextranjero = $direccionextranjero;

        return $this;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(string $pais): self
    {
        $this->pais = $pais;

        return $this;
    }



    public function getOcupacion(): ?string
    {
        return $this->ocupacion;
    }

    public function setOcupacion(string $ocupacion): self
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    public function getLugartrabajo(): ?string
    {
        return $this->lugartrabajo;
    }

    public function setLugartrabajo(string $lugartrabajo): self
    {
        $this->lugartrabajo = $lugartrabajo;

        return $this;
    }

    public function getDuracionestancia(): ?int
    {
        return $this->duracionestancia;
    }

    public function setDuracionestancia(int $duracionestancia): self
    {
        $this->duracionestancia = $duracionestancia;

        return $this;
    }

    public function getFecharegreso(): ?\DateTimeInterface
    {
        return $this->fecharegreso;
    }

    public function setFecharegreso(\DateTimeInterface $fecharegreso): self
    {
        $this->fecharegreso = $fecharegreso;

        return $this;
    }

    public function getDireccionhaiti(): ?string
    {
        return $this->direccionhaiti;
    }

    public function setDireccionhaiti(string $direccionhaiti): self
    {
        $this->direccionhaiti = $direccionhaiti;

        return $this;
    }

    public function getViajehaiti()
    {
        return $this->viajehaiti;
    }

    public function setViajehaiti($viajehaiti)
    {
        $this->viajehaiti = $viajehaiti;
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

     /**
     * Get Estadocivil
     *
     * @return App\Entity\Estadocivil
     */
    public function getEstadocivil() {
        return $this->Estadocivil;
    }

    public function setEstadocivil( $tipoestado ) {
        $this->Estadocivil = $tipoestado;

        return $this;
    }


 /**
     * Get EstadoSolicitudvisa
     *
     * @return App\Entity\EstadoSolicitudvisa
     */
    public function getEstadoSolicitudvisa() {
        return $this->EstadoSolicitudvisa;
    }

    public function setEstadoSolicitudvisa( $tipoestado ) {
        $this->EstadoSolicitudvisa = $tipoestado;

        return $this;
    }

    public function getFechainiciotramite()
    {
        return $this->fechainiciotramite;
    }

    public function setFechainiciotramite( $fechainiciotramite)
    {
        $this->fechainiciotramite = $fechainiciotramite;

        return $this;
    }

  public function getFechafinaltramite()
    {
        return $this->fechafinaltramite;
    }

    public function setFechafinaltramite( $fechafinaltramite)
    {
        $this->fechafinaltramite = $fechafinaltramite;

        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function getFechasolicitud()
    {
        return $this->fechasolicitud;
    }

    public function setFechasolicitud( $fechasolicitud)
    {
        $this->fechasolicitud = $fechasolicitud;

        return $this;
    }

    public function getMotivo(): ?string
    {
        return $this->motivo;
    }

    public function setMotivo(string $motivo): self
    {
        $this->motivo = $motivo;

        return $this;
    }

    public function getFechasalidaviaje()
    {
        return $this->fechasalidaviaje;
    }

    public function setFechasalidaviaje( $fechasalidaviaje)
    {
        $this->fechasalidaviaje = $fechasalidaviaje;

        return $this;
    }

}
