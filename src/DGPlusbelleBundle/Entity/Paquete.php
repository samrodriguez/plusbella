<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paquete
 *
 * @ORM\Table(name="paquete")
 * @ORM\Entity
 */
class Paquete
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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @var float
     *
     * @ORM\Column(name="costo", type="float", precision=10, scale=0, nullable=false)
     */
    private $costo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tratamiento", inversedBy="paquete")
     * @ORM\JoinTable(name="paquete_tratamiento",
     *   joinColumns={
     *     @ORM\JoinColumn(name="paquete", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tratamiento", referencedColumnName="id")
     *   }
     * )
     */
    private $tratamiento;

     /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Sucursal", inversedBy="paquete")
     * @ORM\JoinTable(name="sucursal_paquete",
     *   joinColumns={
     *     @ORM\JoinColumn(name="paquete", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="sucursal", referencedColumnName="id")
     *   }
     * )
     */
    private $sucursal;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tratamiento = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sucursal = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Paquete
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
     * Set costo
     *
     * @param float $costo
     *
     * @return Paquete
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Paquete
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
     * Add tratamiento
     *
     * @param \DGPlusbelleBundle\Entity\Tratamiento $tratamiento
     *
     * @return Paquete
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

    /**
     * Add sucursal
     *
     * @param \DGPlusbelleBundle\Entity\Sucursal $sucursal
     *
     * @return Paquete
     */
    public function addSucursal(\DGPlusbelleBundle\Entity\Sucursal $sucursal)
    {
        $this->sucursal[] = $sucursal;

        return $this;
    }

    /**
     * Remove sucursal
     *
     * @param \DGPlusbelleBundle\Entity\Sucursal $sucursal
     */
    public function removeSucursal(\DGPlusbelleBundle\Entity\Sucursal $sucursal)
    {
        $this->sucursal->removeElement($sucursal);
    }

    /**
     * Get sucursal
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSucursal()
    {
        return $this->sucursal;
    }
    
    public function __toString() {
    return $this->nombre.'   $'.$this->costo;
    }
}
