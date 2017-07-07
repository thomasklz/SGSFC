<?php

namespace SeguroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", indexes={@ORM\Index(name="idtipousuario_idx", columns={"IdTipoUsuario"}), @ORM\Index(name="idafiliado_idx", columns={"IdAfiliado"})})
 * @ORM\Entity
 */
class Usuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IdUsuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="Usuario", type="string", length=20, nullable=true)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=100, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="Salt", type="string", length=100, nullable=true)
     */
    private $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaCreacion", type="datetime", nullable=true)
     */
    private $fechacreacion;

    /**
     * @var string
     *
     * @ORM\Column(name="Foto", type="string", length=50, nullable=false)
     */
    private $foto;

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
     * @var \Tipousuario
     *
     * @ORM\ManyToOne(targetEntity="Tipousuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdTipoUsuario", referencedColumnName="IdTipoUsuario")
     * })
     */
    private $idtipousuario;



    /**
     * Get idusuario
     *
     * @return integer
     */
    public function getIdusuario()
    {
        return $this->idusuario;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return Usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Usuario
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

    /**
     * Set fechacreacion
     *
     * @param \DateTime $fechacreacion
     *
     * @return Usuario
     */
    public function setFechacreacion($fechacreacion)
    {
        $this->fechacreacion = $fechacreacion;

        return $this;
    }

    /**
     * Get fechacreacion
     *
     * @return \DateTime
     */
    public function getFechacreacion()
    {
        return $this->fechacreacion;
    }

    /**
     * Set foto
     *
     * @param string $foto
     *
     * @return Usuario
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set idafiliado
     *
     * @param \SeguroBundle\Entity\Afiliado $idafiliado
     *
     * @return Usuario
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
     * Set idtipousuario
     *
     * @param \SeguroBundle\Entity\Tipousuario $idtipousuario
     *
     * @return Usuario
     */
    public function setIdtipousuario(\SeguroBundle\Entity\Tipousuario $idtipousuario = null)
    {
        $this->idtipousuario = $idtipousuario;

        return $this;
    }

    /**
     * Get idtipousuario
     *
     * @return \SeguroBundle\Entity\Tipousuario
     */
    public function getIdtipousuario()
    {
        return $this->idtipousuario;
    }
}
