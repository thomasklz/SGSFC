<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orden
 *
 * @ORM\Table(name="orden", indexes={@ORM\Index(name="idbono_idx", columns={"IdBono"}), @ORM\Index(name="idusuario_1_idx", columns={"IdUsuario"}), @ORM\Index(name="idafiliado_1_idx", columns={"IdAfiliado"})})
 * @ORM\Entity
 */
class Orden
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdOrden", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idorden;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaOrden", type="datetime", nullable=true)
     */
    private $fechaorden;

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
     * @var \Bono
     *
     * @ORM\ManyToOne(targetEntity="Bono")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdBono", referencedColumnName="IdBono")
     * })
     */
    private $idbono;

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
     * Get idorden
     *
     * @return integer
     */
    public function getIdorden()
    {
        return $this->idorden;
    }

    /**
     * Set fechaorden
     *
     * @param \DateTime $fechaorden
     *
     * @return Orden
     */
    public function setFechaorden($fechaorden)
    {
        $this->fechaorden = $fechaorden;

        return $this;
    }

    /**
     * Get fechaorden
     *
     * @return \DateTime
     */
    public function getFechaorden()
    {
        return $this->fechaorden;
    }

    /**
     * Set idafiliado
     *
     * @param \SeguroBundle\Entity\Afiliado $idafiliado
     *
     * @return Orden
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
     * Set idbono
     *
     * @param \SeguroBundle\Entity\Bono $idbono
     *
     * @return Orden
     */
    public function setIdbono(\SeguroBundle\Entity\Bono $idbono = null)
    {
        $this->idbono = $idbono;

        return $this;
    }

    /**
     * Get idbono
     *
     * @return \SeguroBundle\Entity\Bono
     */
    public function getIdbono()
    {
        return $this->idbono;
    }

    /**
     * Set idusuario
     *
     * @param \SeguroBundle\Entity\Usuario $idusuario
     *
     * @return Orden
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
