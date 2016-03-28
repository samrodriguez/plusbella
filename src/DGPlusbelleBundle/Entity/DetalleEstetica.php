<?php
    
namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleEstetica
 *
 * @ORM\Table(name="detalle_estetica", indexes={@ORM\Index(name="estetica", columns={"estetica"})})
 * @ORM\Entity
 */
class DetalleEstetica {
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
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false)
     */
    private $nombre;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

    

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
     * Set nombre
     *
     * @param string $nombre
     * 
     * @return DetalleEstetica
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
     * Set estado
     *
     * @param boolean $estado
     *
     * @return DetalleEstetica
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
     * Set estetica
     *
     * @param \DGPlusbelleBundle\Entity\Estetica $estetica
     * 
     * @return DetalleEstetica
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

    public function __toString() 
    {
        return $this->nombre;
    }
}
