<?php
    
namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpcionesDetalleEstetica
 *
 * @ORM\Table(name="opciones_detalle_estetica", indexes={@ORM\Index(name="detalle_estetica", columns={"detalle_estetica"})})
 * @ORM\Entity
 */
class OpcionesDetalleEstetica {
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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;
    
   
    /**
     * @var \DetalleEstetica
     *
     * @ORM\ManyToOne(targetEntity="DetalleEstetica")
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
     * Set nombre
     *
     * @param string $nombre
     * 
     * @return OpcionesDetalleEstetica
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
     * Set detalleEstetica
     *
     * @param \DGPlusbelleBundle\Entity\DetalleEstetica $detalleEstetica
     * 
     * @return OpcionesDetalleEstetica
     */
    public function setDetalleEstetica(\DGPlusbelleBundle\Entity\DetalleEstetica $detalleEstetica = null)
    {
        $this->detalleEstetica = $detalleEstetica;

        return $this;
    }

    /**
     * Get detalleEstetica
     *
     * @return \DGPlusbelleBundle\Entity\DetalleEstetica
     */
    public function getDetalleEstetica()
    {
        return $this->detalleEstetica;
    }

     public function __toString() {
        return $this->nombre;
    }
}
