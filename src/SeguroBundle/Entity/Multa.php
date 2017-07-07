<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Multa
 *
 * @ORM\Table(name="multa", indexes={@ORM\Index(name="idafiliado_idx", columns={"IdAfiliado"}), @ORM\Index(name="idreunion_idx", columns={"IdReunion"})})
 * @ORM\Entity
 */
class Multa
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdMulta", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmulta;

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
     * @var \Reunion
     *
     * @ORM\ManyToOne(targetEntity="Reunion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdReunion", referencedColumnName="IdReunion")
     * })
     */
    private $idreunion;



    /**
     * Get idmulta
     *
     * @return integer
     */
    public function getIdmulta()
    {
        return $this->idmulta;
    }

    /**
     * Set idafiliado
     *
     * @param \SeguroBundle\Entity\Afiliado $idafiliado
     *
     * @return Multa
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
     * Set idreunion
     *
     * @param \SeguroBundle\Entity\Reunion $idreunion
     *
     * @return Multa
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
}
