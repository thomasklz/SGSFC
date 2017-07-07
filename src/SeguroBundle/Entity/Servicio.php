<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicio
 *
 * @ORM\Table(name="servicio")
 * @ORM\Entity
 */
class Servicio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdServicio", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idservicio;

    /**
     * @var string
     *
     * @ORM\Column(name="Servicio", type="string", length=100, nullable=false)
     */
    private $servicio;

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
     * Get idservicio
     *
     * @return integer
     */
    public function getIdservicio()
    {
        return $this->idservicio;
    }

    /**
     * Set servicio
     *
     * @param string $servicio
     *
     * @return Servicio
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;

        return $this;
    }

    /**
     * Get servicio
     *
     * @return string
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * Set fechaingreso
     *
     * @param \DateTime $fechaingreso
     *
     * @return Servicio
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
     * @return Servicio
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
     * @return Servicio
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
