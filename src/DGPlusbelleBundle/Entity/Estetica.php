<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Estetica
 *
 * @ORM\Table(name="estetica")
 * @ORM\Entity(repositoryClass="DGPlusbelleBundle\Repository\EsteticaRepository")
 */

class Estetica 
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
     * @ORM\Column(name="tipo_estetica", type="string", length=150, nullable=false)
     */
    private $tipoEstetica;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;
    

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
     * Set tipoEstetica
     *
     * @param string $tipoEstetica
     * 
     * @return Estetica
     */
    public function setTipoEstetica($tipoEstetica)
    {
        $this->tipoEstetica = $tipoEstetica;

        return $this;
    }

    /**
     * Get tipoEstetica
     *
     * @return string 
     */
    public function getTipoEstetica()
    {
        return $this->tipoEstetica;
    }
    
    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Estetica
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
    
     public function __toString() {
        return $this->tipoEstetica;
    }
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="DetalleEstetica", mappedBy="estetica", cascade={"persist", "remove"})
     * 
     */
    protected $coleccion;
    
    public function __construct()
    {
        //$this->coleccion = array(new EstudioRadTamPlaca(), new EstudioRadTamPlaca());
        $this->coleccion = new ArrayCollection();
    }    
    
    public function getColeccion()
    {
        return $this->coleccion;
    }
    
    public function setColeccion(\Doctrine\Common\Collections\Collection $coleccion)
    {
        $this->coleccion = $coleccion;
        
        foreach ($coleccion as $row) {
            $row->setEstetica($this);
            //$row->s
        }
    }
    
    public function removePlacas(DetalleEstetica $coleccion)
    {
        $this->coleccion->removeElement($coleccion);
    }
    
}
