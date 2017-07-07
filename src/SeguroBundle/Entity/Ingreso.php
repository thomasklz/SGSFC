<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ingreso
 *
 * @ORM\Table(name="ingreso", indexes={@ORM\Index(name="idusuario_2_idx", columns={"IdUsuario"}), @ORM\Index(name="idtipoingreso_idx", columns={"IdTipoIngreso"}), @ORM\Index(name="idafiliado_2_idx", columns={"IdAfiliado"}), @ORM\Index(name="idreunion_1_idx", columns={"IdReunion"}), @ORM\Index(name="idmulta_idx", columns={"IdMulta"}), @ORM\Index(name="idmes_idx", columns={"IdMes"})})
 * @ORM\Entity
 */
class Ingreso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdIngreso", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idingreso;

    /**
     * @var float
     *
     * @ORM\Column(name="Valor", type="float", precision=10, scale=0, nullable=true)
     */
    private $valor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaIngreso", type="datetime", nullable=true)
     */
    private $fechaingreso;

    /**
     * @var \Afiliado
     *
     * @ORM\ManyToOne(targetEntity="Afiliado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdAfiliado", referencedColumnName="IdAfiliado")
     * })
     */
    private $idafiliado;

    /**
     * @var \Mes
     *
     * @ORM\ManyToOne(targetEntity="Mes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdMes", referencedColumnName="IdMes")
     * })
     */
    private $idmes;

    /**
     * @var \Multa
     *
     * @ORM\ManyToOne(targetEntity="Multa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdMulta", referencedColumnName="IdMulta")
     * })
     */
    private $idmulta;

    /**
     * @var \Reunion
     *
     * @ORM\ManyToOne(targetEntity="Reunion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdReunion", referencedColumnName="IdReunion")
     * })
     */
    private $idreunion;

    /**
     * @var \Tipoingreso
     *
     * @ORM\ManyToOne(targetEntity="Tipoingreso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdTipoIngreso", referencedColumnName="IdTipoIngreso")
     * })
     */
    private $idtipoingreso;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdUsuario", referencedColumnName="IdUsuario")
     * })
     */
    private $idusuario;



    /**
     * Get idingreso
     *
     * @return integer
     */
    public function getIdingreso()
    {
        return $this->idingreso;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return Ingreso
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set fechaingreso
     *
     * @param \DateTime $fechaingreso
     *
     * @return Ingreso
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
     * Set idafiliado
     *
     * @param \SeguroBundle\Entity\Afiliado $idafiliado
     *
     * @return Ingreso
     */
    public function setIdafiliado(\SeguroBundle\Entity\Afiliado $idafiliado = null)
    {
        $this->idafiliado = $idafiliado;

        return $this;
    }

    /**
     * Get idafiliado
     *
     * @return \SeguroBundle\Entity\Afiliado
     */
    public function getIdafiliado()
    {
        return $this->idafiliado;
    }

    /**
     * Set idmes
     *
     * @param \SeguroBundle\Entity\Mes $idmes
     *
     * @return Ingreso
     */
    public function setIdmes(\SeguroBundle\Entity\Mes $idmes = null)
    {
        $this->idmes = $idmes;

        return $this;
    }

    /**
     * Get idmes
     *
     * @return \SeguroBundle\Entity\Mes
     */
    public function getIdmes()
    {
        return $this->idmes;
    }

    /**
     * Set idmulta
     *
     * @param \SeguroBundle\Entity\Multa $idmulta
     *
     * @return Ingreso
     */
    public function setIdmulta(\SeguroBundle\Entity\Multa $idmulta = null)
    {
        $this->idmulta = $idmulta;

        return $this;
    }

    /**
     * Get idmulta
     *
     * @return \SeguroBundle\Entity\Multa
     */
    public function getIdmulta()
    {
        return $this->idmulta;
    }

    /**
     * Set idreunion
     *
     * @param \SeguroBundle\Entity\Reunion $idreunion
     *
     * @return Ingreso
     */
    public function setIdreunion(\SeguroBundle\Entity\Reunion $idreunion = null)
    {
        $this->idreunion = $idreunion;

        return $this;
    }

    /**
     * Get idreunion
     *
     * @return \SeguroBundle\Entity\Reunion
     */
    public function getIdreunion()
    {
        return $this->idreunion;
    }

    /**
     * Set idtipoingreso
     *
     * @param \SeguroBundle\Entity\Tipoingreso $idtipoingreso
     *
     * @return Ingreso
     */
    public function setIdtipoingreso(\SeguroBundle\Entity\Tipoingreso $idtipoingreso = null)
    {
        $this->idtipoingreso = $idtipoingreso;

        return $this;
    }

    /**
     * Get idtipoingreso
     *
     * @return \SeguroBundle\Entity\Tipoingreso
     */
    public function getIdtipoingreso()
    {
        return $this->idtipoingreso;
    }

    /**
     * Set idusuario
     *
     * @param \SeguroBundle\Entity\Usuario $idusuario
     *
     * @return Ingreso
     */
    public function setIdusuario(\SeguroBundle\Entity\Usuario $idusuario = null)
    {
        $this->idusuario = $idusuario;

        return $this;
    }

    /**
     * Get idusuario
     *
     * @return \SeguroBundle\Entity\Usuario
     */
    public function getIdusuario()
    {
        return $this->idusuario;
    }
}
