<?php

namespace Validacao ; 

/**
* Validação de Dados
* ------------------
*
* Classe responsavel por validação de dados.
* 
* CHECAR - retorna sempre TRUE ou FALSE indicando se uma informação
* é valida. A checagem é permitida a CPF, E-MAIL, DATAS.
*
* @package     Vita
* @author      wandeco sans - http://sooho.com.br - <sans.pds@gmail.com>
* @copyright   Copyleft (c) 2014
* @license
* @link
* @since       Version 201705112153 (última revisão)
*
* @todo - criar método para a validação de tempo. ex: 21:53:07
* @todo - criar método para a validar endereços IP
* @todo - criar método para a validação de URLs
*/
class Validacao
{

    public function __construct(){}

    /**
     * VERIFICA SE EMAIL VALIDO
     * ------------------------
     * 
     * Recebe uma string, verifica se a mesma trata-se 
     * de um endereço de email é valido.
     * 
     * @param  string - candidato a email
     * @return boolean
     */
    public function isEmail($email) {
    	if(function_exists('filter_var')) {
        	return !filter_var($email, FILTER_VALIDATE_EMAIL) ? false : true;
    	}
        else {
	        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
	        if (preg_match($pattern, $email) === 1) {
	            return true;
	        }
    	}
        return false;
    }

    /**
     * VERIFICA SE CPF VALIDO
     * ----------------------
     * 
     * Verifica se um Número de CPF é valido
     *
     * @access public
     * @param string - numero do cpf com ou sem pontos e hifen a ser verificado
     * @return bool - true caso valido
     **/
    public function isCpf( $cpf ){
        $d1 = 0;
        $d2 = 0;
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        
        /**
         * lista de cpf inválidos que serão ignorados
         * @var array
         */
        $cpfs_invalidos = array(
            '00000000000',
            '01234567890',
            '11111111111',
            '22222222222',
            '33333333333',
            '44444444444',
            '55555555555',
            '66666666666',
            '77777777777',
            '88888888888',
            '99999999999'
        );

        # se o tamanho da string diferente de 11 ou 
        # na lista de cpf invalidos retorna false.
        if(strlen($cpf) != 11 || in_array($cpf, $cpfs_invalidos)) {
            return false;
        } 
        else {
            
            # inicia o processo para achar o primeiro
            # número verificador usando os primeiros 9 dígitos
            for($i = 0; $i < 9; $i++) {
            	# inicialmente $d1 vale zero e é somando.
            	# O loop passa por todos os 9 dígitos iniciais
                $d1 += $cpf[$i] * (10 - $i);
            }

            # acha o resto da divisão da soma acima por 11
            $r1 = $d1 % 11;

            # se $r1 maior que 1 retorna 11 menos $r1 se não
            # retona o valor zero para $d1
            $d1 = ($r1 > 1) ? (11 - $r1) : 0;
            
            # inicia o processo para achar o segundo
            # número verificador usando os primeiros 9 dígitos
            for($i = 0; $i < 9; $i++) {
            	# inicialmente $d2 vale zero e é somando.
            	# O loop passa por todos os 9 dígitos iniciais
                $d2 += $cpf[$i] * (11 - $i);
            }

            # $r2 será o resto da soma do cpf mais $d1 vezes 2
            # dividido por 11
            $r2 = ($d2 + ($d1 * 2)) % 11;

            # se $r2 mair que 1 retorna 11 menos $r2 se não
            # retorna o valor zeroa para $d2
            $d2 = ($r2 > 1) ? (11 - $r2) : 0;

            # retona true se os dois últimos dígitos do cpf
            # forem igual a concatenação de $d1 e $d2 e se não
            # deve retornar false.
            return (substr($cpf, -2) == $d1 . $d2) ? true : false;
        }
    }

    /**
     * VERIFICA SE CNPJ VALIDO
     * -----------------------
     * 
     * Verifica se um Número de CNPJ é valido
     *
     * @access public
     * @param string - numero do CNPJ com ou sem pontos e hifen
     * @return bool - true caso valido
     **/
    public function isCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        
        # Valida tamanho
        if (strlen($cnpj) != 14) return false;
        
        # Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++){
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto)) return false;
        
        # Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++){
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }
    
    /** 
     * TENTA VERIFICAR SE UMA DATA É VALIDA
     * ------------------------------------
     * 
     * Verifica se uma data parece valida quanto a 
     * formatação., e verifica se é uma data gregoriana
     * válida. Note que, uma data pode ser valida em 
     * diversos formatos, Ex: 
     * 11/05/2017
     * 2017-11-05
     * 2017.11.05
     * 11 de Maio de 2017
     * Porém, existem casos de datas validas que podem 
     * causar certa confusão na interpretação por script:
     * 11/05/17
     * 17/11/05
     *
     * O Script a seguir tenta tratar apenas os casos em que, 
     * a data é formatada com 8 digitos e 10 caracteres. Ex:
     * 11/05/2017 e 2017/05/11 
     *
     * @access public
     * @param  [type]  $x [description]
     * @return boolean    [description]
     */
    public function isData( $__data ) {

        # verifica se ano ou dia é o primeiro elemento.
        $__data = str_replace("/", "-", $__data);
        $_data  = explode("-", $__data);
        if(!is_array($_data)) 
            return false;

        # formata data para ficar no formato Americano
        # Y-m-d
        $__data = (strlen($_data[0]) == 4) 
            ? implode("-", $_data) 
            : implode("-", array_reverse($_data));

        # cria uma data a partir do valor encontrado
        $d = DateTime::createFromFormat('Y-m-d', $__data);
        
        return $d && $d->format('Y-m-d') === $__data && 
        checkdate( $d->format('m'), $d->format('d'), $d->format('Y') );
    }
    
}
