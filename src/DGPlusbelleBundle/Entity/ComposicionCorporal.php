<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ComposicionCorporal
 *
 * @ORM\Table(name="composicion_corporal", indexes={@ORM\Index(name="consulta", columns={"consulta"}), @ORM\Index(name="estetica", columns={"estetica"}) })
 * @ORM\Entity
 */
class ComposicionCorporal {
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
     * @ORM\Column(name="peso", type="float", nullable=false)
     */
    private $peso; 
    
    /**
     * @var float
     *
     * @ORM\Column(name="grasa_corporal", type="float", nullable=false)
     */
    private $grasaCorporal; 
    
    /**
     * @var float
     *
     * @ORM\Column(name="agua_corporal", type="float", nullable=false)
     */
    private $aguaCorporal; 
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;
    
    /**
     * @var string
     *
     * @ORM\Column(name="masa_musculo", type="string", length=200, nullable=false)
     */
    private $masaMusculo;     
    
    /**
     * @var integer
     *
     * @ORM\Column(name="valoracion_fisica", type="integer", nullable=false)
     */
    private $valoracionFisica;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="edad_metabolica", type="integer", nullable=false)
     */
    private $edadMetabolica;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="dci_bmr", type="integer", nullable=false)
     */
    private $dciBmr;
    
    /**
     * @var float
     *
     * @ORM\Column(name="masa_osea", type="float", nullable=false)
     */
    private $masaOsea; 
    
    /**
     * @var float
     *
     * @ORM\Column(name="grasa_visceral", type="float", nullable=false)
     */
    private $grasaVisceral; 
    
    /**
     * @var \Consulta
     *
     * @ORM\ManyToOne(targetEntity="Consulta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="consulta", referencedColumnName="id")
     * })
     */
    private $consulta;
    
    /**
     * @var \Estetica
     *
     * @ORM\ManyToOne(targetEntity="Estetica")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estetica", referencedColumnName="id")
     * })
     */
    private $estetica;
    
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
     * @return ComposicionCorporal
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return float
     */
    public function getPeso()
    {
        return $this->peso;
    }
    
    /**
     * Set grasaCorporal
     *
     * @param float $grasaCorporal
     *
     * @return ComposicionCorporal
     */
    public function setGrasaCorporal($grasaCorporal)
    {
        $this->grasaCorporal = $grasaCorporal;

        return $this;
    }

    /**
     * Get grasaCorporal
     *
     * @return float
     */
    public function getGrasaCorporal()
    {
        return $this->grasaCorporal;
    }
    
    /**
     * Set aguaCorporal
     *
     * @param float $aguaCorporal
     *
     * @return ComposicionCorporal
     */
    public function setAguaCorporal($aguaCorporal)
    {
        $this->aguaCorporal = $aguaCorporal;

        return $this;
    }

    /**
     * Get aguaCorporal
     *
     * @return float
     */
    public function getAguaCorporal()
    {
        return $this->aguaCorporal;
    }
    
    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return ComposicionCorporal
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }
    
    /**
     * Set masaMusculo
     *
     * @param string $masaMusculo
     *
     * @return ComposicionCorporal
     */
    public function setMasaMusculo($masaMusculo)
    {
        $this->masaMusculo = $masaMusculo;

        return $this;
    }

    /**
     * Get masaMusculo
     *
     * @return string
     */
    public function getMasaMusculo()
    {
        return $this->masaMusculo;
    }
    
    /**
     * Set valoracionFisica
     *
     * @param integer $valoracionFisica
     *
     * @return ComposicionCorporal
     */
    public function setValoracionFisica($valoracionFisica)
    {
        $this->valoracionFisica = $valoracionFisica;

        return $this;
    }

    /**
     * Get valoracionFisica
     *
     * @return integer
     */
    public function getValoracionFisica()
    {
        return $this->valoracionFisica;
    }
    
    /**
     * Set edadMetabolica
     *
     * @param integer $edadMetabolica
     *
     * @return ComposicionCorporal
     */
    public function setEdadMetabolica($edadMetabolica)
    {
        $this->edadMetabolica = $edadMetabolica;

        return $this;
    }

    /**
     * Get edadMetabolica
     *
     * @return integer
     */
    public function getEdadMetabolica()
    {
        return $this->edadMetabolica;
    }
    
    /**
     * Set dciBmr
     *
     * @param integer $dciBmr
     *
     * @return ComposicionCorporal
     */
    public function setDciBmr($dciBmr)
    {
        $this->dciBmr = $dciBmr;

        return $this;
    }

    /**
     * Get dciBmr
     *
     * @return integer
     */
    public function getdciBmr()
    {
        return $this->dciBmr;
    }
    
    /**
     * Set masaOsea
     *
     * @param float $masaOsea
     *
     * @return ComposicionCorporal
     */
    public function setMasaOsea($masaOsea)
    {
        $this->masaOsea = $masaOsea;

        return $this;
    }

    /**
     * Get masaOsea
     *
     * @return float
     */
    public function getMasaOsea()
    {
        return $this->masaOsea;
    }
    
    /**
     * Set grasaVisceral
     *
     * @param float $grasaVisceral
     *
     * @return ComposicionCorporal
     */
    public function setGrasaVisceral($grasaVisceral)
    {
        $this->grasaVisceral = $grasaVisceral;

        return $this;
    }

    /**
     * Get grasaVisceral
     *
     * @return float
     */
    public function getGrasaVisceral()
    {
        return $this->grasaVisceral;
    }
    
    /**
     * Set consulta
     *
     * @param \DGPlusbelleBundle\Entity\Consulta $consulta
     *
     * @return ComposicionCorporal
     */
    public function setConsulta(\DGPlusbelleBundle\Entity\Consulta $consulta = null)
    {
        $this->consulta = $consulta;

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
    
    /**
     * Set estetica
     *
     * @param \DGPlusbelleBundle\Entity\Estetica $estetica
     *
     * @return ComposicionCorporal
     */
    public function setEstetica(\DGPlusbelleBundle\Entity\Estetica $estetica = null)
    {
        $this->estetica = $estetica;

        return $this;
    }

    /**
     * Get estetica
     *
     * @return \DGPlusbelleBundle\Entity\Estetica
     */
    public function getEstetica()
    {
        return $this->estetica;
    }
}
