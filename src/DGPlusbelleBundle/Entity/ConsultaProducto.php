<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConsultaProducto
 *
 * @ORM\Table(name="consulta_producto", indexes={@ORM\Index(name="fk_medicamento_consulta_medicamento1_idx", columns={"producto"}), @ORM\Index(name="fk_medicamento_consulta_consulta1_idx", columns={"consulta"})})
 * @ORM\Entity
 */
class ConsultaProducto
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
     * @ORM\Column(name="indicaciones", type="string", length=150, nullable=false)
     */
    private $indicaciones;

    /**
     * @var \Consulta
     *
     * @ORM\ManyToOne(targetEntity="Consulta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="consulta", referencedColumnName="id")
     * })
     */
    private $consulta;

    /**
     * @var \Producto
     *
     * @ORM\ManyToOne(targetEntity="Producto", inversedBy="placas", cascade={"persist", "remove"}) 
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="producto", referencedColumnName="id")
     * })
     */
    private $producto;



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
     * Set indicaciones
     *
     * @param string $indicaciones
     *
     * @return ConsultaProducto
     */
    public function setIndicaciones($indicaciones)
    {
        $this->indicaciones = $indicaciones;

        return $this;
    }

    /**
     * Get indicaciones
     *
     * @return string
     */
    public function getIndicaciones()
    {
        return $this->indicaciones;
    }

    /**
     * Set consulta
     *
     * @param \DGPlusbelleBundle\Entity\Consulta $consulta
     *
     * @return ConsultaProducto
     */
    public function setConsulta(\DGPlusbelleBundle\Entity\Consulta $consulta = null)
    {
        $this->consulta = $consulta;

        return $this;
    }

    /**
     * Get consulta
     *
     * @return \DGPlusbelleBundle\Entity\Consulta
     */
    public function getConsulta()
    {
        return $this->consulta;
    }

    /**
     * Set producto
     *
     * @param \DGPlusbelleBundle\Entity\Producto $producto
     *
     * @return ConsultaProducto
     */
    public function setProducto(\DGPlusbelleBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return \DGPlusbelleBundle\Entity\Producto
     */
    public function getProducto()
    {
        return $this->producto;
    }
}
