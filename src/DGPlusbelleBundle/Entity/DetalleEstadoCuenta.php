<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleEstadoCuenta
 *
 * @ORM\Table(name="detalle_estado_cuenta", indexes={@ORM\Index(name="fk_detalle_estado_cuenta_estado_cuenta1_idx", columns={"estado_cuenta"})})
 * @ORM\Entity
 */
class DetalleEstadoCuenta
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
     * @var float
     *
     * @ORM\Column(name="monto", type="float", precision=10, scale=0, nullable=false)
     */
    private $monto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_movimiento", type="datetime", nullable=false)
     */
    private $fechaMovimiento = 'CURRENT_TIMESTAMP';

    /**
     * @var \EstadoCuenta
     *
     * @ORM\ManyToOne(targetEntity="EstadoCuenta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado_cuenta", referencedColumnName="id")
     * })
     */
    private $estadoCuenta;



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
     * Set monto
     *
     * @param float $monto
     *
     * @return DetalleEstadoCuenta
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return float
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set fechaMovimiento
     *
     * @param \DateTime $fechaMovimiento
     *
     * @return DetalleEstadoCuenta
     */
    public function setFechaMovimiento($fechaMovimiento)
    {
        $this->fechaMovimiento = $fechaMovimiento;

        return $this;
    }

    /**
     * Get fechaMovimiento
     *
     * @return \DateTime
     */
    public function getFechaMovimiento()
    {
        return $this->fechaMovimiento;
    }

    /**
     * Set estadoCuenta
     *
     * @param \DGPlusbelleBundle\Entity\EstadoCuenta $estadoCuenta
     *
     * @return DetalleEstadoCuenta
     */
    public function setEstadoCuenta(\DGPlusbelleBundle\Entity\EstadoCuenta $estadoCuenta = null)
    {
        $this->estadoCuenta = $estadoCuenta;

        return $this;
    }

    /**
     * Get estadoCuenta
     *
     * @return \DGPlusbelleBundle\Entity\EstadoCuenta
     */
    public function getEstadoCuenta()
    {
        return $this->estadoCuenta;
    }
}
