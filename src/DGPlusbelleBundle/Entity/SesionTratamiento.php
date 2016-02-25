<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SesionTratamiento
 *
 * @ORM\Table(name="sesion_tratamiento", indexes={@ORM\Index(name="empleado", columns={"empleado"}), @ORM\Index(name="paciente", columns={"paciente"}), @ORM\Index(name="sucursal", columns={"sucursal"}), @ORM\Index(name="tratamiento", columns={"tratamiento"}), @ORM\Index(name="venta_paquete", columns={"venta_paquete"})})
 * @ORM\Entity
 */
class SesionTratamiento {
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
     * @ORM\Column(name="fecha_sesion", type="date", nullable=false)
     */
    private $fechaSesion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_inicio", type="time", nullable=false)
     */
    private $horaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_fin", type="time", nullable=false)
     */
    private $horaFin;
    
    /**
     * @var \VentaPaquete
     *
     * @ORM\ManyToOne(targetEntity="VentaPaquete", inversedBy="ventapaq", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="venta_paquete", referencedColumnName="id")
     * })
     */
    private $ventaPaquete;
    
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
     * @var \Paciente
     *
     * @ORM\ManyToOne(targetEntity="Paciente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="paciente", referencedColumnName="id")
     * })
     */
    private $paciente;
    
    /**
     * @var \Empleado
     *
     * @ORM\ManyToOne(targetEntity="Empleado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empleado", referencedColumnName="id")
     * })
     */
    private $empleado;
    
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
     * @Assert\File(maxSize="6000000")
     */
    private $fileAntes;
    
    
    /**
     * @ORM\OneToMany(targetEntity="ImagenTratamiento", mappedBy="sesionTratamiento", cascade={"persist", "remove"})
     */
    private $imagenTratamiento;
    
    
    /**
     * @ORM\OneToMany(targetEntity="HistorialConsulta", mappedBy="sesiontratamientoreceta", cascade={"persist", "remove"})
     */
    private $sesionpaquetetratamiento;

    
    /**
     * Sets fileAntes.
     *
     * @param UploadedFile $fileAntes
     */
    public function setFileAntes(UploadedFile $fileAntes = null)
    {
        $this->fileAntes = $fileAntes;
    }

    /**
     * Get fileAntes.
     *
     * @return UploadedFile
     */
    public function getFileAntes()
    {
        return $this->fileAntes;
    }
    
     /**
     * @Assert\File(maxSize="6000000")
     */
    private $fileDespues;

    
    /**
     * Sets fileDespues.
     *
     * @param UploadedFile $fileDespues
     */
    public function setFileDespues(UploadedFile $fileDespues = null)
    {
        $this->fileDespues = $fileDespues;
    }

    /**
     * Get fileDespues.
     *
     * @return UploadedFile
     */
    public function getFileDespues()
    {
        return $this->fileDespues;
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
     * Set fechaSesion
     *
     * @param \DateTime $fechaSesion
     *
     * @return SesionTratamiento
     */
    public function setFechaSesion($fechaSesion)
    {
        $this->fechaSesion = $fechaSesion;

        return $this;
    }

    /**
     * Get fechaSesion
     *
     * @return \DateTime
     */
    public function getFechaSesion()
    {
        return $this->fechaSesion;
    }

    /**
     * Set horaInicio
     *
     * @param \DateTime $horaInicio
     *
     * @return SesionTratamiento
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    /**
     * Get horaInicio
     *
     * @return \DateTime
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * Set horaFin
     *
     * @param \DateTime $horaFin
     *
     * @return SesionTratamiento
     */
    public function setHoraFin($horaFin)
    {
        $this->horaFin = $horaFin;

        return $this;
    }

    /**
     * Get horaFin
     *
     * @return \DateTime
     */
    public function getHoraFin()
    {
        return $this->horaFin;
    }
    
    /**
     * Set ventaPaquete
     *
     * @param \DGPlusbelleBundle\Entity\VentaPaquete $ventaPaquete
     *
     * @return SesionTratamiento
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
     * Set sucursal
     *
     * @param \DGPlusbelleBundle\Entity\Sucursal $sucursal
     *
     * @return SesionTratamiento
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
     * @param \DGPlusbelleBundle\Entity\Paciente $paciente
     *
     * @return SesionTratamiento
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
    
     /**
     * Set empleado
     *
     * @param \DGPlusbelleBundle\Entity\Empleado $empleado
     *
     * @return SesionTratamiento
     */
    public function setEmpleado(\DGPlusbelleBundle\Entity\Empleado $empleado = null)
    {
        $this->empleado = $empleado;

        return $this;
    }

    /**
     * Get empleado
     *
     * @return \DGPlusbelleBundle\Entity\Empleado
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Set tratamiento
     *
     * @param \DGPlusbelleBundle\Entity\Tratamiento $tratamiento
     *
     * @return SesionTratamiento
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
    
     
     /**
     * @ORM\OneToMany(targetEntity="ImagenTratamiento", mappedBy="SesionTratamiento", cascade={"persist", "remove"})
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
            $placa->setSesionTratamiento($this);
        }
    }
    
    
    
    /**
     * Set registraReceta
     *
     * @param boolean $registraReceta
     *
     * @return Consulta
     */
    public function setRegistraReceta($registraReceta)
    {
        $this->registraReceta = $registraReceta;

        return $this;
    }

    /**
     * Get reportePlantilla
     *
     * @return boolean
     */
    public function getRegistraReceta()
    {
        return $this->registraReceta;
    }
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="receta_medica", type="boolean", nullable=false)
     */
    private $registraReceta;
}
