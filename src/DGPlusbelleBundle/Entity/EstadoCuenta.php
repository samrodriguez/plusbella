<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstadoCuenta
 *
 * @ORM\Table(name="estado_cuenta", indexes={@ORM\Index(name="fk_estado_cuenta_paciente1_idx", columns={"paciente"})})
 * @ORM\Entity
 */
class EstadoCuenta
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
     * @ORM\Column(name="saldo_cuenta", type="float", precision=10, scale=0, nullable=false)
     */
    private $saldoCuenta;

    /**
     * @var \Paciente
     *
     * @ORM\ManyToOne(targetEntity="Paciente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="paciente", referencedColumnName="id")
     * })
     */
    private $paciente;



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
     * Set saldoCuenta
     *
     * @param float $saldoCuenta
     *
     * @return EstadoCuenta
     */
    public function setSaldoCuenta($saldoCuenta)
    {
        $this->saldoCuenta = $saldoCuenta;

        return $this;
    }

    /**
     * Get saldoCuenta
     *
     * @return float
     */
    public function getSaldoCuenta()
    {
        return $this->saldoCuenta;
    }

    /**
     * Set paciente
     *
     * @param \DGPlusbelleBundle\Entity\Paciente $paciente
     *
     * @return EstadoCuenta
     */
    public function setPaciente(\DGPlusbelleBundle\Entity\Paciente $paciente = null)
    {
        $this->paciente = $paciente;

        return $this;
    }

    /**
     * Get paciente
     *
     * @return \DGPlusbelleBundle\Entity\Paciente
     */
    public function getPaciente()
    {
        return $this->paciente;
    }
}
