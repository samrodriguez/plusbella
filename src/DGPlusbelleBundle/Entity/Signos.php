<?php

namespace DGPlusbelleBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Rol
 *
 * @ORM\Table(name="signos")
 * @ORM\Entity
 */
class Signos
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
     * @ORM\Column(name="peso", type="float", length=30, nullable=false)
     */
    private $peso;
    
    /**
     * @var int
     *
     * @ORM\Column(name="talla", type="integer", length=30, nullable=false)
     */
    private $talla;
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="frec_respiratoria", type="integer", length=30, nullable=false)
     */
    private $frecRespiratoria;
    
    /**
     * @var int
     *
     * @ORM\Column(name="presion_arterial_sistolica", type="integer", length=30, nullable=false)
     */
    private $presionSistolica;
    
    /**
     * @var int
     *
     * @ORM\Column(name="presion_arterial_diastolica", type="integer", length=30, nullable=false)
     */
    private $presionDiastolica;
    
    /**
     * @var float
     *
     * @ORM\Column(name="temperatura", type="float", length=30, nullable=false)
     */
    private $temperatura;
    
    /**
     * @var int
     *
     * @ORM\Column(name="frec_cardiaca", type="integer", length=30, nullable=false)
     */
    private $frecCardiaca;

    
    /**
     * @var \Paciente
     *
     * @ORM\ManyToOne(targetEntity="Consulta", inversedBy="consulta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_paciente", referencedColumnName="id")
     * })
     */
    private $consulta;
    
   
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
     * Set peso
     *
     * @param float $peso
     *
     * @return float
     */
    public function setPeso($peso)
    {
        $this->peso= $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return int
     */
    public function getPeso()
    {
        return $this->peso;
    }
    
    
    /**
     * Set talla
     *
     * @param int $talla
     *
     * @return int
     */
    public function setTalla($talla)
    {
        $this->talla = $talla;

        return $this;
    }

    /**
     * Get talla
     *
     * @return int
     */
    public function getTalla()
    {
        return $this->talla;
    }
    
    
    /**
     * Set frecRespiratoria
     *
     * @param int $frecRespiratoria
     *
     * @return int
     */
    public function setFrecRespiratotira($frecRespiratoria)
    {
        $this->frecRespiratoria = $frecRespiratoria;

        return $this;
    }

    /**
     * Get talla
     *
     * @return int
     */
    public function getFrecRespiratoria()
    {
        return $this->frecRespiratoria;
    }
    
    
    /**
     * Set presionSistolica
     *
     * @param int $presionSistolica
     *
     * @return int
     */
    public function setPresionSistolica($presionSistolica)
    {
        $this->presionSistolica = $presionSistolica;

        return $this;
    }

    /**
     * Get talla
     *
     * @return int
     */
    public function getPresionSistolica()
    {
        return $this->presionSistolica;
    }
    
    
    /**
     * Set presionDiastolica
     *
     * @param int presionDiastolica
     *
     * @return int
     */
    public function setPresionDiastolica($presionDiastolica)
    {
        $this->presionDiastolica = $presionDiastolica;

        return $this;
    }

    /**
     * Get presionDiastolica
     *
     * @return int
     */
    public function getPresionDiastolica()
    {
        return $this->presionDiastolica;
    }
    
    
    /**
     * Set temperatura
     *
     * @param float temperatura
     *
     * @return float
     */
    public function setTemperatura($temperatura)
    {
        $this->temperatura = $temperatura;

        return $this;
    }

    /**
     * Get temperatura
     *
     * @return float
     */
    public function getTemperatura()
    {
        return $this->temperatura;
    }

    
    /**
     * Set frecCardiaca
     *
     * @param int $frecCardiaca
     *
     * @return int
     */
    public function setFrecCardiaca($frecCardiaca)
    {
        $this->frecCardiaca= $frecCardiaca;

        return $this;
    }

    /**
     * Get frecCardiaca
     *
     * @return int
     */
    public function getFrecCardiaca()
    {
        return $this->frecCardiaca;
    }
    
    
    /**
     * Set consulta
     *
     * @param \DGPlusbelleBundle\Entity\Paciente $paciente
     *
     * @return Consulta
     */
    public function setConsulta(\DGPlusbelleBundle\Entity\Consulta $consulta= null)
    {
        $this->consulta= $consulta;

        return $this;
    }

    /**
     * Get consulta
     *
     * @return \DGPlusbelleBundle\Entity\Consulta
     */
    public function getConsulta()
    {
        return $this->consulta;
    }
   
}
