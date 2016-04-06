<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ComposicionCorporal
 *
 * @ORM\Table(name="consulta_botox", indexes={@ORM\Index(name="consulta", columns={"consulta"}), @ORM\Index(name="estetica", columns={"estetica"}) })
 * @ORM\Entity
 */
class ConsultaBotox {
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
     * @ORM\Column(name="area_inyectar", type="string", length=100, nullable=false)
     */
    private $areaInyectar; 
    
    /**
     * @var integer
     *
     * @ORM\Column(name="unidades", type="integer", nullable=false)
     */
    private $unidades;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_caducidad", type="datetime", nullable=true)
     */
    private $fechaCaducidad;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="lote", type="integer", nullable=false)
     */
    private $lote;
    
    /**
     * @var string
     *
     * @ORM\Column(name="marca_producto", type="string", length=100, nullable=false)
     */
    private $marcaProducto;     
    
    /**
     * @var integer
     *
     * @ORM\Column(name="num_aplicacion", type="integer", nullable=false)
     */
    private $numAplicacion;
    
    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float", nullable=false)
     */
    private $valor; 
    
    /**
     * @var string
     *
     * @ORM\Column(name="recomendaciones", type="string", length=1000, nullable=false)
     */
    private $recomendaciones; 
    
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
     * Set areaInyectar
     *
     * @param string $areaInyectar
     *
     * @return ConsultaBotox
     */
    public function setAreaInyectar($areaInyectar)
    {
        $this->areaInyectar = $areaInyectar;

        return $this;
    }

    /**
     * Get areaInyectar
     *
     * @return string
     */
    public function getAreaInyectar()
    {
        return $this->areaInyectar;
    }
    
    /**
     * Set unidades
     *
     * @param integer $unidades
     *
     * @return ConsultaBotox
     */
    public function setUnidades($unidades)
    {
        $this->unidades = $unidades;

        return $this;
    }

    /**
     * Get unidades
     *
     * @return integer
     */
    public function getUnidades()
    {
        return $this->unidades;
    }
    
    /**
     * Set fechaCaducidad
     *
     * @param \DateTime $fechaCaducidad
     *
     * @return ConsultaBotox
     */
    public function setFechaCaducidad($fechaCaducidad)
    {
        $this->fechaCaducidad = $fechaCaducidad;

        return $this;
    }

    /**
     * Get fechaCaducidad
     *
     * @return \DateTime
     */
    public function getFechaCaducidad()
    {
        return $this->fechaCaducidad;
    }
    
    /**
     * Set lote
     *
     * @param integer $lote
     *
     * @return ConsultaBotox
     */
    public function setLote($lote)
    {
        $this->lote = $lote;

        return $this;
    }

    /**
     * Get lote
     *
     * @return integer
     */
    public function getLote()
    {
        return $this->lote;
    }
    
    /**
     * Set marcaProducto
     *
     * @param string $marcaProducto
     *
     * @return ConsultaBotox
     */
    public function setMarcaProducto($marcaProducto)
    {
        $this->marcaProducto = $marcaProducto;

        return $this;
    }

    /**
     * Get marcaProducto
     *
     * @return string
     */
    public function getMarcaProducto()
    {
        return $this->marcaProducto;
    }
    
    /**
     * Set numAplicacion
     *
     * @param integer $numAplicacion
     *
     * @return ConsultaBotox
     */
    public function setNumAplicacion($numAplicacion)
    {
        $this->numAplicacion = $numAplicacion;

        return $this;
    }

    /**
     * Get numAplicacion
     *
     * @return integer
     */
    public function getNumAplicacion()
    {
        return $this->numAplicacion;
    }
    
    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return ConsultaBotox
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }
    
    /**
     * Set recomendaciones
     *
     * @param string $recomendaciones
     *
     * @return ConsultaBotox
     */
    public function setRecomendaciones($recomendaciones)
    {
        $this->recomendaciones = $recomendaciones;

        return $this;
    }

    /**
     * Get recomendaciones
     *
     * @return string
     */
    public function getRecomendaciones()
    {
        return $this->recomendaciones;
    }
    
    /**
     * Set consulta
     *
     * @param \DGPlusbelleBundle\Entity\Consulta $consulta
     *
     * @return ConsultaBotox
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
     * @return ConsultaBotox
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
