<?php

/**
 * Classe de teste Funcionalidade Serviços
 * PSGO010_Manter_Servicos
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

class Servico extends PHPUnit_Framework_TestCase {

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
        require_once 'criarCargaServico.php';
    }

    /**
     * [FE001] – Nenhum registro encontrado.
     */
    public function testFE001NenhumRegistroEncontrado() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("statServicoMsgError"))->getText());
            $this->assertEquals(preg_match('/Obrigatório/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FE007] – Url Digital Inválida
     */
    public function testFE007UrlDigitalInvalida() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $javascript = <<<END_JAVASCRIPT
                    $("#statServico option").eq(1).attr('selected','selected');
                    $("#indiServicoDestaque option").eq(1).attr('selected','selected');
                    $("#idPrestadorServico option").eq(1).attr('selected', 'selected');    
                    $("#idPrestadorServico_chosen a span").text($("#idPrestadorServico option").eq(1).attr('selected', 'selected').text());
                    $("#idCategoriaServico option").eq(0).attr('selected', 'selected');
                    $('.jqte_editor').text('Teste Automatizado 1');
                    $('input[name="indiServicoDigital"]').prop("checked", true);
                    $("#idSegmentoSocial option").eq(0).attr('selected','selected');
                    $("#tipoTempoAtendto").val('E');
                    $("#contextoQtdeEstimativaInicial").show();
                    $("#contextoTempo").show();
                    $("#contextoQtdeEstimativaFinal").show();
                    $("#contextoTipoTempoEstimadoServico").show();
                    $("#nomePopularServico").val('Teste Automatizado 1');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            // Nome do Serviço
            $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->sendKeys('Teste Automatizado 1');
            // Sigla do Serviço
            $this->webDriver->findElement(WebDriverBy::id("siglServico"))->sendKeys('SERV1');
            // Nomes Populares
            $this->webDriver->findElement(WebDriverBy::id("descUrlServicoDigital"))->sendKeys('fdsafdsfdsfds');
            $javascript = <<<END_JAVASCRIPT
                    $("#tipoTempoEstimadoServico").val('D');
                    $("#qtdeEstimativaInicial").val('10');
                    $("#qtdeEstimativaFinal").val('20');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("descUrlServicoDigitalMsgError"))->getText());
            $this->assertEquals(preg_match('/Url informada é inválida/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FE008] – Tempo Início maior que Fim
     */
    public function testFE008TempoInicioMaiorQueFim() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $javascript = <<<END_JAVASCRIPT
                    $("#statServico option").eq(1).attr('selected','selected');
                    $("#indiServicoDestaque option").eq(1).attr('selected','selected');
                    $("#idPrestadorServico option").eq(1).attr('selected', 'selected');    
                    $("#idPrestadorServico_chosen a span").text($("#idPrestadorServico option").eq(1).attr('selected', 'selected').text());
                    $("#idCategoriaServico option").eq(0).attr('selected', 'selected');
                    $('.jqte_editor').text('Teste Automatizado 1');
                    $('input[name="indiServicoDigital"]').prop("checked", true);
                    $("#idSegmentoSocial option").eq(0).attr('selected','selected');
                    $("#tipoTempoAtendto").val('E');
                    $("#contextoQtdeEstimativaInicial").show();
                    $("#contextoTempo").show();
                    $("#contextoQtdeEstimativaFinal").show();
                    $("#contextoTipoTempoEstimadoServico").show();
                    $("#nomePopularServico").val('Teste Automatizado 1');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            // Nome do Serviço
            $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->sendKeys('Teste Automatizado 1');
            // Sigla do Serviço
            $this->webDriver->findElement(WebDriverBy::id("siglServico"))->sendKeys('SERV1');
            // Nomes Populares
            $this->webDriver->findElement(WebDriverBy::id("descUrlServicoDigital"))->sendKeys('http://servicoautomatizado.com.br');
            $javascript = <<<END_JAVASCRIPT
                    $("#tipoTempoEstimadoServico").val('D');
                    $("#qtdeEstimativaInicial").val('80');
                    $("#qtdeEstimativaFinal").val('20');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("qtdeEstimativaInicialMsgError"))->getText());
            $this->assertEquals(preg_match('/Inicio não pode ser maior ou igual a Fim/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FE009] – Solicitante Obrigatório
     */
    public function testFE009SolicitanteObrigatorio() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $javascript = <<<END_JAVASCRIPT
                    $("#statServico option").eq(1).attr('selected','selected');
                    $("#indiServicoDestaque option").eq(1).attr('selected','selected');
                    $("#idPrestadorServico option").eq(1).attr('selected', 'selected');    
                    $("#idPrestadorServico_chosen a span").text($("#idPrestadorServico option").eq(1).attr('selected', 'selected').text());
                    $("#idCategoriaServico option").eq(0).attr('selected', 'selected');
                    $('.jqte_editor').text('Teste Automatizado 1');
                    $('input[name="indiServicoDigital"]').prop("checked", true);
                    $("#idSegmentoSocial option").eq(0).attr('selected','selected');
                    $("#tipoTempoAtendto").val('E');
                    $("#contextoQtdeEstimativaInicial").show();
                    $("#contextoTempo").show();
                    $("#contextoQtdeEstimativaFinal").show();
                    $("#contextoTipoTempoEstimadoServico").show();
                    $("#nomePopularServico").val('Teste Automatizado 1');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            // Nome do Serviço
            $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->sendKeys('Teste Automatizado 1');
            // Sigla do Serviço
            $this->webDriver->findElement(WebDriverBy::id("siglServico"))->sendKeys('SERV1');
            // Nomes Populares
            $this->webDriver->findElement(WebDriverBy::id("descUrlServicoDigital"))->sendKeys('http://servicoautomatizado.com.br');
            $javascript = <<<END_JAVASCRIPT
                    $("#tipoTempoEstimadoServico").val('D');
                    $("#qtdeEstimativaInicial").val('10');
                    $("#qtdeEstimativaFinal").val('20');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
            $this->assertEquals(preg_match('/É obrigatório informar pelo menos/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FE010] – Etapa Obrigatório
     */
    public function testFE010EtapaObrigatorio() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $javascript = <<<END_JAVASCRIPT
                    $("#statServico option").eq(1).attr('selected','selected');
                    $("#indiServicoDestaque option").eq(1).attr('selected','selected');
                    $("#idPrestadorServico option").eq(1).attr('selected', 'selected');    
                    $("#idPrestadorServico_chosen a span").text($("#idPrestadorServico option").eq(1).attr('selected', 'selected').text());
                    $("#idCategoriaServico option").eq(0).attr('selected', 'selected');
                    $('.jqte_editor').text('Teste Automatizado 1');
                    $('input[name="indiServicoDigital"]').prop("checked", true);
                    $("#idSegmentoSocial option").eq(0).attr('selected','selected');
                    $("#tipoTempoAtendto").val('E');
                    $("#contextoQtdeEstimativaInicial").show();
                    $("#contextoTempo").show();
                    $("#contextoQtdeEstimativaFinal").show();
                    $("#contextoTipoTempoEstimadoServico").show();
                    $("#nomePopularServico").val('Teste Automatizado 1');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            // Nome do Serviço
            $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->sendKeys('Teste Automatizado 1');
            // Sigla do Serviço
            $this->webDriver->findElement(WebDriverBy::id("siglServico"))->sendKeys('SERV1');
            // Nomes Populares
            $this->webDriver->findElement(WebDriverBy::id("descUrlServicoDigital"))->sendKeys('http://servicoautomatizado.com.br');
            $javascript = <<<END_JAVASCRIPT
                    $("#tipoTempoEstimadoServico").val('D');
                    $("#qtdeEstimativaInicial").val('10');
                    $("#qtdeEstimativaFinal").val('20');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(2);
            $javascript = <<<END_JAVASCRIPT
                    $("#btnAbaSolicitante").trigger('click');
                    $("#btnAddSolicitante").trigger('click');                    
                    $("#descTipoSolic1").val('Solicitante Automatizado');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
            $this->assertEquals(preg_match('/É obrigatório informar pelo menos/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FE011] – Custo Obrigatório
     */
    public function testFE011CustoObrigatorio() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $javascript = <<<END_JAVASCRIPT
                    $("#statServico option").eq(1).attr('selected','selected');
                    $("#indiServicoDestaque option").eq(1).attr('selected','selected');
                    $("#idPrestadorServico option").eq(1).attr('selected', 'selected');    
                    $("#idPrestadorServico_chosen a span").text($("#idPrestadorServico option").eq(1).attr('selected', 'selected').text());
                    $("#idCategoriaServico option").eq(0).attr('selected', 'selected');
                    $('.jqte_editor').text('Teste Automatizado 1');
                    $('input[name="indiServicoDigital"]').prop("checked", true);
                    $("#idSegmentoSocial option").eq(0).attr('selected','selected');
                    $("#tipoTempoAtendto").val('E');
                    $("#contextoQtdeEstimativaInicial").show();
                    $("#contextoTempo").show();
                    $("#contextoQtdeEstimativaFinal").show();
                    $("#contextoTipoTempoEstimadoServico").show();
                    $("#nomePopularServico").val('Teste Automatizado 1');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            // Nome do Serviço
            $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->sendKeys('Teste Automatizado 1');
            // Sigla do Serviço
            $this->webDriver->findElement(WebDriverBy::id("siglServico"))->sendKeys('SERV1');
            // Nomes Populares
            $this->webDriver->findElement(WebDriverBy::id("descUrlServicoDigital"))->sendKeys('http://servicoautomatizado.com.br');
            $javascript = <<<END_JAVASCRIPT
                    $("#tipoTempoEstimadoServico").val('D');
                    $("#qtdeEstimativaInicial").val('10');
                    $("#qtdeEstimativaFinal").val('20');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(2);
            $javascript = <<<END_JAVASCRIPT
                    $("#btnAbaSolicitante").trigger('click');
                    $("#btnAddSolicitante").trigger('click');                    
                    $("#descTipoSolic1").val('Solicitante Automatizado');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(2);
            $javascript = <<<END_JAVASCRIPT
                    $("#btnAbaEtapa").trigger('click');
                    $("#btnAddEtapa").trigger('click');
                    $("#nomeEtapaServico1").val('Etapa Serviço Automatizado');
                    $("#indiEtapaPossuiCustoS1").trigger('click');                    
                    
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@data-notify-html='mensagemHtml']"))->getText());
            $this->assertEquals(preg_match('/É obrigatório informar pelo menos 1 valor/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

    /**
     * [FA004] – Cadastrar Novo Serviço
     */
    public function testFA004CadastroNovoServicoSucesso() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $javascript = <<<END_JAVASCRIPT
                    $("#statServico option").eq(1).attr('selected','selected');
                    $("#indiServicoDestaque option").eq(1).attr('selected','selected');
                    $("#idPrestadorServico option").eq(1).attr('selected', 'selected');    
                    $("#idPrestadorServico_chosen a span").text($("#idPrestadorServico option").eq(1).attr('selected', 'selected').text());
                    $("#idCategoriaServico option").eq(0).attr('selected', 'selected');
                    $('.jqte_editor').text('Teste Automatizado 1');
                    $('input[name="indiServicoDigital"]').prop("checked", true);
                    $("#idSegmentoSocial option").eq(0).attr('selected','selected');
                    $("#tipoTempoAtendto").val('E');
                    $("#contextoQtdeEstimativaInicial").show();
                    $("#contextoTempo").show();
                    $("#contextoQtdeEstimativaFinal").show();
                    $("#contextoTipoTempoEstimadoServico").show();
                    $("#nomePopularServico").val('Teste Automatizado 1');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            // Nome do Serviço
            $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->sendKeys('Teste Automatizado 1');
            // Sigla do Serviço
            $this->webDriver->findElement(WebDriverBy::id("siglServico"))->sendKeys('SERV1');
            // Nomes Populares
            $this->webDriver->findElement(WebDriverBy::id("descUrlServicoDigital"))->sendKeys('http://servicoautomatizado.com.br');
            $javascript = <<<END_JAVASCRIPT
                    $("#tipoTempoEstimadoServico").val('D');
                    $("#qtdeEstimativaInicial").val('10');
                    $("#qtdeEstimativaFinal").val('20');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(2);
            $javascript = <<<END_JAVASCRIPT
                    $("#btnAbaSolicitante").trigger('click');
                    $("#btnAddSolicitante").trigger('click');                    
                    $("#descTipoSolic1").val('Solicitante Automatizado');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(2);
            $javascript = <<<END_JAVASCRIPT
                    $("#btnAbaEtapa").trigger('click');
                    $("#btnAddEtapa").trigger('click');
                    $("#nomeEtapaServico1").val('Etapa Serviço Automatizado');
                    
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
     * [FE004] – Nome de Serviço Duplicado.
     */
    public function testFE004NomeServicoDuplicado() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@id='btnNovo']"))->click();
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $javascript = <<<END_JAVASCRIPT
                    $("#statServico option").eq(1).attr('selected','selected');
                    $("#indiServicoDestaque option").eq(1).attr('selected','selected');
                    $("#idPrestadorServico option").eq(1).attr('selected', 'selected');    
                    $("#idPrestadorServico_chosen a span").text($("#idPrestadorServico option").eq(1).attr('selected', 'selected').text());
                    $("#idCategoriaServico option").eq(0).attr('selected', 'selected');
                    $('.jqte_editor').text('Teste Automatizado 1');
                    $('input[name="indiServicoDigital"]').prop("checked", true);
                    $("#idSegmentoSocial option").eq(0).attr('selected','selected');
                    $("#tipoTempoAtendto").val('E');
                    $("#contextoQtdeEstimativaInicial").show();
                    $("#contextoTempo").show();
                    $("#contextoQtdeEstimativaFinal").show();
                    $("#contextoTipoTempoEstimadoServico").show();
                    $("#nomePopularServico").val('Teste Automatizado 1');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            // Nome do Serviço
            $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->sendKeys('Teste Automatizado 1');
            // Sigla do Serviço
            $this->webDriver->findElement(WebDriverBy::id("siglServico"))->sendKeys('SERV1');
            // Nomes Populares
            $this->webDriver->findElement(WebDriverBy::id("descUrlServicoDigital"))->sendKeys('http://servicoautomatizado.com.br');
            $javascript = <<<END_JAVASCRIPT
                    $("#tipoTempoEstimadoServico").val('D');
                    $("#qtdeEstimativaInicial").val('10');
                    $("#qtdeEstimativaFinal").val('20');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(2);
            $javascript = <<<END_JAVASCRIPT
                    $("#btnAbaSolicitante").trigger('click');
                    $("#btnAddSolicitante").trigger('click');                    
                    $("#descTipoSolic1").val('Solicitante Automatizado');
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(2);
            $javascript = <<<END_JAVASCRIPT
                    $("#btnAbaEtapa").trigger('click');
                    $("#btnAddEtapa").trigger('click');
                    $("#nomeEtapaServico1").val('Etapa Serviço Automatizado');
                    
END_JAVASCRIPT;
            $this->webDriver->executeScript($javascript, []);
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::id("nomeServicoMsgError"))->getText());
            $this->assertEquals(preg_match('/Já possui um registro com este nome/', $msg), 1);
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            // Nome do segmento
            $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->sendKeys('automatizado');
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            // Nome do segmento
            $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->sendKeys('automatizado');
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-success']"))->click();
            sleep(2);
            $this->webDriver->findElement(WebDriverBy::xpath("//button[@class='btn btn-default']"))->click();
            sleep(2);
            $dados = $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->getAttribute('value');
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
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
     *  [FA005] – Editar Serviço.
     */
    public function testFA005EditarServiço() {
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::id("row0grid"))->click();
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            // Nome do Serviço
            $this->webDriver->findElement(WebDriverBy::id("nomeServico"))->sendKeys(' - Alterado');
            sleep(3);
            $this->webDriver->findElement(WebDriverBy::xpath("//a[@class='btn btn-success pull-right btnSalvar']"))->click();
            sleep(5);
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
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
        require_once 'criarDependenciaServico.php';
        try {
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/segmento-sociedade");
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
            require_once 'limparDependenciaServico.php';
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
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
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

    /**
     *  [FE006] – Usuário sem acesso a órgão
     */
    public function testFE006UsuarioSemAcessoOrgao() {
        try {
            require_once 'limparCargaServico.php';
            // Acessa o sistema.
            $this->configuration->login();
            sleep(3);
            $this->webDriver->get($this->configuration->urlSistema() . "admin/servico");
            $this->webDriver->findElement(WebDriverBy::className("introjs-skipbutton"))->click();
            sleep(3);
            $msg = trim($this->webDriver->findElement(WebDriverBy::xpath("//div[@class='alert alert-warning alert-dismissible fade in']"))->getText());
            $this->assertEquals(preg_match('/Para ter acesso aos recursos desta funcionalidade você deve possuir permissão de acesso a pelo menos/', $msg), 1);
            // Sair do sistema.
            $this->configuration->logout();
        } catch (\Exception $e) {
            throw($e);
        }
    }

}
