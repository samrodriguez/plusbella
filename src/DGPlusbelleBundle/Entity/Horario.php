<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Horario
 *
 * @ORM\Table(name="horario")
 * @ORM\Entity
 */
class Horario
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
     * @ORM\Column(name="dia_horario", type="date", nullable=false)
     */
    private $diaHorario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_inicio", type="time", nullable=false)
     */
    private $horaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horario_fin", type="time", nullable=false)
     */
    private $horarioFin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Empleado", mappedBy="horario")
     */
    private $empleado;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleado = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set diaHorario
     *
     * @param \DateTime $diaHorario
     *
     * @return Horario
     */
    public function setDiaHorario($diaHorario)
    {
        $this->diaHorario = $diaHorario;

        return $this;
    }

    /**
     * Get diaHorario
     *
     * @return \DateTime
     */
    public function getDiaHorario()
    {
        return $this->diaHorario;
    }

    /**
     * Set horaInicio
     *
     * @param \DateTime $horaInicio
     *
     * @return Horario
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    /**
     * Get horaInicio
     *
     * @return \DateTime
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * Set horarioFin
     *
     * @param \DateTime $horarioFin
     *
     * @return Horario
     */
    public function setHorarioFin($horarioFin)
    {
        $this->horarioFin = $horarioFin;

        return $this;
    }

    /**
     * Get horarioFin
     *
     * @return \DateTime
     */
    public function getHorarioFin()
    {
        return $this->horarioFin;
    }

    /**
     * Add empleado
     *
     * @param \DGPlusbelleBundle\Entity\Empleado $empleado
     *
     * @return Horario
     */
    public function addEmpleado(\DGPlusbelleBundle\Entity\Empleado $empleado)
    {
        $this->empleado[] = $empleado;

        return $this;
    }

    /**
     * Remove empleado
     *
     * @param \DGPlusbelleBundle\Entity\Empleado $empleado
     */
    public function removeEmpleado(\DGPlusbelleBundle\Entity\Empleado $empleado)
    {
        $this->empleado->removeElement($empleado);
    }

    /**
     * Get empleado
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }
}
