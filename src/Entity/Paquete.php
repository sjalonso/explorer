<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaqueteRepository")
 * @UniqueEntity(fields={"codigopaquete"}, message="Ya existe un paquete con este código")
 */
class Paquete
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
    private $fechaida;

    /**
     * @ORM\Column(type="date")
     */
    private $fecharegreso;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantasiento;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantasientooriginal;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciocuba;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciodestino;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $precioinfante;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $precioinfantedestino;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciodestinodoble;

     /**
      * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciodestinotriple;

      /**
       * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciodestinocuadruple;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciominisimple;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciominidoble;

     /**
      * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciominitriple;

      /**
       * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciominicuadruple;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciominininno;

      /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciomininvsimple;

      /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciomininvdoble;

      /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciomininvtriple;

      /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciomininvcuadruple;

      /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    private $preciomininvinfante;

     /**
     *@Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Ruta" , inversedBy="Paquete")
     *
     */
    protected $Ruta;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="EstadoPaquete" , inversedBy="Paquete")
     *
     */
    protected $EstadoPaquete;

      /**
     * @ORM\Column(type="string", length=255)
     */
    private $codigopaquete;

        /**
     * @ORM\OneToMany(targetEntity="Reserva" , mappedBy="paquete")
     *
     */
    protected $reserva;

    protected $preciototal;

    /**
     *@Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Aerolineas" , inversedBy="Paquete")
     *
     */
    protected $aerolineas;

      /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     *  
     * @Assert\Length(
     *      max = 1500,
     *      
     *      maxMessage = "No puede tener más de {{ 1500 }} caracteres",
     *      
     * )
     */
     
    private $descripcion;


    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     */
    private $fechalimite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaida(): ?\DateTimeInterface
    {
        return $this->fechaida;
    }

    public function setFechaida(\DateTimeInterface $fechaida): self
    {
        $this->fechaida = $fechaida;

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

    public function getCantasiento(): ?int
    {
        return $this->cantasiento;
    }

    public function setCantasiento(int $cantasiento): self
    {
        $this->cantasiento = $cantasiento;

        return $this;
    }

    public function getCantasientooriginal(): ?int
    {
        return $this->cantasientooriginal;
    }

    public function setCantasientooriginal(int $cantasiento): self
    {
        $this->cantasientooriginal = $cantasiento;

        return $this;
    }

    public function getPreciocuba(): ?int
    {
        return $this->preciocuba;
    }

    public function setPreciocuba(int $preciocuba): self
    {
        $this->preciocuba = $preciocuba;

        return $this;
    }

    public function getPreciodestino(): ?int
    {
        return $this->preciodestino;
    }

    public function setPreciodestino(int $preciodestino): self
    {
        $this->preciodestino = $preciodestino;

        return $this;
    }

    public function getPrecioinfante(): ?int
    {
        return $this->precioinfante;
    }

    public function setPrecioinfante(int $precioinfante): self
    {
        $this->precioinfante = $precioinfante;

        return $this;
    }

    public function getPreciodestinodoble(): ?int
    {
        return $this->preciodestinodoble;
    }

    public function setPreciodestinodoble(int $preciodestinodoble): self
    {
        $this->preciodestinodoble = $preciodestinodoble;

        return $this;
    }

    public function getPreciodestinotriple(): ?int
    {
        return $this->preciodestinotriple;
    }

    public function setPreciodestinotriple(int $preciodestinotriple): self
    {
        $this->preciodestinotriple = $preciodestinotriple;

        return $this;
    }

    public function getPreciodestinocuadruple(): ?int
    {
        return $this->preciodestinocuadruple;
    }

    public function setPreciodestinocuadruple(int $preciodestinocuadruple): self
    {
        $this->preciodestinocuadruple = $preciodestinocuadruple;

        return $this;
    }

    public function getPreciominisimple(): ?int
    {
        return $this->preciominisimple;
    }

    public function setPreciominisimple(int $preciominisimple): self
    {
        $this->preciominisimple = $preciominisimple;

        return $this;
    }

    public function getPreciominidoble(): ?int
    {
        return $this->preciominidoble;
    }

    public function setPreciominidoble(int $preciominidoble): self
    {
        $this->preciominidoble = $preciominidoble;

        return $this;
    }

    public function getPreciominitriple(): ?int
    {
        return $this->preciominitriple;
    }

    public function setPreciominitriple(int $preciominitriple): self
    {
        $this->preciominitriple = $preciominitriple;

        return $this;
    }

    public function getPreciominicuadruple(): ?int
    {
        return $this->preciominicuadruple;
    }

    public function setPreciominicuadruple(int $preciominicuadruple): self
    {
        $this->preciominicuadruple = $preciominicuadruple;

        return $this;
    }

    public function getPreciominininno(): ?int
    {
        return $this->preciominininno;
    }

    public function setPreciominininno(int $preciominininno): self
    {
        $this->preciominininno = $preciominininno;

        return $this;
    }

        /**
     * Get DiaIda
     *
     * @return App\Entity\DiaIda
     */
    public function getDiaIda() {
        return $this->DiaIda;
    }

    public function setDiaIda( $dia ) {
        $this->DiaIda = $dia;

        return $this;
    }

        /**
     * Get DiaVuelta
     *
     * @return App\Entity\DiaVuelta
     */
    public function getDiaVuelta() {
        return $this->DiaVuelta;
    }

    public function setDiaVuelta( $dia ) {
        $this->DiaVuelta = $dia;

        return $this;
    }

       /**
     * Get EstadoPaquete
     *
     * @return App\Entity\EstadoPaquete
     */
    public function getEstadoPaquete() {
        return $this->EstadoPaquete;
    }

    public function setEstadoPaquete( $tipoestado ) {
        $this->EstadoPaquete = $tipoestado;

        return $this;
    }

         /**
     * Get Ruta
     *
     * @return App\Entity\Ruta
     */
    public function getRuta() {
        return $this->Ruta;
    }

    public function setRuta( $ruta) {
        $this->Ruta = $ruta;

        return $this;
    }

    public function getCodigopaquete(): ?string
    {
        return $this->codigopaquete;
    }

    public function setCodigopaquete(string $codigopaquete): self
    {
        $this->codigopaquete = $codigopaquete;

        return $this;
    }

    public function __construct() {
        $this->reserva = new \Doctrine\Common\Collections\ArrayCollection();
        
    }

       /**
     * Get paquete
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReserva() {
        return $this->reserva;
    }

    public function addReserva(Reserva $reserva): self
    {
        if (!$this->reserva->contains($reserva)) {
            $this->reserva[] = $reserva;
            $reserva->setHabitacion($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reserva->contains($reserva)) {
            $this->reserva->removeElement($reserva);
            // set the owning side to null (unless already changed)
            if ($reserva->getPaquete() === $this) {
                $reserva->setPaquete(null);
            }
        }

        return $this;
    }

    public function getPreciomininvsimple(): ?int
    {
        return $this->preciomininvsimple;
    }

    public function setPreciomininvsimple(int $preciomininvsimple): self
    {
        $this->preciomininvsimple = $preciomininvsimple;

        return $this;
    }


    
    public function getPreciomininvdoble(): ?int
    {
        return $this->preciomininvdoble;
    }

    public function setPreciomininvdoble(int $preciomininvdoble): self
    {
        $this->preciomininvdoble = $preciomininvdoble;

        return $this;
    }

    
    public function getPreciomininvtriple(): ?int
    {
        return $this->preciomininvtriple;
    }

    public function setPreciomininvtriple(int $preciomininvtriple): self
    {
        $this->preciomininvtriple = $preciomininvtriple;

        return $this;
    }

    
    public function getPreciomininvcuadruple(): ?int
    {
        return $this->preciomininvcuadruple;
    }

    public function setPreciomininvcuadruple(int $preciomininvcuadruple): self
    {
        $this->preciomininvcuadruple = $preciomininvcuadruple;

        return $this;
    }

    public function getPreciomininvinfante(): ?int
    {
        return $this->preciomininvinfante;
    }

    public function setPreciomininvinfante(int $preciomininvinfante): self
    {
        $this->preciomininvinfante = $preciomininvinfante;

        return $this;
    }

 public function getPreciototal(){
    $reservas = $this->reserva;
    $this->preciototal = 0;
    foreach ($reservas as $elem){

        if($elem->getInfante()== false && $elem->getTipopaquete()== 'Paquete Normal' && $elem->getHospedaje()=='Habitación Simple' ){
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciodestino();
        }
        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Paquete Normal' && $elem->getHospedaje()=='Habitación Doble')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciodestinodoble();
        }
        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Paquete Normal' && $elem->getHospedaje()=='Habitación Triple')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciodestinotriple();
        }
        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Paquete Normal' && $elem->getHospedaje()=='Habitación Cuádruple')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciodestinocuadruple();
        }
        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Mini paquete con visa' && $elem->getHospedaje()=='Habitación Simple')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciominisimple();
        }
        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Mini paquete con visa' && $elem->getHospedaje()=='Habitación Doble')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciominidoble();
        }
        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Mini paquete con visa' && $elem->getHospedaje()=='Habitación Triple')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciominitriple();
        }
        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Mini paquete con visa' && $elem->getHospedaje()=='Habitación Cuádruple')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciominicuadruple();
        }

        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Mini paquete sin visa' && $elem->getHospedaje()=='Habitación Simple')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciomininvsimple();
        }
        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Mini paquete sin visa' && $elem->getHospedaje()=='Habitación Doble')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciomininvdoble();
        }
        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Mini paquete sin visa' && $elem->getHospedaje()=='Habitación Triple')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciomininvtriple();
        }
        else if($elem->getInfante()== false && $elem->getTipopaquete()== 'Mini paquete sin visa' && $elem->getHospedaje()=='Habitación Cuádruple')
        {
            $this->preciototal += $this->getPreciocuba();
            $this->preciototal += $this->getPreciomininvcuadruple();
        }


        else if($elem->getInfante()== true && $elem->getTipopaquete()== 'Paquete Normal' )
        {
            $this->preciototal += $this->getPrecioinfante();
            $this->preciototal += $this->getPrecioinfantedestino();
        }
        else if($elem->getInfante()== true && $elem->getTipopaquete()== 'Mini paquete con visa'  )
        {
            $this->preciototal += $this->getPrecioinfante();
            $this->preciototal += $this->getPreciominininno();
        }

        else if($elem->getInfante()== true && $elem->getTipopaquete()== 'Mini paquete sin visa' )
        {
            $this->preciototal += $this->getPrecioinfante();
            $this->preciototal += $this->getPreciomininvinfante();
        }

    }

    return $this->preciototal;

}

   public function getPrecioinfantedestino(): ?int
    {
        return $this->precioinfantedestino;
    }

    public function setPrecioinfantedestino(int $precioinfantedestino): self
{
    $this->precioinfantedestino = $precioinfantedestino;

    return $this;
}

    public function __toString()
    {
        return $this->codigopaquete;
    }


    public function getPreciototaladulto(){
            $pcuba = $this->getPreciocuba();
            $pdest = $this->getPreciodestino();
            $this->prectotal = $pcuba+$pdest;
            return $this->prectotal;
            }
        
    
    public function getPreciototalinfante(){
            $pinfcuba = $this->getPrecioinfante();
            $pinfdest = $this->getPrecioinfantedestino();
            $this->prectotal = $pinfcuba+$pinfdest;
            return $this->prectotal;
    }

    public function getPreciototaladultodoble(){
            $pcuba = $this->getPreciocuba();
            $pdest = $this->getPreciodestinodoble();
            $this->prectotal = $pcuba+$pdest;
            return $this->prectotal;
        }

    public function getPreciototaladultotriple(){
            $pcuba = $this->getPreciocuba();
            $pdest = $this->getPreciodestinotriple();
            $this->prectotal = $pcuba+$pdest;
            return $this->prectotal;
            }   

    public function getPreciototaladultocuadruple(){
            $pcuba = $this->getPreciocuba();
            $pdest = $this->getPreciodestinocuadruple();
            $this->prectotal = $pcuba+$pdest;
            return $this->prectotal;
                }

    public function getPreciototaladultominisimple(){
            $pcuba = $this->getPreciocuba();
            $pdest = $this->getPreciominisimple();
            $this->prectotal = $pcuba+$pdest;
            return $this->prectotal;
                    }
    public function getPreciototaladultominidoble(){
            $pcuba = $this->getPreciocuba();
            $pdest = $this->getPreciominidoble();
            $this->prectotal = $pcuba+$pdest;
            return $this->prectotal;
                        }
    public function getPreciototaladultominitriple(){
            $pcuba = $this->getPreciocuba();
            $pdest = $this->getPreciominitriple();
            $this->prectotal = $pcuba+$pdest;
            return $this->prectotal;
                            }  
    public function getPreciototaladultominicuadruple(){
            $pcuba = $this->getPreciocuba();
            $pdest = $this->getPreciominicuadruple();
            $this->prectotal = $pcuba+$pdest;
            return $this->prectotal;
                                }  
                                
    public function getPreciototalinfantemini(){
            $pinfcuba = $this->getPrecioinfante();
            $pinfdest = $this->getPreciominininno();
            $this->prectotal = $pinfcuba+$pinfdest;
            return $this->prectotal;
                        }  
                        
    public function getPreciototaladultomininvsimple(){
        $pcuba = $this->getPreciocuba();
        $pdest = $this->getPreciomininvsimple();
        $this->prectotal = $pcuba+$pdest;
        return $this->prectotal;
    }

        public function getPreciototaladultomininvdoble(){
            $pcuba = $this->getPreciocuba();
            $pdest = $this->getPreciomininvdoble();
            $this->prectotal = $pcuba+$pdest;
            return $this->prectotal;   
                                                }                     
       
    
       public function getPreciototaladultomininvtriple(){
            $pcuba = $this->getPreciocuba();
            $pdest = $this->getPreciomininvtriple();
            $this->prectotal = $pcuba+$pdest;
            return $this->prectotal;   
             }
             
        public function getPreciototaladultomininvcuadruple(){
           $pcuba = $this->getPreciocuba();
           $pdest = $this->getPreciomininvcuadruple();
           $this->prectotal = $pcuba+$pdest;
           return $this->prectotal;   
                 }   
                 
        public function getPreciototalinfantemininv(){
         $pcuba = $this->getPrecioinfante();
         $pdest = $this->getPreciomininvinfante();
         $this->prectotal = $pcuba+$pdest;
         return $this->prectotal;   
                          }           
                                               
        /**
     * Get Aerolineas
     *
     * @return App\Entity\Aerolineas
     */
    public function getAerolineas() {
        return $this->aerolineas;
    }

    public function setAerolineas( $aerolineas) {
        $this->aerolineas = $aerolineas;

        return $this;
    } 

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFechalimite()
    {
        return $this->fechalimite;
    }

    public function setFechalimite($fechalimite)
    {
        $this->fechalimite = $fechalimite;

        return $this;
    }

}
