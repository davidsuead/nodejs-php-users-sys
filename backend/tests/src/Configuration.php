<?php

namespace Tests;

use Facebook\WebDriver\Interactions\Touch\WebDriverScrollAction;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverExpectedCondition;


class Configuration {

    public $url = 'http://psgo.local.go.gov.br/';
    public $host = 'http://localhost:4444/wd/hub';

    /**
     * @var RemoteWebDriver
     */
    protected $webDriver;
    protected $usuario = '00123451132';
    protected $senha = 'teste123';

    function __construct($webDriver) {
        $this->webDriver = $webDriver;
    }

    /**
     * Executa o login no sistema PSGO.
     */
    public function login() {
        $this->webDriver->get($this->url . "admin");
        // Usuario
        $this->webDriver->findElement(WebDriverBy::id("username"))->sendKeys($this->usuario);

        // Senha
        $this->webDriver->findElement(WebDriverBy::id("password"))->sendKeys($this->senha);

        /*
         * Clicar em Autenticar
         */
        $this->webDriver->findElement(WebDriverBy::className('btnLogin'))->click();
    }

    /**
     * Realiza o processo de sa�da do sistema e fecha a janela.
     */
    public function logout() {
        sleep(2);
        $this->webDriver->switchTo()->defaultContent();
        $this->webDriver->findElement(WebDriverBy::className("deslogar"))->click();
        /**
         * Fecha a Tela
         */
        $this->webDriver->switchTo()->window($this->webDriver->getWindowHandle())->close();
    }

    public function urlSistema() {
        return $this->url;
    }

    public function host() {
        return $this->host;
    }

    public function usuario() {
        return $this->usuario;
    }

    public function senha() {
        return $this->senha;
    }

    /**
     * Posiciona na primeira janela criada na fase de testes.
     */
    public function primeiraJanela() {
        $todasjanelas = $this->webDriver->getWindowHandles();
        $primeiraJanela = reset($todasjanelas);
        $this->webDriver->switchTo()->window($primeiraJanela);
    }

    /**
     * Fecha a primeira janela subsequente a atual.
     */
    public function fechaJanela() {
        $windows = $this->webDriver->getWindowHandles();

        // Identifica o handle da primeira janela.
        $firstWinHandle = $this->webDriver->getWindowHandle();

        // Percorre todas as janelas.
        foreach ($windows as $window) {

            // Fecha todas as outras janelas.
            if ($firstWinHandle != $window) {
                $this->webDriver->switchTo()->window($window)->close();
            }
        }

        // Posiciona na primeira janela.
        $this->webDriver->switchTo()->window($firstWinHandle);
    }

}
