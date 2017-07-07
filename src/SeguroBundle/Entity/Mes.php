<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mes
 *
 * @ORM\Table(name="mes")
 * @ORM\Entity
 */
class Mes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdMes", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmes;

    /**
     * @var string
     *
     * @ORM\Column(name="Mes", type="string", length=45, nullable=true)
     */
    private $mes;

    /**
     * @var string
     *
     * @ORM\Column(name="Valor", type="string", length=45, nullable=true)
     */
    private $valor;

    /**
     * @var \Date
     *
     * @ORM\Column(name="Fecha", type="date", nullable=true)
     */
    private $fecha;



    /**
     * Get idmes
     *
     * @return integer
     */
    public function getIdmes()
    {
        return $this->idmes;
    }

    /**
     * Set mes
     *
     * @param string $mes
     *
     * @return Mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return string
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set valor
     *
     * @param string $valor
     *
     * @return Mes
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set fecha
     *
     * @param \Date $fecha
     *
     * @return Mes
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \Date
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}
