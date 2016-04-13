<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleVentaPaquete
 *
 * @ORM\Table(name="detalle_venta_paquete", indexes={@ORM\Index(name="venta_paquete", columns={"venta_paquete"}), @ORM\Index(name="tratamiento", columns={"tratamiento"})})
 * @ORM\Entity
 * 
 */
class DetalleVentaPaquete {
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
     * @ORM\Column(name="num_sesiones", type="integer", nullable=false)
     */
    private $numSesiones;

    /**
     * @var \Paquete
     *
     * @ORM\ManyToOne(targetEntity="VentaPaquete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="venta_paquete", referencedColumnName="id")
     * })
     */
    private $ventaPaquete;

    /**
     * @var \Tratamiento
     *
     * @ORM\ManyToOne(targetEntity="Tratamiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tratamiento", referencedColumnName="id")
     * })
     */
    private $tratamiento;

        
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
     * Set numSesiones
     *
     * @param integer $numSesiones
     *
     * @return DetalleVentaPaquete
     */
    public function setNumSesiones($numSesiones)
    {
        $this->numSesiones = $numSesiones;

        return $this;
    }

    /**
     * Get numSesiones
     *
     * @return integer
     */
    public function getNumSesiones()
    {
        return $this->numSesiones;
    }

    /**
     * Set ventaPaquete
     *
     * @param \DGPlusbelleBundle\Entity\VentaPaquete $ventaPaquete
     *
     * @return DetalleVentaPaquete
     */
    public function setVentaPaquete(\DGPlusbelleBundle\Entity\VentaPaquete $ventaPaquete = null)
    {
        $this->ventaPaquete = $ventaPaquete;

        return $this;
    }

    /**
     * Get ventaPaquete
     *
     * @return \DGPlusbelleBundle\Entity\VentaPaquete
     */
    public function getVentaPaquete()
    {
        return $this->ventaPaquete;
    }

    /**
     * Set tratamiento
     *
     * @param \DGPlusbelleBundle\Entity\Tratamiento $tratamiento
     *
     * @return DetalleVentaPaquete
     */
    public function setTratamiento(\DGPlusbelleBundle\Entity\Tratamiento $tratamiento = null)
    {
        $this->tratamiento = $tratamiento;

        return $this;
    }

    /**
     * Get tratamiento
     *
     * @return \DGPlusbelleBundle\Entity\Tratamiento
     */
    public function getTratamiento()
    {
        return $this->tratamiento;
    }
    
    public function __toString() 
    {
        return $this->getTratamiento()->getNombre() ? $this->getTratamiento()->getNombre() : '';
    }
}
