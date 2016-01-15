<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Plantilla
 *
 * @ORM\Table(name="plantilla")
 * @ORM\Entity
 */

class Plantilla 
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
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="clinica", type="string", length=75, nullable=false)
     */
    private $clinica;

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
     * @return Plantilla
     */
    public function setClinica($clinica)
    {
        $this->clinica = $clinica;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getClinica()
    {
        return $this->clinica;
    }
    
    
    /**
     * Set nombre
     *
     * @param string $nombre
     * 
     * @return Plantilla
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
     * @return Plantilla
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
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Plantilla
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
        return $this->nombre;
    }
    
    /**
     * @ORM\OneToMany(targetEntity="DetallePlantilla", mappedBy="plantilla", cascade={"persist", "remove"})
     */
    protected $placas;
    
    public function __construct()
    {
        //$this->placas = array(new EstudioRadTamPlaca(), new EstudioRadTamPlaca());
        $this->placas = new ArrayCollection();
    }    
    
    public function getPlacas()
    {
        return $this->placas;
    }
    
    public function setPlacas(\Doctrine\Common\Collections\Collection $placas)
    {
        $this->placas = $placas;
        
        foreach ($placas as $placa) {
            $placa->setPlantilla($this);
            //$placa->s
        }
    }
    
    public function removePlacas(DetallePlantilla $placa)
    {
        $this->placas->removeElement($placa);
    }
    
}
