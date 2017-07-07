<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bono
 *
 * @ORM\Table(name="bono")
 * @ORM\Entity
 */
class Bono
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdBono", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idbono;

    /**
     * @var string
     *
     * @ORM\Column(name="Bono", type="string", length=100, nullable=false)
     */
    private $bono;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaIngreso", type="date", nullable=false)
     */
    private $fechaingreso;

    /**
     * @var float
     *
     * @ORM\Column(name="Valor", type="float", precision=10, scale=0, nullable=false)
     */
    private $valor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Estado", type="boolean", nullable=false)
     */
    private $estado;



    /**
     * Get idbono
     *
     * @return integer
     */
    public function getIdbono()
    {
        return $this->idbono;
    }

    /**
     * Set bono
     *
     * @param string $bono
     *
     * @return Bono
     */
    public function setBono($bono)
    {
        $this->bono = $bono;

        return $this;
    }

    /**
     * Get bono
     *
     * @return string
     */
    public function getBono()
    {
        return $this->bono;
    }

    /**
     * Set fechaingreso
     *
     * @param \DateTime $fechaingreso
     *
     * @return Bono
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
     * Set valor
     *
     * @param float $valor
     *
     * @return Bono
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
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Bono
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
