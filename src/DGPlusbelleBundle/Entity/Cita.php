<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cita
 *
 * @ORM\Table(name="cita", indexes={ @ORM\Index(name="fk_cita_paciente1_idx", columns={"paciente"}), @ORM\Index(name="fk_cita_empleado1_idx", columns={"empleado"}), @ORM\Index(name="fk_cita_tratamiento1_idx", columns={"tratamiento"})})
 * @ORM\Entity(repositoryClass="DGPlusbelleBundle\Repository\CitaRepository")
 */
class Cita
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
     * @ORM\Column(name="fecha_cita", type="date", nullable=false)
     */
    private $fechaCita;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_cita", type="time", nullable=false)
     */
    private $horaCita;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_fin", type="time", nullable=false)
     */
     // private $horaFin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=false)
     */
    private $fechaRegistro = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", nullable=false)
     */
    private $estado;

    

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
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sucursal", referencedColumnName="id")
     * })
     */
    private $sucursal;
    

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
     * @var \Tratamiento
     *
     * @ORM\ManyToOne(targetEntity="Tratamiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tratamiento", referencedColumnName="id")
     * })
     */
    private $tratamiento;



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
     * Set fechaCita
     *
     * @param \DateTime $fechaCita
     *
     * @return Cita
     */
    public function setFechaCita($fechaCita)
    {
        $this->fechaCita = $fechaCita;

        return $this;
    }

    /**
     * Get fechaCita
     *
     * @return \DateTime
     */
    public function getFechaCita()
    {
        return $this->fechaCita;
    }

    /**
     * Set horaCita
     *
     * @param \DateTime $horaCita
     *
     * @return Cita
     */
    public function setHoraCita($horaCita)
    {
        $this->horaCita = $horaCita;

        return $this;
    }

    /**
     * Get horaCita
     *
     * @return \DateTime
     */
    public function getHoraCita()
    {
        return $this->horaCita;
    }

    /**
     * Set horaFin
     *
     * @param \DateTime $horaFin
     *
     * @return Cita
     */
    /* public function setHoraFin($horaFin)
    {
        $this->horaFin = $horaFin;

        return $this;
    }*/

    /**
     * Get horaFin
     *
     * @return \DateTime
     */
    /*   public function getHoraFin()
    {
        return $this->horaFin;
    }  */

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     *
     * @return Cita
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
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Cita
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
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

    

    /**
     * Set paciente
     *
     * @param \DGPlusbelleBundle\Entity\Paciente $paciente
     *
     * @return Cita
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
     * Set tratamiento
     *
     * @param \DGPlusbelleBundle\Entity\Tratamiento $tratamiento
     *
     * @return Cita
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
     * Set sucursal
     *
     * @param \DGPlusbelleBundle\Entity\Sucursal $sucursal
     *
     * @return VentaPaquete
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
