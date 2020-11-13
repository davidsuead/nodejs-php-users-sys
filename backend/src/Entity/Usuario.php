<?php

namespace App\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Usuario
 *
 * @Table(name="usuario")
 * @Entity(repositoryClass="App\Repository\UsuarioRepository")
 */
class Usuario
{
    /**
     * @var int
     *
     * @Column(name="id_usuario", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idUsuario;

    /**
     * @var string
     *
     * @Column(name="desc_nome", type="string", length=100, nullable=false)
     */
    private $descNome;

    /**
     * @var string
     *
     * @Column(name="numr_cpf", type="string", length=11, nullable=false, options={"fixed"=true})
     */
    private $numrCpf;

    /**
     * @var string
     *
     * @Column(name="desc_email", type="string", length=255, nullable=false)
     */
    private $descEmail;

    /**
     * @var string
     *
     * @Column(name="desc_senha", type="string", length=255, nullable=false)
     */
    private $descSenha;

    /**
     * @var \DateTime|null
     *
     * @Column(name="data_nascimento", type="date", nullable=true)
     */
    private $dataNascimento;

    /**
     * @var \DateTime
     *
     * @Column(name="data_registro", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dataRegistro = 'CURRENT_TIMESTAMP';

    /**
     * @var \UsuarioEndereco
     * Um usuário pode ter vários telefones
     * @OneToMany(targetEntity="UsuarioEndereco", mappedBy="usuario")
     * @OrderBy({"idEndereco" = "ASC"})
     */
    private $enderecos;

    /**
     * @var \UsuarioBiometria
     * Um usuário pode ter vários tokens
     * @OneToMany(targetEntity="UsuarioBiometria", mappedBy="usuario")
     * @OrderBy({"idBiometria" = "ASC"})
     */
    private $biometrias;

    /**
     * @var \UsuarioTelefone
     * Um usuário pode ter vários telefones
     * @OneToMany(targetEntity="UsuarioTelefone", mappedBy="usuario")
     * @OrderBy({"idUsuarioTelefone" = "ASC"})
     */
    private $telefones;

    /**
     * @var \UsuarioToken
     * Um usuário pode ter vários tokens
     * @OneToMany(targetEntity="UsuarioToken", mappedBy="usuario")
     * @OrderBy({"idToken" = "DESC"})
     */
    private $tokens;

    /**
     * Definir os ArrayColletions para as relações OneToMany
     */
    public function __construct() {
        $this->enderecos = new ArrayCollection();
        $this->biometrias = new ArrayCollection();
        $this->telefones = new ArrayCollection();
        $this->tokens = new ArrayCollection();
    }

    /**
     * Get the value of idUsuario
     *
     * @return  int
     */ 
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     *
     * @param  int  $idUsuario
     *
     * @return  self
     */ 
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of descNome
     *
     * @return  string
     */ 
    public function getDescNome()
    {
        return $this->descNome;
    }

    /**
     * Set the value of descNome
     *
     * @param  string  $descNome
     *
     * @return  self
     */ 
    public function setDescNome($descNome)
    {
        $this->descNome = $descNome;

        return $this;
    }

    /**
     * Get the value of numrCpf
     *
     * @return  string
     */ 
    public function getNumrCpf()
    {
        return $this->numrCpf;
    }

    /**
     * Set the value of numrCpf
     *
     * @param  string  $numrCpf
     *
     * @return  self
     */ 
    public function setNumrCpf($numrCpf)
    {
        $this->numrCpf = $numrCpf;

        return $this;
    }

    /**
     * Get the value of descEmail
     *
     * @return  string
     */ 
    public function getDescEmail()
    {
        return $this->descEmail;
    }

    /**
     * Set the value of descEmail
     *
     * @param  string  $descEmail
     *
     * @return  self
     */ 
    public function setDescEmail($descEmail)
    {
        $this->descEmail = $descEmail;

        return $this;
    }

    /**
     * Get the value of descSenha
     *
     * @return  string
     */ 
    public function getDescSenha()
    {
        return $this->descSenha;
    }

    /**
     * Set the value of descSenha
     *
     * @param  string  $descSenha
     *
     * @return  self
     */ 
    public function setDescSenha($descSenha)
    {
        $this->descSenha = password_hash($descSenha, PASSWORD_BCRYPT);

        return $this;
    }

    /**
     * Get the value of dataNascimento
     *
     * @return  \DateTime|null
     */ 
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * Set the value of dataNascimento
     *
     * @param  \DateTime|null  $dataNascimento
     *
     * @return  self
     */ 
    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;

        return $this;
    }

    /**
     * Get the value of dataRegistro
     *
     * @return  \DateTime
     */ 
    public function getDataRegistro()
    {
        return $this->dataRegistro;
    }

    /**
     * Set the value of dataRegistro
     *
     * @param  \DateTime  $dataRegistro
     *
     * @return  self
     */ 
    public function setDataRegistro($dataRegistro)
    {
        $this->dataRegistro = $dataRegistro;

        return $this;
    }

    /**
     * Get um usuário pode ter vários telefones
     *
     * @return  \UsuarioEndereco
     */ 
    public function getEnderecos()
    {
        return $this->enderecos;
    }

    /**
     * Get um usuário pode ter vários tokens
     *
     * @return  \UsuarioBiometria
     */ 
    public function getBiometrias()
    {
        return $this->biometrias;
    }

    /**
     * Get um usuário pode ter vários telefones
     *
     * @return  \UsuarioTelefone
     */ 
    public function getTelefones()
    {
        return $this->telefones;
    }

    /**
     * Get um usuário pode ter vários tokens
     *
     * @return  \UsuarioToken
     */ 
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * Retorna o telefone principal do usuário
     *
     * @return \UsuarioTelefone
     */
    public function getTelefonePrincipal()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('indiPrincipal', 'S'));
        return $this->telefones->matching($criteria);
    }

    /**
     * Retorna o telefone principal do usuário
     *
     * @return \UsuarioTelefone
     */
    public function getTelefoneOutro()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('indiPrincipal', 'N'));
        return $this->telefones->matching($criteria);
    }
}
