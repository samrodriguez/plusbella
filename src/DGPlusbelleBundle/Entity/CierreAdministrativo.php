<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CierreAdministrativo
 *
 * @ORM\Entity
 * @ORM\Table(name="cierre_administrativo")
 */
class CierreAdministrativo
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
     * @var \DateTime
     *
     * @ORM\Column(name="horaInicio", type="time", nullable=false)
     */
    private $horaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaFin", type="time", nullable=false)
     */
    private $horaFin;

    /**
     * @var string
     *
     * @ORM\Column(name="motivo", type="string", nullable=false)
     */
    private $motivo;
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    
    /**
     * @var \Empleado
     *
     * @ORM\ManyToOne(targetEntity="Empleado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_empleado", referencedColumnName="id")
     * })
     */
    private $empleado;
    
    
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
     * Set horaInicio
     *
     * @param \DateTime $horaInicio
     *
     * @return Cita
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;
        return $this;
    }

    /**
     * Get horaFin
     *
     * @return \DateTime
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }
    

    /**
     * Set horaFin
     *
     * @param \DateTime $horaFin
     *
     * @return Cita
     */
    public function setHoraFin($horaFin)
    {
        $this->horaFin = $horaFin;

        return $this;
    }

    /**
     * Get horaFin
     *
     * @return \DateTime
     */
    public function getHoraFin()
    {
        return $this->horaFin;
    }
    
    /**
     * Set motivo
     *
     * @param string $motivo
     *
     * @return Persona
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha= $fecha;
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha(){
        return $this->fecha;
    }
    
    
    
    /**
     * Set empleado
     *
     * @param \DGPlusbelleBundle\Entity\Empleado $empleado
     *
     * @return Cita
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
    
    
    
    public function __toString() {
        return $this->motivo;
    }
}
