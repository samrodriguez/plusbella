<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SeguimientoPaquete
 *
 * @ORM\Table(name="seguimiento_paquete", indexes={@ORM\Index(name="id_venta_paquete", columns={"id_venta_paquete"})})
 * @ORM\Entity
 */
class SeguimientoPaquete {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_sesion", type="integer", nullable=false)
     */
    private $numSesion;
    
    /**
     * @var \VentaPaquete
     *
     * @ORM\ManyToOne(targetEntity="VentaPaquete", inversedBy="seguimiento_paquete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_venta_paquete", referencedColumnName="id")
     * })
     */
    private $idVentaPaquete;
    
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
     * Set numSesion
     *
     * @param integer $numSesion
     *
     * @return SeguimientoPaquete
     */
    public function setNumSesion($numSesion)
    {
        $this->numSesion = $numSesion;

        return $this;
    }

    /**
     * Get numSesion
     *
     * @return integer
     */
    public function getNumSesion()
    {
        return $this->numSesion;
    }
    
    /**
     * Set idVentaPaquete
     *
     * @param \DGPlusbelleBundle\Entity\VentaPaquete $idVentaPaquete
     *
     * @return SeguimientoPaquete
     */
    public function setVentaPaquete(\DGPlusbelleBundle\Entity\VentaPaquete $idVentaPaquete = null)
    {
        $this->idVentaPaquete = $idVentaPaquete;

        return $this;
    }

    /**
     * Get idVentaPaquete
     *
     * @return \DGPlusbelleBundle\Entity\VentaPaquete
     */
    public function getVentaPaquete()
    {
        return $this->idVentaPaquete;
    }
    
}
