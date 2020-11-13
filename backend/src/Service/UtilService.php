<?php

namespace App\Service;

/**
 * Service para manipular Funções uteis na aplicação, conversores de data, limpar maskaras e etc.
 * @author David Diniz <diniz.david@gmail.com>
 */
class UtilService 
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * Transforma o mês em númerico
     * @param string $strMes Mês enviado
     * @return string Número do Mês
     */
    public function nrMes($strMes) 
    {
        switch ($strMes) {
            case 'Jan' :
                return '01';
                break;
            case 'Feb' :
                return '02';
                break;
            case 'Mar' :
                return '03';
                break;
            case 'Apr' :
                return '04';
                break;
            case 'May' :
                return '05';
                break;
            case 'Jun' :
                return '06';
                break;
            case 'Jul' :
                return '07';
                break;
            case 'Aug' :
                return '08';
                break;
            case 'Sep' :
                return '09';
                break;
            case 'Oct' :
                return '10';
                break;
            case 'Nov' :
                return '11';
                break;
            case 'Dec' :
                return '12';
                break;
        }
    }

    /**
     * Pega o dia da Semana de acordo com a data enviada
     * @param Date $data Data Informada
     * @return string Dia da Semana
     */
    public function diaSemana($data) 
    {
        $ano = substr($data, 0, 4);
        $mes = substr($data, 5, 2);
        $dia = substr($data, 8, 2);
        $diasemana = date("w", mktime(0, 0, 0, $mes, $dia, $ano));


        switch ($diasemana) {
            case"0": $diasemana = "Domingo";
                break;
            case"1": $diasemana = "Segunda-Feira";
                break;
            case"2": $diasemana = "Terça-Feira";
                break;
            case"3": $diasemana = "Quarta-Feira";
                break;
            case"4": $diasemana = "Quinta-Feira";
                break;
            case"5": $diasemana = "Sexta-Feira";
                break;
            case"6": $diasemana = "Sábado";
                break;
        }

        return "$diasemana";
    }

    /**
     * Adicona um zero a esquerda do número
     * @param int $numero Número a ser alterado
     * @return string Número com um zero a esquerda
     */
    public function adicionaZero($numero) 
    {
        switch ($numero) {
            case 0:
                return '00';
                break;
            case 1:
                return '01';
                break;
            case 2:
                return '02';
                break;
            case 3:
                return '03';
                break;
            case 4:
                return '04';
                break;
            case 5:
                return '05';
                break;
            case 6:
                return '06';
                break;
            case 7:
                return '07';
                break;
            case 8:
                return '08';
                break;
            case 9:
                return '09';
                break;
            default :
                return $numero;
                break;
        }
    }

    /**
     * Remove os caracteres de CPF e CNPJ
     * @param int $valor CPF ou CNPJ
     * @return string Somente os números
     */
    public function limpaCpfCpnj($valor) 
    {
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        return $valor;
    }

    /**
     *  Valida se é uma data válida
     * @param date $date Data
     * @return bool Se data inválida igual falso.
     */
    public function checkData($date) 
    {
        if (!isset($date) || $date == "") {
            return false;
        }

        list ($dd, $mm, $yy) = explode("/", $date);
        if ($dd != "" && $mm != "" && $yy != "") {
            if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)) {
                return checkdate($mm, $dd, $yy);
            }
        }
        return false;
    }

    /**
     * Pega o Ip do usuário
     * @return string Retorna Endereço Ip do Usuário
     */
    public function getIp() 
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Adiciona maskara em CPF, CNPJ, DATA etc
     * CNPJ mask($cnpj,'##.###.###/####-##');
     * CPF mask($cpf,'###.###.###-##');
     * CEP mask($cep,'#####-###');
     * DATA mask($data,'##/##/####');
     * @param string $val Dado a ser aplicado a maskara
     * @param string $mask Padrão da maskara
     * @return string Dados com a maskara apliada
     */
    public function mask($val, $mask) 
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    /**
     * função para remover pontuação, espaços e caracteres especiais
     * @param string $string Dado a ser aplicado
     * @return string Dados formatado
     */
    public function sanitizeString($string) 
    {
        // matriz de entrada
        $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');
        // matriz de saída
        $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');
        // devolver a string
        return str_replace($what, $by, $string);
    }

    /**
     * Criar Url amigável sem caracteres especiais
     * @param string $texto Dado a ser aplicado
     * @return string Dados formatado
     */
    public function urlAmigavel($texto) 
    {
        $array1 = array("'", '"', "!", "@", "#", "$", "%", "¨", "&", "*", "(", ")", "-", "=", "+", "´", "`", "[", "]", "{", "}", "~", "^", ",", "<", ">", ";", ":", "/", "?", "\\", "|", "¹", "²", "³", "£", "¢", "¬", "§", "ª", "º", "°", " ");
        $array2 = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "-");
        return str_replace($array1, $array2, $texto);
    }

    /*
     * Converte caracter maiusculo para minusculo e vise versa
     * @param string $term Dado a ser aplicado
     * @param string $tp tipo a ser convertido 1 -Maiusculo, 0 - Minusculo
     * @return string Dados formatado
     */

    public function converte($term, $tp) 
    {
        if ($tp == "1")
            $palavra = strtr(strtoupper($term), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
        elseif ($tp == "0")
            $palavra = strtr(strtolower($term), "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß", "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");

        return $palavra;
    }

    /**
     * Retira Caracteres Especiais
     * @param string $texto Texto a ser removido
     * @return string O Texto com códigos removidos
     */
    public function retiraCaractEspeciais($texto) 
    {
        $array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "'", '"', "!", "@", "#", "$", "%", "¨", "&", "*", "(", ")", "-", "=", "+", "´", "`", "[", "]", "{", "}", "~", "^", ",", "<", ">", ";", ":", "/", "?", "\\", "|", "¹", "²", "³", "£", "¢", "¬", "§", "ª", "º", "°", "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç", " ", "ñ");
        $array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C", " ", "n");
        return str_replace($array1, $array2, $texto);
    }

    /**
     * Remover Duplo Espaço String
     * @param string $texto Texto a ser removido
     * @return string O Texto com códigos removidos
     */
    public function retiraEspacoDuplicado($texto) 
    {
        return preg_replace('/( ){2,}/', '$1', trim($texto));
    }

    /**
     * Retorna a data por extenso
     * @return string Data escrita por extenso
     */
    public function dataExtenso() 
    {
        $ano = date("Y");
        $mesext = date("m");
        $dia = date("d");

        switch ($mesext) {
            case 1: $mesext = "Janeiro";
                break;
            case 2: $mesext = "Fevereiro";
                break;
            case 3: $mesext = "Mar&ccedil;o";
                break;
            case 4: $mesext = "Abril";
                break;
            case 5: $mesext = "Maio";
                break;
            case 6: $mesext = "Junho";
                break;
            case 7: $mesext = "Julho";
                break;
            case 8: $mesext = "Agosto";
                break;
            case 9: $mesext = "Setembro";
                break;
            case 10: $mesext = "Outubro";
                break;
            case 11: $mesext = "Novembro";
                break;
            case 12: $mesext = "Dezembro";
                break;
        }
        return "$dia de $mesext de $ano.";
    }

    /**
     * Trata anti-injection de formulários
     * @param type $texto
     * @return Texto
     */
    public function antiInjection($texto) 
    {
        $texto = trim($texto); //limpa espaços vazio
        $texto = strip_tags($texto); //tira tags html e php
        $texto = addslashes($texto); //Adiciona barras invertidas a uma string
        return $texto;
    }

    /**
     * Calcula Porcentagem
     * @param int $parcial
     * @param int $total
     * @return float Resultado Porcetagem
     */
    public function porcentagem($parcial, $total) 
    {
        return round(($parcial * 100) / $total);
    }

    public function getRandomString($length = 32) 
    {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }

    public function encodeLength($length) {
        if ($length <= 0x7F) {
            return chr($length);
        }
        $temp = ltrim(pack('N', $length), chr(0));
        return pack('Ca*', 0x80 | strlen($temp), $temp);
    }

    /**
     * Recebe uma string e retorna apenas o texto, sem tags
     *
     * @param string $str
     * @return string
     */
    public function apenasTexto($str)
    {
        return strip_tags(filter_var($str, FILTER_SANITIZE_STRING));
    }

    /**
     * Converte data para Banco de Dados
     * @param date $data Data
     * @return date Retorna data padrão de banco xxxx-xx-xx
     */
    public function getConverteDataBanco($data) {
        $novadatabanco = substr($data, 6, 4) . "-" . substr($data, 3, 2) . "-" . substr($data, 0, 2);
        return $novadatabanco;
    }

    /**
     * Converte data Banco BR
     * @param date $data Data
     * @return date Retorna data padrão de BR xx/xx/xxxx
     */
    public function getConverteDataBr($data) {
        $novadata = substr($data, 8, 2) . "/" . substr($data, 5, 2) . "/" . substr($data, 0, 4);
        return $novadata;
    }
}
