<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VentaPaquete
 *
 * @ORM\Table(name="venta_paquete", indexes={@ORM\Index(name="fk_paquete_vendido_paquete1_idx", columns={"paquete"}), @ORM\Index(name="fk_paquete_vendido_sucursal1_idx", columns={"sucursal"}), @ORM\Index(name="fk_venta_paquete_persona1_idx", columns={"paciente"}), @ORM\Index(name="fk_venta_paquete_persona2_idx", columns={"empleado"}), @ORM\Index(name="fk_venta_paquete_usuario1_idx", columns={"usuario"})})
 * @ORM\Entity
 */
class VentaPaquete
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_venta", type="date", nullable=false)
     */
    private $fechaVenta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime", nullable=true)
     */
    private $fechaRegistro;

    /**
     * @var \Paquete
     *
     * @ORM\ManyToOne(targetEntity="Paquete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="paquete", referencedColumnName="id")
     * })
     */
    private $paquete;

    /**
     * @var \Sucursal
     *
     * @ORM\ManyToOne(targetEntity="Sucursal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sucursal", referencedColumnName="id")
     * })
     */
    private $sucursal;

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="ventapaquete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="paciente", referencedColumnName="id")
     * })
     */
    private $paciente;

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empleado", referencedColumnName="id")
     * })
     */
    private $empleado;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     * })
     */
    private $usuario;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cuotas", type="integer", nullable=false)
     */
    private $cuotas;



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
     * Set fechaVenta
     *
     * @param \DateTime $fechaVenta
     *
     * @return VentaPaquete
     */
    public function setFechaVenta($fechaVenta)
    {
        $this->fechaVenta = $fechaVenta;

        return $this;
    }

    /**
     * Get fechaVenta
     *
     * @return \DateTime
     */
    public function getFechaVenta()
    {
        return $this->fechaVenta;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     *
     * @return VentaPaquete
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set paquete
     *
     * @param \DGPlusbelleBundle\Entity\Paquete $paquete
     *
     * @return VentaPaquete
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
     * Set sucursal
     *
     * @param \DGPlusbelleBundle\Entity\Sucursal $sucursal
     *
     * @return VentaPaquete
     */
    public function setSucursal(\DGPlusbelleBundle\Entity\Sucursal $sucursal = null)
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    /**
     * Get sucursal
     *
     * @return \DGPlusbelleBundle\Entity\Sucursal
     */
    public function getSucursal()
    {
        return $this->sucursal;
    }

    /**
     * Set paciente
     *
     * @param \DGPlusbelleBundle\Entity\Persona $paciente
     *
     * @return VentaPaquete
     */
    public function setPaciente(\DGPlusbelleBundle\Entity\Persona $paciente = null)
    {
        $this->paciente = $paciente;

        return $this;
    }

    /**
     * Get paciente
     *
     * @return \DGPlusbelleBundle\Entity\Persona
     */
    public function getPaciente()
    {
        return $this->paciente;
    }

    /**
     * Set empleado
     *
     * @param \DGPlusbelleBundle\Entity\Persona $empleado
     *
     * @return VentaPaquete
     */
    public function setEmpleado(\DGPlusbelleBundle\Entity\Persona $empleado = null)
    {
        $this->empleado = $empleado;

        return $this;
    }

    /**
     * Get empleado
     *
     * @return \DGPlusbelleBundle\Entity\Persona
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Set usuario
     *
     * @param \DGPlusbelleBundle\Entity\Usuario $usuario
     *
     * @return VentaPaquete
     */
    public function setUsuario(\DGPlusbelleBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \DGPlusbelleBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    
    
    /**
     * Set cuotas
     *
     * @param integer $cuotas
     *
     * 
     */
    public function setCuotas($cuotas)
    {
        $this->cuotas = $cuotas;

        return $this;
    }

    /**
     * Get numSesiones
     *
     * @return integer
     */
    public function getCuotas()
    {
        return $this->cuotas;
    }
}
