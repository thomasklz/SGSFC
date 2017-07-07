<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipousuario
 *
 * @ORM\Table(name="tipousuario")
 * @ORM\Entity
 */
class Tipousuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdTipoUsuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtipousuario;

    /**
     * @var string
     *
     * @ORM\Column(name="Tipo", type="string", length=15, nullable=true)
     */
    private $tipo;



    /**
     * Get idtipousuario
     *
     * @return integer
     */
    public function getIdtipousuario()
    {
        return $this->idtipousuario;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Tipousuario
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
