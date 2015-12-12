<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ImagenTratamiento
 *
 * @ORM\Table(name="imagen_consulta")
 * @ORM\Entity
 */
class ImagenConsulta{
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
     * @ORM\Column(name="foto_consulta", type="string", length=255, nullable=true)
     */
    private $foto;
    
     
     /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @var \Consulta
     *
     * @ORM\ManyToOne(targetEntity="Consulta", inversedBy="placas2", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_consulta", referencedColumnName="id")
     * })
     */
    private $consulta;
    
    /**
     * Sets fileAntes.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file= null)
    {
        $this->file= $file;
    }

    /**
     * Get fileAntes.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
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
     * Set fotoAntes
     *
     * @param string $foto
     *
     * @return SeguimientoPaquete
     */
    public function setFoto($foto)
    {
        $this->foto= $foto;

        return $this;
    }

    /**
     * Get fotoAntes
     *
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }
    
    
    
    
    /**
     * Set consulta
     *
     * @param \DGPlusbelleBundle\Entity\Consulta $consulta
     *
     * @return HistorialConsulta
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
    
     
}
