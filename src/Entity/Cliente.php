<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClienteRepository")
 */
class Cliente
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
     * @ORM\Column(type="string", length=255)
     */
    private $apellido;

    /**
     * @ORM\Column(type="date")
     */
    private $fechanacimiento;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pasaporte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lugarexpedicion;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaexpiracion;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaexpedicion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(message = "Correo no valido.")
     */
    private $correo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lugarnacimiento;

   

       /**
     * @ORM\OneToMany(targetEntity="Reserva", mappedBy="Cliente")
     */
    protected $Reserva;

       /**
     * @ORM\OneToMany(targetEntity="SolicitudVisa", mappedBy="Cliente")
     */
    protected $SolicitudVisa;

    /**
     * @ORM\JoinColumn(name="sucursal_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\ManyToOne(targetEntity="Sucursal" , inversedBy="Cliente")
     *
     */
    protected $sucursal;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Sexo" , inversedBy="Cliente")
     *
     */
    protected $sexo;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Titulo" , inversedBy="Cliente")
     *
     */
    protected $titulo;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     *
     */
    private $user;

       /**
     * @ORM\Column(type="string", length=255)
     */
    private $nacionalidad;

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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getFechanacimiento(): ?\DateTimeInterface
    {
        return $this->fechanacimiento;
    }

    public function setFechanacimiento(\DateTimeInterface $fechanacimiento): self
    {
        $this->fechanacimiento = $fechanacimiento;

        return $this;
    }

    public function getPasaporte(): ?string
    {
        return $this->pasaporte;
    }

    public function setPasaporte(string $pasaporte): self
    {
        $this->pasaporte = $pasaporte;

        return $this;
    }

    public function getLugarexpedicion(): ?string
    {
        return $this->lugarexpedicion;
    }

    public function setLugarexpedicion(string $lugarexpedicion): self
    {
        $this->lugarexpedicion = $lugarexpedicion;

        return $this;
    }

    public function getFechaexpiracion(): ?\DateTimeInterface
    {
        return $this->fechaexpiracion;
    }

    public function setFechaexpiracion(\DateTimeInterface $fechaexpiracion): self
    {
        $this->fechaexpiracion = $fechaexpiracion;

        return $this;
    }

    public function getFechaexpedicion()
    {
        return $this->fechaexpedicion;
    }

    public function setFechaexpedicion($fechaexpedicion)
    {
        $this->fechaexpedicion = $fechaexpedicion;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

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

    public function getLugarnacimiento(): ?string
    {
        return $this->lugarnacimiento;
    }

    public function setLugarnacimiento(string $lugarnacimiento): self
    {
        $this->lugarnacimiento = $lugarnacimiento;

        return $this;
    }

    

    public function getSucursal() {
        return $this->sucursal;
    }

    public function setSucursal($sucursal) {
        $this->sucursal = $sucursal;
        return $this;
    }

    
    /**
     * Get Sexo
     *
     * @return App\Entity\Sexo
     */
    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo( $tiposexo ) {
        $this->sexo = $tiposexo;

        return $this;
    }

       /**
     * Get Titulo
     *
     * @return App\Entity\Titulo
     */
    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo( $prefijo ) {
        $this->titulo = $prefijo;

        return $this;
    }

    public function __toString()
    {
        return $this->nombre." ".$this->apellido;
    }

    public function getNacionalidad(): ?string
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad(string $nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }
}
