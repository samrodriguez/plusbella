<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sucursal
 *
 * @ORM\Table(name="sucursal")
 * @ORM\Entity
 */
class Sucursal
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
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=200, nullable=false)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=12, nullable=false)
     */
    private $telefono;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=10, nullable=false)
     */
    private $slug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Paquete", mappedBy="sucursal")
     */
    private $paquete;

   /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tratamiento", mappedBy="sucursal")
     */
    private $tratamiento;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->paquete = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tratamiento = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Sucursal
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Sucursal
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Sucursal
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Sucursal
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Sucursal
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add paquete
     *
     * @param \DGPlusbelleBundle\Entity\Paquete $paquete
     *
     * @return Sucursal
     */
    public function addPaquete(\DGPlusbelleBundle\Entity\Paquete $paquete)
    {
        $this->paquete[] = $paquete;

        return $this;
    }

    /**
     * Remove paquete
     *
     * @param \DGPlusbelleBundle\Entity\Paquete $paquete
     */
    public function removePaquete(\DGPlusbelleBundle\Entity\Paquete $paquete)
    {
        $this->paquete->removeElement($paquete);
    }

    /**
     * Get paquete
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaquete()
    {
        return $this->paquete;
    }

    /**
     * Add tratamiento
     *
     * @param \DGPlusbelleBundle\Entity\Tratamiento $tratamiento
     *
     * @return Sucursal
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
    
    public function __toString() {
    return $this->nombre ? $this->nombre : '';
    }
}
