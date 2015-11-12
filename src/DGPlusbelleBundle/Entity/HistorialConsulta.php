<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImagenTratamiento
 *
 * @ORM\Table(name="historial_consulta", indexes={@ORM\Index(name="consulta", columns={"consulta"}), @ORM\Index(name="fk_detallehistorial_plantilla1", columns={"detalle_plantilla"})})
 * @ORM\Entity
 */
class HistorialConsulta {
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
     * @ORM\Column(name="valor_detalle", type="string", length=255, nullable=false)
     */
    private $valorDetalle;
    
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
     * @var \DetallePlantilla
     *
     * @ORM\ManyToOne(targetEntity="DetallePlantilla")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="detalle_plantilla", referencedColumnName="id")
     * })
     */
    private $detallePlantilla;
    
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
     * Set valorDetalle
     *
     * @param string $valorDetalle
     *
     * @return HistorialConsulta
     */
    public function setValorDetalle($valorDetalle)
    {
        $this->valorDetalle = $valorDetalle;

        return $this;
    }

    /**
     * Get valorDetalle
     *
     * @return string
     */
    public function getValorDetalle()
    {
        return $this->valorDetalle;
    }

    /**
     * Set consulta
     *
     * @param \DGPlusbelleBundle\Entity\Consulta $consulta
     *
     * @return HistorialConsulta
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
     * @param \DGPlusbelleBundle\Entity\DetallePlantilla $detallePlantilla
     *
     * @return HistorialConsulta
     */
    public function setDetallePlantilla(\DGPlusbelleBundle\Entity\DetallePlantilla $detallePlantilla = null)
    {
        $this->detallePlantilla = $detallePlantilla;

        return $this;
    }

    /**
     * Get detallePlantilla
     *
     * @return \DGPlusbelleBundle\Entity\DetallePlantilla
     */
    public function getDetallePlantilla()
    {
        return $this->detallePlantilla;
    }
    
    public function __toString() {
        return $this->valorDetalle ? $this->valorDetalle : '';
    }
}
