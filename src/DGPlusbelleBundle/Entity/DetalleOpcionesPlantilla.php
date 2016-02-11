<?php
    
namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetallePlantilla
 *
 * @ORM\Table(name="detalle_opciones_plantilla", indexes={@ORM\Index(name="id_plantilla", columns={"plantilla"})})
 * @ORM\Entity
 */
class DetalleOpcionesPlantilla {
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
     * @ORM\Column(name="nombre", type="string", length=75, nullable=false)
     */
    private $nombre;
    
   
    /**
     * @var \Plantilla
     *
     * @ORM\ManyToOne(targetEntity="DetallePlantilla")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_detalle_plantilla", referencedColumnName="id")
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
     * Set nombre
     *
     * @param string $nombre
     * 
     * @return DetallePlantilla
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    
    
    
    /**
     * Set plantilla
     *
     * @param \DGPlusbelleBundle\Entity\Plantilla $detallePlantilla
     * 
     * @return DetallePlantilla
     */
    public function setDetallePlantilla(\DGPlusbelleBundle\Entity\DetallePlantilla $detallePlantilla = null)
    {
        $this->detallePlantilla = $detallePlantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return \DGPlusbelleBundle\Entity\DetallePlantilla
     */
    public function getDetallePlantilla()
    {
        return $this->detallePlantilla;
    }

     public function __toString() {
        return $this->nombre;
    }
}
