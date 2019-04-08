<?php

if (!function_exists('Mask')) {

    /**
     * $cnpj = '17804682000198';
     * echo Mask("##.###.###/####-##",$cnpj).'<BR>';
     * 
     * $cpf = '21450479480';
     * echo Mask("###.###.###-##",$cpf).'<BR>';
     * 
     * $cep = '36970000';
     * echo Mask("#####-###",$cep).'<BR>';
     * 
     * $telefone = '3391922727';
     * echo Mask("(##)####-####",$telefone).'<BR>';
     * 
     * $data = '21072014';
     * echo Mask("##/##/####",$data);
     *
     * @param string $mask
     * @param string $str
     * @return string
     */
    function Mask($mask, $str){
	    $str = str_replace(" ","",$str);
	    for($i=0;$i<strlen($str);$i++){
	        $mask[strpos($mask,"#")] = $str[$i];
	    }
	    return $mask;
	}
}
