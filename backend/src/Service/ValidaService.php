<?php

namespace App\Service;

use Respect\Validation\Validator as V;

/**
 * Service para manipular Validações de Formulário
 * @author David Diniz <diniz.david@gmail.com>
 */
class ValidaService 
{

    /**
     * Filter - Organiza o array de retorno
     * @return Array - Dados de validação filtrados
     */
    public function filter($validacao) 
    {
        $retFilter = array();
        foreach ($validacao as $key => $valid) {
            /*
             * Procura validações obrigatórias primeiro
             */
            foreach ($valid as $chave => $value) {
                if ($chave == "notEmpty") {
                    $retFilter['obrigatorio'][$key][] = $value;
                } else {
                    if ($key == 'arquivo') {
                        $retFilter['file'][] = $value;
                    }
                    $retFilter[$key][] = $value;
                }
            }
        }
        return $retFilter['obrigatorio'] ?? $retFilter;
    }

    /**
     * Validar se a Extensão do arquivo é aceita
     * @return Array - Dados de validação filtrados
     */
    public function validaExtensao($file, $formatosAceitos) 
    {
        $valido = false;
        if (count($formatosAceitos) > 0) {
            foreach ($formatosAceitos as $format) {
                $validationResult = V::file()->mimetype($format)->validate($file);
                if ($validationResult) {
                    $valido = true;
                }
            }
        }
        return $valido;
    }

    /**
     * Verifica se é um CPF válido
     *
     * @param string $cpf
     * @return boolean
     */
    public function isCpf($cpf)
    {
        // Extrai somente números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

        /**
         * Verifica se o CPF tem 11 caracteres
         * Verifica se há uma sequência de digitos repetidos. Ex.: 111.111.111-11
         */
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        // Primeiro dígito
        for ($i = 0, $j = 10, $sum = 0; $i < 9; $i++, $j--) {
            $sum += $cpf[$i] * $j;
        }        
	    $remainder = $sum % 11;        
	    if ($cpf[9] != ($remainder < 2 ? 0 : 11 - $remainder)) {
            return false;
        }
        
        // Segundo dígito
        for ($i = 0, $j = 11, $sum = 0; $i < 10; $i++, $j--) {
            $sum += $cpf[$i] * $j;
        }
	    $remainder = $sum % 11;
	    return $cpf[10] == ($remainder < 2 ? 0 : 11 - $remainder);
    }

}
