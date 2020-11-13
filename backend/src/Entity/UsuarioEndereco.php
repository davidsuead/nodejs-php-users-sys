<?php

namespace App\Entity;

/**
 * UsuarioEndereco
 *
 * @Table(name="usuario_endereco")
 * @Entity(repositoryClass="App\Repository\UsuarioEnderecoRepository")
 */
class UsuarioEndereco
{
    /**
     * @var int
     *
     * @Column(name="id_endereco", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idEndereco;

    /**
     * @var string
     *
     * @Column(name="desc_rua", type="string", length=100, nullable=false)
     */
    private $descRua;

    /**
     * @var int|null
     *
     * @Column(name="numr_endereco", type="smallint", nullable=true)
     */
    private $numrEndereco;

    /**
     * @var string|null
     *
     * @Column(name="desc_complemento", type="string", length=255, nullable=true)
     */
    private $descComplemento;

    /**
     * @var string
     *
     * @Column(name="desc_bairro", type="string", length=100, nullable=false)
     */
    private $descBairro;

    /**
     * @var string
     *
     * @Column(name="desc_cidade", type="string", length=100, nullable=false)
     */
    private $descCidade;

    /**
     * @var string
     *
     * @Column(name="desc_uf", type="string", length=2, nullable=false, options={"fixed"=true})
     */
    private $descUf;

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
     * Get the value of idEndereco
     *
     * @return  int
     */ 
    public function getIdEndereco()
    {
        return $this->idEndereco;
    }

    /**
     * Set the value of idEndereco
     *
     * @param  int  $idEndereco
     *
     * @return  self
     */ 
    public function setIdEndereco($idEndereco)
    {
        $this->idEndereco = $idEndereco;

        return $this;
    }

    /**
     * Get the value of descRua
     *
     * @return  string
     */ 
    public function getDescRua()
    {
        return $this->descRua;
    }

    /**
     * Set the value of descRua
     *
     * @param  string  $descRua
     *
     * @return  self
     */ 
    public function setDescRua($descRua)
    {
        $this->descRua = $descRua;

        return $this;
    }

    /**
     * Get the value of numrEndereco
     *
     * @return  int|null
     */ 
    public function getNumrEndereco()
    {
        return $this->numrEndereco;
    }

    /**
     * Set the value of numrEndereco
     *
     * @param  int|null  $numrEndereco
     *
     * @return  self
     */ 
    public function setNumrEndereco($numrEndereco)
    {
        $this->numrEndereco = $numrEndereco;

        return $this;
    }

    /**
     * Get the value of descComplemento
     *
     * @return  string|null
     */ 
    public function getDescComplemento()
    {
        return $this->descComplemento;
    }

    /**
     * Set the value of descComplemento
     *
     * @param  string|null  $descComplemento
     *
     * @return  self
     */ 
    public function setDescComplemento($descComplemento)
    {
        $this->descComplemento = $descComplemento;

        return $this;
    }

    /**
     * Get the value of descBairro
     *
     * @return  string
     */ 
    public function getDescBairro()
    {
        return $this->descBairro;
    }

    /**
     * Set the value of descBairro
     *
     * @param  string  $descBairro
     *
     * @return  self
     */ 
    public function setDescBairro($descBairro)
    {
        $this->descBairro = $descBairro;

        return $this;
    }

    /**
     * Get the value of descCidade
     *
     * @return  string
     */ 
    public function getDescCidade()
    {
        return $this->descCidade;
    }

    /**
     * Set the value of descCidade
     *
     * @param  string  $descCidade
     *
     * @return  self
     */ 
    public function setDescCidade($descCidade)
    {
        $this->descCidade = $descCidade;

        return $this;
    }

    /**
     * Get the value of descUf
     *
     * @return  string
     */ 
    public function getDescUf()
    {
        return $this->descUf;
    }

    /**
     * Set the value of descUf
     *
     * @param  string  $descUf
     *
     * @return  self
     */ 
    public function setDescUf($descUf)
    {
        $this->descUf = $descUf;

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
