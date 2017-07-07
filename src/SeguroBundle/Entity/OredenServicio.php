<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OredenServicio
 *
 * @ORM\Table(name="oreden_servicio", indexes={@ORM\Index(name="idorden_idx", columns={"IdOrden"}), @ORM\Index(name="idservicio_idx", columns={"IdServicio"})})
 * @ORM\Entity
 */
class OredenServicio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdOrdenServicio", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idordenservicio;

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
     * @var \Servicio
     *
     * @ORM\ManyToOne(targetEntity="Servicio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdServicio", referencedColumnName="IdServicio")
     * })
     */
    private $idservicio;



    /**
     * Get idordenservicio
     *
     * @return integer
     */
    public function getIdordenservicio()
    {
        return $this->idordenservicio;
    }

    /**
     * Set idorden
     *
     * @param \SeguroBundle\Entity\Orden $idorden
     *
     * @return OredenServicio
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
     * Set idservicio
     *
     * @param \SeguroBundle\Entity\Servicio $idservicio
     *
     * @return OredenServicio
     */
    public function setIdservicio(\SeguroBundle\Entity\Servicio $idservicio = null)
    {
        $this->idservicio = $idservicio;

        return $this;
    }

    /**
     * Get idservicio
     *
     * @return \SeguroBundle\Entity\Servicio
     */
    public function getIdservicio()
    {
        return $this->idservicio;
    }
}
