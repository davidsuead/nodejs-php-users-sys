<?php

namespace App\Entity;

/**
 * UsuarioBiometria
 *
 * @Table(name="usuario_biometria")
 * @Entity(repositoryClass="App\Repository\UsuarioBiometriaRepository")
 */
class UsuarioBiometria
{
    /**
     * @var int
     *
     * @Column(name="id_biometria", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idBiometria;

    /**
     * @var string
     *
     * @Column(name="desc_nome_original", type="string", length=100, nullable=false)
     */
    private $descNomeOriginal;

    /**
     * @var string
     *
     * @Column(name="desc_nome", type="string", length=100, nullable=false)
     */
    private $descNome;

    /**
     * @var string
     *
     * @Column(name="desc_caminho_arquivo", type="string", length=300, nullable=false)
     */
    private $descCaminhoArquivo;

    /**
     * @var \DateTime
     *
     * @Column(name="data_upload", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dataUpload = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @Column(name="numr_ip_upload", type="string", length=15, nullable=false)
     */
    private $numrIpUpload;

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
     * Get the value of idBiometria
     *
     * @return  int
     */ 
    public function getIdBiometria()
    {
        return $this->idBiometria;
    }

    /**
     * Set the value of idBiometria
     *
     * @param  int  $idBiometria
     *
     * @return  self
     */ 
    public function setIdBiometria($idBiometria)
    {
        $this->idBiometria = $idBiometria;

        return $this;
    }

    /**
     * Get the value of descNomeOriginal
     *
     * @return  string
     */ 
    public function getDescNomeOriginal()
    {
        return $this->descNomeOriginal;
    }

    /**
     * Set the value of descNomeOriginal
     *
     * @param  string  $descNomeOriginal
     *
     * @return  self
     */ 
    public function setDescNomeOriginal($descNomeOriginal)
    {
        $this->descNomeOriginal = $descNomeOriginal;

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
     * Get the value of descCaminhoArquivo
     *
     * @return  string
     */ 
    public function getDescCaminhoArquivo()
    {
        return $this->descCaminhoArquivo;
    }

    /**
     * Set the value of descCaminhoArquivo
     *
     * @param  string  $descCaminhoArquivo
     *
     * @return  self
     */ 
    public function setDescCaminhoArquivo($descCaminhoArquivo)
    {
        $this->descCaminhoArquivo = $descCaminhoArquivo;

        return $this;
    }

    /**
     * Get the value of dataUpload
     *
     * @return  \DateTime
     */ 
    public function getDataUpload()
    {
        return $this->dataUpload;
    }

    /**
     * Set the value of dataUpload
     *
     * @param  \DateTime  $dataUpload
     *
     * @return  self
     */ 
    public function setDataUpload($dataUpload)
    {
        $this->dataUpload = $dataUpload;

        return $this;
    }

    /**
     * Get the value of numrIpUpload
     *
     * @return  string
     */ 
    public function getNumrIpUpload()
    {
        return $this->numrIpUpload;
    }

    /**
     * Set the value of numrIpUpload
     *
     * @param  string  $numrIpUpload
     *
     * @return  self
     */ 
    public function setNumrIpUpload($numrIpUpload)
    {
        $this->numrIpUpload = $numrIpUpload;

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
