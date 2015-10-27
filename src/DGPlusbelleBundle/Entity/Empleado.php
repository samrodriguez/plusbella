<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Empleado
 *
 * @ORM\Table(name="empleado", indexes={@ORM\Index(name="fk_empleado_persona1_idx", columns={"persona"}), @ORM\Index(name="fk_empleado_sucursal1_idx", columns={"sucursal"})})
 * @ORM\Entity
 */
class Empleado
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
     * @ORM\Column(name="cargo", type="string", length=75, nullable=false)
     */
    private $cargo;

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="empleado", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona", referencedColumnName="id")
     * })
     */
    private $persona;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Horario", inversedBy="empleado")
     * @ORM\JoinTable(name="horario_medico",
     *   joinColumns={
     *     @ORM\JoinColumn(name="empleado", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="horario", referencedColumnName="id")
     *   }
     * )
     */
    private $horario;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tratamiento", inversedBy="empleado")
     * @ORM\JoinTable(name="medico_tratamiento",
     *   joinColumns={
     *     @ORM\JoinColumn(name="empleado", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tratamiento", referencedColumnName="id")
     *   }
     * )
     */
    private $tratamiento;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->horario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tratamiento = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set cargo
     *
     * @param string $cargo
     *
     * @return Empleado
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set foto
     *
     * @param string $foto
     *
     * @return Empleado
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set persona
     *
     * @param \DGPlusbelleBundle\Entity\Persona $persona
     *
     * @return Empleado
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

    /**
     * Set sucursal
     *
     * @param \DGPlusbelleBundle\Entity\Sucursal $sucursal
     *
     * @return Empleado
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

    /**
     * Add horario
     *
     * @param \DGPlusbelleBundle\Entity\Horario $horario
     *
     * @return Empleado
     */
    public function addHorario(\DGPlusbelleBundle\Entity\Horario $horario)
    {
        $this->horario[] = $horario;

        return $this;
    }

    /**
     * Remove horario
     *
     * @param \DGPlusbelleBundle\Entity\Horario $horario
     */
    public function removeHorario(\DGPlusbelleBundle\Entity\Horario $horario)
    {
        $this->horario->removeElement($horario);
    }

    /**
     * Get horario
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Add tratamiento
     *
     * @param \DGPlusbelleBundle\Entity\Tratamiento $tratamiento
     *
     * @return Empleado
     */
    public function addTratamiento(\DGPlusbelleBundle\Entity\Tratamiento $tratamiento)
    {
        $this->tratamiento[] = $tratamiento;

        return $this;
    }

    /**
     * Remove tratamiento
     *
     * @param \DGPlusbelleBundle\Entity\Tratamiento $tratamiento
     */
    public function removeTratamiento(\DGPlusbelleBundle\Entity\Tratamiento $tratamiento)
    {
        $this->tratamiento->removeElement($tratamiento);
    }

    /**
     * Get tratamiento
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTratamiento()
    {
        return $this->tratamiento;
    }
    
    public function __toString() {
    return $this->cargo ? $this->cargo : '';
    }
}
