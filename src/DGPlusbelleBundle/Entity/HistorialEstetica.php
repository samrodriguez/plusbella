<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialEstetica
 *
 * @ORM\Table(name="historial_estetica", indexes={@ORM\Index(name="consulta", columns={"consulta"}), @ORM\Index(name="detalle_estetica", columns={"detalle_estetica"})})
 * @ORM\Entity
 */
class HistorialEstetica {
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
     * @ORM\Column(name="valor", type="string", length=750, nullable=false)
     */
    private $valor;
    
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
     * @var \DetalleEstetica
     *
     * @ORM\ManyToOne(targetEntity="OpcionesDetalleEstetica")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="detalle_estetica", referencedColumnName="id")
     * })
     */
    private $detalleEstetica;
    
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
     * Set valor
     *
     * @param string $valor
     *
     * @return HistorialEstetica
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }
    
    /**
     * Get valorDetalle
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set consulta
     *
     * @param \DGPlusbelleBundle\Entity\Consulta $consulta
     *
     * @return HistorialEstetica
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
     * Set detallePlantilla
     *
     * @param \DGPlusbelleBundle\Entity\OpcionesDetalleEstetica $detalleEstetica
     *
     * @return HistorialEstetica
     */
    public function setDetalleEstetica(\DGPlusbelleBundle\Entity\OpcionesDetalleEstetica $detalleEstetica = null)
    {
        $this->detalleEstetica = $detalleEstetica;

        return $this;
    }

    /**
     * Get detalleEstetica
     *
     * @return \DGPlusbelleBundle\Entity\OpcionesDetalleEstetica
     */
    public function getDetalleEstetica()
    {
        return $this->detalleEstetica;
    }
    
    public function __toString() {
        return $this->valor ? $this->valor : '';
    }
}
