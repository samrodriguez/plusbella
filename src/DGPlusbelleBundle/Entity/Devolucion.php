<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devolucion
 *
 * @ORM\Table(name="devolucion", indexes={@ORM\Index(name="fk_devolucion_paciente1_idx", columns={"paciente"}), @ORM\Index(name="fk_devolucion_empleado1_idx", columns={"empleado"})})
 * @ORM\Entity
 */
class Devolucion
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
     * @var float
     *
     * @ORM\Column(name="monto", type="float", precision=10, scale=0, nullable=false)
     */
    private $monto;

    /**
     * @var string
     *
     * @ORM\Column(name="motivo", type="string", length=200, nullable=false)
     */
    private $motivo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_devolucion", type="datetime", nullable=false)
     */
    private $fechaDevolucion = 'CURRENT_TIMESTAMP';

    /**
     * @var \Empleado
     *
     * @ORM\ManyToOne(targetEntity="Empleado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empleado", referencedColumnName="id")
     * })
     */
    private $empleado;

    /**
     * @var \Paciente
     *
     * @ORM\ManyToOne(targetEntity="Paciente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="paciente", referencedColumnName="id")
     * })
     */
    private $paciente;
    
    
    /**
     * @var \Ventapqeute
     *
     * @ORM\ManyToOne(targetEntity="VentaPaquete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="venta_paquete", referencedColumnName="id")
     * })
     */
    private $ventapaquete;
    
    
    /**
     * @var \PersonaTratamiento
     *
     * @ORM\ManyToOne(targetEntity="PersonaTratamiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona_tratamiento", referencedColumnName="id")
     * })
     */
    private $personatratamiento;


    private $flagDevolucion;
    

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
     * Set monto
     *
     * @param float $monto
     *
     * @return Devolucion
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return float
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     *
     * @return Devolucion
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set fechaDevolucion
     *
     * @param \DateTime $fechaDevolucion
     *
     * @return Devolucion
     */
    public function setFechaDevolucion($fechaDevolucion)
    {
        $this->fechaDevolucion = $fechaDevolucion;

        return $this;
    }

    /**
     * Get fechaDevolucion
     *
     * @return \DateTime
     */
    public function getFechaDevolucion()
    {
        return $this->fechaDevolucion;
    }

    /**
     * Set empleado
     *
     * @param \DGPlusbelleBundle\Entity\Empleado $empleado
     *
     * @return Devolucion
     */
    public function setEmpleado(\DGPlusbelleBundle\Entity\Empleado $empleado = null)
    {
        $this->empleado = $empleado;

        return $this;
    }

    /**
     * Get empleado
     *
     * @return \DGPlusbelleBundle\Entity\Empleado
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Set paciente
     *
     * @param \DGPlusbelleBundle\Entity\Paciente $paciente
     *
     * @return Devolucion
     */
    public function setPaciente(\DGPlusbelleBundle\Entity\Paciente $paciente = null)
    {
        $this->paciente = $paciente;

        return $this;
    }

    /**
     * Get paciente
     *
     * @return \DGPlusbelleBundle\Entity\Paciente
     */
    public function getPaciente()
    {
        return $this->paciente;
    }
    
    
    
    /**
     * Set ventapaquete
     *
     * @param \DGPlusbelleBundle\Entity\VentaPaquete $ventapaquete
     *
     * @return Devolucion
     */
    public function setVentaPaquete(\DGPlusbelleBundle\Entity\VentaPaquete $ventapaquete = null)
    {
        $this->ventapaquete = $ventapaquete;

        return $this;
    }

    /**
     * Get ventapaquete
     *
     * @return \DGPlusbelleBundle\Entity\VentaPaquete
     */
    public function getVentaPaquete()
    {
        return $this->ventapaquete;
    }
    
    
    /**
     * Set personatratamiento
     *
     * @param \DGPlusbelleBundle\Entity\PersonaTratamiento $personatratamiento
     *
     * @return Devolucion
     */
    public function setPersonaTratamiento(\DGPlusbelleBundle\Entity\PersonaTratamiento $personatratamiento = null)
    {
        $this->personatratamiento = $personatratamiento;

        return $this;
    }

    /**
     * Get personatratamiento
     *
     * @return \DGPlusbelleBundle\Entity\PersonaTratamiento
     */
    public function getPersonaTratamiento()
    {
        return $this->personatratamiento;
    }
    
    
    public function setFlagDevolucion($flagDevolucion)
    {
        $this->flagDevolucion = $flagDevolucion;

        return $this;
    }

    
    public function getFlagDevolucion()
    {
        return $this->flagDevolucion;
    }
}
