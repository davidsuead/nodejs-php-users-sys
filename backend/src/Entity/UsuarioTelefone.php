<?php

namespace App\Entity;

/**
 * UsuarioTelefone
 *
 * @Table(name="usuario_telefone")
 * @Entity(repositoryClass="App\Repository\UsuarioTelefoneRepository")
 */
class UsuarioTelefone
{
    /**
     * @var int
     *
     * @Column(name="id_usuario_telefone", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idUsuarioTelefone;

    /**
     * @var string
     *
     * @Column(name="desc_telefone", type="string", length=15, nullable=false)
     */
    private $descTelefone;

    /**
     * @var string
     *
     * @Column(name="indi_principal", type="string", length=1, nullable=false)
     */
    private $indiPrincipal;

    /**
     * @var \Usuario
     *
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumns({
     *   @JoinColumn(name="id_usuario", referencedColumnName="id_usuario")
     * })
     */
    private $usuario;

    /**
     * Get the value of idUsuarioTelefone
     *
     * @return  int
     */ 
    public function getIdUsuarioTelefone()
    {
        return $this->idUsuarioTelefone;
    }

    /**
     * Set the value of idUsuarioTelefone
     *
     * @param  int  $idUsuarioTelefone
     *
     * @return  self
     */ 
    public function setIdUsuarioTelefone($idUsuarioTelefone)
    {
        $this->idUsuarioTelefone = $idUsuarioTelefone;

        return $this;
    }

    /**
     * Get the value of descTelefone
     *
     * @return  string
     */ 
    public function getDescTelefone()
    {
        return $this->descTelefone;
    }

    /**
     * Set the value of descTelefone
     *
     * @param  string  $descTelefone
     *
     * @return  self
     */ 
    public function setDescTelefone($descTelefone)
    {
        $this->descTelefone = $descTelefone;

        return $this;
    }

    /**
     * Get the value of indiPrincipal
     *
     * @return  string
     */ 
    public function getIndiPrincipal()
    {
        return $this->indiPrincipal;
    }

    /**
     * Set the value of indiPrincipal
     *
     * @param  string  $indiPrincipal
     *
     * @return  self
     */ 
    public function setIndiPrincipal(string $indiPrincipal)
    {
        $this->indiPrincipal = $indiPrincipal;

        return $this;
    }

    /**
     * Get the value of usuario
     *
     * @return  \Usuario
     */ 
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set the value of usuario
     *
     * @param  \Usuario  $usuario
     *
     * @return  self
     */ 
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }
}
