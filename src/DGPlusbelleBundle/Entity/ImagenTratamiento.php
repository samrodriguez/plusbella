<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="nombre_imagen", type="string", length=255, nullable=false)
     */
    private $nombreImagen;
    
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
}
