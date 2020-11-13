<?php

/**
 * Classe de teste Funcionalidade Documentos
 * PSGO005_Manter_Documento
 */

namespace Tests;

use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit_Framework_TestCase;

class Documento extends PHPUnit_Framework_TestCase {

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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("nomeDocumentoServicoMsgError"))->getText());
            $this->assertEquals(preg_match('/Obrigatório/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FA004] – Cadastrar Novo Documento
     */
    public function testFA004CadastroNovoDocumentoSucesso() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // Nome do Documento
            $this->webDriver->findElement(WebDriverBy::id("nomeDocumentoServico"))->sendKeys('Teste Automatizado 1');
            // Dispobinilidade
            $javascript = <<<END_JAVASCRIPT
                    $('#idTipoDocumento').val('INF');
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
     * [FE004] – Nome Documento Duplicado.
     */
    public function testFE004NomeDeDocumentoDuplicado() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // Nome do Documento
            $this->webDriver->findElement(WebDriverBy::id("nomeDocumentoServico"))->sendKeys('Teste Automatizado 1');
            // Dispobinilidade
            $javascript = <<<END_JAVASCRIPT
                    $('#idTipoDocumento').val('INF');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("nomeDocumentoServicoMsgError"))->getText());
            $this->assertEquals(preg_match('/Já possui um registro com este nome/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FE006] – Tamanho do Arquivo Inválido.
     */
    public function testFE006TamanhoArquivoInvalido() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // Nome do Documento
            $this->webDriver->findElement(WebDriverBy::id("nomeDocumentoServico"))->sendKeys('Teste Automatizado Download');
            // Dispobinilidade
            $javascript = <<<END_JAVASCRIPT
                    $('#idTipoDocumento').val('VIS');
                    $('.gridArquivo').show();
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);

            // set local temp path
            $arquivo = __DIR__ . '/arquivo/imagemGrandeJPG.jpg';
            // getting the input element
            $fileInput = $this->webDriver->findElement(WebDriverBy::id('arquivo'));
            // set the file detector
            $fileInput->setFileDetector(new LocalFileDetector());
            // upload the file and submit the form
            $fileInput->sendKeys($arquivo);
            $this->webDriver->findElement(WebDriverBy::id("file"))->sendKeys($arquivo);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("fileMsgError"))->getText());
            $this->assertEquals(preg_match('/Tamanho máximo aceito para o arquivo/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FE007] – Extensão do Arquivo Inválido.
     */
    public function testFE007ExtensaoArquivoInvalido() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // Nome do Documento
            $this->webDriver->findElement(WebDriverBy::id("nomeDocumentoServico"))->sendKeys('Teste Automatizado Download');
            // Dispobinilidade
            $javascript = <<<END_JAVASCRIPT
                    $('#idTipoDocumento').val('VIS');
                    $('.gridArquivo').show();
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);

            // set local temp path
            $arquivo = __DIR__ . '/arquivo/arquivo.txt';
            // getting the input element
            $fileInput = $this->webDriver->findElement(WebDriverBy::id('arquivo'));
            // set the file detector
            $fileInput->setFileDetector(new LocalFileDetector());
            // upload the file and submit the form
            $fileInput->sendKeys($arquivo);
            $this->webDriver->findElement(WebDriverBy::id("file"))->sendKeys($arquivo);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("fileMsgError"))->getText());
            $this->assertEquals(preg_match('/Formato do arquivo é inválido/', $msg), 1);
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            // Nome do Documento
            $this->webDriver->findElement(WebDriverBy::id("nomeDocumentoServico"))->sendKeys('automatizado');
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-success']"))->click();
            sleep(2);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[contains(text(),'Teste Automatizado 1')]"))->getText());
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            // Nome da Categoria
            $this->webDriver->findElement(WebDriverBy::id("nomeDocumentoServico"))->sendKeys('automatizado');
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-success']"))->click();
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-default']"))->click();
            sleep(2);
            $dados = $this->webDriver->findElement(WebDriverBy::id("nomeDocumentoServico"))->getAttribute('value');
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
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
     *  [FA005] – Editar Documento.
     */
    public function testFA005EditarDocumento() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("row0grid"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // Nome da Categoria
            $this->webDriver->findElement(WebDriverBy::id("nomeDocumentoServico"))->sendKeys(' - alterado');
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("row0grid"))->click();
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("row0grid"))->click();
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
     *  [FE005] – Registro com dependência
     */
    public function testFA005RegistroComDependencia() {
        require_once 'criarDependenciaDocumento.php';
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("row0grid"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("btnExcluir"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("btnExcluir"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
            $this->assertEquals(preg_match('/Não foi possível excluir o registro pois o mesmo/', $msg), 1);
            require_once 'limparDependenciaDocumento.php';
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/documento");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("row0grid"))->click();
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

}
