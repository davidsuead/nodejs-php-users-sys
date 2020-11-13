<?php

/**
 * Classe de teste Funcionalidade Acessos de Usuários
 * PSGO002_Manter_Acesso_Usuario
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

class AcessoUsuario extends PHPUnit_Framework_TestCase {

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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("cpfUsuarioMsgError"))->getText());
            $this->assertEquals(preg_match('/Obrigatório/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FE005] – Órgão não selecionado.
     */
    public function testFE005OrgaoNaoSelecionado() {
        try {
            require_once 'criarDependenciaAcessoUsuario.php';
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // CPF do Usuário
            $this->webDriver->findElement(WebDriverBy::id("cpfUsuario"))->sendKeys('001.234.511-32');
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::className("btnSalvar"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("btnSalvar"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
            $this->assertEquals(preg_match('/Selecione pelo menos 1 Órgão/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FE006] – Usuário não possui acesso a funcionalidade.
     */
    public function testFE006UsuarioNaoPossuiAcessoaFuncionalidade() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // CPF do Usuário
            $this->webDriver->findElement(WebDriverBy::id("cpfUsuario"))->sendKeys('012.344.921-99');
            // Descrição do Órgão
            $javascript = <<<END_JAVASCRIPT
                $('.idPrestadorServico') . prop("checked", true);
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::className("btnSalvar"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
            $this->assertEquals(preg_match('/Esse usuário não tem permissão na Funcionalidade/', $msg), 1);
            // Sair do sistema.
            sleep(2);
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FA004] – Cadastrar Novo Acesso.
     */
    public function testFA004CadastrarNovoAcessoSucesso() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // CPF do Usuário
            $this->webDriver->findElement(WebDriverBy::id("cpfUsuario"))->sendKeys('001.234.511-32');
            // Descrição do Órgão
            $javascript = <<<END_JAVASCRIPT
                $('.idPrestadorServico') . prop("checked", true);
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::className("btnSalvar"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("btnSalvar"))->click();
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
     * [FE004] – CPF duplicado
     */
    public function testFE004CPFDuplicado() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // CPF do Usuário
            $this->webDriver->findElement(WebDriverBy::id("cpfUsuario"))->sendKeys('001.234.511-32');
            // Descrição do Órgão
            $javascript = <<<END_JAVASCRIPT
                $('.idPrestadorServico') . prop("checked", true);
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::className("btnSalvar"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("btnSalvar"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("cpfUsuarioMsgError"))->getText());
            $this->assertEquals(preg_match('/Já possui um registro com este CPF/', $msg), 1);
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            // CPF
            $this->webDriver->findElement(WebDriverBy::id("numrCpfUsuario"))->sendKeys('001.234.511-32');
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-success']"))->click();
            sleep(2);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[contains(text(),'123451132')]"))->getText());
            $this->assertEquals(preg_match('/123451132/', $msg), 1);
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
//            // CPF
            $this->webDriver->findElement(WebDriverBy::id("numrCpfUsuario"))->sendKeys('001.234.511-32');
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-success']"))->click();
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-default']"))->click();
            sleep(2);
            $dados = $this->webDriver->findElement(WebDriverBy::id("numrCpfUsuario"))->getAttribute('value');
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
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
     *  [FA005] – Editar Acesso Usuário.
     */
    public function testFA005EditarOrgao() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("row0grid"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
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
     *  [FA006] – Excluir Registro
     */
    public function testFA006ExcluirRegistro() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/acesso-usuario");
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
            require_once 'limparDependenciaAcessoUsuario.php';

            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

}
