<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VentaPaquete
 *
 * @ORM\Table(name="persona_tratamiento", indexes={@ORM\Index(name="fk_empleado_tratamiento", columns={"empleado"}), @ORM\Index(name="fk_paciente_tratamiento2", columns={"paciente"}), @ORM\Index(name="fk_tratamiento_tratamiento", columns={"tratamiento"})})
 * @ORM\Entity
 */
class PersonaTratamiento
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="ventapaquete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="paciente", referencedColumnName="id")
     * })
     */
    private $paciente;

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empleado", referencedColumnName="id")
     * })
     */
    private $empleado;

     /**
     * @var \Tratamiento
     *
     * @ORM\ManyToOne(targetEntity="Tratamiento", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tratamiento", referencedColumnName="id")
     * })
     */
    private $tratamiento;
    
    
    /**
     * @ORM\OneToMany(targetEntity="SesionVentaTratamiento", mappedBy="personaTratamiento", cascade={"persist", "remove"})
     */
    private $sestratamiento;


    /**
     * @var float
     *
     * @ORM\Column(name="costotratamiento", type="float", nullable=false)
     */
    private $costoConsulta; 
    
    /**
     * @var integer
     *
     * @ORM\Column(name="num_sesiones", type="integer", nullable=false)
     */
    private $numSesiones;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_venta", type="datetime", nullable=false)
     */
    private $fechaVenta;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=true)
     */
    private $fechaRegistro;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cuotas", type="integer", nullable=false)
     */
    private $cuotas;
    
    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=1000, nullable=true)
     */
    private $observaciones; 
    
    /**
     * @var \Descuento
     *
     * @ORM\ManyToOne(targetEntity="Descuento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="descuento", referencedColumnName="id")
     * })
     */
    private $descuento;
    
    
    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sucursal", referencedColumnName="id")
     * })
     */
    private $sucursal;
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     *
     * @return VentaPaquete
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set paciente
     *
     * @param \DGPlusbelleBundle\Entity\Persona $paciente
     *
     * @return VentaPaquete
     */
    public function setPaciente(\DGPlusbelleBundle\Entity\Persona $paciente = null)
    {
        $this->paciente = $paciente;

        return $this;
    }

    /**
     * Get paciente
     *
     * @return \DGPlusbelleBundle\Entity\Persona
     */
    public function getPaciente()
    {
        return $this->paciente;
    }
    
    /**
     * Set fechaVenta
     *
     * @param \DateTime $fechaVenta
     *
     * @return VentaPaquete
     */
    public function setFechaVenta($fechaVenta)
    {
        $this->fechaVenta = $fechaVenta;

        return $this;
    }

    /**
     * Get fechaVenta
     *
     * @return \DateTime
     */
    public function getFechaVenta()
    {
        return $this->fechaVenta;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return VentaPaquete
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }
    
    /**
     * Set empleado
     *
     * @param \DGPlusbelleBundle\Entity\Persona $empleado
     *
     * @return VentaPaquete
     */
    public function setEmpleado(\DGPlusbelleBundle\Entity\Persona $empleado = null)
    {
        $this->empleado = $empleado;

        return $this;
    }

    /**
     * Get empleado
     *
     * @return \DGPlusbelleBundle\Entity\Persona
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Set tratamiento
     *
     * @param \DGPlusbelleBundle\Entity\Tratamiento $tratamiento
     *
     * @return PaqueteTratamiento
     */
    public function setTratamiento(\DGPlusbelleBundle\Entity\Tratamiento $tratamiento = null)
    {
        $this->tratamiento = $tratamiento;

        return $this;
    }

    /**
     * Get tratamiento
     *
     * @return \DGPlusbelleBundle\Entity\Tratamiento
     */
    public function getTratamiento()
    {
        return $this->tratamiento;
    }
    
    
    
    /**
     * Set tratamiento
     *
     * @param \DGPlusbelleBundle\Entity\Tratamiento $tratamiento
     *
     * @return PaqueteTratamiento
     */
    public function setSesTratamiento(\DGPlusbelleBundle\Entity\PersonaTratamiento $sestratamiento = null)
    {
        $this->sestratamiento = $sestratamiento;

        return $this;
    }

    /**
     * Get tratamiento
     *
     * @return \DGPlusbelleBundle\Entity\Tratamiento
     */
    public function getSesTratamiento()
    {
        return $this->sestratamiento;
    }
    
    
    
    /**
     * Set costoConsulta
     *
     * @param float $costoConsulta
     *
     * @return Consulta
     */
    public function setCostoConsulta($costoConsulta)
    {
        $this->costoConsulta = $costoConsulta;

        return $this;
    }

    /**
     * Get costoConsulta
     *
     * @return float
     */
    public function getCostoConsulta()
    {
        return $this->costoConsulta;
    }
    
    /**
     * Set numSesiones
     *
     * @param integer $numSesiones
     *
     * @return PaqueteTratamiento
     */
    public function setNumSesiones($numSesiones)
    {
        $this->numSesiones = $numSesiones;

        return $this;
    }

    /**
     * Get numSesiones
     *
     * @return integer
     */
    public function getNumSesiones()
    {
        return $this->numSesiones;
    }
    
    
     /**
     * Set cuotas
     *
     * @param integer $cuotas
     *
     * @return PaqueteTratamiento
     */
    public function setCuotas($cuotas)
    {
        $this->cuotas = $cuotas;

        return $this;
    }

    /**
     * Get cuotas
     *
     * @return integer
     */
    public function getCuotas()
    {
        return $this->cuotas;
    }
    
    public function __toString() {
    return $this->tratamiento->getNombre() ? $this->tratamiento->getNombre() : '';
    }
    
    /**
     * Set descuento
     *
     * @param \DGPlusbelleBundle\Entity\Descuento $descuento
     *
     * @return Cita
     */
    public function setDescuento(\DGPlusbelleBundle\Entity\Descuento $descuento = null)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return \DGPlusbelleBundle\Entity\Descuento
     */
    public function getDescuento()
    {
        return $this->descuento;
    }
    
     /**
     * Set sucursal
     *
     * @param \DGPlusbelleBundle\Entity\Sucursal $sucursal
     *
     * @return Cita
     */
    public function setSucursal(\DGPlusbelleBundle\Entity\Sucursal $sucursal = null)
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    /**
     * Get sucursal
     *
     * @return \DGPlusbelleBundle\Entity\Sucursal
     */
    public function getSucursal()
    {
        return $this->sucursal;
    }
}
