<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ImagenTratamiento
 *
 * @ORM\Table(name="imagen_tratamiento")
 * @ORM\Entity
 */
class ImagenTratamiento {
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
     * @ORM\Column(name="foto_antes", type="string", length=255, nullable=true)
     */
    private $fotoAntes;
    
     /**
     * @var string
     *
     * @ORM\Column(name="foto_despues", type="string", length=255, nullable=true)
     */
    private $fotoDespues;
    
     /**
     * @var \sesionTratamiento
     *
     * @ORM\ManyToOne(targetEntity="SesionTratamiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sesion_tratamiento", referencedColumnName="id")
     * })
     */
    private $sesionTratamiento;
    
     /**
     * @var \sesionVentaTratamiento
     *
     * @ORM\ManyToOne(targetEntity="SesionVentaTratamiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sesion_venta_tratamiento", referencedColumnName="id")
     * })
     */
    private $sesionVentaTratamiento;
    
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
     * Set fotoAntes
     *
     * @param string $fotoAntes
     *
     * @return SeguimientoPaquete
     */
    public function setFotoAntes($fotoAntes)
    {
        $this->fotoAntes = $fotoAntes;

        return $this;
    }

    /**
     * Get fotoAntes
     *
     * @return string
     */
    public function getFotoAntes()
    {
        return $this->fotoAntes;
    }
    
     /**
     * Set fotoDespues
     *
     * @param string $fotoDespues
     *
     * @return SeguimientoPaquete
     */
    public function setFotoDespues($fotoDespues)
    {
        $this->fotoDespues = $fotoDespues;

        return $this;
    }

    /**
     * Get fotoDespues
     *
     * @return string
     */
    public function getFotoDespues()
    {
        return $this->fotoDespues;
    }
    
    /**
     * Set sesionTratamiento
     *
     * @param \DGPlusbelleBundle\Entity\SesionTratamiento $sesionTratamiento
     *
     * @return ImagenTratamiento
     */
    public function setSesionTratamiento(\DGPlusbelleBundle\Entity\SesionTratamiento $sesionTratamiento = null)
    {
        $this->sesionTratamiento = $sesionTratamiento;

        return $this;
    }

    /**
     * Get sesionTratamiento
     *
     * @return \DGPlusbelleBundle\Entity\SesionTratamiento
     */
    public function getSesionTratamiento()
    {
        return $this->sesionTratamiento;
    }
    
    /**
     * Set sesionVentaTratamiento
     *
     * @param \DGPlusbelleBundle\Entity\SesionVentaTratamiento $sesionVentaTratamiento
     *
     * @return ImagenTratamiento
     */
    public function setSesionVentaTratamiento(\DGPlusbelleBundle\Entity\SesionVentaTratamiento $sesionVentaTratamiento = null)
    {
        $this->sesionVentaTratamiento = $sesionVentaTratamiento;

        return $this;
    }

    /**
     * Get sesionVentaTratamiento
     *
     * @return \DGPlusbelleBundle\Entity\SesionVentaTratamiento
     */
    public function getSesionVentaTratamiento()
    {
        return $this->sesionVentaTratamiento;
    }
}
