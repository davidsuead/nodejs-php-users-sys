<?php

namespace App\Entity;

/**
 * UsuarioToken
 *
 * @Table(name="usuario_token")
 * @Entity(repositoryClass="App\Repository\UsuarioTokenRepository")
 */
class UsuarioToken
{
    /**
     * @var int
     *
     * @Column(name="id_token", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idToken;

    /**
     * @var string
     *
     * @Column(name="access_token", type="string", length=255, nullable=false)
     */
    private $accessToken;

    /**
     * @var string
     *
     * @Column(name="tempo_expiracao", type="string", length=11, nullable=false)
     */
    private $tempoExpiracao;

    /**
     * @var string
     *
     * @Column(name="tempo_geracao", type="string", length=11, nullable=false)
     */
    private $tempoGeracao;

    /**
     * @var \DateTime
     *
     * @Column(name="data_registro", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dataRegistro = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @Column(name="valido", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $valido;

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
     * Get the value of idToken
     *
     * @return  int
     */ 
    public function getIdToken()
    {
        return $this->idToken;
    }

    /**
     * Set the value of idToken
     *
     * @param  int  $idToken
     *
     * @return  self
     */ 
    public function setIdToken($idToken)
    {
        $this->idToken = $idToken;

        return $this;
    }

    /**
     * Get the value of accessToken
     *
     * @return  string
     */ 
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the value of accessToken
     *
     * @param  string  $accessToken
     *
     * @return  self
     */ 
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get the value of tempoExpiracao
     *
     * @return  string
     */ 
    public function getTempoExpiracao()
    {
        return $this->tempoExpiracao;
    }

    /**
     * Set the value of tempoExpiracao
     *
     * @param  string  $tempoExpiracao
     *
     * @return  self
     */ 
    public function setTempoExpiracao($tempoExpiracao)
    {
        $this->tempoExpiracao = $tempoExpiracao;

        return $this;
    }

    /**
     * Get the value of tempoGeracao
     *
     * @return  string
     */ 
    public function getTempoGeracao()
    {
        return $this->tempoGeracao;
    }

    /**
     * Set the value of tempoGeracao
     *
     * @param  string  $tempoGeracao
     *
     * @return  self
     */ 
    public function setTempoGeracao($tempoGeracao)
    {
        $this->tempoGeracao = $tempoGeracao;

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
     * Get the value of valido
     *
     * @return  string
     */ 
    public function getValido()
    {
        return $this->valido;
    }

    /**
     * Set the value of valido
     *
     * @param  string  $valido
     *
     * @return  self
     */ 
    public function setValido($valido)
    {
        $this->valido = $valido;

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
