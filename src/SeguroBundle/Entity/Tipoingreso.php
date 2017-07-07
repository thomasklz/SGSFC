<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipoingreso
 *
 * @ORM\Table(name="tipoingreso")
 * @ORM\Entity
 */
class Tipoingreso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdTipoIngreso", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtipoingreso;

    /**
     * @var string
     *
     * @ORM\Column(name="NombreIngreso", type="string", length=100, nullable=false)
     */
    private $nombreingreso;



    /**
     * Get idtipoingreso
     *
     * @return integer
     */
    public function getIdtipoingreso()
    {
        return $this->idtipoingreso;
    }

    /**
     * Set nombreingreso
     *
     * @param string $nombreingreso
     *
     * @return Tipoingreso
     */
    public function setNombreingreso($nombreingreso)
    {
        $this->nombreingreso = $nombreingreso;

        return $this;
    }

    /**
     * Get nombreingreso
     *
     * @return string
     */
    public function getNombreingreso()
    {
        return $this->nombreingreso;
    }
}
