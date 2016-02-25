<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaqueteTratamiento
 *
 * @ORM\Table(name="paquete_tratamiento", indexes={@ORM\Index(name="fk_paquete_has_tratamiento_paquete1_idx", columns={"paquete"}), @ORM\Index(name="fk_paquete_has_tratamiento_tratamiento1_idx", columns={"tratamiento"})})
 * @ORM\Entity
 * 
 */
class PaqueteTratamiento {
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
     * @ORM\ManyToOne(targetEntity="Paquete",inversedBy="placas", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="paquete", referencedColumnName="id")
     * })
     */
    private $paquete;

    /**
     * @var \Tratamiento
     *
     * @ORM\ManyToOne(targetEntity="Tratamiento", inversedBy="paquetetratamiento", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tratamiento", referencedColumnName="id")
     * })
     */
    private $tratamiento;


    /**
     * @ORM\OneToMany(targetEntity="SesionTratamiento", mappedBy="sesiontratamientoreceta", cascade={"persist", "remove"})
     */
    private $ventapaq;

    
    
    
    
    /**
     * Set ventapaq
     *
     * @param \DGPlusbelleBundle\Entity\SesionTratamiento $ventapaq
     *
     * @return PaqueteTratamiento
     */
    public function setVentaPaq(\DGPlusbelleBundle\Entity\SesionTratamiento $ventapaq = null)
    {
        $this->ventapaq = $ventapaq;

        return $this;
    }

    /**
     * Get tratamiento
     *
     * @return \DGPlusbelleBundle\Entity\SesionTratamiento
     */
    public function getVentaPaq()
    {
        return $this->ventapaq;
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
     * Set numSesiones
     *
     * @param integer $numSesiones
     *
     * @return PaqueteTratamiento
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
     * Set paquete
     *
     * @param \DGPlusbelleBundle\Entity\Paquete $paquete
     *
     * @return PaqueteTratamiento
     */
    public function setPaquete(\DGPlusbelleBundle\Entity\Paquete $paquete = null)
    {
        $this->paquete = $paquete;

        return $this;
    }

    /**
     * Get paquete
     *
     * @return \DGPlusbelleBundle\Entity\Paquete
     */
    public function getPaquete()
    {
        return $this->paquete;
    }

    /**
     * Set tratamiento
     *
     * @param \DGPlusbelleBundle\Entity\Tratamiento $tratamiento
     *
     * @return PaqueteTratamiento
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
