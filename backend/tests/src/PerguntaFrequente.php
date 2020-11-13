<?php

/**
 * Classe de teste Funcionalidade Perguntas Frequentes
 * PSGO008_Manter_Perguntas_Frequentes
 */

namespace Tests;

use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit_Framework_TestCase;

class PerguntaFrequente extends PHPUnit_Framework_TestCase {

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

        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
        $this->configuration = new Configuration($this->webDriver);
    }

    /**
     * [FE001] – Nenhum registro encontrado.
     */
    public function testFE001NenhumRegistroEncontrado() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(5);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//span[contains(text(),'Nenhum registro encontrado')]"))->getText());
            $this->assertEquals(preg_match('/Nenhum registro encontrado/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FE002] – Campos obrigatórios não preenchidos.
     */
    public function testFE002CamposObrigatoriosNaoPreenchidos() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("descPerguntaFormuladaMsgError"))->getText());
            $this->assertEquals(preg_match('/Obrigatório/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FA004] – Cadastrar Nova Pergunta.
     */
    public function testFA004CadastraNovaPerguntaSucesso() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // Pergunta
            $this->webDriver->findElement(WebDriverBy::id("descPerguntaFormulada"))->sendKeys('Teste Automatizado 1');
            // Resposta
            $javascript = <<<END_JAVASCRIPT
                    $('.jqte_editor').text('Teste Automatizado 1');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
            $this->assertEquals(preg_match('/Registro gravado com sucesso/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     *  [FA001] – Pesquisar Registro.
     */
    public function testFA001PesquisarRegistro() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            // Pergunta
            $this->webDriver->findElement(WebDriverBy::id("descPerguntaFormulada"))->sendKeys('automatizado');
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-success']"))->click();
            sleep(2);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//a[contains(text(),'Teste Automatizado 1')]"))->getText());
            $this->assertEquals(preg_match('/Teste Automatizado/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     *  [FA009] – Limpar Dados do Filtro.
     */
    public function testFA009LimparDadosDoFiltro() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            // Nome do Órgão
            $this->webDriver->findElement(WebDriverBy::id("descPerguntaFormulada"))->sendKeys('automatizado');
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-success']"))->click();
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-default']"))->click();
            sleep(2);
            $dados = $this->webDriver->findElement(WebDriverBy::id("descPerguntaFormulada"))->getAttribute('value');
            $this->assertEmpty($dados);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     *  [FA002] – Exportar XLSx.
     */
    public function testFA002ExportarXLSx() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("exportXls"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::className("containerNotificacaoGeral"))->getText());
            $this->assertEmpty($msg);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     *  [FA003] – Exportar PDF.
     */
    public function testFA003ExportarPdf() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("exportPdf"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::className("containerNotificacaoGeral"))->getText());
            $this->assertEmpty($msg);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     *  [FA005] – Editar Pergunta.
     */
    public function testFA005EditarPergunta() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[contains(text(),'Teste Automatizado 1')]"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // Pergunta
            $this->webDriver->findElement(WebDriverBy::id("descPerguntaFormulada"))->sendKeys(' - alterado');
            // Resposta
            $javascript = <<<END_JAVASCRIPT
                    $('.jqte_editor').text('Teste Automatizado 1 - alterado');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
            $this->assertEquals(preg_match('/Registro gravado com sucesso/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     *  [FA007] – Voltar Lista
     */
    public function testFA007VoltarLista() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[contains(text(),'Teste Automatizado 1')]"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("btnVoltar"))->click();
            sleep(3);
            $lista = trim($this->webDriver->findElement(WebDriverBy::id("containergridListagem"))->getText());
            $this->assertNotEmpty($lista);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     *  [FA008] – Cancelar Exclusão.
     */
    public function testFA008CancelarExclusao() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[contains(text(),'Teste Automatizado 1')]"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("btnExcluir"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("btnCancelar"))->click();
            sleep(3);
            $modal = trim($this->webDriver->findElement(WebDriverBy::xpath("//body[@class='pace-done']"))->getAttribute('class'));
            $this->assertEquals(preg_match('/modal-open/', $modal), 0);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     *  [FA006] – Excluir Registro
     */
    public function testFA006ExcluirRegistro() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[contains(text(),'Teste Automatizado 1')]"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("btnExcluir"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("btnExcluir"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
            $this->assertEquals(preg_match('/Registro excluído com sucesso/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     *  FA010 – Alterar Ordem Registro
     */
    public function testFA010AlterarOrdemRegistro() {
        try {
            require_once 'criarDependenciaPergunta.php';
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/pergunta-frequente");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("fa-caret-square-o-down"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("fa-caret-square-o-up"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("row0grid"))->getText());
            $this->assertEquals(preg_match('/Automatizado 2/', $msg), 1);
            require_once 'limparDependenciaPergunta.php';
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

}
