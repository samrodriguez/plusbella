<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tratamiento
 *
 * @ORM\Table(name="tratamiento", indexes={@ORM\Index(name="fk_tratamiento_categoria_tratamiento1_idx", columns={"categoria"})})
 * @ORM\Entity(repositoryClass="DGPlusbelleBundle\Repository\CatalogoTratamientoRepository")
 */
class Tratamiento
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
     * @var \Categoria
     *
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoria", referencedColumnName="id")
     * })
     */
    private $categoria;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Empleado", mappedBy="tratamiento")
     */
    private $empleado;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Paquete")
     */
   private $paquete;

     /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Sucursal", inversedBy="tratamiento")
     * @ORM\JoinTable(name="sucursal_tratamiento",
     *   joinColumns={
     *     @ORM\JoinColumn(name="tratamiento", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="sucursal", referencedColumnName="id")
     *   }
     * )
     */
    private $sucursal;
    
    

    
    /**
     * @ORM\OneToMany(targetEntity="PaqueteTratamiento", mappedBy="tratamiento", cascade={"persist", "remove"})
     */
    private $paquetetratamiento;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleado = new \Doctrine\Common\Collections\ArrayCollection();
        $this->paquete = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Tratamiento
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
     * @return Tratamiento
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
     * @return Tratamiento
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
     * Set categoria
     *
     * @param \DGPlusbelleBundle\Entity\Categoria $categoria
     *
     * @return Tratamiento
     */
    public function setCategoria(\DGPlusbelleBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \DGPlusbelleBundle\Entity\Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Add empleado
     *
     * @param \DGPlusbelleBundle\Entity\Empleado $empleado
     *
     * @return Tratamiento
     */
    public function addEmpleado(\DGPlusbelleBundle\Entity\Empleado $empleado)
    {
        $this->empleado[] = $empleado;

        return $this;
    }

    /**
     * Remove empleado
     *
     * @param \DGPlusbelleBundle\Entity\Empleado $empleado
     */
    public function removeEmpleado(\DGPlusbelleBundle\Entity\Empleado $empleado)
    {
        $this->empleado->removeElement($empleado);
    }

    /**
     * Get empleado
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Add paquete
     *
     * @param \DGPlusbelleBundle\Entity\Paquete $paquete
     *
     * @return Tratamiento
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
     * Add sucursal
     *
     * @param \DGPlusbelleBundle\Entity\Sucursal $sucursal
     *
     * @return Tratamiento
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
    return $this->nombre ? $this->nombre : '';
    }
}
