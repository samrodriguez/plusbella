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
     * @ORM\ManyToOne(targetEntity="Tratamiento", inversedBy="placas", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tratamiento", referencedColumnName="id")
     * })
     */
    private $tratamiento;


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
     * @ORM\Column(name="fecha_venta", type="date", nullable=false)
     */
    private $fechaVenta;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=true)
     */
    private $fechaRegistro;

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
    
    public function __toString() {
    return $this->tratamiento->getNombre() ? $this->tratamiento->getNombre() : '';
    }
}
