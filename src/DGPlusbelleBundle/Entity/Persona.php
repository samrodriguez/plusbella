<?php

namespace DGPlusbelleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Persona
 *
 * @ORM\Table(name="persona")
 * @ORM\Entity
 */
class Persona
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
     * @ORM\Column(name="primer_nombre", type="string", length=50, nullable=false)
     */
    private $primerNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="segundo_nombre", type="string", length=75, nullable=true)
     */
    private $segundoNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="primer_apellido", type="string", length=50, nullable=false)
     */
    private $primerApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="segundo_apellido", type="string", length=50, nullable=true)
     */
    private $segundoApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido_casada", type="string", length=50, nullable=true)
     */
    private $apellidoCasada;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=200, nullable=false)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=12, nullable=false)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    /*private $estado;*/

     /**
     * @ORM\OneToMany(targetEntity="Paciente", mappedBy="persona", cascade={"persist", "remove"})
     */
    private $paciente;

    /**
     * @ORM\OneToMany(targetEntity="Empleado", mappedBy="persona", cascade={"persist", "remove"})
     */
    private $empleado;

    function getPaciente() {
        return $this->paciente;
    }

    function setPaciente($paciente) {
        $this->paciente = $paciente;
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
     * Set primerNombre
     *
     * @param string $primerNombre
     *
     * @return Persona
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    /**
     * Get primerNombre
     *
     * @return string
     */
    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * Set segundoNombre
     *
     * @param string $segundoNombre
     *
     * @return Persona
     */
    public function setSegundoNombre($segundoNombre)
    {
        $this->segundoNombre = $segundoNombre;

        return $this;
    }

    /**
     * Get segundoNombre
     *
     * @return string
     */
    public function getSegundoNombre()
    {
        return $this->segundoNombre;
    }

    /**
     * Set primerApellido
     *
     * @param string $primerApellido
     *
     * @return Persona
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    /**
     * Get primerApellido
     *
     * @return string
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Set segundoApellido
     *
     * @param string $segundoApellido
     *
     * @return Persona
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    /**
     * Get segundoApellido
     *
     * @return string
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Set apellidoCasada
     *
     * @param string $apellidoCasada
     *
     * @return Persona
     */
    public function setApellidoCasada($apellidoCasada)
    {
        $this->apellidoCasada = $apellidoCasada;

        return $this;
    }

    /**
     * Get apellidoCasada
     *
     * @return string
     */
    public function getApellidoCasada()
    {
        return $this->apellidoCasada;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Persona
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Persona
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Persona
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Persona
     */
    /*public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }*/

    /**
     * Get estado
     *
     * @return boolean
     */
    /*public function getEstado()
    {
        return $this->estado;
    }*/
    
    public function __toString() {
    return $this->primerNombre.' '.$this->primerApellido;
    }
}
