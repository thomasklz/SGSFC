<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipoegreso
 *
 * @ORM\Table(name="tipoegreso")
 * @ORM\Entity
 */
class Tipoegreso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdTipoEgreso", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtipoegreso;

    /**
     * @var string
     *
     * @ORM\Column(name="NombreEgreso", type="string", length=100, nullable=false)
     */
    private $nombreegreso;



    /**
     * Get idtipoegreso
     *
     * @return integer
     */
    public function getIdtipoegreso()
    {
        return $this->idtipoegreso;
    }

    /**
     * Set nombreegreso
     *
     * @param string $nombreegreso
     *
     * @return Tipoegreso
     */
    public function setNombreegreso($nombreegreso)
    {
        $this->nombreegreso = $nombreegreso;

        return $this;
    }

    /**
     * Get nombreegreso
     *
     * @return string
     */
    public function getNombreegreso()
    {
        return $this->nombreegreso;
    }
}
