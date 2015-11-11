<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImagenTratamiento
 *
 * @ORM\Table(name="imagen_tratamiento", indexes={@ORM\Index(name="id_historial_consulta", columns={"historial_consulta"})})
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
     * @ORM\Column(name="nombre_imagen", type="string", length=255, nullable=false)
     */
    private $nombreImagen;
    
    /**
     * @var \HistorialConsulta
     *
     * @ORM\ManyToOne(targetEntity="HistorialConsulta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="historial_consulta", referencedColumnName="id")
     * })
     */
    private $historialConsulta;
    
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
     * Set nombreImagen
     *
     * @param string $nombreImagen
     *
     * @return ImagenTratamiento
     */
    public function setNombreImagen($nombreImagen)
    {
        $this->nombreImagen = $nombreImagen;

        return $this;
    }

    /**
     * Get nombreImagen
     *
     * @return string
     */
    public function getNombreImagen()
    {
        return $this->nombreImagen;
    }

    /**
     * Set historialConsulta
     *
     * @param \DGPlusbelleBundle\Entity\HistorialConsulta $historialConsulta
     *
     * @return ImagenTratamiento
     */
    public function setHistorialConsulta(\DGPlusbelleBundle\Entity\HistorialConsulta $historialConsulta = null)
    {
        $this->historialConsulta = $historialConsulta;

        return $this;
    }

    /**
     * Get historialConsulta
     *
     * @return \DGPlusbelleBundle\Entity\HistorialConsulta
     */
    public function getHistorialConsulta()
    {
        return $this->historialConsulta;
    }
}
