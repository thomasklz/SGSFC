<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egreso
 *
 * @ORM\Table(name="egreso", indexes={@ORM\Index(name="idusuario_idx", columns={"IdUsuario"}), @ORM\Index(name="idorden_1", columns={"IdOrden"})})
 * @ORM\Entity
 */
class Egreso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdEgreso", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idegreso;

    /**
     * @var float
     *
     * @ORM\Column(name="Valor", type="float", precision=10, scale=0, nullable=true)
     */
    private $valor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaEgreso", type="datetime", nullable=true)
     */
    private $fechaegreso;

    /**
     * @var \Orden
     *
     * @ORM\ManyToOne(targetEntity="Orden")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdOrden", referencedColumnName="IdOrden")
     * })
     */
    private $idorden;
 
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
     * Get idegreso
     *
     * @return integer
     */
    public function getIdegreso()
    {
        return $this->idegreso;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return Egreso
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
     * Set fechaegreso
     *
     * @param \DateTime $fechaegreso
     *
     * @return Egreso
     */
    public function setFechaegreso($fechaegreso)
    {
        $this->fechaegreso = $fechaegreso;

        return $this;
    }

    /**
     * Get fechaegreso
     *
     * @return \DateTime
     */
    public function getFechaegreso()
    {
        return $this->fechaegreso;
    }

    /**
     * Set idorden
     *
     * @param \SeguroBundle\Entity\Orden $idorden
     *
     * @return Egreso
     */
    public function setIdorden(\SeguroBundle\Entity\Orden $idorden = null)
    {
        $this->idorden = $idorden;

        return $this;
    }

    /**
     * Get idorden
     *
     * @return \SeguroBundle\Entity\Orden
     */
    public function getIdorden()
    {
        return $this->idorden;
    }

    /**
     * Set idusuario
     *
     * @param \SeguroBundle\Entity\Usuario $idusuario
     *
     * @return Egreso
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
