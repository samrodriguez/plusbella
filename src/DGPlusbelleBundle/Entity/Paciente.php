<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paciente
 *
 * @ORM\Table(name="paciente", uniqueConstraints={@ORM\UniqueConstraint(name="dui_paciente_UNIQUE", columns={"dui"})}, indexes={@ORM\Index(name="fk_paciente_persona1_idx", columns={"persona"})})
 * @ORM\Entity
 */
class Paciente
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
     * @var string
     *
     * @ORM\Column(name="dui", type="string", length=10, nullable=false)
     */
    private $dui;

    /**
     * @var string
     *
     * @ORM\Column(name="estado_civil", type="string", length=30, nullable=false)
     */
    private $estadoCivil;

    /**
     * @var string
     *
     * @ORM\Column(name="sexo", type="string", length=1, nullable=false)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="ocupacion", type="string", length=75, nullable=false)
     */
    private $ocupacion;

    /**
     * @var string
     *
     * @ORM\Column(name="lugar_trabajo", type="string", length=200, nullable=true)
     */
    private $lugarTrabajo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=false)
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="referido_por", type="string", length=100, nullable=false)
     */
    private $referidoPor;

    /**
     * @var string
     *
     * @ORM\Column(name="persona_emergencia", type="string", length=100, nullable=true)
     */
    private $personaEmergencia;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_emergencia", type="string", length=12, nullable=false)
     */
    private $telefonoEmergencia;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="paciente", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona", referencedColumnName="id")
     * })
     */
    private $persona;



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
     * Set dui
     *
     * @param string $dui
     *
     * @return Paciente
     */
    public function setDui($dui)
    {
        $this->dui = $dui;

        return $this;
    }

    /**
     * Get dui
     *
     * @return string
     */
    public function getDui()
    {
        return $this->dui;
    }

    /**
     * Set estadoCivil
     *
     * @param string $estadoCivil
     *
     * @return Paciente
     */
    public function setEstadoCivil($estadoCivil)
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    /**
     * Get estadoCivil
     *
     * @return string
     */
    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Paciente
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set ocupacion
     *
     * @param string $ocupacion
     *
     * @return Paciente
     */
    public function setOcupacion($ocupacion)
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    /**
     * Get ocupacion
     *
     * @return string
     */
    public function getOcupacion()
    {
        return $this->ocupacion;
    }

    /**
     * Set lugarTrabajo
     *
     * @param string $lugarTrabajo
     *
     * @return Paciente
     */
    public function setLugarTrabajo($lugarTrabajo)
    {
        $this->lugarTrabajo = $lugarTrabajo;

        return $this;
    }

    /**
     * Get lugarTrabajo
     *
     * @return string
     */
    public function getLugarTrabajo()
    {
        return $this->lugarTrabajo;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return Paciente
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set referidoPor
     *
     * @param string $referidoPor
     *
     * @return Paciente
     */
    public function setReferidoPor($referidoPor)
    {
        $this->referidoPor = $referidoPor;

        return $this;
    }

    /**
     * Get referidoPor
     *
     * @return string
     */
    public function getReferidoPor()
    {
        return $this->referidoPor;
    }

    /**
     * Set personaEmergencia
     *
     * @param string $personaEmergencia
     *
     * @return Paciente
     */
    public function setPersonaEmergencia($personaEmergencia)
    {
        $this->personaEmergencia = $personaEmergencia;

        return $this;
    }

    /**
     * Get personaEmergencia
     *
     * @return string
     */
    public function getPersonaEmergencia()
    {
        return $this->personaEmergencia;
    }

    /**
     * Set telefonoEmergencia
     *
     * @param string $telefonoEmergencia
     *
     * @return Paciente
     */
    public function setTelefonoEmergencia($telefonoEmergencia)
    {
        $this->telefonoEmergencia = $telefonoEmergencia;

        return $this;
    }

    /**
     * Get telefonoEmergencia
     *
     * @return string
     */
    public function getTelefonoEmergencia()
    {
        return $this->telefonoEmergencia;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Paciente
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
     * Set persona
     *
     * @param \DGPlusbelleBundle\Entity\Persona $persona
     *
     * @return Paciente
     */
    public function setPersona(\DGPlusbelleBundle\Entity\Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \DGPlusbelleBundle\Entity\Persona
     */
    public function getPersona()
    {
        return $this->persona;
    }
}
