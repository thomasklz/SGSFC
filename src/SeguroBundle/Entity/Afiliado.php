<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Afiliado
 *
 * @ORM\Table(name="afiliado", indexes={@ORM\Index(name="idafiliadodependiente_idx", columns={"IdAfiliacioneDependiente"})})
 * @ORM\Entity
 */
class Afiliado
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdAfiliado", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idafiliado;

    /**
     * @var string
     *
     * @ORM\Column(name="Nombre", type="string", length=45, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="Apellido", type="string", length=45, nullable=false)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="Cedula", type="string", length=11, nullable=false)
     */
    private $cedula;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaIngreso", type="date", nullable=false)
     */
    private $fechaingreso;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaNacimiento", type="date", nullable=false)
     */
    private $fechanacimiento;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Fallecido", type="boolean", nullable=true)
     */
    private $fallecido;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaFallecido", type="date",  nullable=true)
     */
    private $fechafallecido;

    /**
     * @var string
     *
     * @ORM\Column(name="Sexo", type="string", length=10, nullable=true)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="Parentesco", type="string", length=15, nullable=false)
     */
    private $parentesco;

    /**
     * @var string
     *
     * @ORM\Column(name="TipoAfiliacion", type="string", length=10, nullable=false)
     */
    private $tipoafiliacion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ActaFuncion", type="boolean", nullable=true)
     */
    private $actafuncion;

    /**
     * @var \Afiliado
     *
     * @ORM\ManyToOne(targetEntity="Afiliado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdAfiliacioneDependiente", referencedColumnName="IdAfiliado")
     * })
     */
    private $idafiliacionedependiente;



    /**
     * Get idafiliado
     *
     * @return integer
     */
    public function getIdafiliado()
    {
        return $this->idafiliado;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Afiliado
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Afiliado
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set cedula
     *
     * @param string $cedula
     *
     * @return Afiliado
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return string
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set fechanacimiento
     *
     * @param \DateTime $fechanacimiento
     *
     * @return Afiliado
     */
    public function setFechanacimiento($fechanacimiento)
    {
        $this->fechanacimiento = $fechanacimiento;

        return $this;
    }

    /**
     * Get fechanacimiento
     *
     * @return \DateTime
     */
    public function getFechanacimiento()
    {
        return $this->fechanacimiento;
    }

    /**
     * Set fechaingreso
     *
     * @param \DateTime $fechaingreso
     *
     * @return Afiliado
     */
    public function setFechaingreso($fechaingreso)
    {
        $this->fechaingreso = $fechaingreso;

        return $this;
    }

    /**
     * Get fechaingreso
     *
     * @return \DateTime
     */
    public function getFechaingreso()
    {
        return $this->fechaingreso;
    }

    /**
     * Set fallecido
     *
     * @param Boolean $fallecido
     *
     * @return Afiliado
     */
    public function setFallecido($fallecido)
    {
        $this->fallecido = $fallecido;

        return $this;
    }

    /**
     * Get fallecido
     *
     * @return Boolean
     */
    public function getFallecido()
    {
        return $this->fallecido;
    }

    /**
     * Set fechafallecido
     *
     * @param \DateTime $fechafallecido
     *
     * @return Afiliado
     */
    public function setFechafallecido($fechafallecido)
    {
        $this->fechafallecido = $fechafallecido;

        return $this;
    }

    /**
     * Get fechafallecido
     *
     * @return \DateTime
     */
    public function getFechafallecido()
    {
        return $this->fechafallecido;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Afiliado
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set parentesco
     *
     * @param string $parentesco
     *
     * @return Afiliado
     */
    public function setParentesco($parentesco)
    {
        $this->parentesco = $parentesco;

        return $this;
    }

    /**
     * Get parentesco
     *
     * @return string
     */
    public function getParentesco()
    {
        return $this->parentesco;
    }

    /**
     * Set tipoafiliacion
     *
     * @param string $tipoafiliacion
     *
     * @return Afiliado
     */
    public function setTipoafiliacion($tipoafiliacion)
    {
        $this->tipoafiliacion = $tipoafiliacion;

        return $this;
    }

    /**
     * Get tipoafiliacion
     *
     * @return string
     */
    public function getTipoafiliacion()
    {
        return $this->tipoafiliacion;
    }

    /**
     * Set actafuncion
     *
     * @param boolean $actafuncion
     *
     * @return Afiliado
     */
    public function setActafuncion($actafuncion)
    {
        $this->actafuncion = $actafuncion;

        return $this;
    }

    /**
     * Get actafuncion
     *
     * @return boolean
     */
    public function getActafuncion()
    {
        return $this->actafuncion;
    }

    /**
     * Set idafiliacionedependiente
     *
     * @param \SeguroBundle\Entity\Afiliado $idafiliacionedependiente
     *
     * @return Afiliado
     */
    public function setIdafiliacionedependiente(\SeguroBundle\Entity\Afiliado $idafiliacionedependiente = null)
    {
        $this->idafiliacionedependiente = $idafiliacionedependiente;

        return $this;
    }

    /**
     * Get idafiliacionedependiente
     *
     * @return \SeguroBundle\Entity\Afiliado
     */
    public function getIdafiliacionedependiente()
    {
        return $this->idafiliacionedependiente;
    }
   
}
