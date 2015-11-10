<?php
    
namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetallePlantilla
 *
 * @ORM\Table(name="detalle_plantilla", indexes={@ORM\Index(name="id_plantilla", columns={"plantilla"})})
 * @ORM\Entity
 */
class DetallePlantilla {
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
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=200, nullable=true)
     */
    private $descripcion;

    

    /**
     * @var \Plantilla
     *
     * @ORM\ManyToOne(targetEntity="Plantilla")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plantilla", referencedColumnName="id")
     * })
     */
    private $plantilla;
    
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
     * Set descripcion
     *
     * @param string $descripcion
     * 
     * @return DetallePlantilla
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
        
    /**
     * Set plantilla
     *
     * @param \DGPlusbelleBundle\Entity\Plantilla $plantilla
     * 
     * @return DetallePlantilla
     */
    public function setPlantilla(\DGPlusbelleBundle\Entity\Plantilla $plantilla = null)
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return \DGPlusbelleBundle\Entity\Plantilla
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }

     public function __toString() {
        return $this->nombre;
    }
}
