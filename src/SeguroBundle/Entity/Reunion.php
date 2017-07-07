<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reunion
 *
 * @ORM\Table(name="reunion")
 * @ORM\Entity
 */
class Reunion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdReunion", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idreunion;

    /**
     * @var string
     *
     * @ORM\Column(name="Temas", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="No permiten valores nulos")
     */
    private $temas;

    /**
     * @var string
     *
     * @ORM\Column(name="Descripcion", type="string", length=300, nullable=false)
     * @Assert\NotBlank(message="No permiten valores nulos")
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaReunion", type="datetime", nullable=false)
     */
    protected $fechareunion;

    /**
     * @var float
     *
     * @ORM\Column(name="ValorReunion", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank(message="No permiten valores nulos")
     */
    private $valorreunion;

    /**
     * @var float
     *
     * @ORM\Column(name="ValorMulta", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank(message="No permiten valores nulos")
     */
    private $valormulta;

    /**
     * Get idreunion
     *
     * @return integer
     */
    public function getIdreunion()
    {
        return $this->idreunion;
    }

    /**
     * Set temas
     *
     * @param string $temas
     *
     * @return Reunion
     */
    public function setTemas($temas)
    {
        $this->temas = $temas;

        return $this;
    }

    /**
     * Get temas
     *
     * @return string
     */
    public function getTemas()
    {
        return $this->temas;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Reunion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fechareunion
     *
     * @param \DateTime $fechareunion
     *
     * @return Reunion
     */
    public function setFechareunion($fechareunion)
    {
        $this->fechareunion = $fechareunion;

        return $this;
    }

    /**
     * Get fechareunion
     *
     * @return \DateTime
     */
    public function getFechareunion()
    {
        return $this->fechareunion;
    }

    /**
     * Set valorreunion
     *
     * @param float $valorreunion
     *
     * @return Reunion
     */
    public function setValorreunion($valorreunion)
    {
        $this->valorreunion = $valorreunion;

        return $this;
    }

    /**
     * Get valorreunion
     *
     * @return float
     */
    public function getValorreunion()
    {
        return $this->valorreunion;
    }

    /**
     * Set valormulta
     *
     * @return float
     */
    public function setValormulta($valormulta)
    {
        $this->valormulta = $valormulta;

        return $this;
    }

    /**
     * Get valormulta
     *
     * @return float
     */
    public function getValormulta()
    {
        return $this->valormulta;
    }
}
