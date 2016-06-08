<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SesionVentaTratamiento
 *
 * @ORM\Table(name="sesion_venta_tratamiento", indexes={@ORM\Index(name="empleado", columns={"empleado"}), @ORM\Index(name="sucursal", columns={"sucursal"}), @ORM\Index(name="persona_tratamiento", columns={"persona_tratamiento"})})
 * @ORM\Entity
 */
class SesionVentaTratamiento {
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
     * @ORM\Column(name="fecha_sesion", type="datetime", nullable=false)
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
     * @var \PersonaTratamiento
     *
     * @ORM\ManyToOne(targetEntity="PersonaTratamiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona_tratamiento", referencedColumnName="id")
     * })
     */
    private $personaTratamiento;
    
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
     * @var \Empleado
     *
     * @ORM\ManyToOne(targetEntity="Empleado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="empleado", referencedColumnName="id")
     * })
     */
    private $empleado;
    
     /**
     * @Assert\File(maxSize="6000000")
     */
    private $fileAntes;

    
    /**
     * @ORM\OneToMany(targetEntity="ImagenTratamiento", mappedBy="sesionVentaTratamiento", cascade={"persist", "remove"})
     */
    private $imagenTratamiento;
    
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
     * @return SesionVentaTratamiento
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
     * @return SesionVentaTratamiento
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
     * @return SesionVentaTratamiento
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
     * Set sucursal
     *
     * @param \DGPlusbelleBundle\Entity\Sucursal $sucursal
     *
     * @return SesionVentaTratamiento
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
     * Set empleado
     *
     * @param \DGPlusbelleBundle\Entity\Empleado $empleado
     *
     * @return SesionVentaTratamiento
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
     * Set personaTratamiento
     *
     * @param \DGPlusbelleBundle\Entity\PersonaTratamiento $personaTratamiento
     *
     * @return SesionVentaTratamiento
     */
    public function setPersonaTratamiento(\DGPlusbelleBundle\Entity\PersonaTratamiento $personaTratamiento = null)
    {
        $this->personaTratamiento = $personaTratamiento;

        return $this;
    }

    /**
     * Get personaTratamiento
     *
     * @return \DGPlusbelleBundle\Entity\PersonaTratamiento
     */
    public function getPersonaTratamiento()
    {
        return $this->personaTratamiento;
    }
    
    
    /**
     * Set ventaPaquete
     *
     * @param \DGPlusbelleBundle\Entity\VentaPaquete $ventatratamiento
     *
     * @return SesionTratamiento
     */
    public function setImagenTratamiento(\DGPlusbelleBundle\Entity\ImagenTratamiento $imagenTratamiento = null)
    {
        $this->imagenTratamiento = $imagenTratamiento;

        return $this;
    }

    /**
     * Get ventaPaquete
     *
     * @return \DGPlusbelleBundle\Entity\VentaPaquete
     */
    public function getImagenTratemiento()
    {
        return $this->imagenTratamiento;
    }
    
     
     /**
     * @ORM\OneToMany(targetEntity="ImagenTratamiento", mappedBy="SesionVentaTratamiento", cascade={"persist", "remove"})
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
            $placa->setSesionVentaTratamiento($this);
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
