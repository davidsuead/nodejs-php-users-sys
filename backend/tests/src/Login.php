<?php

/**
 * Classe de teste Funcionalidade Login com Portal Goias
 * PSGO001_Manter_Login
 */

namespace Tests;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit_Framework_TestCase;

class Login extends PHPUnit_Framework_TestCase {

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var RemoteWebDriver
     */
    protected $webDriver;

    /**
     * Configuração do ambiente de teste.
     */
    public function setUp() {
        $capabilities = DesiredCapabilities::chrome();
        $this->configuration = new Configuration($this->webDriver);
        $this->webDriver = RemoteWebDriver::create($this->configuration->host(), $capabilities);
    }

    /**
     * Executa [FE001] – Campos obrigatórios não preenchidos.
     */
    public function testFE001CamposObrigatorios() {
        $this->webDriver->get($this->configuration->urlSistema() . "admin");
        // Usuario
        $this->webDriver->findElement(WebDriverBy::id("username"))->sendKeys('');

        // Senha
        $this->webDriver->findElement(WebDriverBy::id("password"))->sendKeys('');

        /*
         * Clicar em Autenticar
         */
        $this->webDriver->findElement(WebDriverBy::className('btnLogin'))->click();
        sleep(3);
        $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
        $this->assertEquals(preg_match('/Usuário é um Campo Obrigatório/', $msg), 1);
        sleep(3);
        /**
         * Fecha a Tela
         */
        $this->webDriver->switchTo()->window($this->webDriver->getWindowHandle())->close();
    }

    /**
     * Executa FE002 – Usuário ou Senha Inválido.
     */
    public function testFE002UsuarioSenhaInvalido() {
        $this->webDriver->get($this->configuration->urlSistema() . "admin");
        // Usuario
        $this->webDriver->findElement(WebDriverBy::id("username"))->sendKeys('dasdsdsa');

        // Senha
        $this->webDriver->findElement(WebDriverBy::id("password"))->sendKeys('dsadsdsa');
        /*
         * Clicar em Autenticar
         */
        $this->webDriver->findElement(WebDriverBy::className('btnLogin'))->click();
        sleep(3);
        $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
        $this->assertEquals(preg_match('/Usuário inválido! Verifique/', $msg), 1);
        sleep(3);
        /**
         * Fecha a Tela
         */
        $this->webDriver->switchTo()->window($this->webDriver->getWindowHandle())->close();
    }

    /**
     * Executa FE005 – Sem acesso a aplicação
     */
    public function testFE005SemAcessoAplicacao() {
        $this->webDriver->get($this->configuration->urlSistema() . "admin");
        // Usuario
        $this->webDriver->findElement(WebDriverBy::id("username"))->sendKeys('123438110');

        // Senha
        $this->webDriver->findElement(WebDriverBy::id("password"))->sendKeys('teste123');
        /*
         * Clicar em Autenticar
         */
        $this->webDriver->findElement(WebDriverBy::className('btnLogin'))->click();
        sleep(3);
        $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
        $this->assertEquals(preg_match('/Você não possui permissão de acesso nesta aplicação/', $msg), 1);
        sleep(3);
        /**
         * Fecha a Tela
         */
        $this->webDriver->switchTo()->window($this->webDriver->getWindowHandle())->close();
    }

    /**
     * Executa FE006 – Conta Inativa
     */
    public function testFE006ContaInativa() {
        $this->webDriver->get($this->configuration->urlSistema() . "admin");
        // Usuario
        $this->webDriver->findElement(WebDriverBy::id("username"))->sendKeys('123476127');

        // Senha
        $this->webDriver->findElement(WebDriverBy::id("password"))->sendKeys('teste123');
        /*
         * Clicar em Autenticar
         */
        $this->webDriver->findElement(WebDriverBy::className('btnLogin'))->click();
        sleep(3);
        $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
        $this->assertEquals(preg_match('/está INATIVO/', $msg), 1);
        sleep(3);
        /**
         * Fecha a Tela
         */
        $this->webDriver->switchTo()->window($this->webDriver->getWindowHandle())->close();
    }

    /**
     * Executa Fluxo Básico
     */
    public function testFluxoBasicoLogadoComSucesso() {
        $this->webDriver->get($this->configuration->urlSistema() . "admin");
        // Usuario
        $this->webDriver->findElement(WebDriverBy::id("username"))->sendKeys($this->configuration->usuario());

        // Senha
        $this->webDriver->findElement(WebDriverBy::id("password"))->sendKeys($this->configuration->senha());

        /*
         * Clicar em Autenticar
         */
        $this->webDriver->findElement(WebDriverBy::className('btnLogin'))->click();
        sleep(4);
        $resultado = trim($this->webDriver->findElement(WebDriverBy::className("page-title"))->getText());
        $this->assertEquals('Inicial', $resultado);
        $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
        sleep(3);
        $this->webDriver->switchTo()->window($this->webDriver->getWindowHandle())->close();
    }

    /**
     * Executa Alternativo - FA001 – Logout do Sistema
     */
    public function testFA001LogoutSistema() {
        $this->webDriver->get($this->configuration->urlSistema() . "admin");
        // Usuario
        $this->webDriver->findElement(WebDriverBy::id("username"))->sendKeys($this->configuration->usuario());

        // Senha
        $this->webDriver->findElement(WebDriverBy::id("password"))->sendKeys($this->configuration->senha());

        /*
         * Clicar em Autenticar
         */
        $this->webDriver->findElement(WebDriverBy::className('btnLogin'))->click();
        sleep(4);
        $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
        sleep(2);
        $this->webDriver->switchTo()->defaultContent();
        $this->webDriver->findElement(WebDriverBy::className("deslogar"))->click();
        sleep(3);
        $formLogin = trim($this->webDriver->findElement(WebDriverBy::xpath("//form[@id='form-login']"))->getText());
        $this->assertNotEmpty($formLogin);
        $this->webDriver->switchTo()->window($this->webDriver->getWindowHandle())->close();
    }

}
